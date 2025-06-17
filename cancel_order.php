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
   <title></title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

    <h1 class="heading">Cancelled orders</h1>
    

   </section>

<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/user_script.js"></script>


</body>
</html>