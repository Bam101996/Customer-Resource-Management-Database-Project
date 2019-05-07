<html>
<head>
<title>Shipments in Progress</title>
</head>

<body>
<h1>Shipments in Progress</hl>
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
    print<<<_HTML_
    <h2> 1. Items not listing an actual shipping date should be contacted within a day of the expected shipping date.</h2><br>
    <h2> 2. Items whose actual shipping dates are more than a day after the expected date should be contacted within a day of the expected shipping date.</h2><br>
    <h2> 3. Please contact all outstanding orders within 1 - 2 weeks following actual shipping date to see if product was received.</h2><br> 
_HTML_;
    
    try{
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Phone, cust.Email, ord.Quote_ID, ship.Shipment_ID, ship.Model, ship.Quantity, ship.Expected_ship_date, ship.Actual_ship_date
                FROM PROJECT_LEADS_AND_CUSTOMERS as cust, PROJECT_SALESPEOPLE as sales, PROJECT_ORDERS as ord, PROJECT_ORDER_SHIPMENTS as ship
                WHERE sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID AND ord.Cust_ID = cust.ID AND ord.Quote_ID = ship.Quote_ID AND ship.Has_been_delivered = 'No';") as $outstanding) {
                    
                print("Name: ".$outstanding['Name']."<br>Company: ".$outstanding['Company']."<br>Phone: ".$outstanding['Phone'].
                "<br>Email: ".$outstanding['Email']."<br>Shipment #: ".$outstanding['Shipment_ID']."<br>Quote #: ".$outstanding['Quote_ID']."<br>Model#: ".$outstanding['Model']."<br>Quantity: ".$outstanding['Quantity'].
                "<br>Expected shipping date: ".$outstanding['Expected_ship_date']."<br>Actual shipping date: " .$outstanding['Actual_ship_date']."<br><br>");
                }
                
             
    }
    
    catch(PDOException $e) {
        $e->getMessage();
    }
}

    
?>
<br><FORM action = "home.php">
<input type = "submit" value = "Return to home page.">
</FORM>
</body>
</html>