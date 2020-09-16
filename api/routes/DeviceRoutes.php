<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 19.04.2019
 * Time: 17:34
 */

$app->group('/api', function () {
    $this->post('/device', App\Controllers\DeviceController::class . ':createDevice');

    $this->get('/device', App\Controllers\DeviceController::class . ':getDevices');
    $this->get('/device/{uuid}', App\Controllers\DeviceController::class . ':getDevice');

    $this->put('/device/{uuid}/name', App\Controllers\DeviceController::class . ':changeName');
    $this->put('/device/{uuid}/room', App\Controllers\DeviceController::class . ':changeRoom');
});