<?php

declare(strict_types=1);

namespace Slimwp\Core\Controllers;

use Slimwp\Core\Classes\Wpapi\WpApi;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Slimwp extends AbstractTwigController
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function index(Request $request, Response $response, array $args = []): Response
    {
        $wpapi = new WpApi($this->settings, $this->redis);
        //$lastposts = $wpapi->getLastPosts(1, true);
        $lastposts = $wpapi->getObjects('posts', 1, true);

        return $this->render($response, 'Core/Views/home.twig', [
            'pageTitle' => 'Home',
            'lastposts' => $lastposts
        ]);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function post_get(Request $request, Response $response, array $args = []): Response
    {
        $slug = $args['slug'];
        $wpapi = new WpApi($this->settings, $this->redis);
        $lastposts = $wpapi->getObjects('posts', 1, true);
        $article = $wpapi->getObject('posts', $slug, true);

        return $this->render($response, 'Core/Views/post.twig', [
            'pageTitle' => 'Home',
            'lastposts' => $lastposts,
            'article' => $article
        ]);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function ajax_post_get(Request $request, Response $response, array $args = []): Response
    {
        $slug = $args['slug'];
        $wpapi = new WpApi($this->settings, $this->redis);
        $article = $wpapi->getObject('posts', $slug, true);

        $payload = json_encode($article);

        $response->getBody()->write($payload);
        return $response
                  ->withHeader('Content-Type', 'application/json')
                  ->withStatus(200);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function ajax_modal_post_get(Request $request, Response $response, array $args = []): Response
    {
        $slug = $args['slug'];
        $wpapi = new WpApi($this->settings, $this->redis);
        $article = $wpapi->getSinglePost($slug)[0];

        return $this->render($response, 'Core/Views/templates/partials/modals/post.twig', [
            'pageTitle' => 'Home',
            'article' => $article
        ]);
    }
}
