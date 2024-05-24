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
   
    ob_start();

    
    function clearCache()
    {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

   
    clearCache();

    function createDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
     
        $pasta_fotos = "fotos_com_data/";

  
        createDirectory($pasta_fotos);

      
        if (empty($_FILES["imagens"]["name"][0])) {
            echo '<div class="alert alert-warning">Nenhuma foto foi enviada.</div>';
            exit;
        }

   
        $arquivos_sucesso = array();

    
        foreach ($_FILES["imagens"]["tmp_name"] as $key => $tmp_name) {
   
            $mime_type = mime_content_type($tmp_name);
            if (strpos($mime_type, "image/") === 0) {
               
                $exif = exif_read_data($tmp_name);
                $data_hora = isset($exif["DateTimeOriginal"]) ? $exif["DateTimeOriginal"] : date("Y-m-d H:i:s");

             
                $nome_original = pathinfo($_FILES["imagens"]["name"][$key], PATHINFO_FILENAME);
                $extensao = pathinfo($_FILES["imagens"]["name"][$key], PATHINFO_EXTENSION);

             
                $nome_arquivo = $pasta_fotos . uniqid() . "_" . $nome_original . "." . $extensao;

               
                move_uploaded_file($tmp_name, $nome_arquivo);

             
                $imagem = imagecreatefromjpeg($nome_arquivo);

                // Definir algumas cores
                $cor_fundo = imagecolorallocate($imagem, 255, 255, 255);
                $cor_texto = imagecolorallocate($imagem, 255, 255, 255);

                
                $fonte = "./arial/arial.ttf";
                $proporcao_tamanho = 0.05 * min(imagesx($imagem), imagesy($imagem));
                $tamanho_fonte = max(10, floor($proporcao_tamanho));

                
                $largura_imagem = imagesx($imagem);
                if ($largura_imagem >= 600) {
                    $posicao_x = 20;
                } elseif ($largura_imagem >= 400) {
                    $posicao_x = 10;
                } else {
                    $posicao_x = 5;
                }

                // Definir a posição do texto
                $posicao_x = 10;
                $posicao_y = 35;


                imagettftext($imagem, $tamanho_fonte, 0, $posicao_x, $posicao_y, $cor_texto, $fonte, $data_hora);

               
                imagejpeg($imagem, $nome_arquivo);

              
                imagedestroy($imagem);

               
                $arquivos_sucesso[] = $nome_arquivo;
            } else {
                echo '<div class="alert alert-warning">Arquivo inválido: ' . $_FILES["imagens"]["name"][$key] . '</div>';
            }
        }

        if (!empty($arquivos_sucesso)) {
            echo '<div class="alert alert-success">Fotos carregadas e data/hora adicionada com sucesso!</div>';
            header("Location: baixar.php");
            exit;
        }
    }
    ?>

        <form class="form text-center input-group" method="post" enctype="multipart/form-data">
            <h2>Enviar fotos</h2>

            <div class="mb-3">
                <label for="imagens">Selecione as fotos:</label>
            </div>
            <div class="mb-3">
                <input class="form-control" id="" type="file" name="imagens[]" id="imagens" class="form-control-file" multiple required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Enviar</button>
            <a class="mt-3" href="baixar.php">Verificar arquivos enviados</a>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
