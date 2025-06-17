<?php

include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
   exit();
}

// Fetch user details
$select_details = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?");
$select_details->execute([$user_id]);
$user_details = $select_details->fetch(PDO::FETCH_ASSOC);

$name = $user_details['name'] ?? '';
$number = $user_details['number'] ?? '';
$email = $user_details['email'] ?? '';
$address = $user_details['address'] ?? '';

if(isset($_POST['order'])){
   $select_details1 = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_details1->execute([$user_id]);
   $user_details1 = $select_details1->fetch(PDO::FETCH_ASSOC);
   $aname = $user_details1['user_name'];
   $aemail = $user_details1['email'];

   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $address = isset($_POST['flat']) ? ''. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'] : $address;
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, address, total_products, total_price) VALUES(?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $aname, $number, $email, $address, $total_products, $total_price]);

      if($user_details){
         $update_details = $conn->prepare("UPDATE `users_details` SET number = ?, address = ? WHERE user_id = ?");
         $update_details->execute([$number, $address, $user_id]);
      }else{
         $insert_details = $conn->prepare("INSERT INTO `users_details`(user_id, name, number, email, address) VALUES(?,?,?,?,?)");
         $insert_details->execute([$user_id, $aname, $number, $aemail, $address]);
      }

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Order placed successfully!';
   }else{
      $message[] = 'Your cart is empty';
   }
}

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <link rel="stylesheet" href="css/user_style.css">
   <style>
      #edit-button, #edit-num {
         background: #fff;
         font-size: 2rem;
         cursor: pointer;
         margin-left: 2%;
      }
      .readonly {
         background-color: #f3f3f3;
      }
   </style>
   <script>
      function toggleAddressEdit() {
         const addressInput = document.getElementById('single-address');
         const addressFields = document.getElementById('address-fields');
         const editButton = document.getElementById('edit-button');
         if (addressFields.style.display === 'none') {
            addressFields.style.display = 'block';
            addressInput.style.display = 'none';
            editButton.style.display = 'none';
         } else {
            addressFields.style.display = 'none';
            addressInput.style.display = 'block';
            editButton.style.display = 'inline';
         }
      }

      function numEdit() {
         const numInput = document.getElementById('num_edit');
         
         if (numInput.readOnly) {
            numInput.readOnly = false;
            numInput.classList.remove('readonly');
         } else {
            numInput.readOnly = true;
            numInput.classList.add('readonly');
         }
      }
   </script>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">
   <form action="" method="post">
      <div class="display-orders">
         <h3>Place your orders</h3>

         <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].')';
                  $total_products = implode(', ', $cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
            <p><?= $fetch_cart['name']; ?> <span>(<?= '₹'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span></p>
         <?php
               }
            }else{
               echo '<p class="empty">Your cart is empty!</p>';
            }
         ?>

         <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products); ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand total: <span>₹<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>Billing Details</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Your number:</span>
            <div style="display: flex; align-items: center;">
               <input type="number" name="number" placeholder="Enter your number" value="<?= htmlspecialchars($number); ?>" id="num_edit" class="box" min="0" max="9999999999" required>
               <?php if($number): ?>
                  <button type="button" id="edit-num" onclick="numEdit()">✏️</button>
               <?php endif; ?>
            </div>
         </div>

         <div class="inputBox">
            <span>Your address:</span>
            <?php if($address): ?>
               <div style="display: flex; align-items: center;">
                  <input type="text" id="single-address" name="address" value="<?= htmlspecialchars($address); ?>" class="box" readonly>
                  <button type="button" id="edit-button" onclick="toggleAddressEdit()">✏️</button>
               </div>
               <div id="address-fields" style="display: none;">
                  <input type="text" name="flat" placeholder="Flat no." class="box">
                  <input type="text" name="street" placeholder="Street" class="box">
                  <input type="text" name="city" placeholder="City" class="box">
                  <input type="text" name="state" placeholder="State" class="box">
                  <input type="text" name="country" placeholder="Country" class="box">
                  <input type="number" name="pin_code" placeholder="Pin code" class="box">
               </div>
            <?php else: ?>
               <div id="address-fields">
                  <input type="text" name="flat" placeholder="Flat no." class="box" required>
                  <input type="text" name="street" placeholder="Street" class="box" required>
                  <input type="text" name="city" placeholder="City" class="box" required>
                  <input type="text" name="state" placeholder="State" class="box" required>
                  <input type="text" name="country" placeholder="Country" class="box" required>
                  <input type="number" name="pin_code" placeholder="Pin code" class="box" required>
               </div>
            <?php endif; ?>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>" value="Place Order">
   </form>
</section>

<?php include 'components/user_footer.php'; ?>

</body>
</html>
