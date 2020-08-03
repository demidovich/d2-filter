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

            foreach ($rules as $rule) {

                if (! method_exists(__CLASS__, $rule)) {
                    throw new \RuntimeException("Non-existent filtering rule \"{$rule}\"");
                }

                if (! is_string($input[$param])) {
                    continue;
                }

                $input[$param] = self::$rule($input[$param]);
            }
        }

        return $input;
    }

    /**
     * Escape, strip tags, specialchars
     */
    private static function sanitize_string(string $value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    private static function trim(string $value)
    {
        return trim($value);
    }

    private static function strip_tags(string $value)
    {
        return strip_tags($value);
    }

    private static function strip_repeat_spaces(string $value)
    {
        return preg_replace('/\s+/u', ' ', $value);
    }

    private static function digits_only(string $value)
    {
        return preg_replace('/[^0-9]/si', '', $value);
    }

    private static function to_upper(string $value)
    {
        return mb_strtoupper($value, 'utf-8');
    }

    private static function to_lower(string $value)
    {
        return mb_strtolower($value, 'utf-8');
    }
}