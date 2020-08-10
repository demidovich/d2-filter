<?php

namespace D2\Filter;

use RuntimeException;

class Filter
{
    private $rules = [];

    public function __construct(array $rulesets)
    {
        $this->load($rulesets);
    }

    private function load(array $rulesets): void
    {
        foreach ($rulesets as $param => $ruleset) {

            $rules = explode('|', $ruleset);
            $class = get_called_class();

            foreach ($rules as $ruleMethod) {
                if (! method_exists($class, $ruleMethod)) {
                    throw new RuntimeException("Missing filtering method {$class}::{$ruleMethod}()");
                }
            }

            // If a trim exists it will be executed last
            if (in_array('trim', $rules)) {
                $key = array_search('trim', $rules);
                if ($key < array_key_last($rules)) {
                    unset($rules[$key]);
                    array_push($rules, 'trim');
                }
            }

            $this->rules[$param] = $rules;
        }
    }

    public function apply(array $data): array
    {
        if (empty($data) || empty($this->rules)) {
            return $data;
        }

        foreach ($this->rules as $param => $rules) {

            if (! isset($data[$param])) {
                continue;
            }

            if (! is_scalar($data[$param])) {
                continue;
            }

            foreach ($rules as $ruleMethod) {
                $data[$param] = $this->$ruleMethod($data[$param]);
            }
        }

        return $data;
    }

    /**
     * Escape, strip tags, specialchars
     */
    protected function sanitize_string($value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    protected function trim($value)
    {
        return trim($value);
    }

    protected function strip_tags($value)
    {
        return strip_tags($value);
    }

    protected function strip_repeat_spaces($value)
    {
        return preg_replace('/\s+/u', ' ', $value);
    }

    protected function digits_only($value)
    {
        return preg_replace('/[^0-9]/si', '', $value);
    }

    protected function to_upper($value)
    {
        return mb_strtoupper($value, 'utf-8');
    }

    protected function to_lower($value)
    {
        return mb_strtolower($value, 'utf-8');
    }
}
