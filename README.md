
# Datas Exif
Resumo:

O projeto "Datas Exif" foi desenvolvido para atender a uma necessidade interna na prefeitura de Umurama. Um setor específico carecia de uma solução que permitisse aos contribuintes visualizarem fotos dos locais juntamente com a data em que foram capturadas. Como estagiário de infraestrutura de TI na Diretoria de TI da prefeitura, fui designado para resolver esse problema. Não encontrando nenhuma solução gratuita disponível, optei por desenvolver uma. Embora o programa esteja em uso, não pude dedicar tempo para atualizá-lo devido a outras demandas.

## Funcionalidades
O programa analisa metadados de fotos e insere automaticamente a data de captura no canto superior esquerdo das imagens.

### Instalação
Baixe o arquivo.
Descompacte-o.
Ative a extensão Exif no arquivo php.ini.
(Opcional) Modifique as configurações no php.ini para ajustar a quantidade de arquivos que podem ser processados simultaneamente.

**no Docker** 
siga estes passos no terminal:

A cesse as pasta do php - nano /etc/php/<versão do PHP>/php.ini

Abra o arquivo php.ini e faça as seguintes alterações:

Altere a linha post_max_size para post_max_size=300M.

Altere a linha upload_max_filesize para upload_max_filesize=300M.

Altere a linha max_file_uploads para max_file_uploads=500.

Remova o ponto e vírgula (;) na atrás das linhas: extension=gd, extension=mbstring e extension=exif.

Após fazer todas as alterações no arquivo php.ini, salve-o e reinicie o servidor Apache para que as alterações entrem em vigor.

Contribuição
Contribuições são bem-vindas! Abra uma issue para discutir as mudanças propostas ou envie um pull request.

