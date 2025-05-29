<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function admin()
    {
        $this->render('admin');
    }

    public function drink()
    {
        $this->render('drink');
    }

    public function home()
    {
        $this->render('home');
    }

    public function search()
    {
        $this->render('search');
    }

    public function user()
    {
        $this->render('user');
    }

}