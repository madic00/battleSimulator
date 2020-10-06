<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    
    if(isset($_GET['idGame'])) {
        $idGame = $_GET['idGame'];

        $armies = Army::getArmiesByGame($idGame);
        
        goodHttpResponse();

        $data = ["armies" => $armies, "error" => ""];
        
    } else {
        badHttpRequest();

        $data = ["armies" => null, "error" => "Select game first!"];
    }

    echo json_encode($data);



?>