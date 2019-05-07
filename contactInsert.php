<html>
<head>
<title>Contact Insertion</title>
</head>

<body>
<?php

session_start();
$servername = "cs314.iwu.edu";

try{
    $conn = new PDO("mysql:host=$servername;dbname=DB_BLAKE",
    $_SESSION['username'], $_SESSION['pass']);
    $conn -> setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $e) {
    echo "Database connection failure!" . $e -> getMessage();
}

$custStmt = $conn->prepare("SELECT cust.ID as resultID FROM PROJECT_LEADS_AND_CUSTOMERS as cust
                        WHERE cust.Name = ?");
$custStmt->execute(array($_POST['customer']));
$custResult = $custStmt->fetch(PDO::FETCH_ASSOC);

$salesStmt = $conn->prepare("SELECT sales.ID as salesResult FROM PROJECT_SALESPEOPLE as sales
                        WHERE sales.userID = mid(user(), 1, instr(user(), '@') - 1)");
$salesStmt->execute();
$salesResult = $salesStmt->fetch(PDO::FETCH_ASSOC);

try {  
    $sql = "INSERT INTO PROJECT_CONTACTS (Cust_ID, Contact_date, Salesperson_ID, Method, Success, Wants_info_quote, Still_interested, Waiting_for_order, Notes)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($custResult['resultID'], $_POST['dateContact'], $salesResult['salesResult'], $_POST['method'], 
              $_POST['success'], $_POST['more_info'], $_POST['interest'], $_POST['order_wait'], $_POST['notes']));
    echo "The contact was added sucessfully!";
}

catch (PDOException $e) {
    echo "Failed to add contact. ".$e->getMessage();
}
    
?>
<br><br><FORM action = "contacts.php">
<input type = "submit" value = "Return to contacts page.">
</FORM>
</body>
</html>


