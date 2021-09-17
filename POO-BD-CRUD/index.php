
<?php

include "./config.php";
include DIR_TEMPLATE."header.php";


spl_autoload_register(function ($classe) {
    include "./classes/" .$classe .".php";
});

if(isset($_GET['pagina'])){
    $pagina=$_GET['pagina'];
    include DIR_HOME.$pagina.".php";
}

include DIR_TEMPLATE."footer.php";
?>