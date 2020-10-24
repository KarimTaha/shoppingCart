<?php
    include './Utils/Connector.php';

    class Products extends Connector {

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

        function __construct($name, $priceUsd) {
            $this->name = $name;
            $this->priceUsd = $priceUsd;
        }
        function get_name() {
            return $this->name;
        }
        function get_price_usd() {
            return $this->priceUsd;
        }
    }

?>