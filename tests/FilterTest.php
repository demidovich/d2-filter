<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use D2\Filter\Filter;

class FilterTest extends TestCase
{
    public function rules_data()
    {
        return [
            ['sanitize_string', 'string<br>', 'string'],
            ['trim', '  string ', 'string'],
            ['strip_tags', 'string<br>', 'string'],
            ['strip_repeat_spaces', 'string    string string', 'string string string'],
            ['digits_only', '+7 (123) 45-67-890', '71234567890'],
            ['to_upper', 'string', 'STRING'],
            ['to_lower', 'StRING', 'string'],
        ];
    }

    /**
     * @dataProvider rules_data
     */
    public function test_rules($rule, $dirtyValue, $cleanValue)
    {
        $input = ['field' => $dirtyValue];
        $rules = ['field' => $rule];

        $input = Filter::apply($rules, $input);

        $this->assertEquals($cleanValue, $input['field']);
    }
}
