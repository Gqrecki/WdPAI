<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {

    public function getUserByLogin($login) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new User($row['login'], $row['password'], $row['role'], true);
    }

    public function addUser($login, $password, $role = 'user') {
        $stmt = $this->db->prepare('INSERT INTO users (login, password, role) VALUES (:login, :password, :role)');
        return $stmt->execute([
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role
        ]);
    }
}
