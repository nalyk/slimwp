<?php
namespace Slimwp\Core\Classes\Wpapi;

class WpApi {

    protected $settings;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function getLastPosts($page = 1)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/posts?page=".$page."&status=publish&_embed";
        $res = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
            $array = json_decode(json_encode($res->body), true);

        return $array;
    }

    public function getSinglePost($slug)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/posts?slug=".$slug."&status=publish&_embed";
        $res = \Httpful\Request::get($url)
            ->expectsJson()
            ->send();
            $array = json_decode(json_encode($res->body), true);
            
        return $array;
    }

}
