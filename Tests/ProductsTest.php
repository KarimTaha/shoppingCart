<?php
    use PHPUnit\Framework\TestCase;

    use function PHPUnit\Framework\assertEquals;

    class ProductsTest extends TestCase {

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
        
        public function testArrayToCommaSeparated() {
            require './Models/Products.php';
            $resultString = arrayToCommaSeparated(Array("A", "B", "C"));
            $this->assertEquals("A,B,C", $resultString);
        }

        public function testGetSingleProduct() {
            $conn = $this->getDBConn();
            $products = getProducts($conn, array("Shoes"));

            $this->assertEquals(1,count($products));

            $this->assertEquals("Shoes",$products[0]->get_name());
            $this->assertEquals(24.99,$products[0]->get_price_usd());

            $conn->close();
        }

        public function testGetDuplicateProduct() {
            $conn = $this->getDBConn();

            $products = getProducts($conn, array("Shoes", "Shoes"));

            $this->assertEquals(1,count($products));

            $this->assertEquals("Shoes",$products[0]->get_name());
            $this->assertEquals(24.99,$products[0]->get_price_usd());
            $conn->close();
        }

        public function testGetMultipleProducts() {
            $conn = $this->getDBConn();

            $products = getProducts($conn, array("Shoes", "T-shirt", "Jacket", "Pants"));

            $this->assertEquals(4,count($products));

            $this->assertEquals("T-shirt",$products[0]->get_name());
            $this->assertEquals("Pants",$products[1]->get_name());
            $this->assertEquals("Jacket",$products[2]->get_name());
            $this->assertEquals("Shoes",$products[3]->get_name());

            $this->assertEquals(10.99,$products[0]->get_price_usd());
            $this->assertEquals(14.99,$products[1]->get_price_usd());
            $this->assertEquals(19.99,$products[2]->get_price_usd());
            $this->assertEquals(24.99,$products[3]->get_price_usd());

            $conn->close();
        }

        public function testGetWrongProduct() {
            $conn = $this->getDBConn();
            $products = getProducts($conn, array("Wrong"));

            $this->assertEquals(0,count($products));

            $conn->close();
        }
    }

?>