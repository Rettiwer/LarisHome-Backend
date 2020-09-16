<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 15.04.2019
 * Time: 09:20
 */

namespace App\Utilities;

use \Firebase\JWT\JWT;

class JwtUtilities
{
    public static function generateNewToken($uuid) {
        $privateKey = file_get_contents(__DIR__ . '/jwtRS256.key');
        $token = array(
            "uuid" => $uuid,
            "iss" => "larishome.com",
            "aud" => ["larishome.com"],
            "iat" => time(),
            "exp" =>  time() + 60

        );

        $jwt = JWT::encode($token, $privateKey, 'RS256');
        return $jwt;
    }

    /*
     *  Validate token and if valid return user token
     */
    public static function validateToken($token) {
        $publicKey = file_get_contents(__DIR__ . '/jwtRS256.key.pub');
        try {
            $payload = JWT::decode($token, $publicKey, array('RS256'));
            return $payload->uuid;
        }
        catch (\Exception $exception) {
            return null;
        }
    }
}