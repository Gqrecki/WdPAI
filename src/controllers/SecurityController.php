<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController {

    public function login()
    {
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            return $this->render('login');
        }
        $user = new User("user@user.pl", "123");
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($user->getEmail() !== $email) {
            return $this->render('login', ['message' => ['Invalid email.']]);
        }
        if ($user->getPassword() !== $password) {
            return $this->render('login', ['message' => ['Invalid password.']]);
        }
        return $this->render('home');
    }
}