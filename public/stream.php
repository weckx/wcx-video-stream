<?php

//Determine file path according to extension
if (!isset($_GET['ext']) || $_GET['ext'] == 'mp4') {
    $path = dirname(__FILE__) . '/../resource/rain.mp4';
} else if ($_GET['ext'] == 'webm') {
    $path = dirname(__FILE__) . '/../resource/rain.webm';
}

// Determina o mimetype do arquivo
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($path);
header('Content-Type: ' . $mime);

// Tamanho do arquivo
$size = filesize($path);

//Verifica se foi passado o cabeçalho Range
if (isset($_SERVER['HTTP_RANGE'])) {
    // Parse do valor do campo
    list($specifier, $value) = explode('=', $_SERVER['HTTP_RANGE']);
    if ($specifier != 'bytes') {
        header('HTTP/1.1 400 Bad Request');
        return;
    }

    // Determina os bytes de início/fim
    list($from, $to) = explode('-', $value);
    if (!$to) {
        $to = $size - 1;
    }

    // Abre o arquivo no modo bináro
    $fp = fopen($path, 'rb');
    if (!$fp) {
        header('HTTP/1.1 500 Internal Server Error');
        return;
    }

    // Cabeçalho da resposta
    header('HTTP/1.1 206 Partial Content');
    header('Accept-Ranges: bytes');

    // Tamanho da resposta
    header('Content-Length: ' . ($to - $from));

    // Bytes enviados na resposta
    header("Content-Range: bytes {$from}-{$to}/{$size}");

    // Avança até o primeiro byte solicitado
    fseek($fp, $from);

    // Manda os dados
    while(true){
        // Verifica se já chegou ao byte final
        if(ftell($fp) >= $to){
            break;
        }

        // Envia o conteúdo
        echo fread($fp, 8192);

        // Flush do buffer
        ob_flush();
        flush();
    }
}
else {
    // Se não possui o cabeçalho Range, envia todo o arquivo
    header('Content-Length: ' . $size);

    // Lê o arquivo
    readfile($path);
}
