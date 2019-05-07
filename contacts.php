<html>
<head>
<title>Update Contact Information</title>
</head>

<body>
<h1>Update Contact Information</h1><br>
<h2>Use the fields below to update customer contact information:</h2><br>
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
    <FORM method = "post" action = "contactInsert.php">
    Customer name: <input type = "text" name = "customer"><br><br>
    
    Contact date: <input type = "date" name = "dateContact"><br><br>

    Was the contact successful? <select name = "success">
                                <option value = "Yes">Yes</option>
                                <option value = "No">No</option>
                                </select><br><br>

    How was the contact made? <select name = "method">
                              <option value = "Phone">Phone</option>
                              <option value = "In person">In person</option>
                              <option value = "Email">Email</option>
                              </select><br><br>
                          
    Did the contact request more information or a quote? <select name = "more_info">
                                                         <option value = "Yes">Yes</option>
                                                         <option value = "No">No</option>
                                                         </select><br><br>
    
    Does the contact still have interest in doing business with us? <select name = "interest">
                                                                  <option value = "Yes">Yes</option>
                                                                  <option value = "No">No</option>
                                                                  </select><br><br>
                                                                  
    Is the contact still waiting for all or part of an order? <select name = "order_wait">
                                                           <option value = "Yes">Yes</option>
                                                           <option value = "No">No</option>
                                                           </select><br><br>
                                                     
    Notes (limit 500 characters): <textarea name = "notes" rows = "10" cols = "30"></textarea><br><br>
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