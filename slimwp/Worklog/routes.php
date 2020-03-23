<?php

use Slimwp\Core\Middleware\RedirectGuests;
use Slimwp\Core\Middleware\RestrictGuests;
use Slimwp\Core\Middleware\AntiXSSMiddleware;
use Slimwp\Worklog\Controllers\WorklogController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

// Define the app routes.

$app->group('/api/worklog/v1', function (RouteCollectorProxy $group) {
    $group->get ('/users', WorklogController::class . ':me_get')->setName('worklog.users.api01'); 
    $group->get ('/domains', WorklogController::class . ':we_get')->setName('worklog.domains.api01'); 
    $group->post('/items[/{uid}]', WorklogController::class . ':me_post')->setName('worklog.items.api01'); 
    $group->patch('/items[/{uid}]', WorklogController::class . ':patch');
})->add(RestrictGuests::class)->add(AntiXSSMiddleware::class);

$app->group('/worklog', function (RouteCollectorProxy $group) {
    $group->get ('/me', WorklogController::class . ':me_ui')->setName('worklog.me'); 
    $group->get ('/we', WorklogController::class . ':we_ui')->setName('worklog.we'); 
    // $group->get ('/schema-migration/0-1', WorklogController::class . ':migrate_jsonschema_0_1');
    // TODO - replace commenting out with propper authorization treatment.
})->add(RedirectGuests::class);
