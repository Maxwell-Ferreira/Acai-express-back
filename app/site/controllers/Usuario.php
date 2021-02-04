<?php

namespace App\site\controllers;

 if (!defined('URL')){
     header("location: /");
     exit();
 }

class Usuario {
     private $dados;

    public function index() {

        if(isset($_SESSION['idUsuario'])){
            $usuarios = new \Site\Models\Usuario();
            $this->dados["usuarios"] = $usuarios->listar();

            $carregarView = new \Config\ConfigView("usuarios/index", $this->dados);
            $carregarView->renderizar();
        }else{
            header("location: ".URL."login/index");
        }
    }

    public function cadastrar(){
       
        if(isset($_SESSION['idUsuario'])){
            $this->dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($this->dados['btnCadastrar'])) {
                unset($this->dados['btnCadastrar']);

                $cadastrar = new \Site\Models\Usuario();
                $cadastrar->cadastrarUsuario($this->dados);
                
            }

            $carregarView = new \Config\ConfigView("usuarios/cadastrar", $this->dados);
            $carregarView->renderizar();
        }else{
            header("location: ".URL."login/index");
        }
    }

    public function excluir($id = null){
        if(isset($_SESSION['idUsuario'])){
            
            $excluir = new \Site\Models\Usuario();
            $excluir->excluirUsuario($id);

            header("location: ".URL."usuario/index");
        }else{
            header("location: ".URL."login/index");
        }
    }

    public function editar($id = null){
        if(isset($_SESSION['idUsuario'])){
            
            $usuario = new \Site\Models\Usuario();
            $this->dados['usuario'] = $usuario->getUsuarioById($id);

            if($this->dados['usuario']){
                unset($_SESSION['msg']);
                $this->dados['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
                if (!empty($this->dados['form']['btnEditar'])) {
                    unset($this->dados['form']['btnEditar']);

                    $editar = new \Site\Models\Usuario();
                    $editar->editarUsuario($this->dados);

                    header("location: ".URL."usuario/index");
                }

                $carregarView = new \Config\ConfigView("usuarios/editar", $this->dados);
                $carregarView->renderizar();
            }else{
                $_SESSION['msg'] = "<div class='msg-error'>Erro: Usuário não existe</div>";
                header("location: ".URL."usuario/index");
            }
        }else{
            header("location: ".URL."login/index");
        }
    }
 
    public function buscar(){
        if(isset($_SESSION['idUsuario'])){

            $busca = $_GET['string'];

            if(strcmp($busca, "")){
                if(isset($_GET['buscarId'])){
                    $usuarios = new \Site\Models\Usuario();
                    $this->dados["usuarios"] = $usuarios->getUsuarioById($busca);
                }else{
                    $usuarios = new \Site\Models\Usuario();
                    $this->dados["usuarios"] = $usuarios->getUsuariosByString($busca);
                }
            }

            $carregarView = new \Config\ConfigView("usuarios/buscar", $this->dados);
            $carregarView->renderizar();
        }else{
            header("location: ".URL."login/index");
        }
    }

}
