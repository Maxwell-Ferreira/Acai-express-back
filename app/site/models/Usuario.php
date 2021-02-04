<?php

namespace Site\models;

if (!defined('URL')){
    header("location: /");
    exit();
}


class Usuario{

    private $tabela = "usuario";
    private $result;
    private $id;
    private $dados;

    public function listar(){

        $listar = new \Site\models\helper\ModelsRead();

        $listar->exeReadEspecifico("SELECT u.*
                          FROM {$this->tabela} u
                          ORDER BY u.id_usuario ASC");
        $this->result['usuarios'] = $listar->getResult();
        return $this->result['usuarios'];
    }

    public function getUsuarioById($id){

        $listar = new \Site\models\helper\ModelsRead();

        $listar->exeReadEspecifico("SELECT u.*
                          FROM {$this->tabela} u
                          WHERE u.id_usuario = :id
                          ORDER BY u.id_usuario ASC", "id=$id");
        $this->result['usuario'] = $listar->getResult();
        return $this->result['usuario'];
    }

    public function getUsuarioByEmail($emailUser){

        $listar = new \Site\models\helper\ModelsRead();

        $listar->exeReadEspecifico("SELECT u.*
                          FROM {$this->tabela} u
                          WHERE u.email_usuario = :emailUser
                          ORDER BY u.email_usuario ASC", "emailUser=$emailUser");
        $this->result['usuario'] = $listar->getResult();
        return $this->result['usuario'];
    }

    public function getUsuariosByString($string){

        $listar = new \Site\models\helper\ModelsRead();

        $listar->exeReadEspecifico("SELECT u.*
                            FROM $this->tabela u
                            WHERE u.nome_usuario LIKE '%".$string."%'
                            ORDER BY u.id_usuario ASC");
        $this->result['usuarios'] = $listar->getResult();
        return $this->result['usuarios'];
    }

    public function cadastrarUsuario(array $dados){
        $this->dados = $dados;
        $this->validarDados();
        if ($this->result){
            $this->exeAddUsuario(); 
        }
    }

    private function validarDados(){
        $this->dados = array_map('strip_tags', $this->dados);
        $this->dados = array_map('trim', $this->dados);
        if (in_array('', $this->dados)){
            $_SESSION['msg'] = "
                    <div class='msg-error'>Erro ao enviar: Os campos obrigatórios não foram preenchidos!</div>";
        }else{
            if (filter_var($this->dados['email_usuario'], FILTER_VALIDATE_EMAIL)){
                $getUsuario = new Usuario();
                $resultado['cliente'] = $getUsuario->getUsuarioByEmail($this->dados['email_usuario']);

                if(!$resultado['cliente']){
                    $this->result = true;
                }else{
                    $_SESSION['msg'] = "<div class='msg-error'>Erro ao enviar: E-mail já cadastrado!</div>";
                }
            }else{
                $_SESSION['msg'] = "<div class='msg-error'>Erro ao enviar: O campo e-mail é inválido!</div>";
            }
        }
    }


    private function exeAddUsuario(){
        $inserir = new \Site\models\helper\ModelsCreate();
        $inserir->exeCreate($this->tabela, $this->dados);

        if ($inserir->getResult()){
            $this->result = true;
            $_SESSION['msg'] = "<div class='msg-success'>Usuario cadastrado com sucesso!</div>";
        }else{
            $_SESSION['msg'] = "<div class='msg-error'>Cliente não enviado com sucesso! Erro: {$inserir->getMsg()}</div>";
        }
    }

    public function excluirUsuario($id){
        $this->id = (int) $id;
        $usuario = $this->getUsuarioById($this->id);
        if ($usuario) {
            $apagarUsuario = new \Site\Models\helper\ModelsDelete();
            $apagarUsuario->exeDelete("usuario", "WHERE id_usuario =:id", "id={$this->id}");
            if ($apagarUsuario->getResult()) {
                $_SESSION['msg'] = "<div class='msg-success'>Usuário excluído com sucesso!</div>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<div class='msg-error'>Erro: Usuário não foi apagado!</div>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<div class='msg-error'>Erro: Usuário não existe!</div>";
            $this->result = false;
        }
    }

    public function editarUsuario(array $dados){
        $this->dados = $dados;
        $this->validarDadosEditar();
        if($this->getResult()){
            $this->updateUsuario();
        }
    }

    private function validarDadosEditar(){
        $this->dados['form'] = array_map('strip_tags', $this->dados['form']);
        $this->dados['form'] = array_map('trim', $this->dados['form']);
        if (in_array('', $this->dados['form'])){
            $_SESSION['msg'] = "<div class='msg-error'>Erro ao enviar: Os campos obrigatórios não foram preenchidos!</div>";
        }else{
            if (filter_var($this->dados['form']['email_usuario'], FILTER_VALIDATE_EMAIL)){
                $getUsuario = new Usuario();
                $resultado['usuario'] = $getUsuario->getUsuarioByEmail($this->dados['form']['email_usuario']);

                if(!$resultado['usuario'] || $resultado['usuario'][0]['email_usuario'] == $this->dados['usuario'][0]['email_usuario']){
                    $this->result = true;
                }else{
                    $this->result = false;
                    $_SESSION['msg'] = "<div class='msg-error'>Erro ao enviar: Este email já está sendo utilizado!</div>";
                }
            }else{
                $_SESSION['msg'] = "<div class='msg-error'>Erro ao enviar: O campo e-mail é inválido!</div>";
            }
        }
    }

    private function updateUsuario()
    { 
        $alterar = new \Site\models\helper\ModelsUpdate();
        $alterar->exeUpdate("usuario", $this->dados['form'], "WHERE id_usuario =:id", "id=" . $this->dados['usuario'][0]['id_usuario']);
        
        if ($alterar->getResult()) {
            $_SESSION['msg'] = "<div class='msg-success'>Usuário atualizado com sucesso!</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='msg-errro'>Erro: O usuario não foi atualizado!</div>";
            $this->result = false;
        }
    }

    public function getResult(){
        return $this->result;
    }

}
