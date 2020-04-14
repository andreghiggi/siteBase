<?php
include("funcoes.php");
session_start();

$_SESSION['pagamento'] = anti_injection($_GET['pagamento']);
?>