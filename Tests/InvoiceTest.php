<?php
    use PHPUnit\Framework\TestCase;

    use function PHPUnit\Framework\assertEquals;

    class InvoiceTest extends TestCase {

        private $conn = null;

        public function getDBConn(){
            require_once './Utils/Connector.php';
            if($this->conn == null) {
                // Init mySQL database connection
                $mySQLConn = new MySQLConnection();
                $this->conn = $mySQLConn->connect(); 
            }
            return $this->conn;
        }

        public function testGetInvoice() {
            require './Models/Invoice.php';

            /**
             * Pants = 14.99
             * T-shirt = 10.99
             * 
             * Subtotal = 25.98
             * Taxes = 3.6372
             * 
             * Total = 29.6172
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt"),"USD");

            $this->assertEquals(25.98,$invoiceResult->get_subtotal());
            $this->assertEquals(29.6172,$invoiceResult->get_total());

            /**
             * Pants = 14.99
             * T-shirt = 10.99
             * Jacket = 19.99
             * Shoes = 24.99
             * 
             * Subtotal = 70.96
             * Taxes = 9.9344
             * 
             * Offers: 10% shoes
             * Discount = 2.499
             * 
             * Total = 78.3954
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "Jacket", "Shoes"),"USD");

            $this->assertEquals(70.96,$invoiceResult->get_subtotal());
            $this->assertEquals(78.3954,$invoiceResult->get_total());

            /**
             * Pants = 14.99
             * T-shirt = 10.99
             * Jacket = 19.99
             * Shoes = 24.99 * 2 = 49.98
             * 
             * Subtotal = 95.95
             * Taxes = 13.433
             * 
             * Offers: 10% shoes
             * Discount = 4.998
             * 
             * Total = 104.385
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "Jacket", "Shoes", "Shoes"),"USD");

            $this->assertEquals(95.95,$invoiceResult->get_subtotal());
            $this->assertEquals(104.385,$invoiceResult->get_total());

            /**
             * Pants = 14.99
             * T-shirt = 10.99 * 2 = 21.98
             * Jacket = 19.99
             * Shoes = 24.99
             * 
             * Subtotal = 81.95
             * Taxes = 11.473
             * 
             * Offers: 10% shoes, 1x Jacket
             * Discount = 2.499, 9.995
             * 
             * Total = 80.929
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "T-shirt", "Jacket", "Shoes"),"USD");

            $this->assertEquals(81.95,$invoiceResult->get_subtotal());
            $this->assertEquals(80.929,$invoiceResult->get_total());

            /**
             * Pants = 14.99
             * T-shirt = 10.99 * 4 = 43.96
             * Jacket = 19.99
             * Shoes = 24.99
             * 
             * Subtotal = 103.93
             * Taxes = 14.5502
             * 
             * Offers: 10% shoes, 1x Jacket
             * Discount = 2.499, 9.995
             * 
             * Total = 105.9862
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "T-shirt", "T-shirt", "T-shirt", "Jacket", "Shoes"),"USD");

            $this->assertEquals(103.93,$invoiceResult->get_subtotal());
            $this->assertEquals(105.9862,$invoiceResult->get_total());

            /**
             * Pants = 14.99
             * T-shirt = 10.99 * 4 = 43.96
             * Jacket = 19.99 * 2 = 39.98
             * Shoes = 24.99
             * 
             * Subtotal = 123.92
             * Taxes = 17.3488
             * 
             * Offers: 10% shoes, 2x Jacket
             * Discount = 2.499, 19.99
             * 
             * Total = 118.7798
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "T-shirt", "T-shirt", "T-shirt", "Jacket", "Jacket", "Shoes"),"USD");

            $this->assertEquals(123.92,$invoiceResult->get_subtotal());
            $this->assertEquals(118.7798,$invoiceResult->get_total());

            /**
             * Pants = 14.99 -> 235.4929 EGP
             * T-shirt = 10.99 * 4 = 43.96 -> 690.6116 EGP
             * Jacket = 19.99 * 2 = 39.98 -> 628.0858 EGP
             * Shoes = 24.99 -> 392.5929 EGP
             * 
             * Subtotal = 1,946.7832
             * Taxes = 272.549648
             * 
             * Offers: 10% shoes, 2x Jacket
             * Discount = 39.25929, 314.0429
             * 
             * Total = 1,866.030658 EGP
             */
            $invoice = new Invoice();
            $invoiceResult = $invoice->printInvoice(array("Pants", "T-shirt", "T-shirt", "T-shirt", "T-shirt", "Jacket", "Jacket", "Shoes"),"EGP");

            $this->assertEquals(1946.7832,$invoiceResult->get_subtotal());
            $this->assertEquals(1866.030658,$invoiceResult->get_total());
        }
    }

?>