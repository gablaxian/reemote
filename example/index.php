<?php

require '../vendor/autoload.php';

use Amu\Reemote\Manager;

$ssh = new Manager(array(
    'host'      => '192.168.33.34',
    'username'  => 'vagrant',
    'password'  => 'vagrant'
));

$ssh->run(array(
    'cd /var',
    'ls'
), function($line) {
    echo $line.PHP_EOL;
});

// $ssh->addConnection('production', array(
//     'host'      => '',
//     'username'  => '',
//     'password'  => '',
//     'key'       => '',
//     'keyphrase' => '',
//     'root'      => '/var/www',
// ));

// $ssh->into(array('default','production'))->run(array(
//     'cd /foo/test',
//     'echo "bar"'
// ));