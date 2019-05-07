<html>
<head>
<title>Print Anything Division Customer Management</title>
</head>

<body>
<h1>Print Anything Division Customer Management</h1>
<?php
session_start();
$servername = "cs314.iwu.edu";

if ($_SESSION['username']){
    try{
        $conn = new PDO("mysql:host=$servername;dbname=DB_BLAKE",
        $_SESSION['username'], $_SESSION['pass']);
        $conn->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        print <<<_HTML_
        <br>
_HTML_;
    echo "Welcome, " .$_SESSION['username']."!";
    $success = 1;
    }

    catch (PDOException $e) {
        echo "Database connection failure!";
        echo " ". $e -> getMessage();
        $success = 0;
    }
}    

else {
    $_SESSION['username'] = $_POST['username_login'];
    $_SESSION['pass'] = $_POST['pass_login'];
    
    try{
        $conn = new PDO("mysql:host=$servername;dbname=DB_BLAKE",
        $_SESSION['username'], $_SESSION['pass']);
        $conn->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        print <<<_HTML_
        <br>
_HTML_;
    echo "Welcome, " .$_SESSION['username']."!";
    $success = 1;
    }

    catch (PDOException $e) {
        echo "Database connection failure!";
        echo " ". $e -> getMessage();
        $success = 0;
    }
}

if ($success  == 0) {
    print <<<_HTML_
    <br><br>
    <FORM action = "crm.php">
    <input type = "submit" value = "Try again">
    </FORM>
_HTML_;
    session_destroy();
}

else {
    print <<<_HTML_
    <br><br>
    <h3>New leads/customers (contact within 7 days of given date):</h3><br>
_HTML_;

    try{
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Category, cust.Phone, cust.Email, stat.Date_set FROM PROJECT_LEADS_AND_CUSTOMERS as cust,
                PROJECT_LEAD_CUSTOMER_STATUSES as stat, PROJECT_SALESPEOPLE as sales
                WHERE  stat.New = 1 AND sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID AND stat.Cust_ID = cust.ID;") as $new)
            {
                print("Name: ".$new['Name']."<br>Company: ".$new['Company']."<br>Category: ".$new['Category']."<br>Phone: ".$new['Phone'].
                "<br>Email: ".$new['Email']."<br>Date of most recent status change: ".$new['Date_set']."<br><br>");
            }        
    }
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    print <<<_HTML_
    <h3>Waiting for info leads/customers (contact within 2 days of given date):</h3><br>
_HTML_;
    try {
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Category, cust.Phone, cust.Email, stat.Date_set FROM PROJECT_LEADS_AND_CUSTOMERS as cust,
                PROJECT_LEAD_CUSTOMER_STATUSES as stat, PROJECT_SALESPEOPLE as sales
                WHERE  stat.Waiting_for_info = 1 AND sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID AND stat.Cust_ID = cust.ID;") as $waiting_info)
            {
                print("Name: ".$waiting_info['Name']."<br>Company: ".$waiting_info['Company']."<br>Category: ".$waiting_info['Category']."<br>Phone: ".$waiting_info['Phone'].
                "<br>Email: ".$waiting_info['Email']."<br>Date of most recent status change: ".$waiting_info['Date_set']."<br><br>");
            }
            
    }
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    print <<<_HTML_
    <h3>Decision makers and specifiers (contact every 6 months):</h3>
    <h4>Decision makers:</h4>
_HTML_;

    try{
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Phone, cust.Email FROM PROJECT_LEADS_AND_CUSTOMERS as cust,
                PROJECT_SALESPEOPLE as sales WHERE cust.Category = 'Decision maker' AND sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID;") as $decide)
            {
                print("Name: ".$decide['Name']."<br>Company: ".$decide['Company']."<br>Phone: ".$decide['Phone'].
                "<br>Email: ".$decide['Email']."<br><br>");
            }        
    }
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    print <<<_HTML_
    <h4>Specifiers:</h4>
_HTML_;

    try{
        foreach($conn->query("SELECT cust.Name, cust.Company, cust.Phone, cust.Email FROM PROJECT_LEADS_AND_CUSTOMERS as cust,
                PROJECT_SALESPEOPLE as sales WHERE cust.Category = 'Specifier' AND sales.userID = mid(user(), 1, instr(user(), '@') - 1)
                AND cust.Salesperson_ID = sales.ID;") as $specify)
            {
                print("Name: ".$specify['Name']."<br>Company: ".$specify['Company']."<br>Phone: ".$specify['Phone'].
                "<br>Email: ".$specify['Email']."<br><br>");
            }        
    }
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }


    
    print <<<_HTML_
    <a href = "http://sun.iwu.edu/~bmaxwell/contacts.php">Click here to update contact information.</a><br><br>
    <a href = "http://sun.iwu.edu/~bmaxwell/quotes.php">Click here to create a new quote.</a><br><br>
    <a href = "http://sun.iwu.edu/~bmaxwell/seeQuotes.php">Click here to see all outstanding quotes.</a><br><br>
    <a href = "http://sun.iwu.edu/~bmaxwell/seeOrders.php">Click here to see all shipments in progress.</a><br><br>
    <FORM action = "crm.php">
    <input type = "submit" value = "Log out">
    </FORM>
_HTML_;
}
?>
</body>
</html>