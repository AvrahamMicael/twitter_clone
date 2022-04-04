<?php

namespace App\Controllers;

use App\Connection;
//MF
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);

        $this->view->tweets = $tweet->getAll();

        $this->render('timeline');
    }

    public function tweet() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->salvar();

        header('location: /timeline');
    }

    public function validaAutenticacao() {
        session_start();
        if(empty($_SESSION['id']) && empty($_SESSION['id'])) header('location: /');
    }

    public function quemSeguir() {
        $this->validaAutenticacao();

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $this->view->search = $search;

        $usuarios = [];

        if(!empty($search)) {
            $usuario = Container::getModel('usuario');
            $usuario->__set('nome', $search);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();
        }

        // segue ou não
        $seguidor = Container::getModel('seguidor');
        $seguidor->__set('id', $_SESSION['id']);
        
        foreach($usuarios as $idx => $usuario) {
            $seguidor->__set('id_usuario_seguindo', $usuario['id']);

            if($seguidor->segue()) $usuarios[$idx]['segue'] = true;
            else $usuarios[$idx]['segue'] = false;
        }
        //

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
    }

    public function acao() {
        $this->validaAutenticacao();

        $url = isset($_GET['search']) ? '?search='.$_GET['search'] : '';

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $seguidor = Container::getModel('seguidor');
        $seguidor->__set('id', $_SESSION['id']);
        $seguidor->__set('id_usuario_seguindo', $id_usuario_seguindo);

        if($acao == 'seguir') $seguidor->seguir();
        else $seguidor->deixarDeSeguir();

        header('location: /quem_seguir'.$url);
    }
}