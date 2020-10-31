<?php
    use PHPUnit\Framework\TestCase;

    use function PHPUnit\Framework\assertEquals;

    class OffersTest extends TestCase {

        private $conn = null;

        public function getDBConn(){
            require_once './Utils/Connector.php';
            if($this->conn == null) {
                // Init mySQL database connection
                $mySQLConn = new MySQLConnection();
                $this->conn = $mySQLConn->connect(); 
            }
            return $this->conn;
        }

        /**
         * Function to test getting available offers in the database
         */
        public function testGetOffers() {
            require './Models/Offers.php';

            $conn = $this->getDBConn();
            $offersResult = getOffers($conn);

            $this->assertEquals(2,count($offersResult));

            $this->assertEquals("Shoes",$offersResult[0]->get_code());
            $this->assertEquals("Jacket50",$offersResult[1]->get_code());

            $conn->close();
        }
    }

?>