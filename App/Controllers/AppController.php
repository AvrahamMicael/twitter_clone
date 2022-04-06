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

        $limit = 10;
        $total_tweets = $tweet->getTotalRegistros();
        $this->view->total_paginas = ceil($total_tweets['total'] / $limit);

        $page = isset($_GET['page']) && $_GET['page'] <= $this->view->total_paginas ? $_GET['page'] : 1;
        $this->view->current_page = $page;
        $offset = ($page - 1) * $limit;
        $this->view->tweets = $tweet->getPorPagina($limit, $offset);
        
        $this->getInfoUsuario();

        $this->render('timeline');
    }

    public function tweet() {
        $this->validaAutenticacao();

        if(!empty($_POST['tweet'])) {
            $tweet = Container::getModel('tweet');
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
            $tweet->salvar();    
        }

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

        $this->getInfoUsuario();

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
        $id_usuario_seguindo = isset($_GET['id_usuario']) && $_GET['id_usuario'] != $_SESSION['id'] ? $_GET['id_usuario'] : '';

        $seguidor = Container::getModel('seguidor');
        $seguidor->__set('id', $_SESSION['id']);
        $seguidor->__set('id_usuario_seguindo', $id_usuario_seguindo);

        if($acao == 'seguir') $seguidor->seguir();
        else $seguidor->deixarDeSeguir();

        header('location: /quem_seguir'.$url);
    }

    private function getInfoUsuario() {
        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
    }
}