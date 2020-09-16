<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 15.04.2019
 * Time: 07:58
 */

namespace App\Dao;

class UserDao {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function insertUser($uuid, $firstName, $lastName, $email, $password) {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (id, first_name, last_name, email, password) VALUES (:uuid, :firstName, :lastName, :email, :password)");
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            return true;
        }
        catch (\PDOException $exception) {
            return false;
        }
    }

    public function selectUserByUUID($uuid) {
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, password, is_admin FROM users WHERE id = :id");
            $stmt->bindValue(':id', $uuid, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function selectUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, password, is_admin FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function selectUsers() {
        try {
            $stmt = $this->db->query("SELECT id, first_name, last_name, email FROM users");

            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}