<?php

namespace App\Services;

use Predis\Autoloader;
use Predis\Client;

class RedisServices
{
    protected $client;
    public function __construct()
    {
        Autoloader::register();
        $this->client = new Client();
    }

    protected function getResource(string $url): array
    {
        $urlParts = parse_url($url);
        $resource = explode('/', explode('api/', $urlParts['path'])[1]);
        $data = [
            'resource' => $resource[0],
            'identify' => isset($resource[1]) ? ":{$resource[1]}" : ''
        ];
        return $data;
    }

    public function getCacheRedis(string $url)
    {
        $data = $this->getResource($url);

        $cacheName = "{$data['resource']}{$data['identify']}";
        $value = $this->client->get($cacheName);
        if(!$value){
            return false;
        }

        return $value;
    }

    public function deleteCacheRedis(string $url): bool
    {
        $data = $this->getResource($url);
        $cacheName = "{$data['resource']}{$data['identify']}";
        $value = $this->client->del($cacheName);

        return true;
    }

    public function updateCacheRedis(string $url, $value): bool
    {
        $data = $this->getResource($url);
        $cacheName = "{$data['resource']}{$data['identify']}";
        $this->client->set($cacheName, $value);

        return true;
    }

    public function setCacheRedis(string $url, $value): bool
    {
        $data = $this->getResource($url);

        if($data['identify'] == ''){
            foreach(json_decode($value) as $v){
                if(count($v) <= 1){
                    $cacheName = "{$data['resource']}:{$v->identify}";
                    $this->client->append($cacheName, json_encode($v));
                    return true;
                }

                $cacheName = "{$data['resource']}";
                $this->client->append($cacheName, json_encode($v));

            }
            return true;
        }
        $cacheName = "{$data['resource']}{$data['identify']}";
        $this->client->set($cacheName, json_encode($value));
        return true;
    }
}