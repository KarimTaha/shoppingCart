<?php

    /**
     * This function takes as input the database connection and currency entered by user
     * The currency is fetched from the database, and stored in a CurrencyRate object
     * The CurrencyRate object is returned to the invoice to be used to display the invoice to user
     * If the user enters an invoice that is not present, a warning is shown and USD is used
     */
    function getCurrencyRate($conn, $inputCurrency) {
        if (strlen($inputCurrency) != 3) {
            print_r("Warning: " . $inputCurrency . " is not an accepted Currency. Displaying invoice in USD.\n");
            $inputCurrency = "USD";
        }
        $sql = "select name, rate, symbol from Currency where code = \"$inputCurrency\"";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $name = "";
        $rate = 1.0;
        $symbol = "";
        $stmt->bind_result($name, $rate, $symbol);

        $resultCurrency = null;

        while($stmt->fetch()) {
            $resultCurrency = new CurrencyRate($name, $rate, $symbol, $inputCurrency);
        }

        if ($resultCurrency == null) {
            print_r("Warning: " . $inputCurrency . " is not an accepted Currency. Displaying invoice in USD.\n");
            $resultCurrency = new CurrencyRate("US Dollar", 1.0, "$", "USD");
        }
        $stmt->close();
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