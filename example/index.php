<?php

require '../vendor/autoload.php';

use Amu\Reemote\Manager as SSH;

$ssh = new SSH(array(
    'host'      => '',
    'username'  => '',
    'password'  => '',
    'key'       => '',
    'keyphrase' => '',
    'root'      => '/var/www',
));

$ssh->run(array(
    'echo "hello"'
));

$ssh->addConnection('production', array(
    'host'      => '',
    'username'  => '',
    'password'  => '',
    'key'       => '',
    'keyphrase' => '',
    'root'      => '/var/www',
));

$ssh->into(array('default','production'))->run(array(
    'cd /foo/test',
    'echo "bar"'
));