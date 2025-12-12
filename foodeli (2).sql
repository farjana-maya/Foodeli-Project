-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 07:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodeli`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `label` enum('home','work','other') NOT NULL DEFAULT 'other',
  `recipient_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address_line` text NOT NULL,
  `building_name` varchar(255) DEFAULT NULL,
  `floor_unit` varchar(100) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `special_instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `icon` varchar(255) DEFAULT NULL COMMENT 'Icon URL or class name',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Display order',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(8,2) NOT NULL,
  `max_discount` decimal(8,2) DEFAULT NULL,
  `min_order_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_per_user` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `applicable_restaurants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_restaurants`)),
  `applicable_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_categories`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_riders`
--

CREATE TABLE `delivery_riders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `nid_number` varchar(255) NOT NULL,
  `driving_license` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `vehicle_number` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `nid_photo` varchar(255) DEFAULT NULL,
  `license_photo` varchar(255) DEFAULT NULL,
  `vehicle_photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_deliveries` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_earnings` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_riders`
--

INSERT INTO `delivery_riders` (`id`, `user_id`, `full_name`, `phone`, `email`, `address`, `city`, `postal_code`, `date_of_birth`, `nid_number`, `driving_license`, `vehicle_type`, `vehicle_model`, `vehicle_number`, `profile_photo`, `nid_photo`, `license_photo`, `vehicle_photo`, `status`, `is_active`, `is_available`, `rating`, `total_deliveries`, `created_at`, `updated_at`, `total_earnings`) VALUES
(1, 11, 'manish ahmed', '01674423512', 'manish@gmail.com', 'Motijeel', 'Dhaka', '1236', '2002-09-11', '19952030405060708', 'DHA-1234567-2025', 'bicycle', 'Duranta Freedom 27.5', 'DCC-S-2025-00984', 'LTZzLJbHB4iqj9T7qWply2DhTm8QfsHT4bsdEA2J.jpg', '7XKLM1unGL5UUtfytC42Gjs7JOdDNP2whXqBiHoH.jpg', 'dr51MnS95lc6ctBADokOn7jCi1C9ktEtHgNKuFOf.jpg', 'UYUO1h9EDJ2EV92Z7l7A2I76DN8w7FOlXyxsiO5D.jpg', 'approved', 1, 0, 0.00, 7, '2025-11-02 02:36:23', '2025-11-03 15:22:58', 250.00),
(2, 13, 'Araf Hasan', '01921088798', 'araf@gmail.com', 'Dhanmondi,Dhaka', 'Dhaka', '1236', '2000-02-03', '19952030405060709', 'DHA-1234567-2023', 'bike', 'Duranta Freedom 27.4', 'DCC-S-2025-00986', 'pcS3VTnSIYtJ1zbWlUTll0d08Zdk305Z9JAPsneV.jpg', 'szscVFA3BmbbeRjkpXcGsxCNkIkY8svlxnZy38pH.jpg', 'TRjYFrpPPuNO3auSORr24nuBLA4RmEi1bByEuh7F.jpg', 'XECuG8IuuGv2EAW5LokTGvRjaudBJhevU9LyI47a.jpg', 'approved', 1, 0, 0.00, 1, '2025-11-02 13:23:18', '2025-11-02 13:26:24', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `discount_price` decimal(8,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_vegetarian` tinyint(1) NOT NULL DEFAULT 0,
  `is_vegan` tinyint(1) NOT NULL DEFAULT 0,
  `preparation_time` int(11) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ingredients`)),
  `allergens` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allergens`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `category` text DEFAULT 'main',
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `preparation_time` int(11) NOT NULL DEFAULT 15,
  `ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ingredients`)),
  `allergens` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allergens`)),
  `calories` int(11) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `order_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dietary_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dietary_info`)),
  `spice_level` varchar(255) DEFAULT NULL,
  `portion_size` varchar(255) DEFAULT NULL,
  `cooking_method` varchar(255) DEFAULT NULL,
  `origin_country` varchar(255) DEFAULT NULL,
  `nutritional_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`nutritional_info`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `restaurant_id`, `name`, `description`, `price`, `category`, `image`, `is_available`, `is_featured`, `preparation_time`, `ingredients`, `allergens`, `calories`, `rating`, `total_reviews`, `order_count`, `created_at`, `updated_at`, `dietary_info`, `spice_level`, `portion_size`, `cooking_method`, `origin_country`, `nutritional_info`, `tags`) VALUES
(1, 3, 'rice bowl', 'A hearty, satisfying bowl of perfectly steamed white or brown rice, topped with a flavorful mix of fresh vegetables, your choice of protein (grilled chicken, beef, tofu, or shrimp), and drizzled with a savory sauce. Each bite is a perfect balance of savory, sweet, and umami, making it a comforting yet nutritious meal for any time of day. Served with a side of tangy pickles and a sprinkle of sesame seeds for added crunch and flavor.', 150.00, 'main', 'menu_items/uE6DPbCYjYM4Kfy2CUUN7vtb1DRSSa1MDCM0H4eX.jpg', 1, 0, 20, NULL, NULL, NULL, 0.00, 0, 0, '2025-10-31 14:33:18', '2025-10-31 14:50:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 3, 'Fried Ice Cream', 'A crispy golden shell with creamy vanilla ice cream inside — a perfect sweet finish.', 280.00, 'Dessert', 'menu_items/XrFtj8KrES3qDJ9T9G2tzMLVqQdaoGi5zED64LwO.webp', 1, 0, 8, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-01 13:23:24', '2025-11-01 13:23:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 3, 'Stir-Fried Mixed Vegetables', 'Fresh seasonal vegetables sautéed with garlic and light soy sauce for a healthy delight.', 340.00, 'Side Dish', 'menu_items/nP4ax4c3ZHk8q67Leur5wRm41x61Te2oD5pBOZl5.jpg', 1, 0, 10, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-01 13:26:20', '2025-11-01 13:26:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 5, 'Chinese Set Menu A', 'A complete meal combo with Chicken Fried Rice, Sweet & Sour Chicken, Vegetable Chow Mein, and Soup of the Day.', 340.00, 'Set Menu', 'ZRvMF4RPNy6pZtTypQtwCoMqRELbssaH2pFoFM0e.jpg', 1, 0, 25, NULL, NULL, 0, 0.00, 0, 0, '2025-11-01 14:07:48', '2025-11-01 14:08:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 3, 'White Sauce Pasta', 'A creamy and delicious pasta dish made with a rich, velvety white sauce, mixed with sautéed vegetables and perfectly cooked penne pasta. Topped with herbs and cheese for an irresistible flavor.', 280.00, 'Italian', '7443NheGJNJdCLxZwnITnpUAETym2nsDUabixhPP.jpg', 1, 0, 20, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-02 14:25:51', '2025-11-02 14:25:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 3, 'Red Sauce Pasta', 'A flavorful and tangy pasta dish made with a rich tomato-based sauce, sautéed garlic, herbs, and a hint of spice. Tossed with perfectly cooked penne pasta and topped with cheese for a classic Italian taste.', 240.00, 'Italian', 'l5nFL7pdQ2DnH6gr26l4CwctWVxASzIBDGLJyXSU.jpg', 1, 0, 20, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-02 14:29:14', '2025-11-02 14:29:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 3, 'Spaghetti', 'Classic Italian spaghetti cooked to perfection and tossed in a flavorful tomato and herb sauce. Garnished with parmesan cheese and fresh basil for an authentic Italian taste. Simple, hearty, and satisfying.', 300.00, 'Italian', 'qtAhgB7bQtLzPaN6N63UtQJP6xe1eiyrCapuxbxs.jpg', 1, 0, 25, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-02 14:31:56', '2025-11-02 14:31:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 6, 'Margherita Pizza', 'Classic Italian pizza topped with fresh mozzarella, tomato sauce, and basil leaves. Simple, authentic, and delicious.', 600.00, 'Italian', 'zSfJQ5Fo3w8qsDLCvDC8IeQULHGA7P3HvGdVMyNv.jpg', 1, 0, 25, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 13:42:13', '2025-11-03 13:42:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 7, 'Cappuccino', 'A perfect balance of espresso, steamed milk, and milk foam. A creamy, rich coffee with a light frothy topping.', 250.00, 'Beverages', 'VZtHxi2MsGJSlD9ZcxHszowYOkuBzcHet8o87aoV.jpg', 1, 0, 7, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:14:15', '2025-11-03 14:14:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 7, 'Iced Latte', 'A refreshing iced coffee drink made with espresso and cold milk, served over ice for a smooth and chill experience.', 280.00, 'Beverages', 'hkPPWmMk0jfh4m757WcKXedwKU4dwb2talz0CDlx.jpg', 1, 0, 5, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:40:54', '2025-11-03 14:40:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 7, 'Mocha', 'A decadent mix of espresso, steamed milk, and rich chocolate syrup, topped with whipped cream. Perfect for chocolate lovers.', 230.00, 'Beverages', 'hw2XjcDnpa22UV1yc6NPJSbs0R7K0s7pzQ8WzlPx.webp', 1, 0, 10, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:44:01', '2025-11-03 14:44:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 7, 'Espresso', 'A strong, bold shot of pure coffee, made by forcing hot water through finely-ground coffee beans. The base for many coffee drinks.', 180.00, 'Beverages', 'oiCmvDei5O85MyPbsLb7oiLLWhA6NVzlxI3KMJ4U.webp', 1, 0, 5, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:48:41', '2025-11-03 14:48:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 6, 'Spicy Mexican Pizza', 'Fiery tomato sauce with jalapeños, minced beef, onions, and melted cheese for spice lovers.', 450.00, 'Italian', 'ttWhJMQCBMELV4mC6TO9LKKI5UnOqZ10bk6G4e9E.jpg', 1, 0, 19, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:52:24', '2025-11-03 14:52:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 6, 'Veggie Supreme Pizza', 'A healthy mix of bell peppers, mushrooms, olives, onions, and sweet corn on a thin crust base.', 500.00, 'Vegetarian', 'M0hTV4sK6ZZRsrzQEeSU8lMRiRIylTZYddmXDqF3.jpg', 1, 0, 17, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 14:55:02', '2025-11-03 14:55:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 5, 'Salmon Sushi Roll', 'Fresh salmon slices rolled with rice, seaweed, and a touch of wasabi — served with soy sauce and pickled ginger.', 380.00, 'Japanese', 'qpfzyVgUgvVDBQa6SDquyrdB2cg8vCf4MSygIu4O.jpg', 1, 0, 15, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 15:00:32', '2025-11-03 15:00:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 5, 'Chicken Shawarma Wrap', 'Marinated chicken slices wrapped with garlic sauce, lettuce, and pickles in soft pita bread.', 220.00, 'Middle Eastern', 'sTDieDi2Y73iaiO7zsHRArDdN2Qw3ksqEDOP0xx7.jpg', 1, 0, 12, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 15:03:35', '2025-11-03 15:03:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 5, 'Spicy Ramen Bowl', 'Japanese noodles in rich spicy broth with egg, vegetables, and sliced beef.', 380.00, 'Japanese', 'tvZPln8dKwoU6WUeFSAbYeDMLcgyvSJmYyaLexrA.jpg', 1, 0, 18, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 15:06:28', '2025-11-03 15:06:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 5, 'Chocolate Lava Cake', 'Warm chocolate cake with a gooey molten center, topped with vanilla ice cream.', 280.00, 'Desserts', 'Niq3io5DLUBoZdZDTIwSYIRlBKRpxCL5hV48XIPd.webp', 1, 0, 14, NULL, NULL, NULL, 0.00, 0, 0, '2025-11-03 15:09:37', '2025-11-03 15:09:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_25_043755_create_categories_table', 1),
(6, '2025_10_25_043813_create_restaurants_table', 1),
(7, '2025_10_25_043834_create_menus_table', 1),
(8, '2025_10_25_043849_create_addresses_table', 1),
(9, '2025_10_25_044459_create_riders_table', 1),
(10, '2025_10_25_044626_create_orders_table', 1),
(11, '2025_10_25_044647_create_order_items_table', 1),
(12, '2025_10_25_044658_create_reviews_table', 1),
(13, '2025_10_25_044709_create_favorites_table', 1),
(14, '2025_10_25_044721_create_coupons_table', 1),
(15, '2025_10_30_164550_create_payments_table', 1),
(16, '2025_10_30_164627_create_carts_table', 1),
(17, '2025_10_31_192203_add_status_to_restaurants_table', 2),
(18, '2025_10_31_195452_create_menu_items_table', 3),
(19, '2024_12_19_000000_add_flexible_fields_to_menu_items_table', 4),
(20, '2025_11_02_081026_create_delivery_riders_table', 5),
(21, '2025_11_02_112630_update_orders_table_structure', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `rider_id` bigint(20) UNSIGNED DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tip` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','preparing','ready','picked_up','on_the_way','delivered','cancelled','rejected') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','card','wallet','online') NOT NULL DEFAULT 'cash',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_transaction_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `delivery_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`delivery_address`)),
  `customer_phone` varchar(255) NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `estimated_delivery_time` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `prepared_at` timestamp NULL DEFAULT NULL,
  `preparing_at` timestamp NULL DEFAULT NULL,
  `ready_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `restaurant_id`, `rider_id`, `items`, `address_id`, `subtotal`, `delivery_fee`, `discount_amount`, `tax_amount`, `tip`, `total_amount`, `status`, `payment_method`, `payment_status`, `payment_transaction_id`, `transaction_id`, `delivery_address`, `customer_phone`, `special_instructions`, `estimated_delivery_time`, `cancellation_reason`, `scheduled_at`, `confirmed_at`, `prepared_at`, `preparing_at`, `ready_at`, `picked_up_at`, `delivered_at`, `cancelled_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 'ORD-1762110818-9942', 12, 3, NULL, '[{\"menu_item_id\":1,\"quantity\":1,\"price\":\"150.00\"},{\"menu_item_id\":10,\"quantity\":1,\"price\":\"280.00\"}]', NULL, 430.00, 10.00, 0.00, 0.00, 0.00, 440.00, 'delivered', 'cash', 'pending', NULL, NULL, '[\"Mirpur\"]', '01672387722', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-02 13:16:55', NULL, '2025-11-02 13:13:38', '2025-11-02 13:16:55', NULL),
(7, 'ORD-1762111520-7053', 12, 3, NULL, '[{\"menu_item_id\":11,\"quantity\":1,\"price\":\"340.00\"}]', NULL, 340.00, 10.00, 0.00, 0.00, 0.00, 350.00, 'delivered', 'cash', 'pending', NULL, NULL, '[\"Mirpur\"]', '01672387722', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-02 13:26:24', NULL, '2025-11-02 13:25:20', '2025-11-02 13:26:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text DEFAULT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(8,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `special_instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_method` enum('cash','card','wallet','online') NOT NULL DEFAULT 'cash',
  `payment_gateway` enum('stripe','paypal','razorpay','bkash','nagad','cash') DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'BDT',
  `status` enum('pending','processing','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `failure_reason` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(12, 'App\\Models\\User', 4, 'auth_token', '5905139a6522f4c672ec46b2bd9a22763b93f39fe198644d98ab900032d58680', '[\"*\"]', NULL, NULL, '2025-10-31 10:29:37', '2025-10-31 10:29:37'),
(13, 'App\\Models\\User', 4, 'auth_token', '7a4a7e5ee2d1765296ff57c5441e282646c58fff94dd3c42819a850018feb907', '[\"*\"]', NULL, NULL, '2025-10-31 10:29:47', '2025-10-31 10:29:47'),
(14, 'App\\Models\\User', 5, 'auth_token', '569908ecd47dd161a429c02e2b63b438dc764fc1b5c97b867c19d3c12ee6b710', '[\"*\"]', '2025-10-31 11:12:34', NULL, '2025-10-31 10:30:10', '2025-10-31 11:12:34'),
(16, 'App\\Models\\User', 2, 'auth_token', '02b3622c992b9f8921bc78f997781dddb1960d5a0de31a13d04021427e8d94fa', '[\"*\"]', '2025-10-31 11:13:25', NULL, '2025-10-31 11:13:19', '2025-10-31 11:13:25'),
(17, 'App\\Models\\User', 2, 'auth_token', '5512c64a999627319eac393ca1628a0079d8740d87c451bdad285f971dd954c7', '[\"*\"]', NULL, NULL, '2025-10-31 11:18:55', '2025-10-31 11:18:55'),
(19, 'App\\Models\\User', 2, 'auth_token', '003522b8e7eb55d24edce13903167089dac1e798a82d13b26b76a637e47a26fe', '[\"*\"]', '2025-10-31 11:29:02', NULL, '2025-10-31 11:25:09', '2025-10-31 11:29:02'),
(21, 'App\\Models\\User', 6, 'auth_token', '482147b4c95768f76c4a5aa60c6879b403938f8cab1936da01079339b1a1da69', '[\"*\"]', '2025-10-31 13:26:29', NULL, '2025-10-31 13:26:26', '2025-10-31 13:26:29'),
(22, 'App\\Models\\User', 6, 'auth_token', '849e201515e876225ca0cb6fd60d6dbb05a02a7af0ceb0ef1285d0d21450e2b8', '[\"*\"]', '2025-10-31 13:28:44', NULL, '2025-10-31 13:26:45', '2025-10-31 13:28:44'),
(23, 'App\\Models\\User', 7, 'auth_token', '8632cffd3de5a1cd529b8669ef79fe1fbf5612f27156955ef2868a1547ab6961', '[\"*\"]', '2025-10-31 13:33:19', NULL, '2025-10-31 13:29:25', '2025-10-31 13:33:19'),
(24, 'App\\Models\\User', 8, 'auth_token', '65df673c774441aada779d296e5c08bf78b8bad2d9c151cce280fe67275c1419', '[\"*\"]', '2025-10-31 13:47:43', NULL, '2025-10-31 13:33:59', '2025-10-31 13:47:43'),
(25, 'App\\Models\\User', 8, 'auth_token', 'bd007359f334bf342bc19f8d9fe216c9f8b01acda23ca6cdb9860b088b48bde8', '[\"*\"]', '2025-10-31 13:48:07', NULL, '2025-10-31 13:48:02', '2025-10-31 13:48:07'),
(26, 'App\\Models\\User', 8, 'auth_token', 'b97a9cb2f11ee0ddfb9fbec8980017d1ee246fb8471f2cf01a33c87ed38314c5', '[\"*\"]', '2025-10-31 14:08:42', NULL, '2025-10-31 13:50:09', '2025-10-31 14:08:42'),
(27, 'App\\Models\\User', 8, 'auth_token', '69723a5575fcca6203c0bbdadc5cd2fbbb0a6dc51b1eff9611c1b13ba637de06', '[\"*\"]', '2025-10-31 14:15:20', NULL, '2025-10-31 14:09:00', '2025-10-31 14:15:20'),
(34, 'App\\Models\\User', 1, 'auth_token', 'a14830f68d3e3951d4a8f962b1b43aba13e4f6b6c612f51e3aecc201d03f396c', '[\"*\"]', NULL, NULL, '2025-11-01 14:28:38', '2025-11-01 14:28:38'),
(39, 'App\\Models\\User', 11, 'auth_token', '73ff069a1aa40db98ec451356062963fd6db272f0b15b7872da76c12b330bd08', '[\"*\"]', '2025-11-02 13:18:11', NULL, '2025-11-02 02:24:13', '2025-11-02 13:18:11'),
(46, 'App\\Models\\User', 13, 'auth_token', '9829a47bf6f3bc7efa201926351693710beb6701a312a278ef9cdc6294847c49', '[\"*\"]', '2025-11-02 14:49:46', NULL, '2025-11-02 13:19:31', '2025-11-02 14:49:46'),
(53, 'App\\Models\\User', 11, 'auth_token', '3c3969dde3f9413b0b4dd415f74d66874f052520bdbfb4ad53dcbbb432de9cfd', '[\"*\"]', '2025-11-03 15:24:40', NULL, '2025-11-03 14:00:36', '2025-11-03 15:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cuisine_type` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `delivery_time` int(11) NOT NULL DEFAULT 30,
  `delivery_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `min_order_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `total_earnings` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `owner_id`, `name`, `slug`, `description`, `cuisine_type`, `address`, `city`, `postal_code`, `latitude`, `longitude`, `phone`, `email`, `logo`, `cover_image`, `rating`, `total_reviews`, `delivery_time`, `delivery_fee`, `min_order_amount`, `is_active`, `status`, `is_featured`, `is_verified`, `opening_time`, `closing_time`, `created_at`, `updated_at`, `deleted_at`, `total_earnings`) VALUES
