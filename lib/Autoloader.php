<?php

require_once "Parsedown/Parsedown.php";
require_once "Twig/Autoloader.php";

/**
 * URL
 */
$url  = explode('/', htmlentities(substr($_SERVER['PATH_INFO'], 1)));

if(count($url) < 1)
{
    echo "Bad request :(";
    exit(0);
}

define('APP', 'app');
define('PAGE', ($url[0] ? $url[0] : 'index'));
define('BASE_URL', str_replace("\\", "", dirname($_SERVER['SCRIPT_NAME'])));

function url($url = null)
{
    return BASE_URL."/".$url;
}

/**
 * Parsedown
 */
function markdown($text)
{
    $Parsedown = new Parsedown();
    echo $Parsedown->text($text);
}

/**
 * Twig
 */
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(ROOT);
$twig = new Twig_Environment($loader);
$twig->addFunction(new Twig_SimpleFunction('url', 'url'));
$twig->addFunction(new Twig_SimpleFunction('markdown', 'markdown'));
