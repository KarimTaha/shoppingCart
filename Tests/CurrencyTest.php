<?php
    use PHPUnit\Framework\TestCase;

    use function PHPUnit\Framework\assertEquals;

    class CurrencyTest extends TestCase {

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

        public function testGetValidCurrency() {
            require './Models/Currency.php';

            $conn = $this->getDBConn();
            $resultCurrency = getCurrencyRate($conn, "EUR");

            $this->assertEquals("Euro",$resultCurrency->get_name());
            $this->assertEquals(0.84,$resultCurrency->get_rate());
            // $this->assertEquals("€",$resultCurrency->get_symbol());
            $this->assertEquals("EUR",$resultCurrency->get_code());

            $conn->close();
        }

        public function testGetInvalidCurrency() {
            $conn = $this->getDBConn();
            $resultCurrency = getCurrencyRate($conn, "GBP");

            $this->assertEquals("US Dollar",$resultCurrency->get_name());
            $this->assertEquals(1,$resultCurrency->get_rate());
            $this->assertEquals("$",$resultCurrency->get_symbol());
            $this->assertEquals("USD",$resultCurrency->get_code());

            $conn->close();
        }
    }

?>