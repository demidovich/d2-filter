<?php

use D2\Filter\Filter;

$autoload = __DIR__.'/../vendor/autoload.php';

if (! file_exists($autoload)) {
    throw new RuntimeException('Install composer dependencies to run test suite');
}

require_once $autoload;

$started = hrtime(true);

$data = [
    'email' => 'MyEmail@GMAIL.com',
    'title' => ' Title<br> ',
    'text'  => ' Text<br> text     text<br>$#@ <> text',
];

$rules = [
    'email' => 'to_lower|trim',
    'title' => 'sanitize_string|trim',
    'text'  => 'sanitize_string|trim',
];

for ($i = 1; $i <= 1000; $i++) {
    $data = (new Filter($rules))->apply($data);
    if ($i === 2) {
        $finished2 = hrtime(true);
    }
    if ($i === 5) {
        $finished5 = hrtime(true);
    }
    if ($i === 10) {
        $finished10 = hrtime(true);
    }
    if ($i === 25) {
        $finished25 = hrtime(true);
    }
    if ($i === 100) {
        $finished100 = hrtime(true);
    }
}

$finished1000 = hrtime(true);

dd([
    '2    ' => (($finished2    - $started) / 1e9) . ' sec',
    '5    ' => (($finished5    - $started) / 1e9) . ' sec',
    '10   ' => (($finished10   - $started) / 1e9) . ' sec',
    '25   ' => (($finished25   - $started) / 1e9) . ' sec',
    '100  ' => (($finished100  - $started) / 1e9) . ' sec',
    '1000 ' => (($finished1000 - $started) / 1e9) . ' sec',
]);