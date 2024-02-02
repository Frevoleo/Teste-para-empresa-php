<?php

$servidor_mysql = 'localhost';
$usuario_mysql = 'escola_admin';
$senha_mysql = '102030';
$banco_dados = 'escola';

$conn = new mysqli($servidor_mysql, $usuario_mysql, $senha_mysql, $banco_dados);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
