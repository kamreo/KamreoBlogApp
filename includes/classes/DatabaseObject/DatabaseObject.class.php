<?php
    class DatabaseObject {

        //Global
        private static $databaseTable;
        private static $objects = array();

        public static function ToObjects( $objects ) {
            foreach( $objects as $key => $value ) $objects->Update( $key, new static( $value ) );
            return $objects;
        }

        public static function SelectAll( $filter = false ) {
            $table = static::$databaseTable;
            $results = Database::Results( "SELECT * FROM {$table} {$filter}" );
            foreach( $results as $key => $object ) $results->Update( $key, new static( $object ) );
            return $results;
        }

        
        public static function SelectWhere( array $where = array() ) {
            if( !is_array( $where ) ) $where = array();

            if( isset( $where['WHERE'] ) ) {
                $where['WHERE'] = array_diff( $where['WHERE'], array( '' ) );
                $where['WHERE'] = 'WHERE '.implode( ' ', $where['WHERE'] );
            }

            if( isset( $where['ORDER BY'] ) ) {
                $where['ORDER BY'] = array_diff( $where['ORDER BY'], array( '' ) );
                $where['ORDER BY'] = 'ORDER BY '.implode( ', ', $where['ORDER BY'] );
            }

            $where = implode( ' ', $where );

            return static::SelectAll( $where );
        }

        public static function SelectFind( $filter = false ) {
            $table = static::$databaseTable;
            $result = Database::Result( "SELECT * FROM {$table} {$filter} LIMIT 1" );
            if( $result ) return new static( $result );
            return false;
        }

        //Local

        private $object = false;

        public function GetTable() { return static::$databaseTable; }
        public function GetTableKey() { return static::$databaseTableKey; }

        public function __construct( $object ) {
            $this->class = get_class( $this );
            $this->Object( $object );
        }

        public function GetId() { return $this->Id(); }
        public function GetObject() { return $this->Object(); }

        public function GetClass() { return $this->class.'-'.$this->GetId(); }
        public function GetAttr() { return $this->class.'="'.$this->GetId().'"'; }

        public function Id() {
            return $this->Get( $this->GetTableKey() );
        }
        public function Object( $object = false ) {
            if( $object ) {
                if( is_object( $object ) ) $this->object = $object;
                else $this->Result( $object );
            }
            return $this->object;
        }
        public function Get( $key ) { return isset( $this->object ) && $this->object && isset( $this->object->$key ) ? $this->object->$key : false; }

        public function Result( $id = false ) {
            $id = $id ? $id : $this->Id();

            $classId = $this->class.'-'.$this->Id();

            if( !is_array( DatabaseObject::$objects ) ) DatabaseObject::$objects = array();

            if( isset( DatabaseObject::$objects[$classId] ) && DatabaseObject::$objects[$classId] ) {
                $this->Object( DatabaseObject::$objects[$classId] );
            } else {
                $object = Database::Result( "SELECT * FROM {$this->GetTable()} WHERE {$this->GetTableKey()} = '{$id}'" );
                return $this->Object( $object );
                DatabaseObject::$objects[$classId] = $this->Object();
            }
        }
        public function Update( $key, $value = false ) {
            if( is_array( $key ) ) {
                $array = $key;
                foreach( $array as $key => $value ) $array[$key] = $key."='{$value}'";
                $array = implode( ',', $array );

                Database::Query( "UPDATE {$this->GetTable()} SET {$array} WHERE {$this->GetTableKey()} = '{$this->Id()}'" );
            } else {
                Database::Query( "UPDATE {$this->GetTable()} SET {$key} = '{$value}' WHERE {$this->GetTableKey()} = '{$this->Id()}'" );
            }
            
            $this->Result();

            return $this->Get( $key );
        }
        public function Delete() { Database::Query( "DELETE FROM {$this->GetTable()} WHERE {$this->GetTableKey()} = '{$this->Id()}'" ); }

        public function Exists() { return $this->object; }

        public static function InsertObject() { $databaseTable = static::$databaseTable; $objectId = Database::Insert( "INSERT INTO {$databaseTable} () VALUES ()" ); return $objectId; }

        public static function Insert( $data = array() ) {
            $databaseTable = static::$databaseTable;

            $keys = array();
            $values = array();
            foreach( $data as $key => $value ) {
                array_push( $keys, $key );
                array_push( $values, '\''.$value.'\'' );
            }

            $keys = implode( ',', $keys );
            $values = implode( ',', $values );

            $objectId = Database::Insert( "INSERT INTO {$databaseTable} ( {$keys} ) VALUES ( {$values} )" );

            return new static( $objectId );
        }

        public function Debug() {
            ?>
            <div style="white-space: pre-wrap;"><?php print_r( $this->object ); ?></div>
            <?php
        }

        public function ToJson() {
            return array();
        }
    }
?>