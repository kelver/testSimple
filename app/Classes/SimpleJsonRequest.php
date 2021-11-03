<?php
namespace App\Classes;

use App\Services\RedisServices;

class SimpleJsonRequest
{
    private static function makeRequest(string $method, string $url, array $parameters = null, array $data = null)
    {
        $resource = new RedisServices();
        $value = $resource->getCacheRedis($url);

        if($value === false || (!in_array($method, ['POST', 'GET']))) {
            echo 'sem redis.';
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
            ]);

            if($method == 'PUT'){
                $parameters = http_build_query($parameters);
            }

            if ($method != 'GET') {
                curl_setopt($curl, CURLOPT_POST, true);
//                var_dump($parameters, $data, http_build_query($parameters));die();
                curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "{$method}");
            }

            $value = curl_exec($curl);

            curl_close($curl);

            if($method === 'PUT'){
                $resource->updateCacheRedis($url, $value);
                return $value;
            }

            if($method === 'DELETE'){
                $resource->deleteCacheRedis($url);
                return $value;
            }
            $resource->setCacheRedis($url, $value);
        }
        return $value;
    }

    public static function get(string $url, array $parameters = null)
    {      
        return self::makeRequest('GET', $url, $parameters);
    }

    public static function post(string $url, array $parameters = null, array $data = null)
    {
		return self::makeRequest('POST', $url, $parameters, $data);
    }

    public static function put(string $url, array $parameters = null, array $data)
    {
        return self::makeRequest('PUT', $url, $parameters, $data);
    }   

    public static function patch(string $url, array $parameters = null, array $data)
    {
        return self::makeRequest('PUT', $url, $parameters, $data);
    }

    public static function delete(string $url, array $parameters = null, array $data = null)
    {
		self::makeRequest('DELETE', $url, $parameters, $data);
    }
}
