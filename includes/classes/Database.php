<?php
    class Database {
        private static $connection = false;

        // static $database_host = "localhost";
        // static $database_user = "root";
        // static $database_pass = "";
        // static $database_name = "kamreo-blog";

        public static function Connect() {

             Database::$connection = mysqli_connect( "localhost","root", "", "kamreo-blog" );

             if ($connection->connect_error) {
                echo('The error was: ' . $connection->connect_error);
             }

             Database::$connection->set_charset( 'utf8' );
            
        } 
        public static function Disconnect() { Database::$connection->close(); }
        public static function Query( $sql ) {
     
             return Database::$connection->query( $sql );
             }
        public static function Insert( $sql ) { Database::Query( $sql ); return Database::$connection->insert_id; }
        public static function Update( $sql ) { return Database::Query( $sql ); }
        public static function Results( $sql ) { $results = Database::Query( $sql ); $list = new gList(); while( $results && $result = mysqli_fetch_assoc( $results ) ) $list->Add( (object)$result ); return $list; }
        public static function Result( $sql ) { $results = Database::Query( $sql ); if( $results && $result = mysqli_fetch_assoc( $results ) ) return (object)$result; return false; }
    }
    
  