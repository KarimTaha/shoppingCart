<?php
    include 'Models/Products.php';

    getInvoice();
    $products = new Products();
    $products->getProducts();

    function getInvoice() {
        global $argv;

        $currency = strtoupper($argv[1]);
        $items = array_slice($argv,2);

        print_r("------------Invoice Input------------");
        print_r('Currency = ' . $currency);
        print_r("\n");
        print_r('Items:');
        print_r("\n");
        print_r($items);
        print_r("------------Invoice Input End------------\n");
    }

?>
