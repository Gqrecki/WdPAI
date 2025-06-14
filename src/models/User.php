<?php

class User{
    private $login;
    private $password;
    private $role;

    public function __construct($login, $password, $role = 'user', $isHashed = false)
    {
        $this->login = $login;
        $this->password = $isHashed ? $password : password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password, $isHashed = false)
    {
        $this->password = $isHashed ? $password : password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
}