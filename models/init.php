<?php 

    session_start();

    defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
    define("SITE_ROOT", $_SERVER['DOCUMENT_ROOT']. "/esagaming");
    define("MODELS", SITE_ROOT . "/models");
    define("IMG_PATH", SITE_ROOT . "/assets/images");

    require_once "functions.php";

    require_once "database.php";
    require_once "dbObject.php";
    // require_once MODELS . "/user.php";
    // require_once MODELS . "/session.php";
    

?>