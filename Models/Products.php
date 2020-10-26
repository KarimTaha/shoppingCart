<?php

    /**
     * Function to get the products entered by the user from database
     * It takes as input the database connection as well as the products array
     * The products are saved in an array of Product objects and returned
     */
    function getProducts($conn, $productsArray) {
        // Create an array with the unique values of the products array
        $productsUniqueArray = array_unique($productsArray);
        // Get a comma separated string with all names in the array
        $productsString = arrayToCommaSeparated($productsUniqueArray);
        // SQL statement to fetch required products
        $sql = "select product_name, price_usd from product where FIND_IN_SET(product_name, \"$productsString\")  order by product_id";
        // Execute SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        // Initialize binded variables
        $product_name = "";
        $price_usd = 0.0;
        // Bind result to variables
        $stmt->bind_result($product_name, $price_usd);
        // Create array to hold the Product objects
        $productsResult = array();
        // Holder object for current Product object to insert into array
        $currentProduct = null;

        // Loop over rows returned by SQL query
        while($stmt->fetch()) {
            // Create a new Product object
            $currentProduct = new Product($product_name, $price_usd);
            // Push the Product into the products array
            array_push($productsResult, $currentProduct);
        }
        // Check if the result has less rows that the input unique objects
        if (count($productsUniqueArray) > count($productsResult)) {
            print_r("Warning: you entered one or more products that do not exist. The invoice will be calculated for the other items.\n");
        }
        // Close the SQL statement
        $stmt->close();
        // Return the array containing the Product Objects
        return $productsResult;
    }

    /**
     * Helper function that takes as input an array of strings, and returns a string of comma separated values
     * Ex: input = [A, B, C, D] ----> output = "A,B,C,D"
     */
    function arrayToCommaSeparated($inputArray){
        // Variable to hold the result, to be returned
        $result = "";
        // Loop over the array
        foreach($inputArray as $item) {
            // Concatenate each item to the result string
            $result .= $item . ",";
        }
        // Ignore the last comma that was added in the loop
        $result = substr($result, 0, strlen($result)-1);
        // Return the result string
        return $result;
    }
    
    // Class product that represents a product (item) that a user can buy
    class Product {
        // Class attributes
        private $name;
        private $priceUsd;

        // Constructor setting name and price
        function __construct($name, $priceUsd) {
            $this->name = $name;
            $this->priceUsd = $priceUsd;
        }
        // Getter functions
        function get_name() {
            return $this->name;
        }
        function get_price_usd() {
            return $this->priceUsd;
        }
    }

?>