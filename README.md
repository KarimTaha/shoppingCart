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

    The Invoice Model is the part where the main logic of the application happens. An Invoice object is created which holds the subtotal, discounts, taxes, and total amounts. The Invoice Model then uses the Products Model to get information from the database about the products entered by the user.

    All prices are returned in USD from the database. For this reason, the next step is using the Currency Model to retrieve the input currency details from the database.

    The last missing information is the available offers, which are fetched using the Offers Model. The available offers are returned in an array to be used to calculate applicable discounts if any.

    Now that all the information needed are available, the Invoice Object is updated with the subtotal, taxes are calculated, discount in calculated using the Offers Model, and accordingly the invoice total is calculated.

    Finally, the invoice is printed in the following format:
    
    Subtotal
    Taxes
    Discounts (if any)
    Total

2. Products

    The Products Model is used to fetch the prices of the products entered by the user. The getProducts function accepts a database connection to access the database, as well as an array of Strings, where each String represents a Product. This array may contain duplicates, so after removing them, we prepare a comma separated String that is then sent to the database to fetch the required products' information. The function then creates an array of Product Objects, which contains the Products' names and prices, and returns it to the Invoice Model.

    A Product Object contains the following attributes:

    1. Product Name
    2. Price in USD

3. Currency

    The Currency Model is used to fetch the currency rate of the target currency entered by the user. The user enters a three character currency that he wants the invoice to use, and if the selected currency is available in the database, it's returned to the Invoice Model to convert all values to the required currency.

    A Currency Object is returned which contains the following attributes:

    1. Currency Name
    2. Conversion Rate (from USD)
    3. Currency Symbol (ex: $)
    4. Currency Code (ex: USD)
    
    If the user enters an invalid or not supported currency, the application uses USD by default.

4. Offers

    The Offers Model is used to fetch all available offers from the database. The offers are returned as an array of Offer Objects, which the Invoice Model then loops over each of them to calculate the applicable discount based on the selected products.

    The Offer object contains the following attributes:

    1. Offer Code
    2. Offer Description
    3. Offer Amount (after calculation)
    4. Offer Count (after calculation)

    The Offer class contains the function calculate discount, which uses a list of Products available in the invoice and their counts, and calculates the eligible discount of this offer when applied to these products.