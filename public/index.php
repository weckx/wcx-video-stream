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
    <link href="//vjs.zencdn.net/4.6/video-js.css" rel="stylesheet">
    <script src="//vjs.zencdn.net/4.6/video.js"></script>
</head>
<body>
    <video id="video" class="video-js vjs-default-skin" controls preload="auto" 
        width="640" height="360" 
        poster="poster.png">
      <source src="stream.php?ext=webm" type='video/webm' />
      <source src="stream.php?ext=mp4" type='video/mp4' />
      <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
</video>
</body>
</html>
