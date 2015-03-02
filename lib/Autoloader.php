<?php

require_once "Parsedown/Parsedown.php";
require_once "Twig/Autoloader.php";
require_once "Daux.io/daux_helper.php";
require_once "Daux.io/daux_directory.php";

/**
 * Parse URL to display the requested page
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
 * Call Parsedown lib to convert Markdown to HTML
 */
function markdown($text)
{
    $Parsedown = new Parsedown();
    echo $Parsedown->text($text);
}

/**
 * List 'doc' folder to generate menus and submenus
 **/
function generate_menu()
{
    $tree = DauxHelper::build_directory_tree(ROOT."/doc");

    $nav = '<ul class="tree">';
    $nav .= build_navigation($tree, '', null, null);
    $nav .= '</ul>';

    echo $nav;
}

function build_navigation($tree, $path, $current_url, $base_page)
{
    $nav = '';
    foreach ($tree->value as $node)
    {
    	$url = $node->uri;
        if ($node->type === Directory_Entry::FILE_TYPE) {
            if ($node->value === 'index') continue;
            $nav .= '<li>';
            $link = ($path === '') ? $url : $path . '/' . $url;
            $nav .= '<a class="mod" href="' . $base_page . $link . '">' . $node->title . '</a></li>';
        } else {
            $nav .= '<li>';
            $link = ($path === '') ? $url : $path . '/' . $url;
            if ($node->index_page) $nav .= '<a class="mod" href="' . $base_page . $link . '">' . $node->title . '</a>';
            else $nav .= '<a href="#" class="mod folder">' . $node->title . '</a></li>';
            $nav .= '<ul>';
            $new_path = ($path === '') ? $url : $path . '/' . $url;
            $nav .= build_navigation($node, $new_path, $current_url, $base_page);
            $nav .= '</ul></li>';
        }
    }

    return $nav;
}

/**
 * Call Twig lib to process template display
 */
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(ROOT);
$twig = new Twig_Environment($loader);
$twig->addFunction(new Twig_SimpleFunction('url', 'url'));
$twig->addFunction(new Twig_SimpleFunction('markdown', 'markdown'));
$twig->addFunction(new Twig_SimpleFunction('dump', 'var_dump'));
$twig->addFunction(new Twig_SimpleFunction('generate_menu', 'generate_menu'));
