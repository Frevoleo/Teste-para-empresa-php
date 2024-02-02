<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once('conexao.php');

function listarClasses() {
    global $conn;

    $sql = "SELECT * FROM classe";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">
                <tr>
                    <th>Descrição da Classe</th>
                    <th>Alterar</th>
                    <th>Excluir</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['descricao'] . '</td>
                    <td><a href="classes.php?acao=alterar&id=' . $row['id'] . '">Alterar</a></td>
                    <td><a href="classes.php?acao=excluir&id=' . $row['id'] . '">Excluir</a></td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo 'Nenhuma classe encontrada.';
    }
}

function listarDisciplinas() {
    global $conn;

    $sql = "SELECT * FROM disciplina";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1">
                <tr>
                    <th>Descrição da Disciplina</th>
                    <th>Alterar</th>
                    <th>Excluir</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['descricao'] . '</td>
                    <td><a href="classes.php?acao=alterar&id=' . $row['id'] . '">Alterar</a></td>
                    <td><a href="classes.php?acao=excluir&id=' . $row['id'] . '">Excluir</a></td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo 'Nenhuma disciplina encontrada.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'adicionar') {
        $descricao = $_POST['descricao'];

        $sql = "INSERT INTO classe (descricao) VALUES ('$descricao')";
        $conn->query($sql);

        echo 'Classe adicionada com sucesso.';
    } elseif ($_POST['acao'] === 'alterar') {
        $id_classe = $_POST['id'];
        $descricao = $_POST['descricao'];

        $sql = "UPDATE classe SET descricao='$descricao' WHERE id='$id_classe'";
        $conn->query($sql);

        echo 'Classe alterada com sucesso.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'adicionar') {
        $descricao = $_POST['descricao'];

        $sql = "INSERT INTO disciplina (descricao) VALUES ('$descricao')";
        $conn->query($sql);

        echo 'Disciplina adicionada com sucesso.';
    } elseif ($_POST['acao'] === 'alterar') {
        $id_disciplina = $_POST['id'];
        $descricao = $_POST['descricao'];

        $sql = "UPDATE disciplina SET descricao='$descricao' WHERE id='$id_disciplina'";
        $conn->query($sql);

        echo 'Disciplina alterada com sucesso.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_classe = $_GET['id'];

    $sql = "DELETE FROM classe WHERE id = '$id_classe'";
    $conn->query($sql);

    echo 'Classe excluída com sucesso.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_disciplina = $_GET['id'];

    $sql = "DELETE FROM disciplina WHERE id = '$id_disciplina'";
    $conn->query($sql);

    echo 'Disciplina excluída com sucesso.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'alterar' && isset($_GET['id'])) {
    $id_classe = $_GET['id'];

    $sql = "SELECT * FROM classe WHERE id = '$id_classe'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $classe_descricao = $row['descricao'];

        echo '<h2>Alterar Classe</h2>
            <form method="post" action="classes.php">
                <label for="descricao">Descrição da Classe:</label>
                <input type="text" name="descricao" value="' . $classe_descricao . '" required>
                
                <input type="hidden" name="acao" value="alterar">
                <input type="hidden" name="id" value="' . $id_classe . '">
                <input type="submit" value="Alterar Classe">
            </form>';
    } else {
        echo 'Classe não encontrada.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'alterar' && isset($_GET['id'])) {
    $id_disciplina = $_GET['id'];

    $sql = "SELECT * FROM disciplina WHERE id = '$id_disciplina'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $disciplina_descricao = $row['descricao'];

        echo '<h2>Alterar Disciplina</h2>
            <form method="post" action="classes.php">
                <label for="descricao">Descrição da Disciplina:</label>
                <input type="text" name="descricao" value="' . $disciplina_descricao . '" required>
                
                <input type="hidden" name="acao" value="alterar">
                <input type="hidden" name="id" value="' . $id_disciplina . '">
                <input type="submit" value="Alterar Disciplina">
            </form>';
    } else {
        echo 'Disciplina não encontrada.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Classes e Disciplinas</title>
</head>
<body>
    <h2>Lista de Classes</h2>

    <?php listarClasses(); ?>

    <h2>Adicionar Classe</h2>

    <form method="post" action="classes.php">
        <label for="descricao">Descrição da Classe:</label>
        <input type="text" name="descricao" required>

        <input type="hidden" name="acao" value="adicionar">
        <input type="submit" value="Adicionar Classe">
    </form>

    <h2>Lista de Disciplinas</h2>

    <?php listarDisciplinas(); ?>

    <h2>Adicionar Disciplina</h2>

    <form method="post" action="classes.php">
        <label for="descricao">Descrição da Disciplina:</label>
        <input type="text" name="descricao" required>

        <input type="hidden" name="acao" value="adicionar">
        <input type="submit" value="Adicionar Disciplina">
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
            url: 'classes.php', 
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
