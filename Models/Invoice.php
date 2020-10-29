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

        // Class attributes
        private $subtotal;
        private $discounts;
        private $discountTotal;
        private $taxes;
        private $total;

        // Constructor
        public function __construct() {
            $this->subtotal = 0;
            $this->discounts = array();
            $this->discountTotal = 0;
            $this->taxes = 0;
            $this->total = 0;
        }

        // Getters
        public function get_subtotal(){
            return $this->subtotal;
        }

        public function get_discounts() {
            return $this->discounts;
        }

        public function get_discountTotal() {
            return $this->discountTotal;
        }

        public function get_taxes() {
            return $this->taxes;
        }

        public function get_total() {
            return $this->total;
        }

        // Setters
        public function set_subtotal($subtotal){
            $this->subtotal = $subtotal;
        }

        public function set_discounts($discounts) {
            $this->discounts = $discounts;
        }

        public function set_discountTotal($discountTotal) {
            $this->discountTotal = $discountTotal;
        }

        public function set_taxes($taxes) {
            $this->taxes = $taxes;
        }

        public function set_total($total) {
            $this->total = $total;
        }


        public function printInvoice($items, $inputCurrency) {

            // Init mySQL database connection
            $mySQLConn = new MySQLConnection();
            $conn = $mySQLConn->connect();

            // Create an Invoice object
            $invoice = new Invoice();

            // Call getProducts to get the products that the user entered from DB
            $productsArray = getProducts($conn, $items);

            // Get the count of every item from the user input list
            $counts = array_count_values($items);

            // Call getCurrencyRate to get the currencyRate object to convert to
            $invoiceCurrency = getCurrencyRate($conn, $inputCurrency);
            $conversionRate = $invoiceCurrency->get_rate();
            $invoiceCurrencyCode = $invoiceCurrency->get_code();

            // Loop over the products array and use the counts array to calculate the subtotal
            foreach($productsArray as $product) {
                // Add (Count * Price) to the subtotal
                $invoice->subtotal += $counts[$product->get_name()] * $product->get_price_usd();
            }

            // Convert subtotal to target currency
            $invoice->subtotal *= $conversionRate;

            // Calculate taxes which are 14% of the subtotal
            $invoice->taxes = $invoice->subtotal * 0.14;
            // Calculate the invoice total by adding taxes
            $invoice->total = $invoice->subtotal + $invoice->taxes;

            // Print the invoice subtotal
            print_r("Subtotal: " . $invoice->subtotal . " " . $invoiceCurrencyCode . "\n");
            // Print the taxes
            print_r("Taxes: " . $invoice->taxes . " " . $invoiceCurrencyCode . "\n");

            // TODO Get available offers
            $availableOffers = getOffers($conn);
            // Boolean to keep track of printing the Discount title once if there exists one or more dicounts
            $discountPrinted = false;
            // Loop over offers available in system to calculate if any offer is applicable
            foreach($availableOffers as $offer) {
                // Call calculateDiscount for each offer passing the products and their counts
                $invoice->discountTotal += $offer->calculateDiscount($productsArray, $counts);
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
            $invoice->discountTotal *= $conversionRate;
            // Subtract the total discount from the total of the invoice (both in target currency)
            $invoice->total -= $invoice->discountTotal;

            // Print the invoice total
            print_r("Total: " . $invoice->total . " " . $invoiceCurrencyCode . "\n");

            // Close the database connection
            $conn->close();

            return $invoice;
        }
    }
?>
