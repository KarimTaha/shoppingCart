<?php


    function getCurrencyRate($conn, $inputCurrency) {

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

        return $resultCurrency;

        $stmt->close();
        $conn->close();
    }
    

    class CurrencyRate {
        private $name;
        private $rate;
        private $symbol;
        private $code;

        function __construct($name, $rate, $symbol, $code) {
            $this->name = $name;
            $this->rate = $rate;
            $this->symbol = $symbol;
            $this->code = $code;
        }
        
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