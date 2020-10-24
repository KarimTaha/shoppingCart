<?php
    include './Utils/Connector.php';

    class Products extends Connector {

        public function getProducts() {
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
    }

?>