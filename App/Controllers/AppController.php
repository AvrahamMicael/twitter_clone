<?php

namespace App\Controllers;

use App\Connection;
//MF
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        session_start();

        if(empty($_SESSION['id']) && empty($_SESSION['id'])) header('location: /');

        $this->render('timeline');
    }
}