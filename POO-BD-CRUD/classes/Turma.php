<?php
/**
     * Classe Turma
     * @author Bismarck Pereira
     * @version 1.0
     * @access public
     */
class Turma{
    /**
     * Atributo código
     * @access private
     * @name $codigo
     */
    private $codigo;
    /**
     * Atributo curso
     * @access private
     * @name $curso
     */
    private $curso;
    /**
     * Atributo nome
     * @access private
     * @name $nome
     */
    private $nome;
    /**
     * Atributo professor
     * @access private
     * @name $professor
     */
    private $professor;
/**
 * Metodo setTurma,responsavel por receber os atributos das variaveis codigo,curso,nome,professor
 * @access public 
 * @nome setTurma
 * @param int
 * @name $codigo
 * @param String
 * @name $curso
 * @param String
 * @name $nome
 * @param Objetc
 * @name $professor
 */
    public function setTurma($codigo, $curso, $nome, $professor){
        $this->codigo=$codigo;
        $this->curso=$curso;
        $this->nome=$nome;
        $this->professor=$professor;
    }
/**
 * Método responsavel por retornar o codigo de Turmas
 * @access public
 * @name getCodigo
 * @return Int
 *
 */
    public function getCodigo(){
        return $this->codigo;
    }
/**
 * Método responsavel por retornar o Curso de Turmas
 * @access public
 * @name getCurso
 * @return String
 */
    public function getCurso(){
        return $this->curso;
    }
    /**
 * Método responsavel por retornar o Nome de Turmas
 * @access public
 * @name getNome
 * @return String
 */
    public function getNome(){
        return $this->nome;
    } 
    /**
    * Método responsavel por retornar o Professor de Turmas
    * @access public
    * @name getProfessor
    * @return Object
    */
    public function getProfessor(){
        return $this->professor;
    }
/**
    * Método responsavel por listar as informações obtidas no banco de dados de Turmas
    * @access public
    * @name listar
    * @return Object
    */
    public static function listar(){
        $db=Database::conexao();
        $turmas=null;
        $retorno=$db->query("SELECT * FROM turma");
        
        while($item=$retorno->fetch(PDO::FETCH_ASSOC)){
            $professor=Professor::getProfessor($item['professor_codigo']);
            $turma=new Turma();
            $turma->setTurma($item['codigo'],$item['curso'],$item['nome'],$professor );
            
            $turmas[]=$turma;
        }

        return $turmas;
    }

    public static function excluir($codigo){
        $db=Database::conexao();
        $turmas=null;
        if($db->query("DELETE FROM turma WHERE codigo=$codigo")){
            return true;
        }
        return false;
    }

    public function salvar(){
        try{
            $db=Database::conexao();
            if(empty($this->codigo)){
                $stm=$db->prepare("INSERT INTO turma (nome, curso, professor_codigo) VALUES (:nome,:curso,:professor)");
                $stm->execute(array(":nome"=>$this->getNome(),":curso"=>$this->getCurso() ,":professor"=>$this->getProfessor()->getCodigo()));
            }else{
                $stm=$db->prepare("UPDATE turma SET nome=:nome,curso=:curso,professor_codigo=:professor_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome"=>$this->nome,":curso"=>$this->curso ,":professor_codigo"=>$this->professor->getCodigo(),":codigo"=>$this->codigo));
            }
            #ppegar o id do registro no banco de dados
            #setar o id do objeto
            return true;
        }catch(Exception $ex){
            echo $ex->getMessage()."<br>";
            return false;
        }
        return true;
    }

    public static function getTurma($codigo){
        $db=Database::conexao();
        $retorno=$db->query("SELECT * FROM turma WHERE codigo=$codigo");
        if($retorno){
            $item=$retorno->fetch(PDO::FETCH_ASSOC);
            $professor=Professor::getProfessor($item['professor_codigo']);
            $turma=new Turma();
            $turma->setTurma($item['codigo'],$item['curso'],$item['nome'],$professor );
           return $turma;
        }
        return false;
    }


}