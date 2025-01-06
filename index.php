<?php
require_once 'autoload.php';

$db = new Database();
$connection = $db->connect();
$product = new Product($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>

    <link href="/public/asset/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/public/asset/js/jquery-3.7.1.min.js"></script>
    <script src="/public/asset/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include 'views/Navigation.php';?>

    <div class="container mt-5">

        <div class="row">
            <?php
if (isset($_GET['view']) && $_GET['view'] === 'addproduct') {
    include 'views/ProductForm.php';
} else {
    include 'views/ProductList.php';
}
?>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        // initialize the cart on page load
        updateCart();
    });
    </script>
</body>

</html>