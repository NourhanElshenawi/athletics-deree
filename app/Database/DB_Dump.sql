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
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `instructorID` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `period` varchar(45) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(45) NOT NULL,
  `currentCapacity` int(11) NOT NULL DEFAULT '0',
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Yoga',1,'11:00:00','11:20:00','spring',11,'studio 1',5,1,0,1,0,0),(2,'kick boxing',2,'15:00:00','15:50:00','spring',8,'studio 3',4,0,1,0,1,0),(3,'Zumba',1,'12:02:00','12:25:00','spring',12,'studio 1',2,1,1,0,0,0),(4,'Water Fitness',1,'12:02:00','12:25:00','spring',12,'Pool',3,0,1,0,0,0),(12,'Latin Dancing',2,'13:11:00','13:35:00','fall',5,'Student Lounge',20,0,1,0,0,0),(22,'Pilates',22,'17:00:00','17:50:00','spring',20,'studio 1',15,0,0,0,1,0),(32,'Abs and Legs',1,'17:00:00','17:50:00','spring',78,'studio 2',8,0,0,0,1,0),(42,'belly dancing',22,'15:15:00','16:15:00','fall',10,'dance studio',10,1,0,0,0,0);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `followsID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends`
--

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` VALUES (62,14,1),(72,14,14),(92,102,1),(102,102,2),(112,102,7),(122,14,7);
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specialty` varchar(45) DEFAULT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructors`
--

LOCK TABLES `instructors` WRITE;
/*!40000 ALTER TABLE `instructors` DISABLE KEYS */;
INSERT INTO `instructors` VALUES (1,'latin',82),(2,'everything',72),(12,'weight loss',62),(22,'aerobic',52),(32,'zumba',42),(42,'pilates',32),(52,'water fitness',0),(62,'testingInstructor',14),(72,'testingInstructor',92);
/*!40000 ALTER TABLE `instructors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `login` datetime NOT NULL,
  `logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID_idx` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,14,'2016-10-30 20:54:19','2016-10-30 23:08:35'),(2,14,'2016-10-30 20:59:08','2016-10-30 23:10:35'),(3,14,'2016-10-30 20:59:08','2016-10-30 23:42:12'),(4,9,'2016-10-30 20:59:08','2016-10-30 23:43:28'),(5,8,'2016-10-30 20:59:08','2016-10-30 23:44:35'),(6,14,'2016-10-30 20:59:08','2016-10-30 23:44:47'),(7,14,'2016-10-30 20:59:08','2016-10-30 23:45:13'),(8,14,'2016-10-30 23:45:13',NULL),(9,14,'2016-10-30 20:59:08','2016-10-30 23:45:57'),(10,14,'2016-10-30 23:52:41','2016-10-30 23:54:06'),(11,14,'2016-10-30 20:59:08','2016-10-30 23:58:00'),(12,14,'2016-10-30 20:59:08','2016-10-30 23:59:08'),(13,14,'2016-10-30 20:59:08','2016-03-12 23:59:08'),(14,14,'2016-03-12 20:58:08','2016-03-12 21:58:08'),(15,14,'2016-02-12 23:58:08','2016-02-12 23:59:08'),(16,14,'2016-02-12 20:58:08','2016-02-12 21:58:08'),(17,14,'2016-02-12 23:58:08','2016-02-12 23:59:08'),(18,14,'2016-02-12 20:58:08','2016-02-12 21:58:08'),(19,14,'2016-10-30 20:59:08','2016-02-12 23:59:08'),(20,14,'2016-02-12 20:58:08','2016-03-12 21:58:08'),(21,14,'2016-01-12 23:58:08','2016-01-12 23:59:08'),(22,14,'2016-01-12 20:58:08','2016-01-12 21:58:08'),(23,14,'2016-01-12 23:58:08','2016-01-12 23:59:08'),(24,14,'2016-01-12 20:58:08','2016-01-12 21:58:08'),(25,14,'2016-01-12 23:58:08','2016-01-12 23:59:08'),(26,14,'2016-01-12 20:58:08','2016-01-12 21:58:08'),(27,1,'2014-05-12 23:58:08','2014-05-12 23:59:08'),(28,1,'2014-05-12 20:58:08','2014-05-12 21:58:08'),(29,2,'2014-05-12 23:58:08','2014-05-12 23:59:08'),(30,2,'2014-11-12 20:58:08','2014-11-12 21:58:08'),(31,9,'2014-11-12 23:58:08','2014-11-12 23:59:08'),(32,9,'2015-04-12 20:58:08','2015-04-12 21:58:08'),(33,1,'2015-04-12 23:58:08','2015-04-12 23:59:08'),(34,1,'2015-04-12 20:58:08','2015-04-12 21:58:08'),(35,2,'2015-07-12 23:58:08',NULL),(36,2,'2015-06-12 20:58:08','2015-06-12 21:58:08'),(37,9,'2015-08-12 23:58:08','2015-08-12 23:59:08'),(38,3,'2015-08-12 20:58:08','2015-08-12 21:58:08'),(39,2,'2014-05-12 22:58:08','2016-11-22 23:07:00'),(40,8,'2014-05-12 22:58:08','2016-12-09 05:45:01'),(41,9,'2014-05-12 21:58:08',NULL),(42,14,'2016-11-22 03:46:07','2016-11-22 03:50:30'),(52,14,'2016-11-22 03:46:43','2016-11-22 03:57:27'),(72,14,'2016-11-22 03:50:36',NULL),(82,14,'2016-11-22 03:55:48','2016-11-22 03:57:07'),(92,14,'2016-11-22 04:00:12','2016-11-22 04:03:14');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `paymentID` varchar(50) NOT NULL,
  `payerID` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (72,1,'EC-9CF45104X6940894E','PAY-42L543450Y355882RLA5BHMA','ET5JFHA6ZUX5G','2016-10-27 00:00:00');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_requests`
--

DROP TABLE IF EXISTS `program_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `program_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `height` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `pastExercise` tinyint(1) NOT NULL DEFAULT '0',
  `currentlyExercising` tinyint(1) NOT NULL DEFAULT '0',
  `currentExercisingIntensity` tinyint(2) NOT NULL DEFAULT '0',
  `activities` varchar(100) NOT NULL DEFAULT '',
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  `saturday` tinyint(1) NOT NULL DEFAULT '0',
  `sunday` tinyint(1) NOT NULL DEFAULT '0',
  `developMuscleStrength` tinyint(1) NOT NULL DEFAULT '0',
  `rehabilitateInjury` tinyint(1) NOT NULL DEFAULT '0',
  `overallFitness` tinyint(1) NOT NULL DEFAULT '0',
  `loseBodyFat` tinyint(1) NOT NULL DEFAULT '0',
  `startExerciseProgram` tinyint(1) NOT NULL DEFAULT '0',
  `designAdvanceProgram` tinyint(1) NOT NULL DEFAULT '0',
  `increaseFlexibility` tinyint(1) NOT NULL DEFAULT '0',
  `sportsSpecificTraining` tinyint(1) NOT NULL DEFAULT '0',
  `increaseMuscleSize` tinyint(1) NOT NULL DEFAULT '0',
  `cardioExercise` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `comments` varchar(150) NOT NULL DEFAULT '',
  `trainerResponse` tinyint(1) NOT NULL DEFAULT '0',
  `trainerComments` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trainerName` varchar(45) DEFAULT 'Dimitris Karagiannis',
  `instructorID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_requests`
