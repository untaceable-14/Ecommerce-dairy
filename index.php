<?php
session_start();
error_reporting(E_ALL); 
ini_set('display_errors', 1);
require_once 'components/connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   // echo $user_id;
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>
<?php
    if(isset($_SESSION['pop-up'])){

      $message[] = 'Logged in sucessfully';

    unset($_SESSION['pop-up']);
}?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <meta name="description" content="Buy the best products at unbeatable prices on YourWebsite!">
   <meta name="keywords" content="ecommerce, online shopping, meoluc, ghee, dairy">
   <meta name="robots" content="index, follow">
   <meta name="google-site-verification" content="tugylyg4lzwSv8kYa1oEvfGj31reNEQFzbS1feeUBP4" /> -->
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/ecommerce logo.png">
   <link rel="stylesheet" href="css/user_style.css">
   
</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>
<!-- <script>
document.getElementById("search_btn").addEventListener("click", function() {
   document.getElementById("new").classList.toggle("activate");
  
});</script> -->

<div class="home-bg">
   <script>
const images = ['images/g2.png', 'images/g4.png', 'images/g3.png']; // Replace with your image paths
let index = 0;
const homeBg = document.querySelector('.home-bg');

function changeBackground() {
    homeBg.style.opacity = "0"; // Fade out
    setTimeout(() => {
        homeBg.style.backgroundImage = `url('${images[index]}')`;
        homeBg.style.opacity = "1"; // Fade in
        index = (index + 1) % images.length;
    }, 1000); // Wait for fade out before changing image
}

// Change background every 3 seconds
setInterval(changeBackground, 5000);

// Initial load
changeBackground();

</script>
<section class="home">
</section>
</div>





<section class="category">

   <h1 class="heading">Shop by category</h1>

   <div class="swiper category-slider">
   
   <div class="swiper-wrapper">

   <a href="category.php?category=ghee" class="swiper-slide slide">
      <img src="images/ghee-outline.jpg" alt="">
      <h3>Ghee</h3>
   </a>

   <!-- <a href="" class="swiper-slide slide">
      <img src="images/icon-2.png" alt="">
      <h3>More products will be added later</h3>
   </a> -->
<!-- 
   <a href="category.php?category=camera" class="swiper-slide slide">
      <img src="images/icon-3.png" alt="">
      <h3>Camera</h3>
   </a>

   <a href="category.php?category=mouse" class="swiper-slide slide">
      <img src="images/icon-4.png" alt="">
      <h3>Mouse</h3>
   </a>

   <a href="category.php?category=fridge" class="swiper-slide slide">
      <img src="images/icon-5.png" alt="">
      <h3>Fridge</h3>
   </a>

   <a href="category.php?category=washing" class="swiper-slide slide">
      <img src="images/icon-6.png" alt="">
      <h3>Washing machine</h3>
   </a>

   <a href="category.php?category=smartphone" class="swiper-slide slide">
      <img src="images/icon-7.png" alt="">
      <h3>Smartphone</h3>
   </a>

   <a href="category.php?category=watch" class="swiper-slide slide">
      <img src="images/icon-8.png" alt="">
      <h3>Watch</h3>
   </a>
   <a href="category.php?category=airpods" class="swiper-slide slide">
      <img src="images/icon-9.png" alt="">
      <h3>Airpods</h3>
   </a> -->
   
   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>₹</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="999" onkeypress="if(this.value.length == 3) return false;" value="1" onclick="if(this.value >=1)">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   // grabCursor:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 10,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
     breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 4,
      },
   },
});
var swiper = new Swiper(".mySwiper", {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   }, 
    breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
<?php
echo "Page loaded completely!<br>";
flush();
?>

</html>
