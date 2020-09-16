<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 20.04.2019
 * Time: 16:58
 */

namespace App\Dao;


class RoomDao {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function insertRoom($uuid, $name, $icon, $icon_color, $members) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO rooms VALUES (:uuid, :name, :icon, :icon_color)");
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':icon_color', $icon_color);

            $stmt->execute();



            foreach ($members as $user_uuid) {
                $stmt = $this->db->prepare("INSERT INTO user_rooms VALUES (:room_uuid, :user_uuid)");
                $stmt->bindParam(':room_uuid', $uuid);
                $stmt->bindParam(':user_uuid', $user_uuid);
                $stmt->execute();
            }

            $this->db->commit();

            return true;
        }
        catch (\PDOException $exception) {
            $this->db->rollBack();
            return false;
        }
    }

    public function selectRoom($uuid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = :id");
            $stmt->bindValue(':id', $uuid,  \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function selectRooms($uuid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM rooms INNER JOIN user_rooms ON rooms.id = user_rooms.room_id WHERE user_rooms.user_id = :uuid");
            $stmt->bindValue(':uuid', $uuid,  \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function selectRoomDevices($uuid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM devices WHERE room_id = :id");
            $stmt->bindValue(':id', $uuid,  \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}