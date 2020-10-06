<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    if(isset($_POST['btnGame'])) {
        $newGame = new Game();
        $newGame->idGameStatus = 1;
        $newGame->gameCodeID = sha1(md5(time() . "battle simulator"));

        $newGame->insert();

        goodHttpResponse();

        $data = ["game" => $newGame, "error" => ""];

    } else {
        badHttpRequest();

        $data = ["game" => null, "error" => "Click new game first!"];
    }

    echo json_encode($data);


?>