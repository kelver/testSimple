<?php
namespace App\Classes;

class SimpleJsonRequest
{
    private static function makeRequest(string $method, string $url, array $parameters = null, array $data = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ]);

        if($method != 'GET'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  $method);
        }

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
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
		
        return [];
    }
}
