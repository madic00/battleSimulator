<?php 

    require_once "../../models/init.php";

    $gameId = 1;

    if(isset($_SESSION['whoAttack'])) {
        $army = Army::getSingle($_SESSION['whoAttack']);
    } else {
        $army = Army::getFirstAttackerArmy($gameId);

        $_SESSION['firstAttackerId'] = $army->id;
    }

    // var_dump($army);

    $totalArmies = count(Army::getArmiesByGame($gameId));

    $attackedArmyId = $army->findWhomToAttack();
    $attackedArmy = Army::getSingle($attackedArmyId);

    // $result = Battle::getAttackResult($army, $attackedArmy);

    $armiesAlive = count(Army::getAliveArmies($gameId));

    $attackedArmyId = $army->findWhomToAttack();
    $attackedArmy = Army::getSingle($attackedArmyId);

    var_dump($army, $attackedArmy);

    if($army->id - 1 > $_SESSION['firstAttackerId'] - $totalArmies) {
        // $_SESSION['whoAttack'] = $army->id - 1;

        for($i = 1; $i <= $totalArmies; $i++) {
            $tmp = Army::getSingle($army->id - $i);

            if($tmp->units > 0) {
                $_SESSION['whoAttack'] = intval($tmp->id);
                break;
            }
        }

    } else {
        $tmpId = intval($_SESSION['firstAttackerId']);
        for($i = 0; $i < $totalArmies; $i++) {
            $tmp = Army::getSingle($tmpId - $i);
            
            if($tmp->units > 0) {
                var_dump($tmp);
                $_SESSION['whoAttack'] = intval($tmp->id);
                break;
            }
        }
                
    }

    // session_destroy();

    var_dump($_SESSION);

?>