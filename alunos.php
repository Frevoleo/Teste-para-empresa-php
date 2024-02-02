<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once('conexao.php');

function listarAlunos() {
    global $conn;

    $sql = "SELECT aluno.id, aluno.nome, classe.descricao
            FROM aluno
            INNER JOIN aluno_classe ON aluno.id = aluno_classe.id_aluno
            INNER JOIN classe ON aluno_classe.id_classe = classe.id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">
                <tr>
                    <th>Nome do Aluno</th>
                    <th>Classe</th>
                    <th>Alterar</th>
                    <th>Excluir</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['nome'] . '</td>
                    <td>' . $row['descricao'] . '</td>
                    <td><a href="alunos.php?acao=alterar&id=' . $row['id'] . '">Alterar</a></td>
                    <td><a href="alunos.php?acao=excluir&id=' . $row['id'] . '">Excluir</a></td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo 'Nenhum aluno encontrado.';
    }
}

function listarClasses($selectedClassId = null) {
    global $conn;

    $sql = "SELECT * FROM classe";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<select name="classe" required>
                <option value="" disabled selected>Selecione a Classe</option>';

        while ($row = $result->fetch_assoc()) {
            $selected = ($row['id'] == $selectedClassId) ? 'selected' : '';
            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
        }

        echo '</select>';
    } else {
        echo 'Nenhuma classe encontrada.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'adicionar') {
        $nome = $_POST['nome'];
        $id_classe = $_POST['classe'];

        $sql = "INSERT INTO aluno (nome) VALUES ('$nome')";
        $conn->query($sql);

        $id_aluno = $conn->insert_id;

        $sql = "INSERT INTO aluno_classe (id_aluno, id_classe) VALUES ('$id_aluno', '$id_classe')";
        $conn->query($sql);

        echo 'Aluno adicionado com sucesso.';
    } elseif ($_POST['acao'] === 'alterar') {
        $id_aluno = $_POST['id'];
        $nome = $_POST['nome'];
        $id_classe = $_POST['classe'];

        $sql = "UPDATE aluno SET nome='$nome' WHERE id='$id_aluno'";
        $conn->query($sql);

        $sql = "UPDATE aluno_classe SET id_classe='$id_classe' WHERE id_aluno='$id_aluno'";
        $conn->query($sql);

        echo 'Aluno alterado com sucesso.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_aluno = $_GET['id'];

    $sql = "DELETE FROM aluno WHERE id = '$id_aluno'";
    $conn->query($sql);

    echo 'Aluno excluído com sucesso.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'alterar' && isset($_GET['id'])) {
    $id_aluno = $_GET['id'];

    $sql = "SELECT aluno.id, aluno.nome, aluno_classe.id_classe
            FROM aluno
            LEFT JOIN aluno_classe ON aluno.id = aluno_classe.id_aluno
            WHERE aluno.id = '$id_aluno'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $aluno_nome = $row['nome'];
        $aluno_classe_id = $row['id_classe'];

        echo '<h2>Alterar Aluno</h2>
            <form method="post" action="alunos.php">
                <label for="nome">Nome do Aluno:</label>
                <input type="text" name="nome" value="' . $aluno_nome . '" required>
                
                <!-- Lista de Classes -->
                ' . listarClasses($aluno_classe_id) . '
                
                <input type="hidden" name="acao" value="alterar">
                <input type="hidden" name="id" value="' . $id_aluno . '">
                <input type="submit" value="Alterar Aluno">
            </form>';
    } else {
        echo 'Aluno não encontrado.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
</head>
<body>
    <h2>Lista de Alunos</h2>

    <?php listarAlunos(); ?>

    <h2>Adicionar Aluno</h2>

    <form method="post" action="alunos.php">
        <label for="nome">Nome do Aluno:</label>
        <input type="text" name="nome" required>

        <?php listarClasses(); ?>

        <input type="hidden" name="acao" value="adicionar">
        <input type="submit" value="Adicionar Aluno">
    </form>
</body>
</html>

<?php

$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {

    $('form').submit(function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'alunos.php',
            data: formData,
            success: function(response) {
                alert(response); 
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
                location.reload();
            }
        });
    });
});
</script>