<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:admin_login.php');
};
if(isset($_POST['update_payment'])){
  $order_id = $_POST['order_id'];
  $payment_status = $_POST['payment_status'];
  $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ? ");
  $update_payment->execute([$payment_status, $order_id]);
  $message[] = 'payment status updated!';
}

if(isset($_POST['update_order'])){
  $order_id = $_POST['order_id'];
  $order_status = $_POST['order_status'];
  $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET order_status = ? WHERE id = ? ");
  $update_payment->execute([$order_status, $order_id]);
  $message[] = 'payment status updated!';
}
if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
  $delete_order->execute([$delete_id]);
  header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
  <link rel="icon" href="../images/ecommerce logo.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <link rel="stylesheet" href="../css/admin_style.css">
    
  </head>
  <body>
<?php include '../components/admin_header.php' ?>
 
<section class="orders">

<h1 class="heading">Placed Orders</h1>

<div class="box-container">
<?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Order Id : <span><?= $fetch_orders['id']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <p>Payment Status:-</p>
         <select name="payment_status" class="select" default>
            <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select> 
         <div class="flex-btn">
          <input type="submit" value="update" class="option-btn" name="update_payment" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?>
        </div>
         <p>Order Status:-</p>

         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="order_status" class="select" default>
            <option selected disabled><?= $fetch_orders['order_status']; ?></option>
            <option value="ordered">Pending</option>
            <option value="shipped">shipped</option>
            <option value="out for delivery">out for delivery</option>
            <option value="delivered">delivered</option>
            <option value="cancelled">cancelled</option>
          </select>
          <div class="flex-btn">
          <input type="submit" value="update" class="option-btn" name="update_order" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?>
        </div>
        <p>Cancellation Request : <span>
    <?php echo ($fetch_orders['order_cancel'] == 'cancelled by user') ? "Requested by user" : "No"; ?>
</span></p>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
   ?>

</div>


</section>



  <script src="../js/admin_script.js"></script>   
  <Footer>
   <center><p class="empty " style="margin-top: 10rem; ">All rights reserved to Niteesh &copy; 2023</p> </center>
</Footer>
</body>
</html>

