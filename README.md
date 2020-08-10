[![Build Status](https://travis-ci.com/demidovich/d2-filter.svg?branch=master)](https://travis-ci.com/demidovich/d2-filter) [![codecov](https://codecov.io/gh/demidovich/d2-filter/branch/master/graph/badge.svg)](https://codecov.io/gh/demidovich/d2-filter)

## d2 filter

Class preparation of input data before validation.

Available rules:

* digits_only
* sanitize_string
* strip_tags
* strip_repeat_spaces
* to_upper
* to_lower
* trim

Example of usage

```php
$raw = [
    'email' => 'MyEmail@gmail.com',
    'text'  => 'Some text <br> ',
    'phone' => '+7 (000) 000-00-00',
];

$data = Filter::apply([
    'email' => 'trim|to_lower',
    'text'  => 'trim|string',
    'phone' => 'digits_only',
], $raw);

// $data['email'] : 'myemail@gmail.com'
// $data['text']  : 'Some text'
// $data['phone'] : '70000000000'
```

Example of custom rule implementation

```php
class MyFilter extends Filter
{
    protected function money($value)
    {
        return str_replace(",", ".", $value);
    }
}

$data = [
    'amount' => '100,00',
];

$rules = [
    'amount' => 'trim|money',
];

$data = (new MyFilter($rules))->apply($raw);
```
