-- PesutTrip Master Database Schema
-- Optimized for clean installs with DROP TABLE protection

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `users`;

-- Table structure for `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin: admin@pesuttrip.com / admin123
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Administrator', 'admin@pesuttrip.com', '$2y$10$W2ikHdCG/M04j7tIp5frLu.bFxDoShHgT4eZnjilGYKE5S0SEfSIq', 'admin');

-- --------------------------------------------------------

-- Table structure for `destinations`
CREATE TABLE `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT 'Alam',
  `location` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `price` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `destinations`
INSERT INTO `destinations` (`name`, `image`, `category`, `location`, `rating`, `price`, `description`) VALUES
('Air Terjun Pinang Seribu', 'assets/img/Air terjun.jpg', 'Alam', 'Samarinda Utara', 4.5, 15000, 'Nikmati keindahan Air Terjun Pinang Seribu di Samarinda Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Masjid Tua Shiratal Mustaqiem', 'assets/img/Masjid Tua Shiratal Mustaqiem.jpg', 'Budaya', 'Samarinda Seberang', 4.8, 0, 'Nikmati keindahan Masjid Tua Shiratal Mustaqiem di Samarinda Seberang. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Bukit Steling Selili', 'assets/img/bukitsteling.jpg', 'Alam', 'Samarinda Ulu', 4.6, 5000, 'Nikmati keindahan Bukit Steling Selili di Samarinda Ulu. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Citra Niaga Kota Lama', 'assets/img/citra niaga.jpeg', 'Kuliner', 'Samarinda Kota', 4.7, 0, 'Nikmati keindahan Citra Niaga Kota Lama di Samarinda Kota. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Desa Budaya Pampang', 'assets/img/desa budaya pampang.jpg', 'Budaya', 'Samarinda Utara', 4.9, 25000, 'Nikmati keindahan Desa Budaya Pampang di Samarinda Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Islamic Center Samarinda', 'assets/img/islamic center Samarinda.jpg', 'Budaya', 'Teluk Lerong Ulu', 4.9, 0, 'Nikmati keindahan Islamic Center Samarinda di Teluk Lerong Ulu. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Jessica Water Park', 'assets/img/jesica water park.jpg', 'Alam', 'Samarinda Utara', 4.3, 35000, 'Nikmati keindahan Jessica Water Park di Samarinda Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Jungle Water World', 'assets/img/jungle water word.jpg', 'Alam', 'Samarinda Utara', 4.4, 40000, 'Nikmati keindahan Jungle Water World di Samarinda Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Kampung Tenun Samarinda', 'assets/img/kampungtenun.jpg', 'Budaya', 'Samarinda Seberang', 4.6, 0, 'Nikmati keindahan Kampung Tenun Samarinda di Samarinda Seberang. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Klenteng Thien Le Kong', 'assets/img/klenteng thien le kong.jpg', 'Budaya', 'Samarinda Kota', 4.7, 0, 'Nikmati keindahan Klenteng Thien Le Kong di Samarinda Kota. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Mahakam Lampion Garden', 'assets/img/mahakam lampion garden.jpg', 'Alam', 'Tepian Mahakam', 4.5, 15000, 'Nikmati keindahan Mahakam Lampion Garden di Tepian Mahakam. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Pulau Kumala', 'assets/img/pulau kumala.jpg', 'Alam', 'Tenggarong', 4.4, 10000, 'Nikmati keindahan Pulau Kumala di Tenggarong. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Rumah Ulin Arya', 'assets/img/rumah ulin arya.jpg', 'Alam', 'Samarinda Utara', 4.7, 60000, 'Nikmati keindahan Rumah Ulin Arya di Samarinda Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Taman Samarendah', 'assets/img/taman samarendah.jpg', 'Kuliner', 'Samarinda Kota', 4.4, 0, 'Nikmati keindahan Taman Samarendah di Samarinda Kota. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Tepian Mahakam Samarinda', 'assets/img/tepian samarinda.jpg', 'Alam', 'Samarinda Kota', 4.5, 0, 'Nikmati keindahan Tepian Mahakam Samarinda di Samarinda Kota. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Vihara Eka Dharma Manggala', 'assets/img/vihara eka dharma.jpg', 'Budaya', 'Samarinda Ulu', 4.6, 0, 'Nikmati keindahan Vihara Eka Dharma Manggala di Samarinda Ulu. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Kepulauan Derawan', 'assets/img/dest_derawan.png', 'Alam', 'Berau', 5.0, 250000, 'Nikmati keindahan Kepulauan Derawan di Berau. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Kutai National Park', 'assets/img/kutai nation park.jpg', 'Alam', 'Kutai Timur', 4.9, 50000, 'Nikmati keindahan Kutai National Park di Kutai Timur. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Gua Telapak Tangan', 'assets/img/gua_telapak_tangan.png', 'Alam', 'Kutai Timur', 4.8, 30000, 'Nikmati keindahan Gua Telapak Tangan di Kutai Timur. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Watu Beach', 'assets/img/hero_kalimantan.png', 'Alam', 'Balikpapan', 4.7, 20000, 'Nikmati keindahan Watu Beach di Balikpapan. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Pantai Lamaru', 'assets/img/feature_kalimantan.png', 'Alam', 'Balikpapan', 4.5, 15000, 'Nikmati keindahan Pantai Lamaru di Balikpapan. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Pantai Manggar Segara Sari', 'assets/img/hero_bg.jpg', 'Alam', 'Balikpapan', 4.6, 10000, 'Nikmati keindahan Pantai Manggar Segara Sari di Balikpapan. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Pulau Beras Basah', 'assets/img/dest_derawan.png', 'Alam', 'Bontang', 4.8, 50000, 'Nikmati keindahan Pulau Beras Basah di Bontang. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Bontang Mangrove Park', 'assets/img/enggang.jpg', 'Alam', 'Bontang', 4.4, 10000, 'Nikmati keindahan Bontang Mangrove Park di Bontang. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Danau Ubur-Ubur Kakaban', 'assets/img/dest_derawan.png', 'Alam', 'Berau', 4.9, 150000, 'Nikmati keindahan Danau Ubur-Ubur Kakaban di Berau. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Gua Haji Mangku', 'assets/img/gua_telapak_tangan.png', 'Alam', 'Berau', 4.7, 30000, 'Nikmati keindahan Gua Haji Mangku di Berau. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Danau Labuan Cermin', 'assets/img/dest_derawan.png', 'Alam', 'Berau', 5.0, 50000, 'Nikmati keindahan Danau Labuan Cermin di Berau. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Desa Wisata Pela', 'assets/img/tepian samarinda.jpg', 'Alam', 'Kutai Kartanegara', 4.8, 20000, 'Nikmati keindahan Desa Wisata Pela di Kutai Kartanegara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Bukit Kebo', 'assets/img/bukitsteling.jpg', 'Alam', 'Balikpapan', 4.4, 5000, 'Nikmati keindahan Bukit Kebo di Balikpapan. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Hutan Lindung Sungai Wain', 'assets/img/kutai nation park.jpg', 'Alam', 'Balikpapan', 4.6, 25000, 'Nikmati keindahan Hutan Lindung Sungai Wain di Balikpapan. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Air Terjun Jantur Mapan', 'assets/img/Air terjun.jpg', 'Alam', 'Kutai Barat', 4.5, 10000, 'Nikmati keindahan Air Terjun Jantur Mapan di Kutai Barat. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Goa Tapak Raja', 'assets/img/gua_telapak_tangan.png', 'Alam', 'Penajam Paser Utara', 4.4, 15000, 'Nikmati keindahan Goa Tapak Raja di Penajam Paser Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Ladang Budaya Tenggarong', 'assets/img/desa budaya pampang.jpg', 'Budaya', 'Kutai Kartanegara', 4.7, 15000, 'Nikmati keindahan Ladang Budaya Tenggarong di Kutai Kartanegara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Ibu Kota Nusantara (IKN)', 'assets/img/hero_kalimantan.png', 'Budaya', 'Penajam Paser Utara', 4.9, 0, 'Nikmati keindahan Ibu Kota Nusantara (IKN) di Penajam Paser Utara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Samboja Lodge Orangutan', 'assets/img/hero_kalimantan.png', 'Alam', 'Kutai Kartanegara', 4.8, 100000, 'Nikmati keindahan Samboja Lodge Orangutan di Kutai Kartanegara. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Titik Nol Nusantara', 'assets/img/hero_kalimantan.png', 'Budaya', 'IKN', 5.0, 0, 'Nikmati keindahan Titik Nol Nusantara di IKN. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.'),
('Pantai Panrita Lopi', 'assets/img/dest_derawan.png', 'Alam', 'Muara Badak', 4.6, 10000, 'Nikmati keindahan Pantai Panrita Lopi di Muara Badak. Pengalaman liburan yang tak terlupakan di Kalimantan Timur.');

-- --------------------------------------------------------

-- Table structure for `bookings`
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `visitors` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('pending','confirmed','approved','rejected','cancelled') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for `reviews`
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `reply_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
