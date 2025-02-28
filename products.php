<!DOCTYPE html>
<html>
<body>

<h1>List of products</h1>

<?php
    require_once("./lib.php");
    $productsList = new Products("./products.xml");
    session_start();
    // Display the appropriate message
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $message['type'] === 'success' ? 'green' : 'red';
        echo "<p style='color: {$type};'>{$message['text']}</p>";
        // Delete the message after it has been displayed
        unset($_SESSION['flash_message']);
    }

    // Check if the form has been submitted
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $product_data=$productsList->get_post_data();
        // If the 'name' field is empty, display an appropriate message
        if (empty($product_data['name'])) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'You must fill the name field!'
            ];
            // Redirect to avoid double submission (PRG strategy)
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            
            $productsList->insert_product_xml($product_data);
            
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'New product added successfully!'
            ];
            // Redirect to avoid double submission (PRG strategy)
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    $productsList->print_html_table_with_all_products();

include 'viewproducts.php';    
?>
  
</body>
</html>