<?php
    /**
     * This is a view file that represents an invoice
     * The invoice is based on the products entered by the user
     * The InvoiceView class extends Invoice model to access the data in the DB
     */
    include './Models/Invoice.php';

    class InvoiceView extends Invoice{
        public function prepareInvoice($items, $inputCurrency) {
            $this->printInvoice($items, $inputCurrency);
        }
    }

?>