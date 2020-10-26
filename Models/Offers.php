<?php
    include_once './Models/Products.php';

    /**
     * Function to get the available offers from database
     * It takes as input the database connection
     * It returns an array of Offers available
     */
    function getOffers($conn) {
        // SQL query to get available offers. TODO use start and end dates
        $sql = "select code, description from Offer";
        // Prepare and execute SQL query
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Variables to be binded to the query result
        $code = "";
        $description = "";
        // Bind variables to the query result
        $stmt->bind_result($code, $description);
        // Array that will hold the Offer objects and be returned
        $offersResult = array();
        // Holder to create the Offer objects and insert into the result array
        $currentOffer = null;

        // Loop over the resulting rows from the SQL query
        while($stmt->fetch()) {
            // Create a new Offer object
            $currentOffer = new Offer($code, $description);
            // Insert the Offer object into the result array
            array_push($offersResult, $currentOffer);
        }
        // Close the SQL statement
        $stmt->close();
        // Return the array of Offer objects
        return $offersResult;
    }
    
    // Class Offer that represents an offer that will be applied to the invoice
    class Offer {
        // Class attributes
        private $code;
        private $description;
        private $amount;
        private $count;

        // Constructor that sets code and description for the offer
        function __construct($code, $description) {
            $this->code = $code;
            $this->description = $description;
        }
        
        // Getter functions
        function get_code() {
            return $this->code;
        }
        function get_description() {
            return $this->description;
        }
        function get_amount(){
            return $this->amount;
        }
        function get_count(){
            return $this->count;
        }

        /**
         * This function calculates the discount for each offer
         * It takes as input an array of the products available in the invoice, and an array with the counts of the products
         * Currently it handles the Shoes and Jacket offers
         * Returns the total amount that the offer will apply to the invoice
         * It also updates the Offer object by calculating the amount and count and storing in the object variables
         */
        function calculateDiscount($productsArray, $counts){
            // Initialize total discount for this offer to be 0
            $totalDiscount = 0.0;
            // Shoes offer
            if ($this->code == "Shoes") {
                // Variable to hold the price of a pair of shoes before discount
                $shoesPrice = 0.0;
                // Variable to hold the count of shoes in the invoice
                $shoesCount = $counts["Shoes"];
                // Loop over the products array to get the shoes price and calculate the discount applicable
                foreach ($productsArray as $product) {
                    if ($product->get_name() == "Shoes") {
                        // Store the price for a pair of shoes
                        $shoesPrice = $product->get_price_usd();
                        // Calculate the discount per pair of shoes
                        $discountPerShoes = $shoesPrice * 0.10;
                        // Calculate the total discount from this offer
                        $totalDiscount += $shoesCount * $discountPerShoes;
                    }
                }
                // Update the count variable with the number of pairs of shoes
                $this->count = $shoesCount;
            }
            // Jacket offer
            elseif ($this->code == "Jacket50") {
                // Variable to hold the number of tshirts in the invoice
                $tshirtCount = 0;
                // Variable to hold the number of jackets in the invoice
                $jacketCount = 0;
                // Variable to hold the price of a single jacket before discount
                $jacketPrice = 0;
                // Get the number of tshirts from the count array
                $tshirtCount = $counts["T-shirt"];
                // Get the number of jackets from the count array
                $jacketCount = $counts["Jacket"];
                // Loop over the products array to get the jacket price and calculate the discount applicable
                foreach ($productsArray as $product) {
                    if ($product->get_name() == "Jacket") {
                        // Get the price for a single jacket before discount
                        $jacketPrice = $product->get_price_usd();
                    }
                } 
                // Get the number of jackets that can have a discount based on the number of tshirts
                $jacketDiscountsMax = floor($tshirtCount/2);
                // Get the actual number of jacket discounts that will be applied
                $jacketDiscountsApplicable = min($jacketCount, $jacketDiscountsMax);
                // Calculate the discount per jacket, regardless of counts
                $discountPerJacket = 0.5 * $jacketPrice;
                // Calculate the total discount that will be applied to the invoice
                $totalDiscount += $jacketDiscountsApplicable * $discountPerJacket;
                // Update the count variable with the number of jacket discounts
                $this->count = $jacketDiscountsApplicable;
            }
            // Update the offer amount with the offer's total discount value
            $this->amount = $totalDiscount;
            // Return the amount of the discount
            return $totalDiscount;
        }
    }

?>