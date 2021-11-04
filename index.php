<?php
require_once 'vendor/autoload.php';

use App\Classes\RequestValidate;
use App\Classes\SimpleJsonRequest;

$request = RequestValidate::mountRoute($_REQUEST);
$result = SimpleJsonRequest::{$request['verbo']}($request['url'], $request['parametros'], $request['data']);

echo($result);
