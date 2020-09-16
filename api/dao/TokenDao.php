<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 25.04.2019
 * Time: 23:55
 */

namespace App\Dao;


class TokenDao {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function insertRefreshToken($refreshToken, $deviceImei, $userId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tokens VALUES(:token, :device_imei, :user_id)");
            $stmt->bindParam(':token', $refreshToken);
            $stmt->bindParam(':device_imei', $deviceImei);
            $stmt->bindParam(':user_id', $userId);

            $stmt->execute();

            return true;
        }
        catch (\PDOException $exception) {
            return false;
        }
    }

    public function selectRefreshToken($refreshToken) {
        try {
            $stmt = $this->db->prepare("SELECT user_id FROM tokens WHERE token = :token");
            $stmt->bindValue(':token', $refreshToken, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

}