<html>
<head>
<title>Outstanding Quotes</title>
</head>

<body>
<h1>Quotes to be resolved (contact within a week of receiving quote):</h1>
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
    try{
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Phone, cust.Email, quote.Quote_ID, quote.Gen_date FROM PROJECT_LEADS_AND_CUSTOMERS as cust,
                PROJECT_SALESPEOPLE as sales, PROJECT_QUOTES as quote
                WHERE sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID AND quote.Cust_ID = cust.ID AND quote.Outstanding = 'Yes';") as $quoted)
            {
                print("Name: ".$quoted['Name']."<br>Company: ".$quoted['Company']."<br>Phone: ".$quoted['Phone'].
                "<br>Email: ".$quoted['Email']."<br>Quote #".$quoted['Quote_ID']." generated on: ".$quoted['Gen_date']."<br><br>");
            }        
    }
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
<br><FORM action = "home.php">
<input type = "submit" value = "Return to home page.">
</FORM>
</body>
</html>
