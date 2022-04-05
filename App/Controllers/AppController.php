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

    public function removerTweet() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('tweet');
        $tweet->__set('id', $_POST['tweet_id']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->removerTweet();

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

        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);
        
        if(empty($search)) $usuarios = $usuario->getAll(true);
        else {
            $usuario->__set('nome', $search);
            $usuarios = $usuario->getAll();
        }

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