<?php

    require_once "../../models/init.php";

    jsonHeaders();

    $gameId = isset($_SESSION['gameId']) ? $_SESSION['gameId'] : 0;

    if(isset($_SESSION['whoAttack'])) {
        $army = Army::getSingle($_SESSION['whoAttack']);
    } else {
        $army = Army::getFirstAttackerArmy($gameId);

        $_SESSION['firstAttackerId'] = $army->id;
    }

    $totalArmies = count(Army::getArmiesByGame($gameId));

    // var_dump($totalArmies);

    $attackedArmyId = $army->findWhomToAttack();

    if($attackedArmyId) {
        $attackedArmy = Army::getSingle($attackedArmyId);
    } else {
        $winner = "Game ended! Army " . $army->name . " won";
        
        echo json_encode(["winner" => $winner]);

        die();
    }

    // var_dump($army, $attackedArmy);

    $res = Battle::getAttackResult($army, $attackedArmy);

    $armiesAlive = count(Army::getAliveArmies($gameId));

    if($armiesAlive == 1) {
        $winner = "Game ended! Winner is " . $army->name . " army";
    } else {
        $winner = false;
    }

    goodHttpResponse();

    $data = ["res" => $res, "winner" => $winner];

    if($army->id - 1 > $_SESSION['firstAttackerId'] - $totalArmies) {
        // $_SESSION['whoAttack'] = $army->id - 1;

        $check = false;

        for($i = 1; $i <= $totalArmies; $i++) {
            $tmp = Army::getSingle($army->id - $i);

            // var_dump($tmp);

            if(!is_null($tmp) && $tmp->units > 0) {
                $_SESSION['whoAttack'] = intval($tmp->id);
                $check = true;
                break;
            }
        }

        if(!$check) {
            $_SESSION['whoAttack'] = $_SESSION['firstAttackerId'];
        }

    } else {
        $tmpId = intval($_SESSION['firstAttackerId']);
        for($i = 0; $i < $totalArmies; $i++) {
            $tmp = Army::getSingle($tmpId - $i);

            if(!is_null($tmp) && $tmp->units > 0) {
                $_SESSION['whoAttack'] = intval($tmp->id);
                break;
            }
        }
                
    }

    // var_dump($_SESSION);

    echo json_encode($data);

?>