<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    goodHttpResponse();

    $games = Game::getAllGamesInfo();

    $data = ["games" => $games, "error" => ""];
    
    echo json_encode($data);


?>