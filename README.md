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

```
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

```
class MyFilter extends Filter
{
    protected static function money($value)
    {
        return str_replace(",", ".", $value);
    }
}

$raw = [
    'amount' => '100,00',
];

$data = Filter::apply([
    'amount' => 'trim|money',
], $raw);
```
