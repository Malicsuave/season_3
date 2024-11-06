     <link rel="stylesheet" href="style.css">
<?php

    include("connections.php");

    ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" value="">
        <br>
        <input type="submit" name="btnUpload" value="Upload Students">
    </form>

    <?php
    if (isset($_POST["btnAdd"])) {
        $names = $_POST["name"];
        $student_nos = $_POST["student_no"];
        $tuition_fees = $_POST["tuition_fee"];
        $misc_fees = $_POST["misc_fee"];
        
        foreach ($student_nos as $index => $student_no) {
            $name = $names[$index];
            $tuition_fee = $tuition_fees[$index];
            $misc_fee = $misc_fees[$index];
            
            // Insert into the database
            mysqli_query($connections, "INSERT INTO student (student_no, name, tuition_fee, misc_fee) VALUES ('$student_no', '$name', '$tuition_fee', '$misc_fee')");
        }
        echo "<script>window.location.href='index.php?notify=<font color=green>Students have been uploaded!</font>';</script>";
    }

    if (isset($_POST["btnUpload"])) {
        echo "<hr>";
        echo "<table border='1' width='60%'>";
        echo "<tr>
                <td><b>Student No.</b></td>
                <td><b>Name</b></td>
                <td><b>Tuition Fee</b></td>
                <td><b>Misc Fee</b></td>
                <td><b>Errors</b></td>
            </tr>
            <tr><td colspan='5'><hr></td></tr>";
            
        echo "<form method='POST'>";

        $btnStatus = "ENABLED";
        $filename = $_FILES["file"]["tmp_name"];

        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $row = 1;
        
            while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
                $student_no = $name = $tuition_fee = $misc_fee = "";
                $errorMsg = "";
        
                if ($row == 1) { // Skip the header row
                    $row++;
                    continue;
                }
        
                // Retrieve and sanitize each column
                $student_no = !empty($data[0]) ? $data[0] : '';
                $name = !empty($data[1]) ? $data[1] : '';
                $tuition_fee = !empty($data[2]) ? str_replace(",", "", $data[2]) : ''; // Remove commas
                $misc_fee = !empty($data[3]) ? str_replace(",", "", $data[3]) : ''; // Remove commas
        
                // Validate data
                if (empty($student_no)) $errorMsg .= "Student No. is empty! ";
                if (empty($name)) $errorMsg .= "Name is empty! ";
                if (empty($tuition_fee)) $errorMsg .= "Tuition Fee is empty! ";
                if (empty($misc_fee)) $errorMsg .= "Misc Fee is empty! ";
        
                // Store data for each valid row
                echo "<input type='hidden' name='student_no[]' value='$student_no'>";
                echo "<input type='hidden' name='name[]' value='$name'>";
                echo "<input type='hidden' name='tuition_fee[]' value='$tuition_fee'>";
                echo "<input type='hidden' name='misc_fee[]' value='$misc_fee'>";
                
                // Display row in table
                echo "<tr>
                        <td>$student_no</td>
                        <td>$name</td>
                        <td>$tuition_fee</td>
                        <td>$misc_fee</td>
                        <td>$errorMsg</td>
                    </tr>";
                $row++;
            }
        
            fclose($file);
        }
        
        echo "<tr>
                <td colspan='5'>
                    <div align='right'>
                        <input type='submit' $btnStatus name='btnAdd' value='Add Students'>
                    </div>
                </td>
            </tr>";
        echo "</form>";
        echo "</table>";
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Season 3</title>
    </head>
    <body>
        <a href="export.php"><button type="button" name="button">Export to Excel</button></a>
        <hr>
        <?php require 'data.php'; ?>7624 



    </body>
    </html>


     <hr>
    <h1>Auto Compute</h1>
    <script type="text/javascript">
    // Total variable to track the sum
    var total = 0;

    // Function to update total based on selected items
    function toggleCheckbox(price, isChecked) {
        if (isChecked) {
            total += parseFloat(price);
        } else {
            total -= parseFloat(price);
        }
        document.getElementById('Totalcost').innerText = "P " + total.toFixed(2);
    }

    // Toggle both checkboxes in sync
    function toggleCheckboxSync(productId, price, isChecked) {
        // Get both checkboxes
        var mainCheckbox = document.querySelector(`input[name="main_${productId}"]`);
        var secondaryCheckbox = document.querySelector(`input[name="id_product[]"][value="${productId}"]`);

        // Set both checkboxes to the same checked state
        if (mainCheckbox) mainCheckbox.checked = isChecked;
        if (secondaryCheckbox) secondaryCheckbox.checked = isChecked;

        // Update the total cost
        toggleCheckbox(price, isChecked);
    }

    // Initialize event listeners for each checkbox when the page loads
    window.onload = function() {
        <?php
        $query_product = mysqli_query($connections, "SELECT * FROM product");
        while ($row_product = mysqli_fetch_array($query_product)) {
            $id_product = $row_product["id_product"];
            $product_price = $row_product["price"];
        ?>
            // Top table checkbox
            document.querySelector(`input[name="main_<?php echo $id_product; ?>"]`).onchange = function() {
                toggleCheckboxSync('<?php echo $id_product; ?>', '<?php echo $product_price; ?>', this.checked);
            };
            // Bottom table checkbox
            document.querySelector(`input[name="id_product[]"][value="<?php echo $id_product; ?>"]`).onchange = function() {
                toggleCheckboxSync('<?php echo $id_product; ?>', '<?php echo $product_price; ?>', this.checked);
            };
        <?php
        }
        ?>
    };
