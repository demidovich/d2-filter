<?php

namespace D2\Filter;

class Filter
{
    public static function apply(array $filters, array $input)
    {
        if (empty($input) || empty($filters)) {
            return $input;
        }

        foreach ($filters as $param => $stringRules) {

            if (! isset($input[$param])) {
                continue;
            }

            $rules = explode('|', $stringRules);

            // If a trim exists it will be executed last
            if (in_array('trim', $rules)) {
                $key = array_search('trim', $rules);
                if ($key < array_key_last($rules)) {
                    unset($rules[$key]);
                    array_push($rules, 'trim');
                }
            }

            $class = get_called_class();

            foreach ($rules as $rule) {

                if (! method_exists($class, $rule)) {
                    throw new \RuntimeException("Non-existent filtering rule \"{$rule}\"");
                }

                if (! is_string($input[$param])) {
                    continue;
                }

                $input[$param] = $class::$rule($input[$param]);
            }
        }

        return $input;
    }

    /**
     * Escape, strip tags, specialchars
     */
    protected static function sanitize_string(string $value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    protected static function trim(string $value)
    {
        return trim($value);
    }

    protected static function strip_tags(string $value)
    {
        return strip_tags($value);
    }

    protected static function strip_repeat_spaces(string $value)
    {
        return preg_replace('/\s+/u', ' ', $value);
    }

    protected static function digits_only(string $value)
    {
        return preg_replace('/[^0-9]/si', '', $value);
    }

    protected static function to_upper(string $value)
    {
        return mb_strtoupper($value, 'utf-8');
    }

    protected static function to_lower(string $value)
    {
        return mb_strtolower($value, 'utf-8');
    }
}