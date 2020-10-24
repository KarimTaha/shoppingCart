<?php
    /**
     * This is a view file used to prepare and print the invoice
     * The invoice is based on the products entered by the user
     * The Invoice class extends Products to access the products data in the DB
     */
    include './Models/Products.php';

    class Invoice extends Products {
        public function printInvoice($items) {

            // Call getProducts to get the products that the user entered from DB
            $productsArray = $this->getProducts($items);

            $counts = array_count_values($items);
            $subtotal = 0;

            foreach($productsArray as $product) {
                $product->count = $counts[$product->name];
                $subtotal += $product->count * $product->priceUsd;
            }

            $taxes = $subtotal * 0.14;
            $total = $subtotal + $taxes;

            print_r("Subtotal: $" . $subtotal . "\n");
            print_r("Taxes: $" . $taxes . "\n");
            print_r("Total: $" . $total . "\n");
        }
    }

?>