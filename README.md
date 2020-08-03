## d2 filter

Class preparation of input data before validation.

```
$raw = [
    'email' => 'MyEmail@gmail.com',
    'text'  => 'Some text <br> ',
    'phone' => '+7 (000) 000-00-00',
];

$data = Filter::apply([
    'email' => 'trim|to_lower',
    'text'  => 'trim|string',
    'phone' => 'digits_only'
], $raw);

// $data['email'] : 'myemail@gmail.com'
// $data['text']  : 'Some text'
// $data['phone'] : '70000000000'
```
