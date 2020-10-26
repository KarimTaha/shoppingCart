<?php

    function getProducts($conn, $productsArray) {

        $productsString = getProductsString($productsArray);
        $sql = "select product_name, price_usd from product where FIND_IN_SET(product_name, \"$productsString\")  order by product_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $product_name = "";
        $price_usd = 0.0;

        $stmt->bind_result($product_name, $price_usd);

        $productsResult = array();
        $currentProduct = null;

        while($stmt->fetch()) {
            $currentProduct = new Product($product_name, $price_usd);
            array_push($productsResult, $currentProduct);
        }

        $stmt->close();

        return $productsResult;
    }

    function getProductsString($productsArray){
        $result = "";
        foreach($productsArray as $product) {
            $result .= $product . ",";
        }
        $result = substr($result, 0, strlen($result)-1);
        return $result;
    }
    

    class Product {
        private $name;
        private $priceUsd;

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