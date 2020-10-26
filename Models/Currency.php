<?php

    /**
     * This function takes as input the database connection and currency entered by user
     * The currency is fetched from the database, and stored in a CurrencyRate object
     * The CurrencyRate object is returned to the invoice to be used to display the invoice to user
     * If the user enters an invoice that is not present, a warning is shown and USD is used
     */
    function getCurrencyRate($conn, $inputCurrency) {
        // Check if input currency is not of length 3
        if (strlen($inputCurrency) != 3) {
            // If so, show warning and use USD for the invoice
            print_r("Warning: " . $inputCurrency . " is not an accepted Currency. Displaying invoice in USD.\n");
            $inputCurrency = "USD";
        }
        // SQL statement to select currency as input
        $sql = "select name, rate, symbol from Currency where code = \"$inputCurrency\"";
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Variables to be binded to the SQL result
        $name = "";
        $rate = 1.0;
        $symbol = "";
        // Bind variables to the result
        $stmt->bind_result($name, $rate, $symbol);
        // Holder to store the CurrencyRate object which will be returned
        $resultCurrency = null;

        // Loop over the resulting rows from the SQL query
        while($stmt->fetch()) {
            // Create a new CurrencyRate object
            $resultCurrency = new CurrencyRate($name, $rate, $symbol, $inputCurrency);
        }
        // If the SQL query didn't return any data, use USD and show warning
        if ($resultCurrency == null) {
            print_r("Warning: " . $inputCurrency . " is not an accepted Currency. Displaying invoice in USD.\n");
            $resultCurrency = new CurrencyRate("US Dollar", 1.0, "$", "USD");
        }
        // Close the SQL statement
        $stmt->close();
        // Return the CurrencyRate object
        return $resultCurrency;
    }
    
    // Class CurrencyRate that represents a currency that will be used in the invoice
    class CurrencyRate {
        // Class attributes
        private $name;
        private $rate;
        private $symbol;
        private $code;

        // Constructor that sets name, rate, symbol as fetched from DB, and Code based on user input
        function __construct($name, $rate, $symbol, $code) {
            $this->name = $name;
            $this->rate = $rate;
            $this->symbol = $symbol;
            $this->code = $code;
        }
        
        // Getter functions
        function get_name() {
            return $this->name;
        }
        function get_rate() {
            return $this->rate;
        }
        function get_symbol() {
            return $this->symbol;
        }
        function get_code() {
            return $this->code;
        }
    }

?>