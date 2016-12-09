-- MySQL dump 10.13  Distrib 5.7.12, for osx10.9 (x86_64)
--
-- Host: us-cdbr-iron-east-04.cleardb.net    Database: heroku_c4df5ab8bcadabc
-- ------------------------------------------------------
-- Server version	5.5.46-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `birthDate` date NOT NULL,
  `gender` char(1) NOT NULL,
  `Phone` int(11) DEFAULT NULL,
  `membershipType` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `student` tinyint(1) DEFAULT '0',
  `faculty` tinyint(1) DEFAULT '0',
  `staff` tinyint(1) DEFAULT '0',
  `external` tinyint(1) DEFAULT '0',
  `alumni` tinyint(1) DEFAULT '0',
  `nurse` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'nourhan el','n@acg.edu','1234','n@acg.edu.jpeg','1994-10-01','M',NULL,0,1,1,0,0,1,0,0,0),(2,'nora','f@acg.edu','1234','f@acg.edu.jpeg','1994-06-01','F',NULL,1,0,0,0,0,0,1,0,0),(7,'kostas','kostas@example.com','kostas','n@acg.edu.jpeg','1991-09-16','M',NULL,0,1,1,0,1,1,0,0,0),(8,'Jane','jane@example.com','jane','jane@example.com.jpeg','1991-09-16','F',NULL,0,1,0,0,0,0,1,0,0),(9,'kate','kate@acg.edu','1234','kate@acg.edu.jpg','1994-10-01','F',NULL,0,1,0,0,1,0,0,0,0),(10,'Robin','robin@acg.edu','1234','robin@acg.edu.jpg','1994-10-01','M',NULL,0,1,0,1,0,0,0,0,1),(14,'layla','layla@acg.edu','1234','layla@acg.edu.jpeg','2016-11-09','M',NULL,1,1,0,0,1,0,0,0,0),(22,'efthimya','e.lyk@acg.edu','1234','e.lyk@acg.edu.jpg','1990-12-02','F',NULL,0,1,0,0,0,0,0,0,0),(32,'petros','p.jack@acg.edu','1234','p.jack@acg.edu.jpeg','1991-12-02','M',NULL,0,1,0,0,0,0,0,0,0),(42,'kostas','k.lyk@acg.edu','1234','k.lyk@acg.edu.jpeg','1989-12-02','M',NULL,0,1,0,0,0,0,0,0,0),(52,'katherine','k.john@acg.edu','1234','k.john@acg.edu.jpeg','1987-12-02','F',NULL,0,1,0,0,0,0,0,0,0),(62,'vasillis','v.lyk@acg.edu','1234','v.lyk@acg.edu.jpeg','1992-12-02','M',NULL,0,1,0,0,0,0,0,0,0),(72,'apostolis','a.lyk@acg.edu','1234','a.lyk@acg.edu.jpeg','1990-12-02','M',NULL,0,1,0,0,0,0,0,0,0),(82,'emma','e.mark@acg.edu','1234','e.mark@acg.edu.jpeg','1988-12-02','F',NULL,0,1,0,0,0,0,0,0,0),(92,'talia','talia@acg.edu','1234','talia@acg.edu.jpeg','1980-12-12','M',NULL,1,1,1,0,0,0,0,0,0),(102,'jennifer','jen@email.com','1234','jen@email.com.jpg','1998-05-15','M',NULL,1,1,0,0,1,0,0,0,1);
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

-- Dump completed on 2016-12-09  4:13:03
