<?php

namespace App\Classes;

use PHPUnit\Util\Exception;

class RequestValidate
{
    public static function mountRoute($request)
    {
        try{
            if(count($request) <= 0){
                parse_str(file_get_contents('php://input'), $request);
            }
            
            if(!isset($request['link'])){
                throw new Exception(json_encode(['erro' => ['message' => 'Unrecognized resource.']]), 404);
            }
            $data = null;
            $url = $request['link'];
            unset($request['link']);
            $verbo = strtolower($_SERVER['REQUEST_METHOD']);

            switch ($verbo){
                case 'get':
                case 'delete':
                    break;
                case 'post':
                case 'put':
                case 'patch':
                    $parametros = $request;
                    $data = $parametros;
                    break;
            }
        }catch (\Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }
        return ['verbo' => $verbo, 'url' => $url, 'parametros' => $parametros, 'data' => $data];
    }
}