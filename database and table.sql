/* First create a database on a local server.
   The database name should match the name in connect.php.
   Then, import this SQL file into the created database. */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `abc` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `payment_status` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `admins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `adminname` VARCHAR(200) NOT NULL,
  `adminpassword` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`id`, `adminname`, `adminpassword`) VALUES
(1, 'santhosh@meoluc', '3dfbf631e44a0c93ad799659a9c1fdd73a91083b');

CREATE TABLE `cart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `pid` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `price` INT NOT NULL,
  `quantity` INT NOT NULL,
  `image` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `number` VARCHAR(12) NOT NULL,
  `message` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `number` VARCHAR(10) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `method` VARCHAR(50) NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `total_products` VARCHAR(1000) NOT NULL,
  `total_price` INT NOT NULL,
  `placed_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `payment_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `order_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `details` VARCHAR(1000) NOT NULL,
  `price` INT NOT NULL,
  `image_01` VARCHAR(100) NOT NULL,
  `image_02` VARCHAR(100) NOT NULL,
  `image_03` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `contents` VARCHAR(255) NOT NULL,
  `stars` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'user.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(20) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- INSERT INTO `users` (`id`, `user_name`,`email`, `password`) VALUES
-- (1, 'niteesh','niteesh2june@gmail.com', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

CREATE TABLE `users_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `number` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wishlist` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `pid` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `price` INT NOT NULL,
  `image` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
