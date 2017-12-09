<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';

use App\Vendor\App;
use App\Vendor\Config\Config;
use App\Vendor\Database\Adapter\PDO\PDOAdapter;
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

$config = $app->getContainer()->registerService('App\Vendor\Config\ConfigInterface',
    function () use($config) {
        return new Config($config);
    }
);

$connectionOptions = $app->getContainer()->getService('App\Vendor\Config\ConfigInterface')->getConnectionOptions();

$app->getContainer()->registerService('App\Vendor\Database\Adapter\DatabaseAdapterInterface',
    function () use($connectionOptions) {
        $dsn = "mysql:host={$connectionOptions['host']};dbname={$connectionOptions['name']}";
        return new PDOAdapter($dsn, $connectionOptions['user'], $connectionOptions['password']);
    }
);

$app->post('/graphql', 'GraphQL:index');
$app->get('/graphql', 'GraphQL:explorer');
$app->run();
