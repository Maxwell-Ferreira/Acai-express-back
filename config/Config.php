<?php
session_start(); //inicializando a sessão
ob_start(); //limpa o buffer de redirecionamento

//url padrão do site 
define('URL', 'http://127.0.0.1/Acai-express-back/');

//controller e métodos padrão
define('CONTROLLER', 'Home');
define('METHOD', 'index');
define('ERROR404', 'Error404');

//dados de acesso ao BD
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBNAME', 'acai_express');