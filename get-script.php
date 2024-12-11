<?php
// Forçar uso de HTTPS
if ($_SERVER["HTTPS"] != "on") {
    $redirect = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: $redirect");
    exit();
}

header('Content-Type: application/json');

// Configurações do banco de dados
$host = 'sql305.infinityfree.com';
$db = 'if0_37801367_afqawfawfawfawfwaf';
$user = 'if0_37801367';
$pass = 'Arthurdavi2AAAD';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["message" => "Erro na conexão com o banco de dados: " . $e->getMessage()]);
    exit();
}

// Consulta o script mais recente com status "pendente"
$stmt = $pdo->prepare("SELECT script FROM scripts WHERE status = 'pendente' ORDER BY created_at DESC LIMIT 1");
$stmt->execute();

// Verifica se o script foi encontrado
if ($stmt->rowCount() > 0) {
    $script = $stmt->fetch(PDO::FETCH_ASSOC)['script'];

    // Verifica se o script contém código HTML/JavaScript (sem tags <script>)
    if (preg_match("/<script.*?>.*?<\/script>/s", $script)) {
        echo json_encode(["message" => "Script contém código HTML/JavaScript, o que não é permitido."]);
        exit();
    }

    // Atualiza o status do script para 'executado'
    $updateStmt = $pdo->prepare("UPDATE scripts SET status = 'executado' WHERE script = :script");
    $updateStmt->execute([':script' => $script]);

    // Retorna o código Lua
    echo json_encode(["script" => $script]);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Nenhum script pendente encontrado."]);
}
?>
