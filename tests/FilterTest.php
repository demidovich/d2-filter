<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use D2\Filter\Filter;
use RuntimeException;

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
            ['to_lower|trim|sanitize_string', ' <br>String  <br>', 'string'], // trim will be executed last
        ];
    }

    /**
     * @dataProvider rules_data
     */
    public function test_rules($stringRules, $dirtyValue, $cleanValue)
    {
        $input = ['field' => $dirtyValue];
        $rules = ['field' => $stringRules];

        $filtered = Filter::apply($rules, $input);

        $this->assertEquals($cleanValue, $filtered['field']);
    }

    public function test_non_string_value()
    {
        $input = ['field' => 1234];
        $rules = ['field' => 'trim|strip_tags'];

        $filtered = Filter::apply($rules, $input);

        $this->assertEquals('integer', gettype($filtered['field']));
        $this->assertEquals($input['field'], $filtered['field']);
    }

    public function test_not_existing_rule_Exception()
    {
        $this->expectException(RuntimeException::class);

        $input = ['field' => 'string'];
        $rules = ['field' => 'trim|not_existing_rule'];

        $filtered = Filter::apply($rules, $input);
    }
}
