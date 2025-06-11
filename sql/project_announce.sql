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
-- Table structure for table `announce`
--

DROP TABLE IF EXISTS `announce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announce` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `font` varchar(255) NOT NULL,
  `size` int NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announce`
--

LOCK TABLES `announce` WRITE;
/*!40000 ALTER TABLE `announce` DISABLE KEYS */;
INSERT INTO `announce` VALUES (1,'qwer1234`','sdasd','가나다','23123','Arial',12,'','2023-08-07 16:02:37'),(2,'이창민','공지사항 테스트','이창민','123','Arial',12,'user 테이블.png','2023-08-07 07:46:22'),(3,'최윤석','왜안댐ㅅㅂ?','최윤석','???','Lucida Sans Unicode',20,'DB명칭 정리.hwp','2023-08-07 08:23:57'),(4,'qwer1234`','ㅇㄴㄹㅇㄹ','가나다','32434','Arial',12,'','2023-08-07 16:10:57'),(5,'qwer1234`','좀되라 제발','가나다','143243432','Georgia',20,'+------------+--------------+------.txt','2023-08-07 16:11:34'),(6,'qwer1234`','test123','가나다','qqwqwq','Arial',12,'','2023-08-07 16:12:16'),(7,'qwer1234`','1233','가나다','ㅂㅂㅈㅂㅈ','Arial',12,'','2023-08-07 16:17:24'),(8,'qwer1234`','????','가나다','이거 왜 ㅈㄹ임?','Arial',12,'','2023-08-07 16:34:27'),(9,'이창민','제일 최근에 쓴 공지글의 이름으로 다 다뀜','이창민','1234','Arial',12,'','2023-08-07 16:35:58'),(10,'이창민','123123','이창민','ㄻㄹㄴㄻㄴㄹ','Arial',12,'','2023-08-07 16:40:18'),(11,'이창민','ㅗ몽ㅁ너ㅗ언모언모','이창민','123123','Arial',12,'','2023-08-07 16:41:52'),(12,'이창민','왜리럼?','이창민','ㄴㅇㄴㅇㄴㅇㄴ','Arial',12,'','2023-08-08 02:57:59'),(13,'qwer1234`','1234','가나다','fdhfjshfjhfsd','Arial',12,'','2023-08-08 05:03:54'),(14,'qwer1234`','adsdsdd','가나다','sdsdsds','Arial',14,'(붙임) 학점 취소원.hwp','2023-08-08 05:08:15'),(15,'qwer1234`','ㅇㄴㄴ','가나다','ㄴㅇㄴㅇㄴ','Arial',12,'','2023-08-08 06:30:08'),(16,'이창민','이창민 테스트','이창민','ㅁㅇㄴㅁㅇㅁㄴㅇㄴㅇㅁㄴㅇㄴㅁㅇㄴㅇㅁ','Arial',12,'증명사진.png','2023-08-08 06:31:11'),(17,'최윤석','11212','최윤석','ㄷㅈㅂㅈㅈㄷㅈ','Verdana',16,'+------------+--------------+------.txt','2023-08-10 07:48:55'),(18,'이창민','assa','이창민','qwqwqwwq','Arial',12,'','2023-08-10 07:50:19'),(19,'최윤석','테스트','최윤석','123','Arial',12,'','2023-08-16 02:40:34'),(20,'qwer1234','sdsdsd','dlkdlfjdkfjkds','sdsdsdsd','Arial',12,'','2023-08-16 07:00:08'),(21,'lee126984573@gmail.com','구글 테스트','창민','123','Arial',12,'','2023-08-25 05:45:16'),(22,'lee126984573@gmail.com','시연테스트123','창민','시연123','Arial',12,'윈도우 서버 정리.txt','2023-10-11 06:34:22'),(23,'lee126984573@gmail.com','시연테스트1111','창민','시연테스트 캡쳐','Arial',12,'증명사진.png','2023-10-11 06:37:35'),(24,'lee126984573@gmail.com','메인','창민','가낙다ㅓㅏ','Arial',12,'','2023-11-28 13:41:32'),(25,'lee126984573@gmail.com','메뉴얼','창민','메뉴얼 테스트 첨부파일 포함','Arial',12,'09.19(화) 회의록.hwp','2023-12-02 03:22:10');
/*!40000 ALTER TABLE `announce` ENABLE KEYS */;
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
