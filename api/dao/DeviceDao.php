<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 19.04.2019
 * Time: 17:40
 */

namespace App\Dao;

class DeviceDao {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function insertDevice($uuid, $name, $type, $icon, $roomId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO devices (id, name, icon, room_id) VALUES (:uuid, :name, :type, :icon, :room_id)");
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':room_id', $roomId);

            $stmt->execute();

            return true;
        }
        catch (\PDOException $exception) {
            return false;
        }
    }

    public function selectDevice($uuid) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM devices WHERE id = :uuid");
            $stmt->bindValue(':uuid', $uuid,  \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}