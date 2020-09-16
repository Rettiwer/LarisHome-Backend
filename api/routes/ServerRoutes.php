<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 13.05.2019
 * Time: 23:57
 */

$app->group('/api', function () {
    $this->post('/server', App\controllers\ServerController::class . ':getStatus');

    $this->get('/server', App\controllers\ServerController::class . ':getServerInfo');
});