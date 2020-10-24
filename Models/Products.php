<?php
    include './Utils/Connector.php';

    class Products extends Connector {

        protected function getAllProducts() {
            $conn = $this->connect();
            $sql = "select product_name, price_usd from product order by product_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $stmt->bind_result($product_id, $product_name);

            while($stmt->fetch()) {
                echo $product_id . ' --- ' . $product_name . "\n";
            }

            $stmt->close();
            $conn->close();
        }

        protected function getProducts($productsArray) {
            $conn = $this->connect();

            $productsString = $this->getProductsString($productsArray);
            $sql = "select product_name, price_usd from product where FIND_IN_SET(product_name, \"$productsString\")  order by product_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $stmt->bind_result($product_name, $price_usd);

            $productsResult = array();
            $currentProduct = null;

            while($stmt->fetch()) {
                // echo $product_id . ' --- ' . $product_name . "\n";
                $currentProduct = new Product($product_name, $price_usd);
                array_push($productsResult, $currentProduct);
            }

            return $productsResult;

            $stmt->close();
            $conn->close();
        }

        function getProductsString($productsArray){
            $result = "";
            foreach($productsArray as $product) {
                $result .= $product . ",";
            }
            $result = substr($result, 0, strlen($result)-1);
            return $result;
        }
    }

    class Product {
        public $name;
        public $priceUsd;
        public $count;

        function __construct($name, $priceUsd) {
            $this->name = $name;
            $this->priceUsd = $priceUsd;
            $this->count = 0;
        }
        function get_name() {
            return $this->name;
        }
        function get_price_usd() {
            return $this->priceUsd;
        }
    }

?>