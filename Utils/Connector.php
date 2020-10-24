<?php

    class Connector {

        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $dbName = "shopping_cart";

        private $debug_mode = false;

        protected function connect(){
            
            $link =  mysqli_connect($this->host, $this->user, $this->password, $this->dbName);

            if ($this->debug_mode && !$link) {
                print_r("Error: Unable to connect to MySQL." . PHP_EOL);
                print_r("Debugging errno: " . mysqli_connect_errno() . PHP_EOL);
                print_r("Debugging error: " . mysqli_connect_error() . PHP_EOL);
                exit;
            }
            
            if ($this->debug_mode) {
                print_r("Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL);
                print_r("Host information: " . mysqli_get_host_info($link) . PHP_EOL);
            }

            return $link;
        }
    }
