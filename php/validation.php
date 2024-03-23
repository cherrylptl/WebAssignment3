<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Receipt</title>
      <style>
         body {
         font-family:'Poppins', sans-serif;
         }
         .container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         }
         .receipt {
         width: 50%;
         border: 2px solid black;
         padding: 20px;
         }
      </style>
   </head>
   <body>
      <div class="container">
         <div class="receipt">
            <?php
               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                   function validateInput($data)
                   {
                       $data = trim($data);
                       $data = stripslashes($data);
                       $data = htmlspecialchars($data);
                       return $data;
                   }
               
                   $errors = "";
                   $name = $email = $phone = $address = $city = $postcode = $creditcard = $expirydate = $year = $password = $confirmPassword =
                       "";
                   $province = $product1 = $product2 = $product3 = $product4 = 0;
               
               // Name
               if (isset($_POST["name"])) {
               $name = validateInput($_POST["name"]);
               if ($name === "") {
                   $errors .= "Name is required.<br>";
               }
               } else {
               $errors .= "Name is required.<br>";
               }
               
               // Email
               if (isset($_POST["email"])) {
               $email = validateInput($_POST["email"]);
               if ($email === "") {
                   $errors .= "Email is required.<br>";
               } else {
                   $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
                   if (!preg_match($emailRegex, $email)) {
                       $errors .= "Email is not valid.<br>";
                   }
               }
               } else {
               $errors .= "Email is required.<br>";
               }
               
               //phone number
               if (isset($_POST["phone"])) {
               $phone = validateInput($_POST["phone"]);
               if ($phone === "") {
                   $errors .= "Phone is required.<br>";
               } else {
                   $phoneRegex = '/^\d{3}-\d{3}-\d{4}$/';
                   if (!preg_match($phoneRegex, $phone)) {
                       $errors .=
                           "Phone is not valid. Please enter in the format xxx-xxx-xxxx.<br>";
                   }
               }
               } else {
               $errors .= "phone is required.<br>";
               }
               
               //address
               if (isset($_POST["address"])) {
               $address = validateInput($_POST["address"]);
               if ($address === "") {
                   $errors .= "Address is required.<br>";
               }
               } else {
               $errors .= "address is required.<br>";
               }
               
               //city
               if (isset($_POST["city"])) {
               $city = validateInput($_POST["city"]);
               if ($city === "") {
                   $errors .= "City is required.<br>";
               }
               } else {
               $errors .= "city is required.<br>";
               }
               
               //postcode
               if (isset($_POST["postcode"])) {
               $postcode = validateInput($_POST["postcode"]);
               $postcodeRegex = '/^[A-Z]\d[A-Z] \d[A-Z]\d$/';
               if ($postcode === "") {
                   $errors .= "Postcode is required.<br>";
               } elseif (!preg_match($postcodeRegex, $postcode)) {
                   $errors .=
                       "Postcode is not in correct format. Please enter in the format X0X 0X0.<br>";
               }
               } else {
               $errors .= "postcode is required.<br>";
               }
               
               //province
               if (isset($_POST["province"])) {
               $province = validateInput($_POST["province"]);
               if ($province === "") {
                   $errors .= "Please select a province.<br>";
               }
               } else {
               $errors .= "Please select a province.<br>";
               }
               
               //credit card
               if (isset($_POST["creditcard"])) {
               $creditcard = validateInput($_POST["creditcard"]);
               if ($creditcard === "") {
                   $errors .= "Credit Card number is required.<br>";
               } else {
                   $creditcardRegex = '/^\d{4}-\d{4}-\d{4}-\d{4}$/';
                   if (!preg_match($creditcardRegex, $creditcard)) {
                       $errors .=
                           "Credit Card is not valid. Please enter in the format xxxx-xxxx-xxxx-xxxx.<br>";
                   }
               }
               } else {
               $errors .= "Credit Card number is required.<br>";
               }
               
               //credit card expiry month
               if (isset($_POST["expirydate"])) {
               $expirydate = validateInput($_POST["expirydate"]);
               $monthRegex = '/^[A-Z]{3}$/';
               if ($expirydate === "") {
                   $errors .= "Credit Card Expiry Month is required.<br>";
               } elseif (!preg_match($monthRegex, $expirydate)) {
                   $errors .=
                       "Expiry Month is not valid. Please enter in the format MMM (ex-NOV).<br>";
               }
               } else {
               $errors .= "Credit Card Expiry Month is required.<br>";
               }
               
               //credit card expiry year
               if (isset($_POST["year"])) {
               $year = validateInput($_POST["year"]);
               $yearRegex = '/^\d{4}$/';
               if ($year === "") {
                   $errors .= "Credit Card Expiry Year is required.<br>";
               } elseif (!preg_match($yearRegex, $year) || $year < 2023) {
                   $errors .=
                       "Credit Card Expiry Year is not valid. Please enter in the format YYYY (ex-2023).<br>";
               }
               } else {
               $errors .= "Credit Card Expiry Year is required.<br>";
               }
               
               //password
               if (isset($_POST["password"])) {
               $password = validateInput($_POST["password"]);
               if ($password === "") {
                   $errors .= "Password is required.<br>";
               }
               } else {
               $errors .= "Password is required.<br>";
               }
               
               //confirm password
               if (isset($_POST["confirmPassword"])) {
               $confirmPassword = validateInput($_POST["confirmPassword"]);
               if ($confirmPassword === "") {
                   $errors .= "Confirm Password is required.<br>";
               } elseif ($password !== $confirmPassword) {
                   $errors .= "Passwords do not match.<br>";
               }
               } else {
               $errors .= "Confirm Password is required.<br>";
               }
               
               //cal tax
               $taxRate = getTaxRate($province);
               if ($taxRate === null) {
               $errors .= "Tax rate for the selected province is not available.<br>";
               }
               
               // Minimum purchase
               $product1 = isset($_POST["product1"]) ? (float) $_POST["product1"] : 0;
               $product2 = isset($_POST["product2"]) ? (float) $_POST["product2"] : 0;
               $product3 = isset($_POST["product3"]) ? (float) $_POST["product3"] : 0;
               $product4 = isset($_POST["product4"]) ? (float) $_POST["product4"] : 0;
               
               if ($product1 * 100 + $product2 * 5 + $product3 * 4 + $product4 * 5 < 10) {
               $errors .= 'Minimum Purchase should be $10 or more.<br>';
               }
               
                   //generate receipt
                   if ($errors === "") {
             
                       // Receipt content
                       $totalCost = $product1 * 100 + $product2 * 5 + $product3 * 4 + $product4 * 4;
                       $tax = $totalCost * $taxRate;
                       $totalAmount = $totalCost + $tax;
                       $receiptContent = "<h1>Receipt</h1>";
                       $receiptContent .= "<p>Name : " . $name . "</p>";
                       $receiptContent .= "<p>Email : " . $email . "</p>";
                       $receiptContent .= "<p>Phone : " . $phone . "</p>";
                       $receiptContent .= "<p>Address : " . $address . "</p>";
                       $receiptContent .= "<p>City : " . $city . "</p>";
                       $receiptContent .= "<p>Postcode : " . $postcode . "</p>";
                       $receiptContent .= "<p>Province : " . $province . "</p>";
                       $receiptContent .= "<p>Credit Card : " . $creditcard . "</p>";
                       $receiptContent .= "<p>Expiry Date : " . $expirydate ."-".$year . "</p>";
                       $receiptContent .= "<h3>List Of Products</h3>";
                       if ((int) $product1 > 0) {
                           $receiptContent .= "<li>Laptop : Quantity - " . $product1 . "</li>";
                       }
                       if ((int) $product2 > 0) {
                           $receiptContent .= "<li>Keyboard : Quantity - " . $product2 . "</li>";
                       }
                       if ((int) $product3 > 0) {
                           $receiptContent .= "<li>Mouse : Quantity - " . $product3 . "</li>";
                       }
                       if ((int) $product4 > 0) {
                           $receiptContent .= "<li>HeadPhone : Quantity - " . $product4 . "</li>";
                       }
                       $receiptContent .= '<p>Total Cost: $' . number_format($totalCost, 2) . "</p>";
                       $receiptContent .= '<p>Tax: $' . number_format($tax, 2) . "</p>";
                       $receiptContent .=
                           '<p>Total Amount: $' . number_format($totalAmount, 2) . "</p>";
               
                   
                       $receipt = $receiptContent;
                       echo $receipt;
               
                       // Clear all fields
                       $name = $email = $phone = $address = $city = $postcode = $province = $creditcard = $expirydate = $year = $password = $confirmPassword = $product1 = $product2 = $product3 = $product4 =
                           "";
                   } else {
                       // Display errors
                       echo $errors;
                   }
               }
               //get tax rate based on province
               function getTaxRate($province)
               {
               switch ($province) {
               case "AB":
                   return 0.05;
               case "BC":
                   return 0.12;
               case "MB":
                   return 0.12;
               case "NB":
                   return 0.15;
               case "BFL":
                   return 0.15;
               case "NT":
                   return 0.05;
               case "NS":
                   return 0.15;
               case "NU":
                   return 0.05;
               case "ON":
                   return 0.13;
               case "PEI":
                   return 0.15;
               case "QU":
                   return 0.14;
               case "SA":
                   return 0.11;
               case "YU":
                   return 0.05;
               default:
                   return 0.0;
               }
               }
               
               ?>
         </div>
      </div>
   </body>
</html>