<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    if(isset($_POST['insertArmy'])) {
        $newArmy = new Army();
        $newArmy->name = $_POST['armyName'];
        $newArmy->units = $_POST['armyUnits'];
        $newArmy->idAttackStrategy = $_POST['attackStrategy'];
        $newArmy->idGame = $_POST['gameId'];

        $newArmy->insert();

        goodHttpResponse();

        $data = ["army" => $newArmy, "error" => "", "status" => true];

        $_SESSION['firstAttackerId'] = $newArmy->id;

    } else {
        badHttpRequest();

        $data = ["army" => null, "error" => "Click new game first!"];
    }

    echo json_encode($data);


?>