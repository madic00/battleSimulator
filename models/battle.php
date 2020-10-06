<?php 

    class Battle {
        private $isStarted;
        
        public static function getAttackResult($attackingArmy, $attackedArmy) {
            if($attackingArmy->willAttack()) {
                $damage = $attackingArmy->attackDamage();

                $attackedArmy->unitsRemoved($damage);

                return "Attack executed. " . $attackingArmy->name . " took " . $damage . " units to the " . $attackedArmy->name;

            } else {
                return "Attack was unsuccessful. Army {$attackingArmy->name} wait for the next round.";
            }
            
        }




    }



?>