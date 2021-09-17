<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <title>Alunos</title>
    </head>
<body>
    <h1>Alunos</h1>
<?php
include "./classes/Professor.php";
include "./classes/Turma.php";
include "./classes/Aluno.php";

#----------Processamento dos dados recebidos do formulário ------
if(isset($_GET['acao'])){
    if($_GET['acao']=="salvar"){
        if($_POST['enviar-aluno']){
            $turma=new Turma();
            $turma->setTurma($_POST['codigo-turma-aluno'], null, null, null);
            $aluno=new Aluno();
            
            $aluno->setAluno(
                $_POST['codigo_aluno'], 
                $_POST['nome-aluno'],
                $_POST['matricula-aluno'],
                $turma
            );
            
            if($aluno->salvar()){
                echo "<div id='msg' class='alert alert-success'>Registro salvo com sucesso!</div>";
            }else{
                echo "<div id='msg' class='alert alert-danger'>Falha ao salvar o registro!</div>";
            }
           
            echo "<script> 
                setTimeout(
                    function(){
                        document.querySelector('#msg').style='display:none';
                    }
                    ,
                    3000
                );
            </script>";
           
            unset($aluno);

        }
        
    }else if($_GET['acao']=="excluir"){
        if(isset($_GET['codigo'])){
            if(Aluno::excluir($_GET['codigo'])){
                echo "<div id='msg' class='alert alert-success'>Registro excluido com sucesso!</div>";
            }else{
                echo "<div id='msg' class='alert alert-danger'>Falha ao excluir o registro!</div>";
            }
            echo "<script> 
                setTimeout(
                    function(){
                        document.querySelector('#msg').style='display:none';
                    }
                    ,
                    3000
                );
            </script>";
        }
        
    }else if($_GET['acao']=="editar"){
        if(isset($_GET['codigo'])){
            $aluno=Aluno::getAluno($_GET['codigo']);
        }
    }
}

if(!isset($aluno)){
    $aluno=new Aluno();
    $aluno->setAluno(null,null,null,new Turma());
}

#------Formulário para cadastrar umA nova Turma/*99//////------------------
?>
<div class="container-fluid">
    <h2> Cadastro de Alunos</h2>
    <form name="form-aluno" method="POST" action="?pagina=alunos&acao=salvar">
        <input type="hidden" name="codigo_aluno" value="<?php echo $aluno->getCodigo()?>"/>
        <div class="input-group mb-2">
            <span class="input-group-text" >Nome do Aluno</span>
            <input type="text" class="form-control" id="nome-aluno" name="nome-aluno"  value="<?php echo $aluno->getNome() ?>" >
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text" >Nº da Matrícula</span>
            <input type="number" class="form-control" id="matricula-aluno" name="matricula-aluno"  value="<?php echo $aluno->getMatricula() ?>" >
        </div>
        
        <div class="input-group mb-2 mb-2"> 
            <label class="input-group-text" for="inputGroupTurma">Turmas</label>   
            <select class="form-select" name="codigo-turma-aluno">
                <option value="<?php echo $aluno->getTurma()->getCodigo()  ?>"><?php echo $aluno->getTurma()->getNome()  ?></option>
                <?php
                    $turmas=Turma::listar();
                    if($turmas){
                        foreach($turmas AS $item){
                            echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                        }
                        
                    }
                ?>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="enviar-aluno" value="Enviar"/>
        
    </form>
</div>

<?php


#---------Listar os usuários -----------------------
?>
<div class="container-fluid">
    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Matricula</th>
            <th scope="col">Turma</th>
            <th scope="col">Professor</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $alunos=Aluno::listar();
                foreach($alunos As $aluno){
                    echo"<tr>
                    <td>{$aluno->getCodigo()}</td>
                    <td>{$aluno->getNome()}</td>
                    <td>{$aluno->getMatricula()}</td>
                    <td>{$aluno->getTurma()->getNome()}</td>
                    <td>{$aluno->getTurma()->getProfessor()->getNome()}</td>
                    <td>
                        <span class='badge rounded-pill bg-primary'>
                            <a href='?pagina=alunos&acao=editar&codigo={$aluno->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                        </span>
                        <span class='badge rounded-pill bg-danger'>
                            <a href='?pagina=alunos&acao=excluir&codigo={$aluno->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                        </span>
                    </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
</div>