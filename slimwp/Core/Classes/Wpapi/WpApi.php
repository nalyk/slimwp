<?php
namespace Slimwp\Core\Classes\Wpapi;

class WpApi {

    protected $settings;
    protected $cache;

    public function __construct($settings, $cache) {
        $this->settings = $settings;
        $this->cache = $cache;
    }

    public function getLastPosts($page = 1, $cached = false)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/posts?page=".$page."&status=publish&_embed";

        if ($cached = true) {
            $key = "posts_".$page;
            $CachedString = $this->cache->getItem($key);

            if (is_null($CachedString->get())) {
                $res = \Httpful\Request::get($url)
                    ->expectsJson()
                    ->send();
                $array = json_decode(json_encode($res->body), true);

                $CachedString->set($array)->expiresAfter(120);
                $this->cache->save($CachedString);

                $array = $CachedString->get();
            } else {
                $array = $CachedString->get();
            }
        } else {
            $res = \Httpful\Request::get($url)
                ->expectsJson()
                ->send();
            $array = json_decode(json_encode($res->body), true);
        }

        return $array;
    }

    public function getSinglePost($slug, $cached = false)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/posts?slug=".$slug."&status=publish&_embed";

        if ($cached = true) {
            $key = "posts_single_".hash('crc32', $slug);
            $CachedString = $this->cache->getItem($key);

            if (is_null($CachedString->get())) {
                $res = \Httpful\Request::get($url)
                    ->expectsJson()
                    ->send();
                $array = json_decode(json_encode($res->body), true);

                $CachedString->set($array)->expiresAfter(120);
                $this->cache->save($CachedString);

                $array = $CachedString->get();
            } else {
                $array = $CachedString->get();
            }
        } else {
            $res = \Httpful\Request::get($url)
                ->expectsJson()
                ->send();
            $array = json_decode(json_encode($res->body), true);
        }

        return $array[0];
    }

    public function getObjects($objects, $page = 1, $cached = false)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/".$objects."?page=".$page."&status=publish&_embed";

        if ($cached = true) {
            $key = $objects."_".$page;
            $CachedString = $this->cache->getItem($key);

            if (is_null($CachedString->get())) {
                $res = \Httpful\Request::get($url)
                    ->expectsJson()
                    ->send();
                $array = json_decode(json_encode($res->body), true);

                $CachedString->set($array)->expiresAfter(120);
                $this->cache->save($CachedString);

                $array = $CachedString->get();
            } else {
                $array = $CachedString->get();
            }
        } else {
            $res = \Httpful\Request::get($url)
                ->expectsJson()
                ->send();
            $array = json_decode(json_encode($res->body), true);
        }

        return $array;
    }

    public function getObject($objects, $slug, $cached = false)
    {
        $url = $this->settings['slimwp']['wphost'] . "/wp-json/wp/v2/".$objects."?slug=".$slug."&status=publish&_embed";

        if ($cached = true) {
            $key = $objects."_single_".hash('crc32', $slug);
            $CachedString = $this->cache->getItem($key);

            if (is_null($CachedString->get())) {
                $res = \Httpful\Request::get($url)
                    ->expectsJson()
                    ->send();
                $array = json_decode(json_encode($res->body), true);

                $CachedString->set($array)->expiresAfter(120);
                $this->cache->save($CachedString);

                $array = $CachedString->get();
            } else {
                $array = $CachedString->get();
            }
        } else {
            $res = \Httpful\Request::get($url)
                ->expectsJson()
                ->send();
            $array = json_decode(json_encode($res->body), true);
        }

        return $array[0];
    }

}
