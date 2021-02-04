<?php

namespace Site\models\helper;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class ModelsUpdate extends ModelsConn 
{

    private $tabela;
    private $dados;
    private $query;
    private $conn;
    private $result;
    private $termos;
    private $values;

    function getResult()
    {
        return $this->result;
    }

    public function exeUpdate($tabela, array $dados, $termos = null, $parseString = null)
    {
        $this->tabela = (string) $tabela;
        $this->dados = $dados;
        $this->termos = (string) $termos;

        //var_dump($this->dados); exit();

        parse_str($parseString, $this->values);
        $this->getIntrucao();
        $this->executarInstrucao();
    }

    private function getIntrucao()
    {
        foreach ($this->dados as $key => $value) {
            $values[] = $key . '= :' . $key;
        }
        $values = implode(', ', $values);
        //var_dump($values); exit();
        $this->query = "UPDATE {$this->tabela} SET {$values} {$this->termos}";

    }

    private function executarInstrucao() 
    {
        $this->conexao();
        try {
           // var_dump($this->query); exit();
            $this->query->execute(array_merge($this->dados, $this->values));
            $this->result = true;
        } catch (Exception $ex) {
            $this->result = null;
        }
    }

    private function conexao()
    {
        $this->conn = parent::getConn();
        $this->query = $this->conn->prepare($this->query);
    }

}