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
            // Get the count of every item from the user input list
            $counts = array_count_values($items);
            // Initialize the invoice subtotal to be 0
            $subtotal = 0;

            // Loop over the products array and use the counts array to calculate the subtotal
            foreach($productsArray as $product) {
                // Add (Count * Price) to the subtotal
                $subtotal += $counts[$product->name] * $product->priceUsd;
            }
            // Calculate taxes which are 14% of the subtotal
            $taxes = $subtotal * 0.14;
            // Calculate the invoice total by adding taxes
            $total = $subtotal + $taxes;

            // Print the invoice subtotal
            print_r("Subtotal: $" . $subtotal . "\n");
            // Print the taxes
            print_r("Taxes: $" . $taxes . "\n");
            // Print the invoice total
            print_r("Total: $" . $total . "\n");
        }
    }

?>