<?php

include 'components/connect.php';

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
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   // Password validation: 8-16 chars, 1 number, 1 special char, 1 uppercase letter
   if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/', $pass)){
      $message[] = "Password must be 8-16 characters long, include at least one number, one special character, and one uppercase letter!";
   } elseif($pass !== $cpass){
      $message[] = "Passwords don't match!";
   } else {
      $pass = sha1($pass);
      $cpass = sha1($cpass);

      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $message[] = 'Entered email is already exists!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(user_name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $insert_user1 = $conn->prepare("INSERT INTO `abc`(name,payment_status) VALUES(?,?)");
         $insert_user1->execute([$name,'pending']);
         $message[] = 'Registered successfully, login now please!';
         header('location:user_login.php');
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration Page</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/user_style.css">
   <link rel="icon" href="ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Registration here</h3>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')"
         pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$"
         title="Password must be 8-16 characters long, with at least one number, one special character, and one uppercase letter.">
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>Already have an account?</p>
      <a href="user_login.php" class="option-btn">Login here</a>
   </form>

</section>

<!-- 
<?php 
include 'components/user_footer.php';
?> -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/user_script.js"></script>

</body>
</html>
