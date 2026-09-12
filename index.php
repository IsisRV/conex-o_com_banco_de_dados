<?php

// Cria a estrutura básica da página HTML
echo "<!DOCTYPE html>";
echo "<html lang='pt-br'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Notas dos Alunos Concluintes</title>";

// Importa o arquivo CSS
echo "<link rel='stylesheet' href='style.css'>";
echo "</head>";
echo "<body>";

// Título da página
echo "<h1>Notas dos Alunos Concluintes</h1>";

// Cria o formulário para pesquisar um aluno pelo nome
echo "<form method='GET'>";
echo "<input type='text' name='busca' placeholder='Digite o nome do aluno'>";
echo "<button type='submit'>Pesquisar</button>";
echo "</form>";

// Dados para realizar a conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pwii";

// Cria a conexão entre o PHP e o MySQL
$conexao = new mysqli($servername, $username, $password, $dbname);

// Verifica se ocorreu algum erro na conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Recebe o nome digitado no campo de pesquisa
// Se nada for digitado, a variável recebe um texto vazio
$busca = $_GET["busca"] ?? "";

// Consulta os alunos no banco de dados
// O LIKE permite pesquisar parte do nome
$sql = "SELECT * FROM alunoconcluinte 
        WHERE nome LIKE '%$busca%'";

// Executa a consulta no banco de dados
$resultado = $conexao->query($sql);

// Cria um array vazio para armazenar os alunos
$alunos = [];

// Percorre os resultados da consulta
// e adiciona cada aluno ao array
foreach ($resultado as $linha) {
    $alunos[] = $linha;
}

// Ordena os alunos pela média, da maior para a menor
usort($alunos, function($a, $b) {

    // Calcula a média do primeiro aluno
    $mediaA = ($a["nota1"] + $a["nota2"] + $a["nota3"] + $a["nota4"]) / 4;

    // Calcula a média do segundo aluno
    $mediaB = ($b["nota1"] + $b["nota2"] + $b["nota3"] + $b["nota4"]) / 4;

    // Coloca a maior média primeiro
    return $mediaB <=> $mediaA;
});

// Cria a tabela HTML
echo "<table border='1'>";

// Cria o cabeçalho da tabela
echo "<tr>";
echo "<th>Ranking</th>";
echo "<th>ID</th>";
echo "<th>Nome</th>";
echo "<th>Nota 1</th>";
echo "<th>Nota 2</th>";
echo "<th>Nota 3</th>";
echo "<th>Nota 4</th>";
echo "<th>Média</th>";
echo "</tr>";

// Define a primeira posição do ranking
$posicao = 1;

// Percorre todos os alunos ordenados
foreach ($alunos as $linha) {

    echo "<tr>";

    // Exibe a posição do aluno no ranking
    echo "<td>" . $posicao . "º</td>";

    // Calcula a média das quatro notas
    $media = ($linha["nota1"] + $linha["nota2"] + $linha["nota3"] + $linha["nota4"]) / 4;

    // Exibe os dados do aluno na tabela
    echo "<td>" . $linha["idalunoconcluinte"] . "</td>";
    echo "<td>" . $linha["nome"] . "</td>";
    echo "<td>" . $linha["nota1"] . "</td>";
    echo "<td>" . $linha["nota2"] . "</td>";
    echo "<td>" . $linha["nota3"] . "</td>";
    echo "<td>" . $linha["nota4"] . "</td>";

    // Exibe a média com uma casa decimal
    echo "<td>" . number_format($media, 1, ',', '.') . "</td>";

    // Passa para a próxima posição do ranking
    $posicao++;

    echo "</tr>";
}

// Finaliza a tabela
echo "</table>";

// Finaliza o corpo e a página HTML
echo "</body>";
echo "</html>";

?>