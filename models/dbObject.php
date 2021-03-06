<?php 

    class DbObject {

        public static function getAll() {
            $sql = "SELECT * FROM " . static::$dbTable;

            return static::getByQuery($sql);
        }

        public static function getSingle($id) {
            $sql = "SELECT * FROM " . static::$dbTable . " WHERE id = $id LIMIT 1";

            // var_dump(static::getByQuery($sql));
            return empty(static::getByQuery($sql)) ? null : static::getByQuery($sql)[0];
        }

        public static function getByQuery($sql) {
            global $db;

            $result = $db->execQuery($sql);
            $objArr = [];

            foreach($result as $row) {
                $objArr[] = static::initObj($row);
            }

            return $objArr;
        }

        public static function initObj($row) {
            $callingClass = get_called_class();
            $obj = new $callingClass();

            foreach($row as $prop => $val) {
                if(property_exists($callingClass, $prop)) {
                    $obj->$prop = $val;
                }
            }

            return $obj;
        }

        protected function getProps() {
            $props = [];

            foreach (static::$tableFields as $field) {
                if(property_exists($this, $field)) {
                    $props[$field] = $this->$field;
                }
            }

            return $props;
        }

        public function save() {
            return isset($this->id) ? $this->update() : $this->insert();
        }

        public function insert() {
            global $db;

            $props = $this->getProps();

            $sql = "INSERT INTO " . static::$dbTable . " (" . implode(",", array_keys($props)) . ")";
            $sql .= " VALUES ('" . implode("','", array_values($props)) . "')";

            $res = $db->execQueryStmt($sql);

            // var_dump($res, $db->lastInsertId());

            if($res) {
                $this->id = $db->lastInsertId();

                return true;
            } else {
                return false;
            }


        }

        public function update() {
            global $db;

            $props = $this->getProps();
            $propPairs = [];

            foreach($props as $key => $val) {
                $propPairs[] = "{$key}='{$val}'";
            }

            $sql = "UPDATE " . static::$dbTable . " SET ";
            $sql .= implode(",", $propPairs);
            $sql .= " WHERE id= " . $this->id;

            return $db->execQueryStmt($sql);
            
        }

        public function delete() {
            global $db;

            $sql = "DELETE FROM " . static::$dbTable . " WHERE id = {$this->id}";

            return $db->execQueryStmt($sql);
        }

        public static function countAll() {
            global $db;

            $sql = "SELECT COUNT(*) as total FROM " . static::$dbTable;
            $resultArr = $db->execQuery($sql);
            return $resultArr[0]['total'];
        }

    }
