<?php
    /**
     * This file will be accessed by user from CMD to enter the shopping cart input.
     * The command will be as follows: php Index.php currency [products]
     * Ex: php Index.php EGP Pants T-shirt
     * 
     * The Invoice view is used to create and print the invoice to the user based on the input
     */

    include 'Views/InvoiceView.php';

    // Call the initInvoice function to fetch the user input and pass it to the Invoice view
    initInvoice();
    
    /**
     * This function takes no input as declared parameters
     * It accesses the global variable argv to fetch the user's input currency and items
     * argv[0] contains the file name - Index.php
     * argv[1] contains the currency input
     * argv[2] till argv[n] contains the items entered with a space as separator
     * A new invoice object is created, and the function printInvoice is called to access the DB
     * and fetch needed info, then print the invoice
     */
    function initInvoice() {
        // Declare global variable that holds the user input from CMD
        global $argv;
        // Fetch the currency that the user input
        $inputCurrency = strtoupper($argv[1]);
        // Fetch the array of products that the user input
        $items = array_slice($argv,2);
        // Handle if the user didn't enter any items in the command
        if (count($items) < 1) {
            print_r("You did not enter any items, or the command is not in the correct format.");
            return;
        }
        // Create an invoiceView object that we will use to prepare the invoice and print it
        $invoiceView = new InvoiceView();
        $invoiceView->prepareInvoice($items, $inputCurrency);
    }
?>
