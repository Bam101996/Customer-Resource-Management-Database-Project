<html>
<head>
<title>Create a new quote</title>
</head>

<body>
<h1>Create a new quote</h1><br>
<h2>Use the fields below to create a new quote:</h2><br>
<?php
session_start();
$servername = "cs314.iwu.edu";

try{
    $conn = new PDO("mysql:host=$servername;dbname=DB_BLAKE",
    $_SESSION['username'], $_SESSION['pass']);
    $conn -> setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    $success = 1;
}

catch (PDOException $e) {
    echo "Database connection failure!" . $e -> getMessage();
    $success = 0;
}

if ($success == 0) {
    print <<<_HTML_
    <br><br>
    <FORM action = "crm.php">
    <input type = "submit" value = "Try again">
    </FORM>
_HTML_;
}

else {
    print <<<_HTML_
    <FORM method = "post" action = "quoteInsert.php">
    
    Quote number: <input type = "number" name = "Quote_ID"><br><br>
   
    Customer name: <input type = "text" name = "customer"><br><br>
    
    Date issued: <input type = "date" name = "dateIssued"><br><br>
                                            
    Is the customer buying model 1 (Big 3D printer)? <select name = "model_1_choice">
                                                     <option value = "Yes">Yes</option>
                                                     <option value = "No">No</option>
                                                     </select><br><br>
    
    Unit price for model 1 (up to 10% off MSRP, ignore if not buying): <input type = "number" name = "model_1_price"><br><br>
    
    Model 1 quantity (ignore if not buying): <input type = "number" name = "model_1_quantity"><br><br>
      
    Is the customer buying model 2 (Bigger 3D printer)? <select name = "model_2_choice">
                                                        <option value = "Yes">Yes</option>
                                                        <option value = "No">No</option>
                                                        </select><br><br>
      
    Unit price for model 2 (Bigger 3D printer) (up to 10% off MSRP, ignore if not buying): <input type = "number" name = "model_2_price"><br><br>
    
    Model 2 quantity (ignore if not buying): <input type = "number" name = "model_2_quantity"><br><br>
    
    Is the customer buying model 3 (Huge 3D printer)? <select name = "model_3_choice">
                                                      <option value = "Yes">Yes</option>
                                                      <option value = "No">No</option>
                                                      </select><br><br>
    
    Unit price for model 3 (Huge 3D printer) (up to 10 % off MSRP, ignore if not buying): <input type = "number" name = "model_3_price"><br><br>
    
    Model 3 quantity (ignore if not buying): <input type = "number" name = "model_3_quantity"><br><br>
    <input type = "submit">
    </FORM>
_HTML_;
}
?>
<br><FORM action = "home.php">
<input type = "submit" value = "Return to home page.">
</FORM>
</body>
</html>
