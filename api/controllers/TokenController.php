<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 25.04.2019
 * Time: 05:42
 */

namespace App\Controllers;

use App\Utilities\JwtUtilities;

use App\Utilities\UuidUtilities;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TokenController {
    private $userDao;
    private $tokenDao;
    private $responseProvider;

    function __construct($app) {
        $this->userDao = new \App\Dao\UserDao($app->db);
        $this->tokenDao = new \App\Dao\TokenDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function createToken(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $email = $data["email"];
        $password = $data["password"];
        $deviceImei = $data["imei"];

        $user = $this->userDao->selectUserByEmail($email);
        if (empty($user))
            return $this->responseProvider->withError($response, "USER_DOES_NOT_EXISTS", 401);

        if (!password_verify($password, $user["password"]))
            return $this->responseProvider->withError($response, "INVALID_PASSWORD", 401);

        $token = JwtUtilities::generateNewToken($user["id"]);
        $refreshToken = UuidUtilities::generateUUID();

        $this->tokenDao->insertRefreshToken($refreshToken, $deviceImei, $user["id"]);

        $tokens = array(
            "token" => $token,
            "refresh_token" => $refreshToken
        );

        return $this->responseProvider->withOkData($response, 'New tokens generated!', [$tokens]);
    }

    public function renewToken(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $refreshToken = $data["refresh_token"];

        $tokenData = $this->tokenDao->selectRefreshToken($refreshToken);
        if (empty($tokenData))
            return $this->responseProvider->withError($response, "Token invalid!", 401);

        $token = [["token" => JwtUtilities::generateNewToken($tokenData["user_id"])]];

        return $this->responseProvider->withOkData($response, 'New token generated!', $token);
    }
}