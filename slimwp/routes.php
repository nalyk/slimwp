<?php


use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

foreach (glob(__ROOT__ . '/slimwp/*/routes.php') as $filename) {
  include_once $filename;
}


