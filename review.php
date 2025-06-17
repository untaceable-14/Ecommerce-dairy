<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();
}

$message = '';

if(isset($_POST['submit'])){
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $content = filter_var($_POST['contents'], FILTER_SANITIZE_STRING);
   $rating = intval($_POST['rating']);
   $image = 'images/user.jpg'; // Default image path

   // Ensure images folder exists
   if (!is_dir('images')) {
       mkdir('images', 0777, true);
   }

   // Image upload handling
   if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
      $img_name = $_FILES['image']['name'];
      $img_tmp = $_FILES['image']['tmp_name'];
      $img_size = $_FILES['image']['size'];
      $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
      $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

      if(in_array($img_ext, $allowed_ext) && $img_size <= 2 * 1024 * 1024){ // 2MB limit
         $new_img_name = uniqid('IMG-', true) . '.' . $img_ext;
         $img_path = 'images/' . $new_img_name;

         if(move_uploaded_file($img_tmp, $img_path)){
            $image = $img_path; // Use uploaded image
         } else {
            $message = "Failed to upload image!";
         }
      } else {
         $message = "Invalid image file. Please upload jpg, jpeg, png, or gif (max 2MB).";
      }
   }

   if($rating >= 0 && $rating <= 5){
      $insert_review = $conn->prepare("INSERT INTO `reviews` (user_id, name, contents, stars, image) VALUES (?, ?, ?, ?, ?)");
      $insert_review->execute([$user_id, $name, $content, $rating, $image]);
   } else {
      $message = "Invalid rating. Please select between 0 to 5 stars.";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Review Product</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/user_style.css">
   <link rel="icon" href="ecommerce logo.png">

   <style>
      .form-container {
         max-width: 500px;
         margin: 2rem auto;
         padding: 2rem;
         background: #f4f4f4;
         border-radius: 8px;
      }
      .box {
         width: 100%;
         padding: 10px;
         margin-bottom: 1rem;
         font-size: 1rem;
      }
      .stars {
         display: flex;
         justify-content: center;
         align-items: center;
         gap: 5%;
         font-size: 2.5rem;
         margin-bottom: 1.5rem;
         cursor: pointer;
      }

      .star {
         color: #ccc;
         transition: color 0.2s;
      }

      .star.active {
         color: gold;
      }

      .message {
         padding: 10px;
         background-color: #d4edda;
         color: #155724;
         border: 1px solid #c3e6cb;
         margin-bottom: 1rem;
      }
      .image-preview {
         max-width: 100%;
         height: auto;
         margin-bottom: 1rem;
         display: none;
         border: 1px solid #ddd;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <?php if($message): ?>
      <div class="message"><?= htmlspecialchars($message); ?></div>
   <?php endif; ?>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Review the Product</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box">
      <input type="text" name="contents" required placeholder="Tell about the product" class="box">

      <p>Rate the product (0-5):</p>
      <input type="hidden" name="rating" id="rating" value="0">
      <div class="stars" id="star-container">
         <span class="star" data-value="1">&#9733;</span>
         <span class="star" data-value="2">&#9733;</span>
         <span class="star" data-value="3">&#9733;</span>
         <span class="star" data-value="4">&#9733;</span>
         <span class="star" data-value="5">&#9733;</span>
      </div>

      <p>Upload an image:</p>
      <input type="file" name="image" accept="image/*" class="box" onchange="previewImage(event)">
      <img id="image-preview" class="image-preview">

      <input type="submit" value="Submit Review" class="btn" name="submit">
   </form>

</section>

<script>
   document.addEventListener('DOMContentLoaded', () => {
      const stars = document.querySelectorAll('.star');
      const ratingInput = document.getElementById('rating');

      stars.forEach(star => {
         star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            ratingInput.value = value;

            stars.forEach(s => s.classList.remove('active'));
            for(let i = 0; i < value; i++){
               stars[i].classList.add('active');
            }
         });
      });
   });

   function previewImage(event) {
      const preview = document.getElementById('image-preview');
      preview.src = URL.createObjectURL(event.target.files[0]);
      preview.style.display = 'block';
   }
</script>

<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/user_script.js"></script>

</body>
</html>