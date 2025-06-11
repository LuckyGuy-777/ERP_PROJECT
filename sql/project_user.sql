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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` varchar(255) NOT NULL,
  `user_pw` varchar(255) DEFAULT 'default_password',
  `email` varchar(255) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_gender` enum('male','female','other') DEFAULT 'other',
  `user_birth` date DEFAULT '2000-01-01',
  `phone_number` varchar(20) DEFAULT '01012345678',
  `device_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('1234','1234','dfsdfdsfsdf@gmail.com','가나다','male','2023-08-10','01081818181','cWPfOMZK4AEZZnyvVvZH85:APA91bGMqNtCDOvdlSsnDBzNsjiHLkx3J8xTh-pXIS3c9pre69R5fGwLS4iCyI37J9HXbATcWzLHb-i5KLDTmVfx_Wt-LPL9b6Cmdffsf-KyM78Mr-TSsV78bE6WTSA_IKATbehTjv0V'),('lee126984573@gmail.com','default_password','lee126984573@gmail.com',NULL,'other','2000-01-01','000-0000-0000','epPkklksNRZcWwazn3BCBr:APA91bHT7j7GuVYPl22Hl-NOlVC0sKLuF3KSjQu_8qt8yzky-UZGPZUlsg5pLVhzlNlqDtK3sR98giJmaNmegLcBSVMwsXZQ1vmT0S5RE3cQNDcZ80tj0194qqWQl96jBeCrg00qMR5j'),('lee99122915@gmail.com','default_password','lee99122915@gmail.com','이창민','other','2000-01-01','01012345678','cf1pIHoZOUypXZ9d3kQiDA:APA91bGpimeYGjoK3txofiA5Jw2iEh3rKPAWVIIoEv_K3mc7aZBbRutytGhuGxXAtUhFufiRsuJwq16kpZeFF5WOcavDUid5A6jf4YpnxwecQcCkB25vYdOS3Wzq_lI-4DqPIGvtZR1U'),('lhm122900@ync.ac.kr','default_password','lhm122900@ync.ac.kr','이창민','other','2000-01-01','01012345678','epPkklksNRZcWwazn3BCBr:APA91bFriiL0jT_Z0JbYF-d9Y80lczhrl7c6u_0iRECHDaxaJtuuGt46RM8JdQmoCSwzbm3syRzrQUqjEp8pNo3DONJ9UFekhGbvZDr99203aNNk7DxvHVGG5pWqAWS8_39ypw5TD4A1'),('manager','1234','manager@gmail.com','관리자','female','2023-08-01','01018181818',NULL),('qwer1234','1234','lhm122900@ync.ac.kr','dlkdlfjdkfjkds','male','2023-08-18','01012341234','epPkklksNRZcWwazn3BCBr:APA91bFriiL0jT_Z0JbYF-d9Y80lczhrl7c6u_0iRECHDaxaJtuuGt46RM8JdQmoCSwzbm3syRzrQUqjEp8pNo3DONJ9UFekhGbvZDr99203aNNk7DxvHVGG5pWqAWS8_39ypw5TD4A1'),('qwerasdf1234','1234','qqqqwwww@gmail.com','시연','male','2023-10-01','01010100101',NULL),('root','1234','tgc06206@naver.com','루트','male','2023-09-06','01030889287','epPkklksNRZcWwazn3BCBr:APA91bHT7j7GuVYPl22Hl-NOlVC0sKLuF3KSjQu_8qt8yzky-UZGPZUlsg5pLVhzlNlqDtK3sR98giJmaNmegLcBSVMwsXZQ1vmT0S5RE3cQNDcZ80tj0194qqWQl96jBeCrg00qMR5j'),('root test','1234','qwer@naver.com','루트1','female','2000-01-01','01012344321',NULL),('tgc06206@naver.com','default_password','tgc06206@naver.com','이창민','male','2000-01-01','000-0000-0000','cf1pIHoZOUypXZ9d3kQiDA:APA91bGpimeYGjoK3txofiA5Jw2iEh3rKPAWVIIoEv_K3mc7aZBbRutytGhuGxXAtUhFufiRsuJwq16kpZeFF5WOcavDUid5A6jf4YpnxwecQcCkB25vYdOS3Wzq_lI-4DqPIGvtZR1U'),('시연테스트','1234','tldusxptmxm@gmail.com','시연테스트','female','2023-10-14','01012124545',NULL),('이창민','1234','dlckdals@gmail.com','가나다','male','2023-08-04','01012341234',NULL),('최윤석','1234','chldbstjr@gmail.com','최윤석','male','2023-08-11','01012341234',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
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
