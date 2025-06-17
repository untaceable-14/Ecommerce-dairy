<?php

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/user_style.css">
   <link rel="icon" href="ecommerce logo.png">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>
      <div class="content">
         <h3>Our story</h3>
         <p>Meoluc dairy products was founded with a passion for purity and tradition. Our goal is to bring you the finest dairy products, crafted with care and authenticity.</p>
         <h3>Why choose us?</h3>
         <ul>
            <li>100% Pure & Traditional</li>
            <li>No Preservatives</li>
            <li>Premium Quality Guaranteed</li>
            <li>Fresh & Nutrient Rich</li>
         </ul>
         <a href="contact.php" class="btn">contact us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="heading">client's reviews</h1>

   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">
         <?php
         $fetch_reviews = $conn->prepare("SELECT * FROM `reviews`");
         $fetch_reviews->execute();
         while($review = $fetch_reviews->fetch(PDO::FETCH_ASSOC)){
         ?>
            <div class="swiper-slide slide">
               <img src="<?= $review['image']; ?>" alt="User Image">
               <p><?= htmlspecialchars($review['contents']); ?></p>
               <div class="stars">
            <?php for($i = 1; $i <= 5; $i++): ?>
               <i class="fa-star <?= $i <= $review['stars'] ? 'fas filled' : 'far'; ?>"></i>
            <?php endfor; ?>
         </div>
               <h3><?= htmlspecialchars($review['name']); ?></h3>
            </div>
         <?php
         }if($fetch_reviews->rowCount() == 0){
            echo '<p class="empty">No reviews yet!</p>';
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
   var swiper = new Swiper(".reviews-slider", {
      loop: true,
      spaceBetween: 20,
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
      breakpoints: {
         0: { slidesPerView: 1 },
         768: { slidesPerView: 2 },
         991: { slidesPerView: 3 },
      },
   });
</script>

</body>
</html>
