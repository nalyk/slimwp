<?php
use Slimwp\Calendar\Controllers\CalendarController;
use Slimwp\Core\Middleware\AntiXSSMiddleware;
use Slimwp\Core\Middleware\RedirectGuests;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

// Define the app routes.
$app->group('/calendar', function (RouteCollectorProxy $group) {
    $group->get ('/events', CalendarController::class . ':events_list_ui')->setName('calendar.events'); 
    $group->get ('/sources', CalendarController::class . ':sources_list_ui')->setName('calendar.sources'); 
})->add(RedirectGuests::class)->add(AntiXSSMiddleware::class);

$app->group('/api/calendar/v1', function (RouteCollectorProxy $group) {
    $group->get ('/events[/{uid}]', CalendarController::class . ':events_list')->setName('calendar.events.api01'); 
    $group->get ('/sources[/{uid}]', CalendarController::class . ':sources_list')->setName('calendar.sources.api01'); 
    $group->post('/sources[/{uid}]', CalendarController::class . ':sources_post');
    $group->patch('/sources[/{uid}]', CalendarController::class . ':sources_patch');
    $group->delete('/sources[/{uid}]', CalendarController::class . ':sources_delete');
})->add(RedirectGuests::class);

/*
t_store_opportunities
t_store_offers
t_store_orders
t_store_cart
t_store_invoices
t_store_disputes

t_calendar_uris
t_worklog_items
t_mail_accounts
t_mail_box
t_mail_cards
t_mail_attachments

t_spider_subscriptions
t_spider_data
t_spider_events

out[$date][$eventid] = $event[data]

https://podio.github.io/jquery-mentions-input/
https://github.com/zurb/tribute
*/