--

LOCK TABLES `program_requests` WRITE;
/*!40000 ALTER TABLE `program_requests` DISABLE KEYS */;
INSERT INTO `program_requests` VALUES (14,14,23,234,1,0,2,'asdf',1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,'asdf',1,'Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.\n\nPraesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.','2016-11-29 21:37:55','Dimitris Karagiannis',32),(16,14,111,111,0,0,1,'aaa',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,'aaaa',1,'testing without session','2016-12-02 01:19:04','Dimitris Karagiannis',52),(32,1,11,11,0,0,0,'111',0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,'11',1,'3 pushups and 3 situps','2016-11-28 22:48:33','Dimitris Karagiannis',12),(52,1,222,222,0,0,0,'222',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,'222',1,'3 pushups and 3 situps','2016-11-28 22:48:33','Dimitris Karagiannis',12),(62,1,222,222,0,0,0,'222',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,'222',1,'3 pushups and 3 situps','2016-11-28 22:48:33','Dimitris Karagiannis',42),(72,1,222,222,0,0,0,'222',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,'222',1,'3 pushups and 3 situps','2016-11-28 22:48:33','Dimitris Karagiannis',0),(112,1,160,70,1,1,2,'asdf',1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,'sadf',1,'30 mins on treadmill','2016-12-09 00:15:47','Dimitris Karagiannis',102),(122,1,160,70,1,1,2,'asdf',1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,'sadf',1,'3 planks for 10 secs\n2 more','2016-12-09 03:37:41','Dimitris Karagiannis',14),(132,1,160,70,1,1,2,'asdf',1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,'sadf',0,'','2016-11-30 15:42:11','Dimitris Karagiannis',0),(142,7,160,70,1,1,2,'asdf',1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,'sadf',0,'','2016-11-30 15:42:31','Dimitris Karagiannis',0),(152,7,160,70,1,1,2,'asdf',1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,'sadf',0,'','2016-11-30 15:43:58','Dimitris Karagiannis',0),(162,1,158,68,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 15:45:35','Dimitris Karagiannis',0),(172,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 15:47:22','Dimitris Karagiannis',0),(182,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 15:57:26','Dimitris Karagiannis',0),(192,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 15:59:30','Dimitris Karagiannis',0),(202,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:00:38','Dimitris Karagiannis',0),(212,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:01:20','Dimitris Karagiannis',0),(222,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:02:23','Dimitris Karagiannis',0),(232,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:03:01','Dimitris Karagiannis',0),(242,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:09:24','Dimitris Karagiannis',0),(252,7,160,70,1,1,0,'',0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:09:58','Dimitris Karagiannis',0),(262,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:10:13','Dimitris Karagiannis',0),(272,7,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'100 pushups\n+ 300 situps','2016-12-09 01:23:55','Dimitris Karagiannis',102),(282,1,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:46:24','Dimitris Karagiannis',0),(292,1,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:50:00','Dimitris Karagiannis',0),(302,1,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 16:50:21','Dimitris Karagiannis',0),(312,8,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'Past program','2016-11-30 16:55:09','Dimitris Karagiannis',42),(322,8,160,70,1,1,0,'',0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'You will do great exercises!','2016-11-30 16:55:10','Dimitris Karagiannis',42),(332,8,157,74,0,1,2,'kj jk jk',0,0,1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,'kljhjh jkh',1,'30 sets of carwheels','2016-12-09 00:27:57','Dimitris Karagiannis',102),(342,8,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'planks and cycling','2016-12-09 04:00:50','Dimitris Karagiannis',10),(352,1,160,70,1,1,0,'hi',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'water fitness class','2016-12-09 00:25:12','Dimitris Karagiannis',102),(362,1,170,59,0,1,2,'',0,1,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,'',0,'','2016-11-30 19:11:46','Dimitris Karagiannis',0),(372,1,160,70,1,1,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'pilates class','2016-12-09 00:17:45','Dimitris Karagiannis',102),(382,1,160,70,1,1,0,'I run marathons..',1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,'I am allergic to peanuts.',0,'','2016-11-30 22:23:55','Dimitris Karagiannis',0),(392,1,160,70,1,1,0,'I play drums..',1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,'hello trainer.',0,'','2016-11-30 22:34:30','Dimitris Karagiannis',0),(402,1,168,70,1,1,1,'',1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,'',1,'water fitness class','2016-12-09 00:22:24','Dimitris Karagiannis',102),(412,102,168,60,1,1,1,'gymnastics',1,0,1,0,0,0,0,2,3,4,5,6,7,8,0,9,1,'back pain',0,'','2016-12-08 23:25:31','Dimitris Karagiannis',0),(422,102,168,60,1,1,0,'gymnastics',1,0,1,0,0,0,0,0,1,2,3,4,5,6,7,8,9,'back pain',1,'yoga class','2016-12-09 00:17:32','Dimitris Karagiannis',102);
/*!40000 ALTER TABLE `program_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `classID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrations`
--

LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
INSERT INTO `registrations` VALUES (2,2,1),(4,1,4),(5,2,2),(182,1,12),(202,1,1),(212,1,3),(222,1,2),(252,14,12),(352,102,1),(372,14,22);
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_certificates`
--

DROP TABLE IF EXISTS `user_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `certificate_file` varchar(200) NOT NULL,
  `certificate_status` tinyint(1) NOT NULL DEFAULT '0',
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cerificate_file_UNIQUE` (`certificate_file`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_certificates`
--

LOCK TABLES `user_certificates` WRITE;
/*!40000 ALTER TABLE `user_certificates` DISABLE KEYS */;
INSERT INTO `user_certificates` VALUES (1,1,'test.pdf',1,'2016-11-08 15:14:26'),(2,2,'test2.pdf',2,'2016-11-08 15:15:12'),(45,10,'test3.pdf',1,'2016-11-08 15:45:30'),(172,1,'2016-12-04 05:12:19_1Nourhan Elshenawi - Resu',2,'2016-12-04 03:12:19'),(182,1,'2016-12-04 05:15:50_1_Nourhan Elshenawi - Resume.pdf',1,'2016-12-04 03:15:50'),(192,1,'2016-12-04 05:17:20_1_membership.pdf',1,'2016-12-04 03:17:20'),(202,14,'2016-12-08 20:36:42_14_Insurance Receipt.pdf',2,'2016-12-08 18:36:37'),(212,14,'2016-12-08 20:48:27_layla@acg.edu_Passport Front.pdf',0,'2016-12-08 18:48:22');
/*!40000 ALTER TABLE `user_certificates` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;
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

-- Dump completed on 2016-12-09  6:09:35
