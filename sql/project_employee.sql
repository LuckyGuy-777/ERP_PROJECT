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
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `employee_id` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_birth` date DEFAULT NULL,
  `user_ssn` varchar(255) DEFAULT NULL,
  `user_gender` varchar(255) DEFAULT NULL,
  `user_nationality` varchar(255) DEFAULT NULL,
  `total_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `home_phone_number` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `employee_rank` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `road_address` varchar(255) DEFAULT NULL,
  `lot_number_address` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES ('10348','1101 깃허브 과제.png','루트1','2000-01-01','3820948390481','female','domestic','38695, 경북 경산시 남천면 남천로 421-9, 경북 경산시 남천면 삼성리 471-3, 1234','38695','01012344321','3243432','2023-12-15','과장','연구개발','qwer@naver.com','경북 경산시 남천면 남천로 421-9, 1234','경북 경산시 남천면 삼성리 471-3, 1234','root test'),('12124545','스크린샷 2023-03-28 134243.png','시연','2023-10-01','3412321321312','male','domestic','42794, 대구 달서구 상화북로 162, 대구 달서구 상인동 1600, 1234','42794','01010100101','23132','2023-10-14','사원','연구개발','qqqqwwww@gmail.com','대구 달서구 상화북로 162, 1234','대구 달서구 상인동 1600, 1234','qwerasdf1234'),('1234','0621-1.jpg','dlkdlfjdkfjkds','2023-08-12','4124241321321','female','foreign','42794, 대구 달서구 상화북로 162, 대구 달서구 상인동 1600, 441243','42794','01012341234','4234','2023-08-19','대리','연구개발','lhm122900@ync.ac.kr','대구 달서구 상화북로 162, 441243','대구 달서구 상인동 1600, 441243','qwer1234'),('123418','WH.png','관리자','2023-08-10','9913131291829','female','domestic','42781, 대구 달서구 월배로 지하 223, 대구 달서구 상인동 204-4, 관리자테스트','42781','01018181818','099876232','2023-08-12','과장','인사','manager@gmail.com','대구 달서구 월배로 지하 223, 관리자테스트','대구 달서구 상인동 204-4, 관리자테스트','manager'),('12345','user 테이블.png','최윤석','2023-08-05','9889898989888','male','domestic','41944, 대구 중구 동덕로 130, 대구 중구 삼덕동2가 50, 12212관','41944','01012341234','25425322','2023-08-18','대리','재무','hdjsahjshds@gmail.com','대구 중구 동덕로 130, 12212관','대구 중구 삼덕동2가 50, 12212관','최윤석'),('1805022','chart134601_1Img.png','루트','2023-09-06','1328738237273','male','domestic','10503, 경기 고양시 덕양구 백양로 51, 경기 고양시 덕양구 화정동 1000-3, 네이버타운','10503','01030889287','0536509250','2023-12-22','과장','영업','tgc06206@naver.com','경기 고양시 덕양구 백양로 51, 네이버타운','경기 고양시 덕양구 화정동 1000-3, 네이버타운','root'),('1805025','0622-6.jpg','이창민','1999-12-29','9912291212121','male','domestic','42794, 대구 달서구 상화북로 162, 대구 달서구 상인동 1600, 121관','42794','01030889287','232323','2023-08-18','과장','인사','tgc06206@naver.com','대구 달서구 상화북로 162, 121관','대구 달서구 상인동 1600, 121관','tgc06206@naver.com'),('1818','WH logo.jpg','가나다','2023-08-18','7546465635465','male','domestic','42686, 대구 달서구 달구벌대로 지하 1476, 대구 달서구 용산동 215-9, 14424','42686','01081818181','532535','2023-08-19','대리','영업','dfsdfdsfsdf@gmail.com','대구 달서구 달구벌대로 지하 1476, 14424','대구 달서구 용산동 215-9, 14424','1234'),('18181818',NULL,'창민',NULL,NULL,NULL,NULL,'42449, 대구 남구 대명로 지하 292, 대구 남구 대명동 224, 509동 322호','42449','01023452345','2352353','2023-08-14','부사장','연구개발','lee126984573@gmail.com','대구 남구 대명로 지하 292, 509동 322호',NULL,'lee126984573@gmail.com'),('19887','스크린샷 2023-07-04 152725.png','시연테스트','2023-10-14','1234513431313','female','domestic','42781, 대구 달서구 월배로 지하 223, 대구 달서구 상인동 204-4, 테스트1','42781','01012124545','5243545235','2023-10-07','대리','인사','tldusxptmxm@gmail.com','대구 달서구 월배로 지하 223, 테스트1','대구 달서구 상인동 204-4, 테스트1','시연테스트');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-04 20:47:41
