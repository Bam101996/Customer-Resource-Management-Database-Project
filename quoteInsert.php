<html>
<head>
<title>Quote Insertion</title>
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
    
$model1PriceStmt = $conn->prepare("SELECT Model as resultModel1 FROM PROJECT_PRODUCTS as product
                                  WHERE product.MSRP = 5000");
$model1PriceStmt->execute();
    
$model1PriceResult = $model1PriceStmt->fetch(PDO::FETCH_ASSOC);
    
$model2PriceStmt = $conn->prepare("SELECT Model as resultModel2 FROM PROJECT_PRODUCTS as product
                                  WHERE product.MSRP = 8000");
$model2PriceStmt->execute();
    
$model2PriceResult = $model2PriceStmt->fetch(PDO::FETCH_ASSOC);
    
$model3PriceStmt = $conn->prepare("SELECT Model as resultModel3 FROM PROJECT_PRODUCTS as product
                                  WHERE product.MSRP = 15000");
$model3PriceStmt->execute();
    
$model3PriceResult = $model3PriceStmt->fetch(PDO::FETCH_ASSOC);
    
$dateToAdd = date_create($_POST['dateIssued']);
date_add($dateToAdd, date_interval_create_from_date_string("3 months"));
$expDate = date_format($dateToAdd, "Y-m-d");

$model1UnitStmt = $conn->prepare("SELECT MSRP as priceModel1 FROM PROJECT_PRODUCTS as product
                                  WHERE product.Model = 1");
$model1UnitStmt->execute();
    
$model1UnitResult = $model1UnitStmt->fetch(PDO::FETCH_ASSOC);

$model2UnitStmt = $conn->prepare("SELECT MSRP as priceModel2 FROM PROJECT_PRODUCTS as product
                                  WHERE product.Model = 2");
$model2UnitStmt->execute();
    
$model2UnitResult = $model2UnitStmt->fetch(PDO::FETCH_ASSOC);

$model3UnitStmt = $conn->prepare("SELECT MSRP as priceModel3 FROM PROJECT_PRODUCTS as product
                                  WHERE product.Model = 3");
$model3UnitStmt->execute();
    
$model3UnitResult = $model3UnitStmt->fetch(PDO::FETCH_ASSOC);

if ((intval($_POST['model_1_price']) < (intval($model1UnitResult['priceModel1']) - (intval($model1UnitResult['priceModel1']) * 0.10)))
   AND ($_POST['model_1_choice'] == "Yes")){
    echo "Failed to create quote. The price entered for printer model ".$model1PriceResult['resultModel1']." is too low.";
}

else if (intval($_POST['model_2_price']) < (intval($model2UnitResult['priceModel2']) - (intval($model2UnitResult['priceModel2']) * 0.10))
           AND ($_POST['model_2_choice'] == "Yes"))  {
    echo "Failed to create quote. The price entered for printer model ".$model2PriceResult['resultModel2']." is too low.";   
}

else if (intval($_POST['model_3_price']) < (intval($model3UnitResult['priceModel3']) - (intval($model3UnitResult['priceModel3']) * 0.10))
         AND ($_POST['model_3_choice'] == "Yes"))    {
    echo "Failed to create quote. Price entered for printer model ".$model3PriceResult['resultModel3']." is too low.";
}

else {

    
    try {  
        $sql = "INSERT INTO PROJECT_QUOTES (Quote_ID, Cust_ID, Salesperson_ID, Gen_date, Exp_date, Outstanding)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST['Quote_ID'], $custResult['resultID'], $salesResult['salesResult'], 
                             $_POST['dateIssued'], $expDate, 'Yes'));
                             
        if ($_POST['model_1_choice'] == "Yes") {
            $item1 = "INSERT INTO PROJECT_QUOTE_ITEMS (Quote_ID, Model, Unit_price, Quantity) 
                     VALUES (?, ?, ?, ?)";
            $itemStmt1 = $conn->prepare($item1);
            $itemStmt1->execute(array($_POST['Quote_ID'], $model1PriceResult['resultModel1'],
                                      $_POST['model_1_price'], $_POST['model_1_quantity']));
        }
        
        if ($_POST['model_2_choice'] == "Yes") {
            $item2 = "INSERT INTO PROJECT_QUOTE_ITEMS (Quote_ID, Model, Unit_price, Quantity) 
                     VALUES (?, ?, ?, ?)";
            $itemStmt2 = $conn->prepare($item2);
            $itemStmt2->execute(array($_POST['Quote_ID'], $model2PriceResult['resultModel2'],
                                      $_POST['model_2_price'], $_POST['model_2_quantity']));
        }
                                      
        if ($_POST['model_3_choice'] == "Yes") {
            $item3 = "INSERT INTO PROJECT_QUOTE_ITEMS (Quote_ID, Model, Unit_price, Quantity) 
                      VALUES (?, ?, ?, ?)";
            $itemStmt3 = $conn->prepare($item3);
            $itemStmt3->execute(array($_POST['Quote_ID'], $model3PriceResult['resultModel3'],
                                      $_POST['model_3_price'], $_POST['model_3_quantity']));
        }
    
    echo "The quote was created sucessfully!";
}

    catch (PDOException $e) {
        echo "Failed to create quote. ".$e->getMessage();
    }
}

?>
<br><br>
<FORM action = "quotes.php">
<input type = "submit" value = "Return to quotes page.">
</FORM>
</body>
</html>