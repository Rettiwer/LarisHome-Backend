<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 13.05.2019
 * Time: 23:57
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ServerController {
    private $responseProvider;

    function __construct($app) {
        $this->responseProvider = $app->ResponseProvider;
    }

    public function getServerInfo(Request $request, Response $response, $args) {
        $torAddress = file_get_contents(__DIR__ . '/../utilities/hostname', FILE_IGNORE_NEW_LINES);

        $torAddress = str_replace(array("\r", "\n"), '', $torAddress);
        $torAddress = array(array( "torAddress" => $torAddress ));

        return $this->responseProvider->withOkData($response, 'SERVER_DATA_FOUND', $torAddress);
    }

    public function getStatus(Request $request, Response $response, $args) {
        return $this->responseProvider->withOk($response, 'SERVER_STATUS_OK');
    }
}