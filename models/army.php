<?php

    class Army extends DbObject {
        public $id;
        public $name;
        public $units;
        public $idAttackStrategy;
        public $idGame;

        private static $damagePerUnit = 0.5;

        protected static $dbTable = "armies";
        protected static $tableFields = ["name", "units", "idAttackStrategy", "idGame"];

        public static function getArmiesByGame($idGame) {
            $sql = "SELECT * FROM armies WHERE idGame = {$idGame} ORDER BY id DESC ";

            return static::getByQuery($sql);
        }

        public static function getAliveArmies($idGame) {
            $sql = "SELECT * FROM armies WHERE idGame = {$idGame} AND units > 0 ORDER BY id DESC";

            return static::getByQuery($sql);
        } 

        public static function getFirstAttackerArmy($idGame) {
            $sql = "SELECT * FROM armies WHERE idGame = {$idGame} AND units > 0 ORDER BY id DESC LIMIT 1";
           
            return static::getByQuery($sql)[0];
        }

        public function willAttack() {
            return rand(0,100) < $this->units;
        }

        public function attackDamage() {
            return ceil($this->units * static::$damagePerUnit);
        }

        public function unitsRemoved($damage) {
            $this->units -= $damage;

            $this->update();
        }

        public function findWhomToAttack() {
            $allArmies = Army::getAliveArmies($this->idGame);

            $armies = [];

            foreach($allArmies as $army) {
                if($army->id != $this->id) {
                    $armies[] = $army;
                }
            }

            if(count($armies) > 0) {
                if($this->idAttackStrategy == 1) {
                    $armyId = static::findRandomArmy($armies);
                                 
                } else if ($this->idAttackStrategy == 2) {
                    $armyId = static::findWeakestArmy($armies);
                } else {
                    $armyId = static::findStrongestArmy($armies);
                }
    
                return $armyId;

            } else {
                return false;
            }

        }

        public static function findRandomArmy($armies) {
            // var_dump($armies);
            $randomIndex = rand(0, count($armies) - 1);

            $attackedArmy = $armies[$randomIndex];

            return $attackedArmy->id;
        }

        public static function findWeakestArmy($armies) {
            $minUnits = 100000000;
            $id = 0;

            foreach($armies as $army) {
                if($army->units < $minUnits) {
                    $minUnits = $army->units;
                    $id = $army->id;
                }
            }

            return $id;
        }

        public static function findStrongestArmy($armies) {
            $maxUnits = -1000000;
            $id = 0;

            foreach($armies as $army) {
                if($army->units > $maxUnits) {
                    $maxUnits = $army->units;
                    $id = $army->id;
                }
            }

            return $id;
        }



    }

?>