</script> 


<table border="0" width="40%">
    <tr>
        <td width="70%"><b>Product Name</b></td>
        <td><b>Price</b></td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    
    <?php
    // Display products in the top table with checkboxes
    $query_product = mysqli_query($connections, "SELECT * FROM product");
    while ($row_product = mysqli_fetch_array($query_product)) {
        $id_product = $row_product["id_product"];
        $product_name = $row_product["product"];
        $product_price = $row_product["price"];
    ?>
        <tr>
            <td>
                <span>
                    <input type="checkbox" name="main_<?php echo $id_product; ?>" value="<?php echo $product_price; ?>">
                    <?php echo ucfirst($product_name); ?>
                </span>
            </td>
            <td>P <?php echo number_format($product_price) . ".00"; ?></td>
        </tr>
    <?php
    }
    ?>

    <form method="POST">
        <?php
        // Display products in the bottom section with checkboxes
        $query_product = mysqli_query($connections, "SELECT * FROM product");
        while ($row_product = mysqli_fetch_array($query_product)) {
            $id_product = $row_product["id_product"];
            $product_name = $row_product["product"];
            $product_price = $row_product["price"];
        ?>
            <span hidden id="get_<?php echo $id_product; ?>">
                <input type="checkbox" name="id_product[]" value="<?php echo $id_product; ?>">
                <label for="check1"><?php echo ucfirst($product_name) . " -  P" . number_format($product_price) . ".00"; ?></label>
                <br>
            </span>
        <?php
        }
        ?>
        
        <tr><td><h1>Total</h1></td><td><span id="Totalcost">P 0.00</span></td></tr>
        <tr><td colspan="2"><input type="submit" name="btnAvail" value="Avail"></td></tr>
    </form>
</table>

<hr>
<?php
if (isset($_POST["btnAvail"])) {
    $total = 0;

    echo "<table border='0' width='40%'>";
    echo "<tr><td width='70%'><b>Product</b></td><td><b>Price</b></td></tr>";
    echo "<tr><td colspan='2'><hr></td></tr>";
    
    foreach ($_POST["id_product"] as $id_product) {
        $get_product = mysqli_query($connections, "SELECT * FROM meal WHERE id_product = '$id_product'");

        while ($row_get = mysqli_fetch_assoc($get_product)) {
            $db_product = $row_get["product"];
            $db_price = $row_get["price"];
            
            echo "<tr><td>$db_product</td><td>P" . number_format($db_price) . ".00</td></tr>";
            
            // Add the price of the current product to the total
            $total += $db_price;
        }
    }
    
    // Display the total row
    echo "<tr><td><b>Total</b></td><td>P " . number_format($total) . ".00</td></tr>";
    echo "</table>";
}
?>


<?php
$categories = ['Meal', 'Drinks', 'Dessert'];
echo "<h1>Mcdonaldo</h1>";
?>

<form method="POST" action="">
    <?php
    foreach ($categories as $category) {
        echo "<h2>{$category}</h2>";
        echo "<table border='0' width='40%'>
                <tr><td width='70%'><b>Product Name</b></td><td><b>Price</b></td><td><b>Select</b></td></tr>
                <tr><td colspan='3'><hr></td></tr>";

        $query_product = mysqli_query($connections, "SELECT * FROM meal WHERE category = '$category'");
        while ($row_product = mysqli_fetch_array($query_product)) {
            $product_name = $row_product["product"];  // Get product name
            $product_price = $row_product["price"];   // Get product price
            $id_product = $row_product["id_product"];
            
            // Display each product with a checkbox
            echo "<tr>
                    <td>{$product_name}</td>
                    <td>P " . number_format($product_price, 2) . "</td>
                    <td><input type='checkbox' name='id_product[]' value='{$id_product}' onclick='toggleCheckbox({$product_price}, this.checked)'></td>
                  </tr>";
        }
        echo "</table><br>";
    }
    ?>
    
    <h3>Total: <span id="Totalcost">P 0.00</span></h3>
    <input type="submit" name="btnAvail" value="Avail">
