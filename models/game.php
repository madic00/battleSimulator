<?php

    class Game extends DbObject {
        public $id;
        public $gameCodeID;
        public $idGameStatus = 1;
        public $gameStatus;

        protected static $dbTable = "games";
        protected static $tableFields = ["idGameStatus", "gameCodeID"];

        public static function getAllGamesInfo() {
            $sql = "SELECT g.*, gs.valueStatus as gameStatus FROM games g INNER JOIN gamestatuses gs ON g.idGameStatus = gs.id";

            return static::getByQuery($sql);
        }

        public static function gameInfo($gameId) {
            $sql = "SELECT g.*, gs.valueStatus as gameStatus FROM games g INNER JOIN gamestatuses gs ON g.idGameStatus = gs.id WHERE g.id = {$gameId} LIMIT 1";

            return static::getByQuery($sql)[0];
        }

    }

?>