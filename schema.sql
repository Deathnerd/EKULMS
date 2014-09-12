CREATE DATABASE IF NOT EXISTS `ekulms` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ekulms`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: ekulms
-- ------------------------------------------------------
-- Server version	5.1.72-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
	`courseId`    TEXT NOT NULL,
	`courseName`  TEXT NOT NULL,
	`description` TEXT NOT NULL
)
	ENGINE =InnoDB
	DEFAULT CHARSET =latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enrollment`
--

DROP TABLE IF EXISTS students;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enrollment` (
	`id`       INT(11) NOT NULL
	COMMENT 'User Id',
	`courseId` LONGTEXT
	           COLLATE latin1_general_ci
)
	ENGINE =MyISAM
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
	`testId`         INT(11)                   NOT NULL,
	`questionNumber` INT(11) DEFAULT NULL,
	`a`              TEXT
	                 COLLATE latin1_general_ci,
	`b`              TEXT
	                 COLLATE latin1_general_ci,
	`c`              TEXT
	                 COLLATE latin1_general_ci,
	`d`              TEXT
	                 COLLATE latin1_general_ci,
	`correct`        CHAR(1)
	                 COLLATE latin1_general_ci DEFAULT NULL,
	`prompt`         LONGTEXT
	                 COLLATE latin1_general_ci NOT NULL
)
	ENGINE =MyISAM
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
	`userId`           INT(11)        NOT NULL,
	`testId`           INT(11) DEFAULT NULL,
	`attempt`          INT(11) DEFAULT NULL,
	`grade`            CHAR(1)
	                   COLLATE latin1_general_ci DEFAULT NULL,
	`submitted`        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`number_correct`   INT(11)        NOT NULL,
	`number_incorrect` INT(11)        NOT NULL,
	`percentage`       DECIMAL(10, 2) NOT NULL
)
	ENGINE =MyISAM
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teach`
--

DROP TABLE IF EXISTS instructors;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teach` (
	`id`           INT(11) NOT NULL AUTO_INCREMENT,
	`courseNumber` TEXT
	               COLLATE latin1_general_ci,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
	`testId`     INT(11) NOT NULL AUTO_INCREMENT,
	`courseId`   LONGTEXT
	             COLLATE latin1_general_ci,
	`testNumber` INT(11) DEFAULT NULL,
	`testName`   TEXT
	             COLLATE latin1_general_ci,
	PRIMARY KEY (`testId`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =11
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
	`id`              INT(11)                   NOT NULL AUTO_INCREMENT,
	`userName`        TEXT
	                  COLLATE latin1_general_ci,
	`password`        TEXT
	                  COLLATE latin1_general_ci,
	`admin`           TINYINT(1)                NOT NULL DEFAULT '0',
	`date_created`    DATETIME                  NOT NULL,
	`last_logged_in`  DATETIME                  NOT NULL,
	`last_logged_out` TIMESTAMP                 NULL DEFAULT NULL,
	`reset_key`       TEXT
	                  COLLATE latin1_general_ci,
	`email`           TEXT
	                  COLLATE latin1_general_ci NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =21
	DEFAULT CHARSET =latin1
	COLLATE =latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2014-08-17  0:15:40
