-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_penjualan_dti.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.categories: ~3 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
	(3, 'Tepung', 'tepung beras mama'),
	(6, 'Telur', 'telur 1 kg'),
	(7, 'zahid', 'keterangan\r\n'),
	(10, 'Aqua', 'aqua');

-- Dumping structure for table db_penjualan_dti.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.customers: ~1 rows (approximately)
INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`) VALUES
	(1, 'zahidin', 'zahid@gmail.com', '64565757', 'prd, sindanglaut');

-- Dumping structure for table db_penjualan_dti.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.products: ~8 rows (approximately)
INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `photo`) VALUES
	(1, 'Beras', '1 kg', 10000.00, NULL, NULL),
	(2, 'zahid', 'asdada', 120000.00, 3, NULL),
	(4, 'zahid', 'dada', 120000.00, 3, NULL),
	(6, 'Mesin Penggiling', 'adada', 120000.00, NULL, NULL),
	(7, 'administrasi', 'asdad', 120000.00, NULL, 'WhatsApp_Image_2024-08-28_at_17_45_13_63f14e16.jpg'),
	(8, 'zahid', 'adada', 120000.00, 3, 'logo-fontCarterOne-1_(1).png'),
	(9, 'zahid', 'asdada', 120000.00, 10, 'Lembar_Kerja_IPAS_Denah_Biru_Putih_Hitam_Ilustratif.jpg');

-- Dumping structure for table db_penjualan_dti.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `sale_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.sales: ~1 rows (approximately)
INSERT INTO `sales` (`id`, `customer_id`, `sale_date`, `total_amount`) VALUES
	(1, 1, '2024-09-28 11:58:23', 1000000.00);

-- Dumping structure for table db_penjualan_dti.sale_details
CREATE TABLE IF NOT EXISTS `sale_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sale_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `sale_details_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.sale_details: ~1 rows (approximately)
INSERT INTO `sale_details` (`id`, `sale_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 1, 10, 100000.00);

-- Dumping structure for table db_penjualan_dti.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan_dti.users: ~9 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `name`) VALUES
	(1, 'andika123', '$2y$10$hG6kNyPoNZmhK4krxlZ6WeDbcPLAQE.JqDWy8dJSD8ZYHj/6R7Jue', 'Andika Pratama'),
	(2, 'sari234', '$2y$10$SZR9LEDJWA//k.ftlIn9tuXz2Oy36PK7JNyYhsru/a38QOohv/OSK', 'Sari Ramadhani'),
	(3, 'budi456', '$2y$10$vd.TgJc1Fx6uh0qrJYUAnOO/ANRBb8dUt0BnDk3Ijgy62sclUGrA2', 'Budi Santoso'),
	(4, 'ratna789', '$2y$10$nQILVFxKWRH2TroVvPSp5e5yQZqF2ejk8SZhN8SQltpez0X1agDDu', 'Ratna Sari'),
	(5, 'putra321', '$2y$10$xGKr3B5vtpxIx89wi51Qm.yojGX/vc/2othm5RTfX8VtcBgnateIq', 'Putra Wibowo'),
	(6, 'dian654', '$2y$10$p59Fsd39L/ViR5teACdDROHvDZwPNSpHZNxLXLWHFX7V3amfW2uEm', 'Dian Kusuma'),
	(7, 'agus987', '$2y$10$SEHMqw/Yo4v9m7kQsuceFeeEDSXLF2Zs6wEwMPw3wqEXCZXUCcZdq', 'Agus Setiawan'),
	(8, 'intan741', '$2y$10$DK4azvVnpTrAlOWvsT9NZ.Ago/JJET8qmU7N/TdgGPSdNEwmsLVKC', 'Intan Permata'),
	(11, 'zahid', '$2y$10$hIfwn2RWLKdQ8OCFNSDxG.lJP1Thc3HhBsH4baeg3bNXgToxm6YXe', 'zahid');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
