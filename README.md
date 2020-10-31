# shoppingCart

## Overview

This application is used to generate an invoice for products that were purchased by the user. The user enters the currency he wants to use, along with a list of the products purchased. The application reads the user input, accesses the database to retrieve the products' prices and available offers, and prints the invoice in the requested currency.

## Solution Design

PHP was used as a backend language in this application. The solution follows Object Oriented design, with the following architecture:

#### Index Page

The page Index.php is the index page of the application. The user enters the command using the CMD terminal, which is them passed to the index page. The application processes the input, and extracts the information needed to be used in issuing the invoice.

#### Views

The InvoiceView is the only view in the application, found in the file Views/InvoiceView.php. This view is responsible for being the link between the Index page, and the backend models.

#### Models

Since the data is stored in a database, Model classes are needed to be the layer that accesses the data and provide it to the application views. The Model classes also contain the application Objects that are used to represent different entities in the solution. The Models used are as follows:

1. Invoice

The invoice is the part where the main logic of the application happens.

2. Products
3. Currency
4. Offers