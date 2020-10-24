<?php
    include './Utils/Connector.php';

    class Products extends Connector {

        protected function getAllProducts() {
            $conn = $this->connect();
            $sql = "select product_id, product_name from product order by product_id";

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
            $sql = "select product_id, product_name from product where FIND_IN_SET(product_name, \"$productsString\")  order by product_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $stmt->bind_result($product_id, $product_name);

            while($stmt->fetch()) {
                echo $product_id . ' --- ' . $product_name . "\n";
            }

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

?>