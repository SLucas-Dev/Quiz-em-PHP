<?php

session_start();

include 'respostas.php';


if (isset($_GET['reiniciar'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}


if (isset($_GET['iniciar'])) {
    $_SESSION['estado_quiz'] = 'quiz';
    $_SESSION['pontuacao'] = 0;
    $_SESSION['pergunta_atual'] = 0;
    $_SESSION['feedback'] = '';
    header("Location: index.php");
    exit();
}


if (isset($_GET['proximo'])) {
    if ($_SESSION['estado_quiz'] === 'feedback') {
        $_SESSION['estado_quiz'] = 'quiz';
        $_SESSION['feedback'] = ''; 
        header("Location: index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
    $resposta_usuario = $_POST['resposta'];
    $indice_pergunta = $_SESSION['pergunta_atual'];
    $resposta_correta = $perguntas[$indice_pergunta]['resposta'];

    if ($resposta_usuario === $resposta_correta) {
        $_SESSION['pontuacao']++;
        $_SESSION['feedback'] = '<p class="correto">Resposta correta!</p>';
    } else {
        $_SESSION['feedback'] = '<p class="incorreto">Resposta incorreta! A resposta correta era: <strong>' . htmlspecialchars($resposta_correta) . '</strong></p>';
    }

    $_SESSION['pergunta_atual']++;

    if ($_SESSION['pergunta_atual'] >= count($perguntas)) {
        $_SESSION['estado_quiz'] = 'final';
    } else {
        $_SESSION['estado_quiz'] = 'feedback';
    }
}


if (!isset($_SESSION['estado_quiz'])) {
    $_SESSION['estado_quiz'] = 'inicio';
    $_SESSION['pontuacao'] = 0;
    $_SESSION['pergunta_atual'] = 0;
    $_SESSION['feedback'] = '';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Interativo em Front-End</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

    <div class="quiz-container">
        
        <h1>Mini Quiz Front-End</h1>

        <?php
        
        if ($_SESSION['estado_quiz'] === 'inicio') {
            
            echo '<div class="tela-inicial">';
            echo '<h2>Bem-vindo(a) ao Quiz de Front-End!</h2>';
            echo '<p>Teste seus conhecimentos em HTML, CSS e JavaScript com 10 perguntas.</p>';
            echo '<a href="index.php?iniciar" class="btn-iniciar">Começar o Quiz</a>';
            echo '</div>';
        } elseif ($_SESSION['estado_quiz'] === 'quiz' || $_SESSION['estado_quiz'] === 'feedback') {
            
            $indice_pergunta = $_SESSION['pergunta_atual'];
            $pergunta_atual = $perguntas[$indice_pergunta];
            
            echo '<div class="tela-pergunta">';
            echo '<h2>Pergunta ' . ($indice_pergunta + 1) . ' de ' . count($perguntas) . '</h2>';
            echo '<h3>' . htmlspecialchars($pergunta_atual['pergunta']) . '</h3>';

            if ($_SESSION['estado_quiz'] === 'feedback') {
                
                echo $_SESSION['feedback'];
                echo '<a href="index.php?proximo" class="btn-proximo">Próxima Pergunta</a>';
            } else {
                
                echo '<form action="index.php" method="post">';
                echo '<ul class="opcoes">';
                foreach ($pergunta_atual['opcoes'] as $opcao) {
                    echo '<li><label><input type="radio" name="resposta" value="' . htmlspecialchars($opcao) . '" required> ' . htmlspecialchars($opcao) . '</label></li>';
                }
                echo '</ul>';
                echo '<button type="submit" name="enviar" class="btn-enviar">Enviar Resposta</button>';
                echo '</form>';
            }
            echo '</div>';
        } elseif ($_SESSION['estado_quiz'] === 'final') {
            
            $pontuacao = $_SESSION['pontuacao'];
            $total_perguntas = count($perguntas);
            echo '<div class="tela-final">';
            echo '<h2>Quiz Concluído!</h2>';
            echo '<p>Sua pontuação final é: <strong>' . $pontuacao . '</strong> de ' . $total_perguntas . '</p>';
            echo '<a href="index.php?reiniciar" class="btn-reiniciar">Tentar Novamente</a>';
            echo '</div>';
        }
        ?>
    </div>

</body>
</html>