<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    if(isset($_POST['btnStartGame'])) {
        $gameId = $_POST['gameId'];
        $armies = Army::getArmiesByGame($gameId);

        if(count($armies) >= 5) {
            $game = Game::getSingle($gameId);
            
            if($game->idGameStatus != 2) {
                $game->idGameStatus = 2;
                $resOp = $game->update();

                $res = "Game started!";
            } else {
                $res = "Game already started";
            }

            session_unset();

            $_SESSION['gameId'] = $gameId;
            
            $status = "true";

            goodHttpResponse();

        } else {
            $res = "The game cannot be started. There is less than 5 armies";
            $status = "false";

            badHttpRequest();
        }

        $data = ["res" => $res, "status" => $status];

    } else {
        badHttpRequest();

        $data = ["res" => null, "error" => "Click start game button first!"];
    }

    echo json_encode($data);


?>