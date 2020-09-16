<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 18.04.2019
 * Time: 22:46
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeviceController {
    private $deviceDao;
    private $responseProvider;

    function __construct($app) {
        $this->deviceDao = new \App\Dao\DeviceDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function createDevice(Request $request, Response $response, $args) {
        $uuid = \App\Utilities\UuidUtilities::generateUUID();

        $data = $request->getParsedBody();
        $name = $data["name"];
        $type = $data["type"];
        $icon = $data["icon"];
        $roomId = $data["room_id"];

        if (!$this->deviceDao->insertDevice($uuid ,$name, $type, $icon, $roomId))
            return $this->responseProvider->withError($response, 'We got problem!', 500);

        $data = array(array("id" => $uuid));

        return $this->responseProvider->withOkData($response, 'Device added!', $data);
    }

    public function getDevice(Request $request, Response $response, $args) {
        $uuid = $args['uuid'];

        $device = $this->deviceDao->selectDevice($uuid);
        if (empty($device))
            return $this->responseProvider->withError($response, 'Device not found!', 404);

        return $this->responseProvider->withOkData($response, 'Device found!', $device);
    }

    public function getDevicesFromRoom(Request $request, Response $response, $args) {
        $uuid = $args['uuid'];

        $device = $this->deviceDao->selectDevicesByRoom($uuid);
        if (empty($device))
            return $this->responseProvider->withError($response, 'Device not found!', 404);

        return $this->responseProvider->withOkData($response, 'Device found!', $device);
    }
}