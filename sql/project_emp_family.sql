-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: project
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `emp_family`
--

DROP TABLE IF EXISTS `emp_family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emp_family` (
  `family_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(255) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `family_birth` date DEFAULT NULL,
  `family_relationship` varchar(255) DEFAULT NULL,
  `family_nationality` varchar(255) DEFAULT NULL,
  `family_work` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`family_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `emp_family_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_family`
--

LOCK TABLES `emp_family` WRITE;
/*!40000 ALTER TABLE `emp_family` DISABLE KEYS */;
INSERT INTO `emp_family` VALUES (3,'12345','123','2023-08-19','부','foreign','employed'),(4,'12345','234','2023-08-03','모','domestic','unemployed'),(31,'1234','qwqwqwqwq','2022-10-20','부','foreign','employed'),(32,'1234','asaasas','2023-08-19','모','domestic','unemployed'),(34,'1805025','가가가','2023-08-04','부','domestic','employed'),(35,'1805025','나나나','2023-08-11','모','foreign','unemployed'),(36,'1805025','다다다','2023-08-12','자','domestic','unemployed'),(38,'18181818','테스트1','2023-05-11','부','foreign','employed'),(39,'123418','1212','2023-08-18','부','foreign','employed'),(40,'1818','345345','2023-08-19','부','domestic','employed'),(41,'19887','시연테스트1','2023-10-02','부','foreign','employed'),(42,'12124545','시연12','2023-10-02','부','foreign','employed'),(45,'1805022','가나다','2023-12-07','부','domestic','unemployed'),(46,'10348','가나다','2023-12-21','모','foreign','employed');
/*!40000 ALTER TABLE `emp_family` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-04 20:47:42
