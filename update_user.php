<?php

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET user_name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = $_POST['new_pass'];
   $cpass = $_POST['cpass'];

   // Password validation: 8-16 chars, 1 number, 1 special char, 1 uppercase letter
   if(!empty($new_pass) && !preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/', $new_pass)){
      $message[] = "New password must be 8-16 characters long, with at least one number, one special character, and one uppercase letter!";
   } elseif($old_pass == $empty_pass){
      $message[] = 'Please enter your old password!';
   } elseif($old_pass != $prev_pass){
      $message[] = "Old password doesn't match!";
   } elseif($new_pass != $cpass){
      $message[] = "Confirm password doesn't match!";
   } elseif(sha1($new_pass) == $old_pass){
      $message[] = 'Try a new password, you are using your old password to update!';
   } else {
      if(!empty($new_pass)){
         $new_pass_hashed = sha1($new_pass);
         $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$new_pass_hashed, $user_id]);
         $message[] = 'Password updated successfully! 😊';
      } else {
         $message[] = 'Please enter a new password!';
      }
   }
}

include 'components/wishlist_cart.php';

?>

<?php          
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_profile->execute([$user_id]);
   if($select_profile->rowCount() > 0){
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   }else{
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/user_style.css">
   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Update Now</h3>
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" value="<?= $fetch_profile["user_name"]; ?>">
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
      <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')"
         pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$"
         title="Password must be 8-16 characters long, with at least one number, one special character, and one uppercase letter.">
      <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" class="btn" name="submit">
   </form>

</section>

<?php 
include 'components/user_footer.php';
?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/user_script.js"></script>

</body>
</html>
