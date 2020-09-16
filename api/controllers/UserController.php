<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 15.04.2019
 * Time: 05:59
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class UserController {
    private $userDao;
    private $responseProvider;

    function __construct($app) {
        $this->userDao = new \App\Dao\UserDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function createUser(Request $request, Response $response, $args) {
        $uuid = \App\Utilities\UuidUtilities::generateUUID();

        $data = $request->getParsedBody();
        $firstName = $data["firstName"];
        $lastName = $data["lastName"];
        $email = $data["email"];
        $password = password_hash($data["password"], PASSWORD_BCRYPT);

        if (!$this->userDao->insertUser($uuid, $firstName, $lastName, $email, $password))
            return $this->responseProvider->withError($response, 'We got problem!', 500);

        return $this->responseProvider->withOk($response, 'User created!');
    }

    public function getUser(Request $request, Response $response, $args) {
        $uuid = $args['uuid'];

        $user = $this->userDao->selectUserByUUID($uuid);

        if (empty($user))
            return $this->responseProvider->withError($response, 'USER_DOES_NOT_EXISTS', 404);

        unset($user["password"]);

        return $this->responseProvider->withOkData($response, 'User found!', $user);
    }

    public function getUsers(Request $request, Response $response, $args) {
        $users = $this->userDao->selectUsers();

        if (empty($users))
            return $this->responseProvider->withError($response, 'There is no one user!', 404);

        unset($users["password"]);

        return $this->responseProvider->withOkData($response, 'Users found!', $users);
    }

    public function changePassword(Request $request, Response $response, $args) {
        $response->getBody()->write("Hellasdo, asd");
        return $response;
    }
}