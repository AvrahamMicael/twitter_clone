<?php

namespace App\Controllers;

use App\Connection;
//MF
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        $this->validaAutenticacao();
        $this->pagination('tweet');
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

        $page = isset($_GET['page']) ? '?page='.$_GET['page'] : '';

        header('location: /timeline'.$page);
    }

    public function validaAutenticacao() {
        session_start();
        if(empty($_SESSION['id']) && empty($_SESSION['id'])) header('location: /');
    }

    public function quemSeguir() {
        $this->validaAutenticacao();
        $this->getInfoUsuario();
        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $this->view->search = isset($_GET['search']) ? $_GET['search'] : $search;
        $this->pagination('usuario');
        $this->render('quemSeguir');
    }

    public function action() {
        $this->validaAutenticacao();

        $url = isset($_GET['search']) ? '?search='.$_GET['search'].'&' : '?';
        $page = isset($_GET['page']) ? 'page='.$_GET['page'] : '';

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) && $_GET['id_usuario'] != $_SESSION['id'] ? $_GET['id_usuario'] : '';

        $seguidor = Container::getModel('seguidor');
        $seguidor->__set('id', $_SESSION['id']);
        $seguidor->__set('id_usuario_seguindo', $id_usuario_seguindo);

        if($action == 'seguir') $seguidor->seguir();
        else $seguidor->deixarDeSeguir();

        header("location: /quem_seguir$url$page");
    }

    private function pagination(string $type) {
        $types = $type.'s';
        $id = $type == 'tweet' ? 'id_usuario' : 'id';
        $searched = null;//quemSeguir

        $type = Container::getModel($type);
        $type->__set($id, $_SESSION['id']);

        if($types == 'usuarios' && !empty($this->view->search)) {
            $type->__set('nome', $this->view->search);
            $searched = true;
        }
        
        $limit = 10;
        $total_registros = $type->getTotalRegistros($searched);
        $this->view->total_paginas = ceil($total_registros['total'] / $limit);
        
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $page = isset($_POST['page']) ? $_POST['page'] : $page;
        $page = $page > 0 && $page <= $this->view->total_paginas ? $page : 1;
        $this->view->current_page = $page;
        $offset = ($page - 1) * $limit;

        $this->view->$types = $type->getPorPagina($limit, $offset, $searched);
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