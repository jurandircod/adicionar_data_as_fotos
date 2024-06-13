<?php
// Definir o fuso horário para facilitar a obtenção da meia-noite local
date_default_timezone_set('America/Sao_Paulo');

// Definir a pasta que será verificada e apagada
$pasta = "fotos_com_data";

// Verificar se a pasta existe
if (is_dir($pasta)) {
    // Obter a hora atual
    $agora = new DateTime();
    // Obter a meia-noite de hoje
    $meiaNoite = new DateTime('midnight');

    // Verificar se a hora atual é igual ou posterior à meia-noite
    if ($agora == $meiaNoite) {
        // Obter todos os arquivos da pasta
        $arquivos = glob($pasta . "/*");

        // Loop para excluir cada arquivo
        foreach ($arquivos as $arquivo) {
            if (is_file($arquivo)) {
                unlink($arquivo);
            }
        }

        // Exibir uma mensagem de confirmação
        echo "Todos os arquivos da pasta foram excluídos.";
    } else {
        // Exibir uma mensagem informando que ainda não é meia-noite
        echo "Ainda não é meia-noite. Os arquivos não foram excluídos.";
    }
} else {
    // Exibir uma mensagem de erro caso a pasta não exista
    echo "A pasta especificada não existe.";
}
?>
