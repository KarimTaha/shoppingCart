<?php
    include 'Views/Invoice.php';

    printInput();
    
    function printInput() {
        global $argv;

        $currency = strtoupper($argv[1]);
        $items = array_slice($argv,2);
        
        $invoice = new Invoice();
        $invoice->printInvoice($items);

        // print_r("------------Invoice Input------------");
        // print_r('Currency = ' . $currency);
        // print_r("\n");
        // print_r('Items:');
        // print_r("\n");
        // print_r($items);
        // print_r("------------Invoice Input End------------\n");
    }

?>
