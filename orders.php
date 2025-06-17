<?php

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');

};

if(isset($_POST['cancel_order'])){
   $order_id = $_POST['order_id'];
   $order_status = "cancelled by user";
   // $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
   $update_status = $conn->prepare("UPDATE `orders` SET order_cancel = ? WHERE id = ? ");
   $update_status->execute([$order_status, $order_id]);
   $message[] = 'Cancellation Initiated!';
   // echo 1;
 }

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Placed orders</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>order_id : <span><?= $fetch_orders['id']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span></p>
      <!-- <p> Payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p> -->
      <p>Order status :
    <span style="color: <?= 
        ($fetch_orders['order_status'] == 'cancelled') ? 'red' : 
        (($fetch_orders['order_status'] == 'delivered') ? 'green' : ''); 
    ?>">
        <?= ucfirst($fetch_orders['order_status']); ?>
    </span>

</p>
      
      <?php
   if($fetch_orders['order_status'] == 'delivered'){  
      ?>
      <p>Give FeedBack about your recent purchase</p><a href="review.php" class="option-btn">feed Back</a>
      <?php
   }else{
      ?>
      <a href="order_status.php?oid=<?= $fetch_orders['id'];?>" class="btn">Track</a>
      <!-- <a href="cancel_order.php?oid=<?= $fetch_orders['id'];?>" class="delete-btn">cancel</a> -->
      <form action="" method="post">
          <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
          <input type="submit" class="delete-btn" name="cancel_order" value="Cancel Order">
       </form>   <?php
   }  

?>
</div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>

   </section>

<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/user_script.js"></script>


</body>
</html>