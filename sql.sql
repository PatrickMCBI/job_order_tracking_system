-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: job_order_system
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jo_id` int DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jo_id` (`jo_id`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`jo_id`) REFERENCES `job_orders` (`id`),
  CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Pending Layout',2,'2025-11-25 01:32:13'),(2,2,'Pending Layout',2,'2025-11-25 01:38:16'),(3,3,'Pending Layout',2,'2025-11-25 01:53:11'),(4,4,'Pending Layout',2,'2025-11-25 02:05:45'),(5,5,'Pending Layout',2,'2026-01-06 02:44:35'),(6,6,'Pending Layout',2,'2026-01-06 14:10:57'),(7,6,'In Layout',3,'2026-01-27 05:06:43'),(8,6,'In Layout',3,'2026-01-27 05:07:52'),(9,6,'Ready for Printing',3,'2026-01-27 05:07:57'),(10,5,'In Layout',3,'2026-01-27 05:08:03'),(11,4,'In Layout',3,'2026-01-27 05:08:05'),(12,3,'In Layout',3,'2026-01-27 05:08:06'),(13,2,'In Layout',3,'2026-01-27 05:08:08'),(14,1,'In Layout',3,'2026-01-27 05:08:09');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_orders`
--

DROP TABLE IF EXISTS `job_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jo_number` varchar(255) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `contact_num` varchar(15) DEFAULT NULL,
  `date_ordered` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `item_description` text,
  `file_upload` text,
  `product_type` int DEFAULT NULL,
  `team_name` varchar(50) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending Layout',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `job_orders_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_orders`
--

LOCK TABLES `job_orders` WRITE;
/*!40000 ALTER TABLE `job_orders` DISABLE KEYS */;
INSERT INTO `job_orders` VALUES (1,'0000001','sample name','09475401808','2025-11-25','2025-11-30','EXAMPLE','uploads/mockups/1764034333_Screenshot 2025-09-18 145531.png',1,'0',2,'In Layout',2,'2025-11-25 01:32:13',NULL),(2,'0000002','customer two','09475401808','2025-11-26','2025-12-01','Example two','uploads/mockups/1764034696_Screenshot 2025-09-22 210338.png',1,'0',4,'In Layout',2,'2025-11-25 01:38:16',NULL),(3,'0000003','three','09475401807','2025-11-27','2025-12-02','this is new','uploads/mockups/1764035591_Screenshot 2025-10-23 081854.png',2,'0',2,'In Layout',2,'2025-11-25 01:53:11',NULL),(4,'0000004','three','09475401808','2025-11-25','2025-11-27','new','uploads/mockups/1764036345_Screenshot 2025-09-26 213634.png',2,'Team Name Sample',1,'In Layout',2,'2025-11-25 02:05:45',NULL),(5,'0000005','new name','09122112112','2026-01-06','2026-01-18','This is example team 2026','uploads/mockups/1767667475_png-clipart-roblox-youtube-internet-meme-humour-youtube-child-toy-block-thumbnail.png',1,'New Team 2026',2,'In Layout',2,'2026-01-06 02:44:35',NULL),(6,'0000006','4','09475401808','2026-01-06','2026-01-14','adsfdsafdsfasf','uploads/mockups/1767708657_Gemini_Generated_Image_hotu6chotu6chotu.png',1,'2026',1,'Ready for Printing',2,'2026-01-06 14:10:57',NULL);
/*!40000 ALTER TABLE `job_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_orders_lineup`
--

DROP TABLE IF EXISTS `job_orders_lineup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_orders_lineup` (
  `jo_lineup_id` int NOT NULL AUTO_INCREMENT,
  `jo_id` int DEFAULT NULL,
  `jo_lineup_name` varchar(20) DEFAULT NULL,
  `jo_lineup_jersey_no` int DEFAULT NULL,
  `jo_lineup_size` varchar(20) DEFAULT NULL,
  `jo_lineup_gender` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`jo_lineup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_orders_lineup`
--

LOCK TABLES `job_orders_lineup` WRITE;
/*!40000 ALTER TABLE `job_orders_lineup` DISABLE KEYS */;
INSERT INTO `job_orders_lineup` VALUES (1,2,'team ones',1,'Medium','Male'),(2,2,'team two',2,'XS','Male'),(3,2,'team three',3,'Small','Female'),(4,2,'team four',4,'Large','Unisex'),(5,3,'one',1,'Medium','Male'),(6,3,'two',2,'Large','Male'),(7,4,'Tone',1,'Medium','Male'),(8,5,'1',1,'Small','Male'),(9,5,'2',2,'Medium','Female'),(10,6,'5',5,'XS','Male');
/*!40000 ALTER TABLE `job_orders_lineup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `job_order_id` (`job_order_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$SO.Alp/1ZYSaIxNUjMDlxee0EiFMSelOEWsCeFrCaCuktD0SAJS.y','admin','2025-11-24 01:24:51'),(2,'encoder','$2y$10$lKyp1HWwd0sLClcntncRk.q1G7XCPTQ1HKY3KC9I9toz67vqkFAzy','encoder','2025-11-24 02:22:12'),(3,'artist','$2y$10$5OFplGsyWx46xlPTvsrnGOQamgKloPsgaoWJKPghEnb2xTQfzIuXS','artist','2025-11-24 03:58:22'),(4,'printer','$2y$10$kuJWyW10B3wjdC3Sk4WIHuyTVYiPfqEj38dTpv0Ii9.ck97zP6g0q','printer','2025-11-24 03:58:44'),(5,'heatpress','$2y$10$eruWXMb5Di/jiKZKof4xyudwZ8iOH6nZPmCU1t6oRVIMWCzTTN.u2','heatpress','2025-11-24 03:59:01'),(6,'cutting','$2y$10$4O4NkoNhDGmGh5paQF8g9utOzL7yaWOtj4bBwnYbhhNxaKDNFEnMu','cutting','2025-11-24 03:59:16'),(7,'sewing','$2y$10$WC6cpkRKN5tU8Wayk4DDvO118v59BV7cPlAXyWBGGDHZv9sBbnlSa','sewing','2025-11-24 03:59:33'),(8,'qc','$2y$10$sbiCOAUvNVERZ/dJgWnOo.cfuHgY0l./KpQHjbIqukzc31e2miaAK','qc','2025-11-24 03:59:43'),(9,'sales','$2y$10$3nCIiGykahXcW.oMUOXJce.o.zZD79th/LmC9heLWpE3jTCn.zY6.','sales','2025-11-24 04:00:01');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-27 13:55:42
