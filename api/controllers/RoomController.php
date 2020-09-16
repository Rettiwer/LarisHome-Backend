<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 20.04.2019
 * Time: 16:47
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RoomController {
    private $roomDao;
    private $responseProvider;

    function __construct($app) {
        $this->roomDao = new \App\Dao\RoomDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function createRoom(Request $request, Response $response, $args) {
        $uuid = \App\Utilities\UuidUtilities::generateUUID();

        $data = $request->getParsedBody();
        $name = $data["name"];
        $icon = $data["icon"];
        $icon_color = $data["icon_color"];
        $members = $data["members"];

        if (!$this->roomDao->insertRoom($uuid ,$name, $icon, $icon_color, $members))
            return $this->responseProvider->withError($response, 'ROOM_NOT_ADDED', 500);

        return $this->responseProvider->withOk($response, 'ROOM_ADDED');
    }

    public function getRoom(Request $request, Response $response, $args) {
        $uuid = $args['uuid'];

        $room = $this->roomDao->selectRoom($uuid);
        if (empty($room))
            return $this->responseProvider->withError($response, 'ROOM_NOT_FOUND', 404);

        return $this->responseProvider->withOkData($response, 'ROOM_FOUND', $room);
    }

    public function getRooms(Request $request, Response $response, $args) {
        $userUuid = $response->getHeader("UserUuid")[0];

        $room = $this->roomDao->selectRooms($userUuid);
        if (empty($room))
            return $this->responseProvider->withError($response, 'ROOMS_NOT_FOUND', 404);

        return $this->responseProvider->withOkData($response, 'ROOM_FOUND', $room);
    }

    public function getRoomDevices(Request $request, Response $response, $args) {
        $uuid = $args['uuid'];

        $devices = $this->roomDao->selectRoomDevices($uuid);
        if (empty($devices))
            return $this->responseProvider->withError($response, 'DEVICES_NOT_FOUND', 404);

        return $this->responseProvider->withOkData($response, 'DEVICES_FOUND', $devices);
    }
}