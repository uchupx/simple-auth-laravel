<?php

namespace App\Plugin;

class Jwt
{
    // protected $payload;
    protected $secret;
    protected $signature;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function create(object $payload)
    {
        $payload->exp      = time() + 3600; // token will expired in 1 hour
        // $payload->exp      = time() - 3600; // test expired token
        $header_encode     = base64_encode(json_encode(array('alg' => 'HS256', 'typ' => 'jwt')));
        $payload_encode    = base64_encode(json_encode($payload));
        $signature         = hash_hmac('SHA256', "$header_encode.$payload_encode", $this->secret, true);
        $signature_encoded = base64_encode($signature);

        return array("token" => "$header_encode.$payload_encode.$signature_encoded", "expired_in" => 3600);
    }

    public function parse(string $jwt)
    {
        $parsed = [];
        $items  = explode('.', $jwt);

        array_push($parsed, base64_decode($items[0]));
        array_push($parsed, base64_decode($items[1]));
        array_push($parsed, $items[2]);

        return $parsed;
    }

    public function check(string $jwt)
    {
        $parsed_jwt = $this->parse($jwt);
        $payload    = json_decode($parsed_jwt[1]);

        if (($payload->exp - time()) < 0) {
            return false;
        }

        $items = explode('.', $jwt);

        $signature        = hash_hmac('SHA256', $items[0] . "." . $items[1], $this->secret, true);
        $hashed_signature = base64_encode($signature);

        if ($hashed_signature === $items[2]) {
            return true;
        } else {
            return false;
        }
    }
}
