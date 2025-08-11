<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_orders->execute([$delete_id]);
    header('location:orders.php');
}

// Function to generate PDF and download
function generateAndDownloadPDF($order_id)
{
    require('fpdf/fpdf.php'); // Include the FPDF library

    // Fetch order details from the database
    global $conn;
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $select_order->execute([$order_id]);
    $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);

    // Create a PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add order details to the PDF
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Order Details', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 10, 'Placed on: ' . $fetch_order['placed_on'], 0, 1);
    $pdf->Cell(0, 10, 'Name: ' . $fetch_order['name'], 0, 1);
    $pdf->Cell(0, 10, 'Number: ' . $fetch_order['number'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $fetch_order['email'], 0, 1);
    $pdf->Cell(0, 10, 'Address: ' . $fetch_order['address'], 0, 1);
    $pdf->Cell(0, 10, 'Payment Method: ' . $fetch_order['method'], 0, 1);
    $pdf->Cell(0, 10, 'Your Orders: ' . $fetch_order['total_products'], 0, 1);
    $pdf->Cell(0, 10, 'Total Price: ' . $fetch_order['total_price'] . '/-', 0, 1);
    $pdf->Cell(0, 10, 'Payment Status: ' . $fetch_order['payment_status'], 0, 1);

    // Save and output the PDF
    $pdf->Output('order_bill_' . $order_id . '.pdf', 'D');
}

// Check if the "Print Bill" button is clicked
if (isset($_POST['print_bill'])) {
    $order_id = $_POST['order_id'];
    generateAndDownloadPDF($order_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
<style>
 /* General Styling for the Page */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7f8;
    margin: 0;
    padding: 0;
}

/* Section Styling for Placed Orders */
.placed-orders {
    padding: 30px;
    text-align: center;
    background-color: #ffffff;
}

.placed-orders .title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
    font-weight: bold;
}

/* Card Container Styling */
.box-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

/* Individual Card Styling */
.box {
    background-color: #fff;
    border-radius: 15px;
  /*  box-shadow: 0px 4px 15px rgba(213, 152, 30, 0.1);
    */padding: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    position: relative;
}

.box:hover {
    transform: translateY(-10px);
    box-shadow: 0px 6px 20pxhsl(145, 65.10%, 46.10%);
}

/* Text Styling inside the Cards */
.box p {
    font-size: 1.1rem;
    margin: 10px 0;
    color: white;
    line-height: 1.5;
}

.box span {
    font-weight: bold;
    color:#c79321;
}

/* Buttons Styling */
.flex-btn {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.option-btn,
.delete-btn {
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 1rem;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: inline-block;
}

/* "Print Bill" Button Styling */
.option-btn {
    background-color: #28a745;
    color: #fff;
    border: none;
}

.option-btn:hover {
    background-color: #218838;
}

/* "Delete" Button Styling */
.delete-btn {
    background-color: #dc3545;
    color: #fff;
    border: none;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* Empty State Styling */
.empty {
    color: #d9534f;
    font-size: 1.5rem;
    font-weight: bold;
}

/* Payment Status Styling */
.box p span[style="color: red"] {
    color: #ff3b3b;
}

.box p span[style="color: green"] {
    color: #28a745;
}


    </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="placed-orders">

    <h1 class="title">placed orders</h1>

    <div class="box-container">

        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $select_orders->execute([$user_id]);
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                    <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
                    <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
                    <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
                    <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
                    <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                    <p> your orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
                    <p> total price : <span>₹<?= $fetch_orders['total_price']; ?></span> </p>
                    <p> payment status : <span
                                style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                                    echo 'red';
                                } else {
                                    echo 'green';
                                } ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
                    <form action="" method="POST">
                        <div class="flex-btn">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <input type="submit" name="print_bill" class="option-btn" value="Print Bill">
                            <a href="orders.php?delete=<?= $fetch_orders['id']; ?>"
                               class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">no orders placed yet!</p>';
        }
        ?>

    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
