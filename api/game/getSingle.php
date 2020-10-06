<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    goodHttpResponse();

    $games = Game::gameInfo(1);

    $data = ["games" => $games, "error" => ""];
    
    echo json_encode($data);


?>