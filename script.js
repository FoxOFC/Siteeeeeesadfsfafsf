document.getElementById('submitBtn').addEventListener('click', function () {
    var scriptInput = document.getElementById('scriptInput').value;

    if (scriptInput.trim() === '') {
        alert("Por favor, insira um script.");
        return;
    }

    var data = {
        script: scriptInput
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit-script.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
        } else {
            alert("Erro ao enviar o script: " + xhr.status);
        }
    };

    xhr.onerror = function () {
        alert("Erro de conexão com o servidor.");
    };

    xhr.send(JSON.stringify(data)); // Envia os dados no formato JSON
});
