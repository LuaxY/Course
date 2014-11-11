<?php

require_once "./lib/Parsedown/Parsedown.php";
require_once "./lib/Twig/Autoloader.php";

/**
 * URL
 */
$url  = explode('/', htmlentities(substr($_SERVER['PATH_INFO'], 1)));

if(count($url) < 2)
{
    echo "Bad request :(";
    exit(0);
}

define('APP', $url[0]);
define('PAGE', ($url[1] ? $url[1] : 'index'));
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
