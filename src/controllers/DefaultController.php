<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function __construct()
    {
        parent::__construct();
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    }

    public function admin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: login');
            exit();
        }
        require_once __DIR__ . '/../../Database.php';
        $db = Database::connect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_drink'])) {
            $stmt = $db->prepare('INSERT INTO drinks (name, volume, alcohol_content, price_range, description) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $_POST['name'], $_POST['volume'], $_POST['alcohol_content'], $_POST['price_range'], $_POST['description']
            ]);
            header('Location: admin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_drink_id'])) {
            $drinkId = $_POST['delete_drink_id'];

            $usersInfo = $db->query('SELECT id, favorite_drinks FROM users_info')->fetchAll(PDO::FETCH_ASSOC);
            foreach ($usersInfo as $info) {
                $favorites = array_filter(array_map('trim', explode(',', $info['favorite_drinks'] ?? '')));
                $newFavorites = array_diff($favorites, [$drinkId]);
                $favString = implode(',', $newFavorites);
                $stmtUpdate = $db->prepare('UPDATE users_info SET favorite_drinks = ? WHERE id = ?');
                $stmtUpdate->execute([$favString, $info['id']]);
            }

            $stmt = $db->prepare('DELETE FROM drinks WHERE id = ?');
            $stmt->execute([$drinkId]);
            header('Location: admin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
            $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$_POST['delete_user_id']]);
            header('Location: admin');
            exit();
        }

        $this->render('admin');
    }

    public function drink()
    {
        $this->render('drink');
    }

    public function home()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }
        $this->render('home');
    }

    public function search()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }
        $this->render('search');
    }

    public function user()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }
        $this->render('user');
    }
}