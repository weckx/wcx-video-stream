<?php
// Para quando for executado pelo php -S redirecionar os arquivos
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>HTML5 Video + PHP</title>
</head>
<body>
    <video id="video" controls preload="auto" width="640" height="360" 
        poster="poster.png">
      <source src="stream.php?ext=webm" type='video/webm' />
      <source src="stream.php?ext=mp4" type='video/mp4' />
</video>
</body>
</html>
