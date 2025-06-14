<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repositories/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        if ($this->isPost()) {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userRepository->getUserByLogin($login);
            if (!$user || !password_verify($password, $user->getPassword())) {
                return $this->render('login', ['message' => ['Nieprawidłowy login lub hasło.']]);
            }
            $_SESSION['user'] = [
                'login' => $user->getLogin(),
                'role' => $user->getRole()
            ];
            header('Location: home');
            exit();
        }
        $this->render('login');
    }

    public function logout()
    {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        header('Location: login');
        exit();
    }

    public function register()
    {
        if ($this->isPost()) {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            if (strlen($login) < 3 || strlen($password) < 5) {
                return $this->render('register', ['message' => ['Login lub hasło za krótkie.']]);
            }
            if ($password !== $password2) {
                return $this->render('register', ['message' => ['Hasła nie są zgodne.']]);
            }
            if ($this->userRepository->getUserByLogin($login)) {
                return $this->render('register', ['message' => ['Login zajęty.']]);
            }
            $this->userRepository->addUser($login, $password);
            return $this->render('login', ['message' => ['Rejestracja udana. Zaloguj się.']]);
        }
        $this->render('register');
    }
}