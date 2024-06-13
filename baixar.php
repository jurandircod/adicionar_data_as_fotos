<!DOCTYPE html>
<html>

<head>
    <title>Baixar Fotos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/Style_baixar.css">
</head>

<body>
    <div class="container">

        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">                   
            <h3 class="navbar-brand" style="text-decoration: white;">Fotos disponíveis</h3>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a href="?baixar&prefix=<?php echo $_GET['prefix']; ?> " class="btn btn-primary mx-1">Baixar Todas as Fotos</a>
                        
                        <a href="index.php" class="btn btn-info mx-1">Enviar Mais Fotos</a>
                    </div>
                </div>
            </div>
        </nav>


        <?php
        $prefix = "";
        if (isset($_GET['prefix'])){
            $prefix = $_GET['prefix'];

        }

        function deleteDirectory($directory)
        {
            if (file_exists($directory)) {
                $files = glob($directory . '/');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($directory);
            }
        }

        // Pasta que contém as fotos
        $pasta_fotos = "fotos_com_data/";

        // Verificar se a pasta existe
        if (!file_exists($pasta_fotos)) {
            echo '<div class="alert alert-warning">Nenhuma foto encontrada.</div>';
            exit;
        }

        // Listar todas as fotos
        $fotos = glob($pasta_fotos . $prefix . "*.*");

        if (empty($fotos)) {
            echo '<div class="alert alert-warning mt-3">Nenhuma foto encontrada.</div>';
            exit;
        }

        // Função para baixar todas as fotos e excluir após o download
        function baixarTodasFotos($fotos)
        {
            $zip = new ZipArchive();
            $zipNome = "todas_as_fotos.zip";

            if ($zip->open($zipNome, ZipArchive::CREATE) === true) {
                foreach ($fotos as $foto) {
                    // Obter o nome do arquivo a ser adicionado ao ZIP
                    $nome_arquivo = basename($foto);
                    // Adicionar o arquivo ao ZIP com o nome original do arquivo
                    $zip->addFile($foto, $nome_arquivo);
                }

                $zip->close();

                // Limpar o buffer de saída
                ob_clean();

                // Definir os cabeçalhos para enviar o arquivo ZIP
                $filesize = filesize($zipNome);
                header("Content-Type: application/zip");
                header("Content-Length: " . $filesize);
                header("Content-Disposition: attachment; filename=\"" . $zipNome . "\"");

                // Ler e enviar o arquivo ZIP
                $fp = fopen($zipNome, 'rb');
                while (!feof($fp)) {
                    echo fread($fp, 1024);
                }
                fclose($fp);

                // Excluir o arquivo ZIP após o download
                unlink($zipNome);

                // Excluir as fotos após o download
                foreach ($fotos as $foto) {
                    unlink($foto);
                }
            
            } else {
                echo '<div class="alert alert-danger">Erro ao criar arquivo ZIP.</div>';
            }
        }

        // Verificar se a ação de baixar todas as fotos foi acionada
        if (isset($_GET['baixar'])) {
            baixarTodasFotos($fotos);    
            exit;
        }

        if (isset($_GET['excluir'])) {
            foreach ($fotos as $foto) {
                unlink($foto);
            }
            echo '<div class="alert alert-warning">As fotos foram excluidas com sucesso</div>';
        }

        ?>

        


        <ul class="list-group">
            <?php foreach ($fotos as $foto) : ?>
                <li class="list-group-item list-group-item-success"><?php echo basename($foto); ?></li>
            <?php endforeach; ?>
        </ul>

        <br>


    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>