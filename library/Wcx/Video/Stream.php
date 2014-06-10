<?php

namespace Wcx\Video;

class Stream
{
    protected $path;

    protected $mimeType;

    /**
     * Create the stream object
     * @param string $path Path to the video file
     * @throws InvalidArgumentException If the file does not exist or is not acessible
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("The file '{$path}' does not exist.");
        }
    }

    public function stream()
    {
        // Caminho do arquivo
        $path = 'fire.mp4';

        // Determina o mimetype do arquivo
        $finfo = new finfo(FILEINFO_MIME);
        $mime = $finfo->file($path);

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


            // Cabeçalho da resposta
            header('HTTP/1.1 206 Partial Content');
            header('Accept-Ranges: bytes');

            // Tamanho da resposta
            header('Content-Length: ' . ($to - $from));

            // Bytes enviados na resposta
            header("Content-Range: bytes {$from}-{$to}/{$size}");

            // Abre o arquivo no modo bináro
            $fp = fopen($file, 'rb');

            // Avança até o primeiro byte solicitado
            fseek($fp, $from);

            // Manda os dados
            while(true){
                // Verifica se já chegou ao byte final
                if(ftell($fp) >= $to){
                    break;
                }

                // Envia o conteúdo
                echo fread($fp, $chunkSize);

                // Flush do buffer
                ob_flush();
                flush();
            }
        }
        else {
            // Se não possui o cabeçalho Range, envia todo o arquivo
            header('Content-Length: ' . $size);

            // Lê o arquivo
            readfile($file);
        }
    }
}
