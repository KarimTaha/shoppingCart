<?php

    include './Models/Products.php';

    class Invoice extends Products {
        public function printInvoice($items) {
            // $this->getAllProducts();
            $this->getProducts($items);
        }
    }

?>