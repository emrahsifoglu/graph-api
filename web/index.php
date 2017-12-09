<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';

use App\Vendor\App;
use App\Vendor\Http\Request\Request;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

$config = [];

try {
    $config = Yaml::parse(file_get_contents('../app/config/config.yml'));
} catch (ParseException $e) {
    $message = $e->getMessage();
    die("Unable to parse the YAML string: $message");
}

if (empty($config)) {
    die('Unable to configure');
}

$app = new App();

$app->get('/', function (Request $request) {
    echo 'Hello world!';
});

$app->run();