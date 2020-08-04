<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use D2\Filter\Filter;
use RuntimeException;
use Tests\Stubs\MyFilter;

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

    public function test_non_scalar_value()
    {
        $input = ['field' => new \stdClass()];
        $rules = ['field' => 'trim|strip_tags'];

        $filtered = Filter::apply($rules, $input);

        $this->assertEquals('object', gettype($filtered['field']));
    }

    public function test_not_existing_rule_exception()
    {
        $this->expectException(RuntimeException::class);

        $input = ['field' => 'string'];
        $rules = ['field' => 'trim|not_existing_rule'];

        $filtered = Filter::apply($rules, $input);
    }

    public function test_empty_rules()
    {
        $input = ['field1' => 1234, 'field2' => true];
        $rules = [];

        $filtered = Filter::apply($rules, $input);

        $this->assertEquals($input, $filtered);
    }

    public function test_no_input_param()
    {
        $input = ['field1' => '1234'];
        $rules = ['field1' => 'trim', 'field2' => 'trim'];

        $filtered = Filter::apply($rules, $input);

        $this->assertEquals($input['field1'], $filtered['field1']);
    }

    public function test_custom_filter_class()
    {
        $input = ['amount' => '100,00'];
        $rules = ['amount' => 'money'];

        $filtered = MyFilter::apply($rules, $input);

        $this->assertEquals('100.00', $filtered['amount']);
    }
}
