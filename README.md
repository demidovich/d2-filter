## d2 filter

Class preparation of input data before validation.

```
$input = Filter::apply([
    'email' => 'trim|to_lower',
    'text'  => 'string|trim',
    'name'  => 'string|trim',
    'phone' => 'digits_only|trim'
], $data);
```

```
$filter = new Filter();
$filter->singleSpaces();
$filter->trim();

$filter->param('email');
$filter->param('text')->stripTags();
$filter->param('name')->stripTags()->stripSpecialshars();
$filter->param('phone')->onlyDigits();

$input = $filter->apply(
    $request->toArray()
);
```

```
$input = (new Filter([
    'email' => 'trim',
    'text'  => 'stripTags|trim',
    'name'  => 'stripTags|stripSpecialshars|trim',
    'phone' => 'onlyDigits|trim'
]))->apply($data);
```