</form>

<script type="text/javascript">
    // Variable to hold total price
    let total = 0;

    // Function to update total based on selected items
    function toggleCheckbox(price, isChecked) {
        if (isChecked) {
            total += parseFloat(price);
        } else {
            total -= parseFloat(price);
        }
        document.getElementById('Totalcost').innerText = "P " + total.toFixed(2);
    }
</script>

<?php
// PHP to handle form submission and display selected products and total
if (isset($_POST["btnAvail"])) {

    require_once 'app/init.php'; // Include your initialization file

    echo "<form method='POST' action='payment'>";
    $total = 0;

    echo "<h2>Selected Items</h2>";
    echo "<table border='1' width='40%'>
            <tr><td><b>Product</b></td><td><b>Price</b></td></tr>";
    
    foreach ($_POST["id_product"] as $id_product) {
        $get_product = mysqli_query($connections, "SELECT * FROM meal WHERE id_product = '$id_product'");
        while ($row = mysqli_fetch_assoc($get_product)) {
            $product_name = $row["product"];  // Get product name like Burger, Soda, etc.
            $product_price = $row["price"];
            
            // Display the product name and price in the selected items table
            echo "<tr><td>{$product_name}</td><td>P " . number_format($product_price, 2) . "</td></tr>";
            $total += $product_price;
        }
    }

    // Display the total cost at the end
    echo "<tr><td><b>Total</b></td><td>P " . number_format($total, 2) . "</td></tr>";
    echo "</table><hr>"; // Corrected closing tag for the table

    echo "</form>";
}
?>


<?php
// PHP to handle form submission and display selected products and total
if (isset($_POST["btnAvail"])) {

    require_once 'app/init.php';

    echo "<form method='POST' action='payment.php'>";
    $total = 0;

    echo "<h2>Selected Items</h2>";
    echo "<table border='1' width='40%'>
            <tr><td><b>Product</b></td><td><b>Price</b></td></tr>";
    
    foreach ($_POST["id_product"] as $id_product) {
        $get_product = mysqli_query($connections, "SELECT * FROM meal WHERE id_product = '$id_product'");
        while ($row = mysqli_fetch_assoc($get_product)) {
            $product_name = $row["product"];  // Get product name like Burger, Soda, etc.
            $product_price = $row["price"];
            
            // Display the product name and price in the selected items table
            echo "<tr><td>{$product_name}</td><td>P " . number_format($product_price, 2) . "</td></tr>";
            $total += $product_price;
        }
    }

    // Display the total cost at the end
    echo "<tr><td><b>Total</b></td><td>P " . number_format($total, 2) . "</td></tr>";
    ?>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="total" value="<?php echo $total * 100;?>">
            <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo $stripe['pub_key']; ?>"
            data-amount="<?php echo $total * 100; ?>"
            data-name="Mcdonaldo"
            data-description="Full Payment"
            data-email="reywillardd01@gmail.com"
            data-currency="php"
            data-image="Picture3.png"
            data-locale="auto">

            </script>

            </td>
        </tr>
    <?php
    echo "</table><hr>";

    echo "</form>";
}
?>
<form method="POST" action="get_pdf_id.php" target="_blank">
    <input type="text" name="id1" placeholder="Enter Product ID">
    <input type="submit" name="pdf" id="pdf" value="PDF">
</form>

<table border="1">
    <tr>
        <th>Product Id</th>
        <th>Product</th>
        <th>Price</th>
        <th>Category</th>
    </tr>

    <?php
    $select = "SELECT * FROM meal";
    $query = mysqli_query($connections, $select);

    // Loop to display each product in the table
    while ($row = mysqli_fetch_array($query)) {
    ?>
        <tr>
            <td><?php echo $row['id_product']; ?></td>
            <td><?php echo $row['product']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['category']; ?></td>
        </tr>
    <?php
    } // End of the while loop
    ?>
</table>`

<form method="POST" action="pdf.php" target="_blank">
    <input type="submit" name="pdf_creater" value="PDF">
    </form> -->
