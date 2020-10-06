<?php

    class AttackStrategy extends DbObject {
        public $id;
        public $valueStrategy;
        public $description;

        protected static $dbTable = "attackstrategies";
        protected static $tableFields = ["valueStrategy", "description"];

    }

?>