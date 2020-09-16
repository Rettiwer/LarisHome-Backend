<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 25.04.2019
 * Time: 05:38
 */


$app->group('/api', function () {
    $this->post('/token', App\Controllers\TokenController::class . ':createToken');
    /*->add(function ($request, $response, $next) {
        $token = $request->getHeader("Authorization")[0];
        if ($token == null)
            return $this->ResponseProvider->withError($response, 'No token!', 401);
        return $next($request, $response);
    });*/

    $this->put('/token', App\Controllers\TokenController::class . ':renewToken');
});