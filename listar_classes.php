<?php
require_once('conexao.php');

function listarClassesComDisciplinas() {
    global $conn;

    $sql = "SELECT classe.id AS id_classe, classe.descricao AS classe_descricao, 
                   disciplina.id AS id_disciplina, disciplina.descricao AS disciplina_descricao
            FROM classe
            LEFT JOIN disciplina_classe ON classe.id = disciplina_classe.id_classe
            LEFT JOIN disciplina ON disciplina_classe.id_disciplina = disciplina.id
            ORDER BY classe.id, disciplina.descricao";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $currentClassId = null;

        echo '<table border="1">
                <tr>
                    <th>Classe</th>
                    <th>Disciplinas</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            if ($currentClassId !== $row['id_classe']) {
                if ($currentClassId !== null) {
                    echo '</td></tr>';
                }
                echo '<tr>
                        <td>' . $row['classe_descricao'] . '</td>
                        <td>';
                $currentClassId = $row['id_classe'];
            }

            echo $row['disciplina_descricao'] . '<br>';
        }

        echo '</td></tr></table>';
    } else {
        echo 'Nenhuma classe encontrada.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Classes com Disciplinas</title>
</head>
<body>
    <h2>Lista de Classes com Disciplinas</h2>

    <?php listarClassesComDisciplinas(); ?>
</body>
</html>

<?php
$conn->close();
?>
