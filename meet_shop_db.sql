-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 09:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(64, 35, 26, 'Bajra - Rotlo ', 15, 1, 'Screenshot 2024-02-08 135208.png'),
(65, 35, 27, 'Bataka sabji', 25, 1, 'Screenshot 2024-02-07 230913.png');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(8, 32, 'raj', 'raj67@gmail.com', '9848762341', 'food is very tasty...');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(12, 32, 'varshil', '56465464', 'kukadiyavarshil@gmail.com', 'cash on delivery', 'flat no. krishna public school,amreli,Gujarat-365601 415 Amreli Gujarat India - 365601', ', sabji ( 1 )', 20000, '13-Nov-2024', 'pending'),
(16, 35, 'varshil1', '56465464', 'kukadiyavarshil@gmail.com', 'cash on delivery', 'flat no. krishna public school,amreli,Gujarat-365601 415 Amreli Gujarat India - 365601', ', sabji ( 5 )', 100000, '10-Jan-2025', 'pending'),
(17, 35, 'varshil123', '56465464', 'arpitkukadiya0@gmail.com', 'cash on delivery', 'flat no. krishna public school,amreli,Gujarat-365601 415 Amreli Gujarat India - 365601', ', Roti ( 1 ), Bajra - Rotlo  ( 2 )', 36, '13-Jan-2025', 'pending'),
(18, 35, 'varshil67', '56465464', 'kukadiyavarshil@gmail.com', 'cash on delivery', 'flat no. krishna public school,amreli,Gujarat-365601 415 Amreli Gujarat India - 365601', ', Bataka sabji ( 1 ), sabji ( 1 )', 75, '13-Jan-2025', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `details`, `price`, `image`) VALUES
(24, 'sabji', 'kathiyavadi', ' sabji tet.........', 50, 'Screenshot 2024-02-07 230517.png'),
(25, 'Roti', 'kathiyavadi', 'Roti is a soft, unleavened flatbread made from whole wheat flour, commonly served with curries and vegetables. It is a staple in South Asian cuisine, offering a nutritious source of carbohydrates and fiber.', 6, 'Screenshot 2024-02-08 132551.png'),
(26, 'Bajra - Rotlo ', 'kathiyavadi', 'Bajra no rotlo is a traditional Gujarati flatbread made from millet flour (bajra). It is known for its earthy flavour and dense texture. Bajra no rotlo is a nutritious alternative to wheat-based bread. It is typically served with vegetables, curries, or yogurt.', 15, 'Screenshot 2024-02-08 135208.png'),
(27, 'Bataka sabji', 'kathiyavadi', 'Bataka Sabji is a flavorful and hearty Indian potato curry made with boiled potatoes, spices, and herbs. It&#39;s often cooked with onions, tomatoes, cumin, turmeric, and other aromatic seasonings, creating a deliciously comforting dish served with roti or rice.', 25, 'Screenshot 2024-02-07 230913.png'),
(28, 'kathiyavadi kadhi', 'kathiyavadi', 'Kathiyavadi Kadhi is a tangy and spicy yogurt-based curry from Gujarat, flavoured with garlic, ginger, and traditional spices. It is typically served with steamed rice or khichdi, offering a rich, savory taste with a hint of sweetness.', 25, 'Screenshot 2024-02-07 230239.png'),
(29, 'Khichdi', 'kathiyavadi', 'Khichdi is a comforting Indian dish made from rice and lentils, cooked with mild spices. It is often considered a wholesome and easy-to-digest meal, typically served with yogurt, pickle, or papad.', 20, 'Screenshot 2024-02-07 225904.png'),
(30, 'dhokli-sabji ', 'kathiyavadi', 'Dhokli nu Sabji is a traditional Gujarati dish made with wheat flour dumplings (dhoklis) simmered in a spiced, tangy curry. The soft dumplings absorb the flavors of the curry, creating a hearty and flavorful one-pot meal.', 25, 'Screenshot 2024-02-07 231236.png'),
(31, 'Ringan no Olo', 'kathiyavadi', 'Ringan no Olo is a traditional Gujarati dish made by roasting eggplant (brinjal) over an open flame and then mashing it with spices, garlic, and mustard oil. It is a smoky, flavorful dish typically served with flatbreads like roti or puri.', 25, 'Screenshot 2024-02-08 134741.png'),
(32, 'Bhajiya ', 'kathiyavadi', 'Bhajiya is a popular Indian snack made by dipping vegetables like potatoes, onions, or spinach in a seasoned chickpea flour batter and deep-frying them. Crispy on the outside and soft on the inside, they are often served with chutneys or tea.', 40, 'Screenshot 2024-02-08 132942.png'),
(33, 'Idli', 'south indian', 'Idli is a soft, fluffy steamed rice cake made from a fermented batter of rice and urad dal (split black lentils). It is a popular breakfast dish in South India, typically served with chutneys and sambar.', 20, 'Screenshot 2024-02-07 215005.png'),
(34, 'Plain-Dosa', 'south indian', 'Plain Dosa is a crispy, thin crepe made from a fermented batter of rice and urad dal. It is a popular South Indian dish, often served with chutneys and sambar for a savory breakfast or snack.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 50, 'Screenshot 2024-02-07 215721.png'),
(35, 'Masala Dosa', 'south indian', 'Masala Dosa is a crispy, thin crepe made from fermented rice and lentil batter, filled with a spiced potato mixture. It is a popular South Indian dish, typically served with chutneys and sambar for a flavorful meal.', 80, 'Screenshot 2024-02-07 220138.png'),
(36, 'Paneer Masala Dosa', 'south indian', 'Paneer Masala Dosa is a variation of the traditional dosa, filled with a spiced paneer (Indian cottage cheese) filling. This crispy dosa is served with chutneys and sambar, offering a rich and flavorful twist on the classic dish.', 90, 'Screenshot 2024-02-07 221032.png'),
(37, 'Pepper Dosa ', 'south indian', 'Pepper Dosa is a spicy variation of the traditional dosa, flavored with black pepper and aromatic spices. It is crispy and savory, often served with chutneys and sambar for a flavorful and tangy meal.', 50, 'Screenshot 2024-02-07 221849.png'),
(38, 'Uttapam', 'south indian', 'Uttapam is a thick, savory South Indian pancake made from fermented rice and urad dal batter. Topped with vegetables like onions, tomatoes, and chilies, it is served with chutneys and sambar for a hearty breakfast or snack.', 90, 'Screenshot 2024-02-07 221440.png'),
(39, 'Cheese Masala Dosa', 'south indian', 'Cheese Masala Dosa is a crispy dosa filled with a spiced potato mixture and topped with melted cheese. This fusion dish combines the traditional flavors of masala dosa with a rich, creamy texture from the cheese.', 100, 'Screenshot 2024-02-07 222947.png'),
(40, 'Cheese Uttapam', 'south indian', 'Cheese Uttapam is a thick, savoury pancake made from fermented rice and dal batter, topped with a generous amount of melted cheese. It is a flavorful twist on the traditional uttapam, often served with chutneys and sambar.', 100, 'Screenshot 2024-02-07 222432.png'),
(41, 'Vada', 'south indian', 'Vada is a deep-fried, crispy Indian snack made from a batter of lentils or potatoes, shaped into round or doughnut-like forms. It is often served with chutneys or sambar, making it a popular choice for breakfast or as an appetizer.', 60, 'Screenshot 2024-02-07 223258.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `image`) VALUES
(31, 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', 'WIN_20240725_10_20_47_Pro.jpg'),
(35, 'ARPIT', 'demo@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', 'peakpx.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(50, 33, 24, 'sabji', 20000, 'Screenshot 2024-02-07 230517.png'),
(52, 32, 36, 'Paneer Masala Dosa', 90, 'Screenshot 2024-02-07 221032.png'),
(57, 35, 25, 'Roti', 6, 'Screenshot 2024-02-08 132551.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
