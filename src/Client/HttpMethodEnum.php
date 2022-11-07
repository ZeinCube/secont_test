<?php

namespace App\Client;

enum HttpMethodEnum: string
{
    case POST = 'POST';
    case GET = 'GET';
    case PUT = 'PUT';
}