<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ParserHelper
{
    public function parse(string $input)
    {
        $type = 'http';

        if (str_contains($input, '://')) {
            [$type, $input] = explode('://', $input, 2);
        }

        $host = $port = $login = $password = null;

        if (str_contains($input, '@')) {
            [$auth, $hostPort] = explode('@', $input, 2);

            if (str_contains($auth, ':')) {
                [$login, $password] = explode(':', $auth, 2);
            } else {
                $login = $auth;
            }

            [$host, $port] = explode(':', $hostPort, 2);
        } else {
            $parts = explode(':', $input);

            if (count($parts) === 4) {
                [$host, $port, $login, $password] = $parts;
            } elseif (count($parts) === 2) {
                [$host, $port] = $parts;
            }
        }

        if (empty($host) || empty($port)) {
            return null;
        }
        
        return compact('host', 'port', 'login', 'password', 'type');
    }
}