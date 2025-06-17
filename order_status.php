<?php

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

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

   <style>
 /* Container for the progress bar */
.box {
   margin-bottom: 2rem;
   padding: 1rem;
}

/* Vertical progress bar */
.progress-bar {
   display: flex;
   flex-direction: column;
   gap: 2rem;
   position: relative;
   padding-left: 2rem;
}

/* Vertical line */
.progress-bar::before {
   content: "";
   position: absolute;
   top: 0;
   left: 3rem;
   width: 4px;
   height: 100%;
   background-color: #ccc;
   z-index: 0;
}

/* Progress circles */
.progress-step {
   font-size: 1rem;
   background-color: #fff;
   border: 2px solid #ccc;
   border-radius: 50%;
   width: 2.5rem;
   height: 2.5rem;
   display: flex;
   justify-content: center;
   align-items: center;
   position: relative;
   z-index: 1;
}

/* Completed and cancelled steps */
.progress-step.completed {
   border-color: #006eff;
   background-color: #006eff;
   color: #fff;
}

.progress-step.cancel {
   border-color: red;
   background-color: red;
   color: #fff;
}

/* Step container (circle + label) */
.step {
   display: flex;
   align-items: center;
   gap: 1rem;
}

/* Status labels */
.step p {
   font-size: 1rem;
   color: #333;
}

/* Responsive: Adjust circle size on smaller screens */
@media (max-width: 768px) {
   .progress-step {
      width: 2rem;
      height: 2rem;
      font-size: 0.875rem;
   }

   .step p {
      font-size: 0.9rem;
   }
}


   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Track Order</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $oid = $_GET['oid'];
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
         $select_orders->execute([$oid]);
         $fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC);
         $order_status = $fetch_orders['order_status'];
   ?>
   <!-- <h1 class="heading"></h1> -->
   <div class="box">
      <p>Order Id: <span><?= $fetch_orders['id']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['total_products']; ?></span></p>
      
      <center><p><b>Track Order</b></p></center>

<?php
$statuses = ['pending', 'shipped', 'out for delivery', 'delivered', 'cancelled'];
$completedIndex = array_search($order_status, $statuses);
?>

<!-- Vertical Progress Bar -->
<ul class="progress-bar">
   <li class="step">
      <div class="progress-step <?= $completedIndex >= 0 ? 'completed' : ''; ?>"></div>
      <p>Ordered</p>
   </li>
   <li class="step">
      <div class="progress-step <?= $completedIndex >= 1 ? 'completed' : ''; ?>"></div>
      <p>Shipped</p>
   </li>
   <li class="step">
      <div class="progress-step <?= $completedIndex >= 2 ? 'completed' : ''; ?>"></div>
      <p>Out for Delivery</p>
   </li>
   <li class="step">
      <div class="progress-step <?= $completedIndex >= 3 ? 'completed' : ''; ?>"></div>
      <p>Delivered</p>
   </li>
   <li class="step">
      <div class="progress-step <?= $order_status == 'cancelled' ? 'cancel' : ''; ?>"></div>
      <p>Cancelled</p>
   </li>
</ul>

<br><br>
<br><br>
<?php
if($fetch_orders['order_status'] == 'cancelled'){  
?>
 <p class="heading1">Your order has been <span style="color:red;">cancelled</span></p>
 <?php
}else if($fetch_orders['order_status'] == 'delivered'){
?>
 <p class="heading1">Your order has been <span style="color:green;">delivered</span></p>
 <?php
}else{
?>
<p class="heading1">Expected Delivery On : <?php
$specific_date = $fetch_orders['placed_on']; 
$date = new DateTime($specific_date);
$date->modify('+3 days'); 
echo '<span>'. $date->format('Y-m-d').'</span>';
?>
</p>
<?php
}
?>
</div>

   <?php
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
