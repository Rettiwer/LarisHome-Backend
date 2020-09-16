<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 20.04.2019
 * Time: 17:36
 */

use App\Utilities\JwtUtilities;


$app->group('/api', function () {
    $this->post('/room', App\Controllers\RoomController::class . ':createRoom');

    $this->get('/room', App\Controllers\RoomController::class . ':getRooms');
    $this->get('/room/{uuid}', App\Controllers\RoomController::class . ':getRoom');
    $this->get('/room/{uuid}/devices', App\Controllers\RoomController::class . ':getRoomDevices');

    $this->put('/room/{uuid}/name', App\Controllers\RoomController::class . ':changeName');
    $this->put('/room/{uuid}/icon', App\Controllers\RoomController::class . ':changeIcon');
})->add(function ($request, $response, $next) {
    $token = $request->getHeader("Authorization")[0];

    if ($token == null)
        return $this->ResponseProvider->withError($response, 'No token!', 401);

    $userUuid = JwtUtilities::validateToken($token);
    if (!$userUuid)
        return $this->ResponseProvider->withError($response, "INVALID_TOKEN", 401);

    $newResponse = $response->withAddedHeader('UserUuid', $userUuid);

    return $next($request, $newResponse);
});;