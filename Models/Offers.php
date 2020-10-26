<?php

    function getOffers($conn) {
        $sql = "select code, description from Offer";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $code = "";
        $description = "";
        $stmt->bind_result($code, $description);

        $offersResult = array();
        $currentOffer = null;

        while($stmt->fetch()) {
            $currentOffer = new Offer($code, $description);
            array_push($offersResult, $currentOffer);
        }

        $stmt->close();
        return $offersResult;
    }
    
    // Class Offer that represents an offer that will be applied to the invoice
    class Offer {
        // Class attributes
        private $code;
        private $description;

        // Constructor that sets code and description for the offer
        function __construct($name, $description) {
            $this->name = $name;
            $this->description = $description;
        }
        
        // Getter functions
        function get_name() {
            return $this->name;
        }
        function get_description() {
            return $this->description;
        }
    }

?>