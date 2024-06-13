<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Fotos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Style.css">
</head>

<body>

    <div class="container">

    <?php
    // esqueci pq coloquei isso aqui, mas se ta ai tem proposito
    ob_start();

    // Função para limpar o cache
    function clearCache()
    {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    // Limpar o cache ao recarregar a página
    clearCache();

    function createDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Pasta para salvar as fotos
        $pasta_fotos = "fotos_com_data/";

        // Verificar se a pasta existe e tem permissão de escrita
        createDirectory($pasta_fotos);

        // Verificar se foram enviadas fotos
        if (empty($_FILES["imagens"]["name"][0])) {
            echo '<div class="alert alert-warning">Nenhuma foto foi enviada.</div>';
            exit;
        }

        // Array para armazenar nomes de arquivos bem-sucedidos
        $arquivos_sucesso = array();

        $prefix= "prefix" . uniqid();

        // Loop através das imagens
        foreach ($_FILES["imagens"]["tmp_name"] as $key => $tmp_name) {
            // Verificar se é realmente uma imagem
            $mime_type = mime_content_type($tmp_name);
            if (strpos($mime_type, "image/") === 0) {
                // Obter a data e hora da foto
                $exif = exif_read_data($tmp_name);
                $data_hora = isset($exif["DateTimeOriginal"]) ? $exif["DateTimeOriginal"] : "Não a data na foto";

                // Obter o nome original do arquivo e a extensão
                $nome_original = pathinfo($_FILES["imagens"]["name"][$key], PATHINFO_FILENAME);
                $extensao = pathinfo($_FILES["imagens"]["name"][$key], PATHINFO_EXTENSION);

                
                // Criar um nome de arquivo único
                $nome_arquivo = $pasta_fotos . $prefix . uniqid() . "_" . $nome_original . "." . $extensao;

                // Copiar e renomear a imagem
                move_uploaded_file($tmp_name, $nome_arquivo);

                // Carregar a imagem
                $imagem = imagecreatefromjpeg($nome_arquivo);

                // Definir algumas cores
                $cor_fundo = imagecolorallocate($imagem, 20, 20, 20);
                $cor_texto = imagecolorallocate($imagem, 255, 191, 0);

                // Definir a fonte e o tamanho do texto
                $fonte = "./arial/arial.ttf";
                $proporcao_tamanho = 0.05 * min(imagesx($imagem), imagesy($imagem));
                $tamanho_fonte = max(10, floor($proporcao_tamanho));

                // Verificar a largura da imagem para definir a posição do texto
                $largura_imagem = imagesx($imagem);
                if ($largura_imagem >= 600) {
                    $posicao_x = 20;
		    $posicao_y = 120;
                } elseif ($largura_imagem >= 400) {
                    $posicao_x = 10;
                } else {
                    $posicao_x = 5;
                }

                // Escrever a data e hora na imagem
                imagettftext($imagem, $tamanho_fonte, 0, $posicao_x, $posicao_y, $cor_texto, $fonte, $data_hora);

                // Salvar a imagem modificada
                imagejpeg($imagem, $nome_arquivo);

                // Liberar memória
                imagedestroy($imagem);

                // Armazenar o nome do arquivo bem-sucedido
                $arquivos_sucesso[] = $nome_arquivo;
            } else {
                echo '<div class="alert alert-warning">Arquivo inválido: ' . $_FILES["imagens"]["name"][$key] . '</div>';
            }
        }

        if (!empty($arquivos_sucesso)) {
            header("Location: baixar.php?prefix=". $prefix);
            exit;
        }
    }
    ?>

        <form class="form text-center input-group" method="post" enctype="multipart/form-data">
            
            <img class="imgpref mb-3" src="./imagens/WhatsApp Image 2023-08-22 at 11.30.17.jpeg" alt="">
            <h2>Enviar fotos</h2>

            <div class="mb-3">
                <label for="imagens">Selecione as fotos:</label>
            </div>
            <div class="mb-3">
                <input class="form-control" id="" type="file" name="imagens[]" id="imagens" class="form-control-file" multiple required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Enviar</button>
        </form>
    
    </div>

    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
