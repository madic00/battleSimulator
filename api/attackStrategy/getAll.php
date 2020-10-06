<?php 

    require_once "../../models/init.php";

    jsonHeaders();

    goodHttpResponse();

    $strategies = AttackStrategy::getAll();

    $data = ["strategies" => $strategies, "error" => ""];
    
    echo json_encode($data);


?>