<?php
    /**
     * This is a Model file used to prepare and print the invoice
     * The invoice is based on the products entered by the user
     * The Invoice class uses Products, Currency, and Offers to access the data in the DB
     */
    include './Models/Products.php';
    include './Models/Currency.php';
    include './Utils/Connector.php';

    class Invoice {
        public function printInvoice($items, $inputCurrency) {

            // Init mySQL database connection
            $mySQLConn = new MySQLConnection();
            $conn = $mySQLConn->connect();

            // Call getProducts to get the products that the user entered from DB
            $productsArray = getProducts($conn, $items);

            // Call getCurrencyRate to get the currencyRate object to convert to
            $invoiceCurrency = getCurrencyRate($conn, $inputCurrency);
            $conversionRate = $invoiceCurrency->get_rate();
            $invoiceCurrencyCode = $invoiceCurrency->get_code();

            // TODO Get available offers

            // Close the database connection
            $conn->close();

            // Get the count of every item from the user input list
            $counts = array_count_values($items);
            // Initialize the invoice subtotal to be 0
            $subtotal = 0;

            // Loop over the products array and use the counts array to calculate the subtotal
            foreach($productsArray as $product) {
                // Add (Count * Price) to the subtotal
                $subtotal += $counts[$product->get_name()] * $product->get_price_usd();
            }

            // Convert subtotal to target currency
            $subtotal *= $conversionRate;

            // Calculate taxes which are 14% of the subtotal
            $taxes = $subtotal * 0.14;
            // Calculate the invoice total by adding taxes
            $total = $subtotal + $taxes;

            // Print the invoice subtotal
            print_r("Subtotal: " . $subtotal . " " . $invoiceCurrencyCode . "\n");
            // Print the taxes
            print_r("Taxes: " . $taxes . " " . $invoiceCurrencyCode . "\n");
            // Print the invoice total
            print_r("Total: " . $total . " " . $invoiceCurrencyCode . "\n");
        }
    }

?>