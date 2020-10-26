<?php
    /**
     * This is a Model file used to prepare and print the invoice
     * The invoice is based on the products entered by the user
     * The Invoice class uses Products, Currency, and Offers to access the data in the DB
     */
    include './Models/Products.php';
    include './Models/Currency.php';
    include './Models/Offers.php';
    include './Utils/Connector.php';

    class Invoice {
        public function printInvoice($items, $inputCurrency) {

            // Init mySQL database connection
            $mySQLConn = new MySQLConnection();
            $conn = $mySQLConn->connect();

            // Call getProducts to get the products that the user entered from DB
            $productsArray = getProducts($conn, $items);

            // Get the count of every item from the user input list
            $counts = array_count_values($items);

            // Call getCurrencyRate to get the currencyRate object to convert to
            $invoiceCurrency = getCurrencyRate($conn, $inputCurrency);
            $conversionRate = $invoiceCurrency->get_rate();
            $invoiceCurrencyCode = $invoiceCurrency->get_code();

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

            // TODO Get available offers
            $availableOffers = getOffers($conn);
            // Total discount is initially 0
            $totalDiscount = 0.0;
            // Boolean to keep track of printing the Discount title once if there exists one or more dicounts
            $discountPrinted = false;
            // Loop over offers available in system to calculate if any offer is applicable
            foreach($availableOffers as $offer) {
                // Call calculateDiscount for each offer passing the products and their counts
                $totalDiscount += $offer->calculateDiscount($productsArray, $counts);
                // If the Offer object has amount = 0, then this offer is not applicable to the items bought
                if ($offer->get_amount() > 0){
                    // Print the Discounts headline only once
                    if (!$discountPrinted) {
                        print_r("Discounts:" . "\n");
                        $discountPrinted = true;
                    }
                    // Print the shoes offer discount in target currency
                    if ($offer->get_code() == "Shoes") {
                        print_r("          10% off shoes: -" . $offer->get_amount() * $conversionRate . " " . $invoiceCurrencyCode . "\n");
                    }
                    // Print the jackets offer discount in target currency
                    elseif ($offer->get_code() == "Jacket50") {
                        // Depending on the number the jackets offer was applied, print jacket or jackets (plural)
                        if ($offer->get_count() == 1) {
                            print_r("          50% off jacket: -" . $offer->get_amount() * $conversionRate . " " . $invoiceCurrencyCode . "\n");
                        }
                        else {
                            print_r("          50% off jackets: -" . $offer->get_amount() * $conversionRate . " " . $invoiceCurrencyCode . "\n");
                        }
                        
                    }
                }
            }
            // Calculate the total discount in the target currency
            $totalDiscount *= $conversionRate;
            // Subtract the total discount from the total of the invoice (both in target currency)
            $total -= $totalDiscount;

            // Print the invoice total
            print_r("Total: " . $total . " " . $invoiceCurrencyCode . "\n");

            // Close the database connection
            $conn->close();
        }
    }
?>
