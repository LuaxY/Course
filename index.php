<?php

error_reporting(E_ALL);
define('ROOT', dirname(__FILE__));
require_once "./lib/Autoloader.php";

// Read configuration files
$config = json_decode(file_get_contents(APP."/config/config.json"));

// Load requested page
$vars = array(
    "config" => $config,
    "app"    => APP,
    "page"   => PAGE
);

if(file_exists(APP."/view/".PAGE.".tpl.html"))
    echo $twig->render(APP."/view/".PAGE.".tpl.html", $vars);
else
{
    if(file_exists(APP."/view/404.tpl.html"))
        echo $twig->render(APP."/view/404.tpl.html", $vars);
    else
        echo $twig->render("common/view/404.tpl.html", $vars);
}

?>
