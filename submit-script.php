<?php
// Definir o conteúdo da resposta como JSON
header('Content-Type: application/json');

// Configurações do banco de dados
$host = 'sql305.infinityfree.com'; // Endereço do seu banco de dados
$db = 'if0_37801367_afqawfawfawfawfwaf'; // Nome do banco de dados
$user = 'if0_37801367'; // Nome de usuário do banco de dados
$pass = 'Arthurdavi2AAAD'; // Senha do banco de dados

// Cria uma conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["message" => "Erro ao conectar ao banco de dados: " . $e->getMessage()]);
    exit();
}

// Lê os dados enviados no corpo da requisição
$inputData = file_get_contents('php://input');

// Decodifica os dados JSON recebidos
$data = json_decode($inputData, true);

// Verifica se o script foi enviado e não está vazio
if (isset($data['script']) && !empty($data['script'])) {
    $script = $data['script'];

    // Prepara a consulta SQL para inserir o script no banco de dados
    $stmt = $pdo->prepare("INSERT INTO scripts (script) VALUES (:script)");
    $stmt->bindParam(':script', $script, PDO::PARAM_STR);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        echo json_encode(["message" => "Script enviado com sucesso!"]);
    } else {
        http_response_code(500); // Erro interno do servidor
        echo json_encode(["message" => "Erro ao salvar o script no banco de dados."]);
    }
} else {
    http_response_code(400); // Requisição inválida
    echo json_encode(["message" => "Script inválido ou vazio."]);
}
?>
