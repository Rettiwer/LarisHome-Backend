<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 15.04.2019
 * Time: 06:47
 */

$app->group('/api', function () {
    $this->post('/user', App\Controllers\UserController::class . ':createUser');

    $this->get('/user', App\Controllers\UserController::class . ':getUsers');
    $this->get('/user/{uuid}', App\Controllers\UserController::class . ':getUser');

    $this->put('/user/{uuid}/password', App\Controllers\UserController::class . ':changePassword');
});