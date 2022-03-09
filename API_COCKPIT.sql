-- MySQL dump 10.13  Distrib 8.0.22, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: api_cockpit
-- ------------------------------------------------------
-- Server version	5.7.35

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
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'CCM','2021-08-19 14:30:34','2021-08-19 14:30:34',NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_companies_table',1),(2,'2014_10_12_000001_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2021_08_19_142134_create_modules_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'zoho desk','zoho-desk',1,'2021-08-19 14:30:34','2021-08-19 14:30:34',NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('admin@example.com','$2y$10$U5WD0fbxrGX3uszJ1wUEGO0OAUqsyLYnkgn8aAG009d/fMOQV9VRi','2021-08-23 14:12:49'),('fecostadev@gmail.com','$2y$10$U4FyFi2bvQWFYPlhMpc0p./lOjzWC07aPRy4nyk1Y21jc2mLGgIx2','2021-08-24 09:40:34');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (19,'App\\Models\\User',1,'auth_token','d30ee719bc38c9ee5dfa4e88629452df14d5d6c18f825c0464cbb62d0e2ea9d8','[\"*\"]',NULL,'2021-08-23 14:26:43','2021-08-23 14:26:43'),(20,'App\\Models\\User',1,'admin@example.com','403e32a48cf566d6ce226aafdfcd92b9c2173b3670707f026e5540b883925789','[\"*\"]','2021-08-24 01:01:33','2021-08-23 14:26:43','2021-08-24 01:01:33'),(21,'App\\Models\\User',2,'auth_token','6b131f123a719df2111108c5ac0cf4a77a62e5431a955afb2620ab13798b5713','[\"*\"]',NULL,'2021-08-23 14:37:05','2021-08-23 14:37:05'),(22,'App\\Models\\User',2,'fecostadev@gmail.com','00d9433cb5b64bbba59225ad3d1c8716e84fdf3ff205853713848798b846ec3d','[\"*\"]',NULL,'2021-08-23 14:37:05','2021-08-23 14:37:05'),(23,'App\\Models\\User',1,'auth_token','f4a16f70e21f62b5583bc6d58082fd87780198edf257ef2134918e75be3bb869','[\"*\"]',NULL,'2021-08-23 14:54:19','2021-08-23 14:54:19'),(24,'App\\Models\\User',1,'admin@example.com','8660c8930d65e737190f04c79bd0acb8902900bb6c4dce7d9a0010337f7acc5f','[\"*\"]','2021-08-23 14:55:02','2021-08-23 14:54:19','2021-08-23 14:55:02'),(25,'App\\Models\\User',2,'auth_token','ea82aa80eb9159384f3bcbd11abe50a3779b8d58cc65a766e514bb918a59a0bb','[\"*\"]',NULL,'2021-08-23 22:43:03','2021-08-23 22:43:03'),(26,'App\\Models\\User',2,'fecostadev@gmail.com','ed09144217b3634747c6014dd58530ac4850a11284cf57577dc0008fc8c0518d','[\"*\"]','2021-08-24 15:31:07','2021-08-23 22:43:03','2021-08-24 15:31:07');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `financeiro` tinyint(1) NOT NULL DEFAULT '0',
  `investimentos` tinyint(1) NOT NULL DEFAULT '0',
  `monitoramento` tinyint(1) NOT NULL DEFAULT '0',
  `tickets` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_company_id_foreign` (`company_id`),
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@example.com',NULL,'$2y$10$iP5VkiIjoLoqze.YRqbfwOF3sPhB91TdmszrLFsaxPEowtkCHOIQC',1,1,'zJWJhLBFX2dJEsFzesZmYY1Xy50bv60jF8BA0CBBYh8I0JaKkwPsjjCIQxJl','2021-08-19 14:30:34','2021-08-23 14:03:23'),(2,'Fernando Novo1','fecostadev@gmail.com',NULL,'$2y$10$z5kbC0BqXX9Bvt1AwWvP..JFPsNfLUPhEU.SzXPN24dJJoVr02Zwi',1,1,'jOVxftHB8yqZbgxz8PvPoCpSUUcKwJmH4UjTsahZyilB1C2QNfRKEUUByIzj','2021-08-23 14:35:16','2021-08-24 15:31:07'),(3,'Fernando Novo','fecostadesousa2@gmail.com',NULL,'123456789',0,1,NULL,'2021-08-23 14:55:02','2021-08-23 14:55:02'),(4,'Fernando','fecostadev2@gmail.com',NULL,'123456789',0,1,NULL,'2021-08-24 00:07:08','2021-08-24 00:07:08'),(5,'Fernando','fecostadev3@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:09:54','2021-08-24 00:09:54'),(6,'Fernando','fecostadev23@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:11:27','2021-08-24 00:11:27'),(7,'Fernando','fecostadev223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:16:07','2021-08-24 00:16:07'),(8,'Fernando','fecostadev2223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:16:54','2021-08-24 00:16:54'),(9,'Fernando','fecostadev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:17:55','2021-08-24 00:17:55'),(10,'Fernando','feco2stadev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:30:08','2021-08-24 00:30:08'),(11,'Fernando','feco2sta1dev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:32:37','2021-08-24 00:32:37'),(12,'Fernando','feco2s23ta1dev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:35:56','2021-08-24 00:35:56'),(13,'Fernando','feco2s23ta12dev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:39:31','2021-08-24 00:39:31'),(14,'Fernando','feco2s23ta1as2dev2s223@gmail.com',NULL,'admin123',0,1,NULL,'2021-08-24 00:40:00','2021-08-24 00:40:00');
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

-- Dump completed on 2021-08-25 17:41:54
