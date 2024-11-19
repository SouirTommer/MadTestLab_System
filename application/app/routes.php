<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello comp3335!');
        return $response;
    });

    $app->get('/test-db', function (Request $request, Response $response) {
        $host = '127.0.0.1';
        $dbname = 'MadTestLab';
        $username = 'admin';
        $password = 'admin';
        $charset = 'utf8';
        $collation = 'utf8_unicode_ci';
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s COLLATE %s', $charset, $collation)
        ];

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
            $stmt = $pdo->query('SELECT 1');
            $result = $stmt->fetch();
            $response->getBody()->write('Database connection is successful: ' . json_encode($result));
        } catch (PDOException $e) {
            $response->getBody()->write('Database connection failed: ' . $e->getMessage());
        } catch (Exception $e) {
            $response->getBody()->write('An unexpected error occurred: ' . $e->getMessage());
        }
        return $response;
    });
};