(3, 8, 'Te_amo', 'Te-amo', 'hahahhahah', 'Other', '1375/4,Shahi Moshjid Road, South Dania, Dhaka-1236', 'Dhaka', '1236', NULL, NULL, '+8801884301122', 'jaforhridoy0@gmail.com', 'logo3.jpg', 'rest3.jpg', 0.00, 0, 30, 10.00, 20.00, 1, 'approved', 0, 0, '09:00:00', '22:00:00', '2025-10-31 13:34:46', '2025-11-01 16:44:00', NULL, 693.00),
(5, 10, 'Dumpling Den', 'dumpling-den', 'Welcome to Dumpling Den, where every bite tells a story of authentic Chinese taste. Enjoy handmade dumplings, flavorful noodles, and sizzling wok dishes — all served fresh in a cozy, modern setting perfect for food lovers and friends alike.', 'American', 'Gulshan', 'Dhaka', '1236', NULL, NULL, '01715096689', 'ayash@gmail.com', 'logo2.jpg', 'rest2.jpg', 0.00, 0, 30, 80.00, 300.00, 1, 'approved', 0, 0, '09:00:00', '22:00:00', '2025-11-01 13:32:31', '2025-11-01 16:35:12', NULL, 594.00),
(6, 14, 'Bella Italia Pizzeria', 'bella-italia-pizzeria', 'Wooden oven pizza with melted cheese and basil leaves.', 'Mexican', 'Old Dhaka', 'Dhaka', '1236', NULL, NULL, '01637238723', 'ahnaf@gmail.com', 'PVic8FhzXpLNW29qvkNbqlEQ1B4JwPBXB7AG07eQ.png', 'RXHy74ZThpbcIcqoN6De31O0TzosWDNRrBJNpizQ.webp', 0.00, 0, 30, 80.00, 300.00, 1, 'pending', 0, 0, '09:00:00', '22:00:00', '2025-11-03 13:05:03', '2025-11-03 13:11:07', NULL, 540.00),
(7, 15, 'Cafe Mocha Lounge', 'cafe-mocha-lounge', 'Coffee cups, pastries, and a warm café ambiance with hanging lights.', 'Other', 'Badda', 'Dhaka', NULL, NULL, NULL, '01784785724', 'eti@gmail.com', 'logo6.jpg', 'green-di.ning-ambiance-stockcake.jpg', 0.00, 0, 30, 80.00, 300.00, 1, 'pending', 0, 0, '09:00:00', '22:00:00', '2025-11-03 14:09:32', '2025-11-03 14:10:15', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `rider_rating` tinyint(4) DEFAULT NULL,
  `rider_comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `admin_response` text DEFAULT NULL,
  `admin_responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_type` enum('bicycle','motorcycle','car','scooter') NOT NULL DEFAULT 'motorcycle',
  `vehicle_number` varchar(50) DEFAULT NULL,
  `vehicle_model` varchar(100) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `license_image` varchar(255) DEFAULT NULL,
  `id_card_number` varchar(100) DEFAULT NULL,
  `id_card_image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `location_updated_at` timestamp NULL DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_deliveries` int(11) NOT NULL DEFAULT 0,
  `completed_deliveries` int(11) NOT NULL DEFAULT 0,
  `cancelled_deliveries` int(11) NOT NULL DEFAULT 0,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('customer','restaurant_owner','rider','admin') NOT NULL DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `total_earnings` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `phone_verified_at`, `role`, `avatar`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `total_earnings`) VALUES
(2, 'Admin', 'admin@foodeli.com', '2025-10-31 07:15:30', '$2y$10$81TNRh5//R2N0hruqNYJyeCfS9DZgZMtBxtddM3SNHpJUMbATWPTO', NULL, NULL, 'admin', NULL, 'active', NULL, '2025-10-31 07:15:30', '2025-10-31 07:15:30', NULL, 467.00),
(3, 'hasan', 'hasan@gmail.com', NULL, '$2y$10$tuGW0xmQD5kDOQsZd7cPiO5vAd4f5c/6KTmLn7F0YlPRVcuZvRnlK', NULL, NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-10-31 10:22:42', '2025-10-31 10:22:42', NULL, 0.00),
(4, 'Test Customer', 'test@example.com', NULL, '$2y$10$CaI2CNTLE.EI8cZbxwPSBu4V7wnxoY3ey/GGzXtbQA1xXoYEK667q', NULL, NULL, 'customer', NULL, 'active', NULL, '2025-10-31 10:29:37', '2025-10-31 10:29:37', NULL, 0.00),
(5, 'araman', 'arman@gmail.com', NULL, '$2y$10$ObjfF2mkQxrvZjOEaXPw7OOyhntOAEzgDbf8irGa0kjqCzoaWbG0C', '+8801986994911', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-10-31 10:30:10', '2025-10-31 10:30:10', NULL, 0.00),
(7, 'hridoy', 'jaforhridoy@gmail.com', NULL, '$2y$10$wxt1PT9opgdQFb2wVzJn9uI3SLBLbkMqNTmSyqvDZzs9UkTZEwPyS', '+8801884301123', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-10-31 13:29:25', '2025-10-31 13:29:25', NULL, 0.00),
(8, 'nila', 'jaforhridoy0@gmail.com', NULL, '$2y$10$/GkgtyD1FsYyq4nN0JJoxeDDiIfArVnGlArSmRi04KcZdFXHNLQbS', '+8801884301124', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-10-31 13:33:59', '2025-10-31 13:33:59', NULL, 0.00),
(10, 'Ayash Hasan', 'ayash@gmail.com', NULL, '$2y$10$rEwiSe9bNBh7cm6/DKMkieu6CXoBze9hbh0Stn9K2WCi79GZ7OH0q', '01715096689', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-11-01 13:28:08', '2025-11-01 13:28:08', NULL, 0.00),
(11, 'manish', 'manish@gmail.com', NULL, '$2y$10$DjZ32awDwjKryFkb/jv.oOB55x2NUqlc6IFzNd5h4PEQs.5/K3NHm', '+8801873824700', NULL, 'rider', NULL, 'active', NULL, '2025-11-02 02:24:13', '2025-11-02 02:24:13', NULL, 0.00),
(12, 'Afroja', 'afroja@gmail.com', NULL, '$2y$10$zvnOfJB188EDoTZ27rSSye4/urILBLl3hKpUBiIWKBd.Gkfk4e2KC', '0167277217361', NULL, 'customer', NULL, 'active', NULL, '2025-11-02 13:12:14', '2025-11-02 13:12:14', NULL, 0.00),
(13, 'Araf Hasan', 'araf@gmail.com', NULL, '$2y$10$3IIVunqYQpiSncA8pOLB9.90RIsei4WN6QhM9TVBKIM9vQyyibPPG', '01921088798', NULL, 'rider', NULL, 'active', NULL, '2025-11-02 13:19:31', '2025-11-02 13:19:31', NULL, 0.00),
(14, 'Ahnaf Ahmed', 'ahnaf@gmail.com', NULL, '$2y$10$FqduHKaxoqx3sexhKTkrqOm3uPqfSPOSXJp2dwMCfNoBYRcdOFhti', '01637834483', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-11-03 12:47:19', '2025-11-03 12:47:19', NULL, 0.00),
(15, 'Eti Ahmed', 'eti@gmail.com', NULL, '$2y$10$/P6UDdZ/BADYd9rVpx32p.Cy0z6je1vNmRl.6kJTBCfRKvLJk0y9W', '0177468356', NULL, 'restaurant_owner', NULL, 'active', NULL, '2025-11-03 14:06:31', '2025-11-03 14:06:31', NULL, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_index` (`user_id`),
  ADD KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_menu_id_foreign` (`menu_id`),
  ADD KEY `carts_restaurant_id_foreign` (`restaurant_id`),
  ADD KEY `carts_user_id_index` (`user_id`),
  ADD KEY `carts_session_id_index` (`session_id`),
  ADD KEY `carts_user_id_restaurant_id_index` (`user_id`,`restaurant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_slug_index` (`slug`),
  ADD KEY `categories_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`),
  ADD KEY `coupons_code_index` (`code`),
  ADD KEY `coupons_is_active_valid_from_valid_until_index` (`is_active`,`valid_from`,`valid_until`);

--
-- Indexes for table `delivery_riders`
--
ALTER TABLE `delivery_riders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_riders_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_restaurant_id_unique` (`user_id`,`restaurant_id`),
  ADD KEY `favorites_user_id_index` (`user_id`),
  ADD KEY `favorites_restaurant_id_index` (`restaurant_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_restaurant_id_index` (`restaurant_id`),
  ADD KEY `menus_category_id_index` (`category_id`),
  ADD KEY `menus_is_available_index` (`is_available`),
  ADD KEY `menus_is_featured_index` (`is_featured`),
  ADD KEY `menus_restaurant_id_category_id_index` (`restaurant_id`,`category_id`),
  ADD KEY `menus_slug_index` (`slug`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_restaurant_id_foreign` (`restaurant_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_user_id_index` (`customer_id`),
  ADD KEY `orders_restaurant_id_index` (`restaurant_id`),
  ADD KEY `orders_rider_id_index` (`rider_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_user_id_status_index` (`customer_id`,`status`),
  ADD KEY `orders_restaurant_id_status_index` (`restaurant_id`,`status`),
  ADD KEY `orders_rider_id_status_index` (`rider_id`,`status`),
  ADD KEY `orders_created_at_index` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_menu_id_index` (`menu_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_index` (`order_id`),
  ADD KEY `payments_user_id_index` (`user_id`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`),
  ADD KEY `payments_status_index` (`status`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurants_slug_unique` (`slug`),
  ADD KEY `restaurants_owner_id_index` (`owner_id`),
  ADD KEY `restaurants_slug_index` (`slug`),
  ADD KEY `restaurants_is_active_is_featured_index` (`is_active`,`is_featured`),
  ADD KEY `restaurants_city_index` (`city`),
  ADD KEY `restaurants_rating_index` (`rating`),
  ADD KEY `restaurants_latitude_longitude_index` (`latitude`,`longitude`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_order_id_user_id_unique` (`order_id`,`user_id`),
  ADD KEY `reviews_user_id_index` (`user_id`),
  ADD KEY `reviews_restaurant_id_index` (`restaurant_id`),
  ADD KEY `reviews_order_id_index` (`order_id`),
  ADD KEY `reviews_restaurant_id_is_approved_index` (`restaurant_id`,`is_approved`),
  ADD KEY `reviews_rating_index` (`rating`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riders_user_id_index` (`user_id`),
  ADD KEY `riders_is_available_is_verified_index` (`is_available`,`is_verified`),
  ADD KEY `riders_current_latitude_current_longitude_index` (`current_latitude`,`current_longitude`),
  ADD KEY `riders_rating_index` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_phone_index` (`phone`),
  ADD KEY `users_role_index` (`role`),
  ADD KEY `users_status_index` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_riders`
--
ALTER TABLE `delivery_riders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_riders`
--
ALTER TABLE `delivery_riders`
  ADD CONSTRAINT `delivery_riders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menus_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_rider_id_foreign` FOREIGN KEY (`rider_id`) REFERENCES `riders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riders`
--
ALTER TABLE `riders`
  ADD CONSTRAINT `riders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
