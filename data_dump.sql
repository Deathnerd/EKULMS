-- MySQL dump 10.13  Distrib 5.1.72, for Win64 (unknown)
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
  `courseId`    VARCHAR(20)
                COLLATE utf8_bin DEFAULT NULL,
  `courseName`  VARCHAR(255)
                COLLATE utf8_bin DEFAULT NULL,
  `description` TEXT
                COLLATE utf8_bin
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES ('CSC191', 'Object Oriented Programming 2',
                              'An advanced instruction on the Java language and Object Oriented Programming. Topics include:\r\n\r\n- Recursion\r\n- Inheritance\r\n- Scoping\r\n- GUI construction'),
  ('CSC195', 'Discrete Math', 'This course will go over your head'), ('GLY108', 'Plate Tectonics', 'Stuff about rocks'),
  ('CSC185', 'Introduction to Computing Concepts', 'The intro course');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructors` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `courseNumber` VARCHAR(20)
                 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructors`
--

LOCK TABLES `instructors` WRITE;
/*!40000 ALTER TABLE `instructors` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `testId`         INT(11)                   NOT NULL,
  `questionNumber` INT(11)                   DEFAULT NULL,
  `a`              TEXT
                   COLLATE utf8_bin,
  `b`              TEXT
                   COLLATE utf8_bin,
  `c`              TEXT
                   COLLATE utf8_bin,
  `d`              TEXT
                   COLLATE utf8_bin,
  `correct`        CHAR(1)
                   CHARACTER SET latin1
                   COLLATE latin1_general_ci DEFAULT NULL,
  `prompt`         LONGTEXT
                   CHARACTER SET latin1
                   COLLATE latin1_general_ci NOT NULL
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (3, 10, '339', '349', '369', '370', 'b',
                                'When a number is divided by 13, the remainder is 11. When the same number is divided by 17, then remainder is 9. What is the number ?'),
  (1, 3, '5B', '5F', '5A', '5C', 'c', 'Convert the binary number 1011010 to hexadecimal.'),
  (1, 1, 'binary coded decimal', 'decimal', 'octal', 'hexadecimal', 'b', 'Base 10 refers to which number system?'),
  (1, 2, '10000111.11', '11010011.01', '00111100.00', '10010111.11', 'd', 'Convert the decimal number 151.75 to binary.'),
  (1, 4, '8', '4', '1', '2', 'b', 'The number of bits used to store a BCD digit is:'),
  (2, 5, 'True', 'False', '', '', 'a', 'Base is the same as radix.'),
  (2, 3, 'True', 'False', '', '', 'a', 'Decimal numbers can be converted into binary by dividing by two and recording the remainders.'),
  (2, 6, 'True', 'False', '', '', 'a', '64 hexadecimal equals 100 decimal.'),
  (3, 8, '0', '3', '5', '11', 'c', 'On dividing a number by 357, we get 39 as remainder. On dividing the same number 17, what will be the remainder ?'),
  (2, 1, '327.375', '12166', '1388', '1476', 'd', 'What is the result when a decimal 5238 is converted to base 16?'),
  (2, 2, '9\'s complement code', 'excess-3 code', '8421 code', 'gray code', 'd', 'A binary code that progresses such that only one bit changes between two successive codes is _________.'),
  (2, 9, '9\'s complement code', 'excess-3 code', '8421 code', 'gray code', 'd', 'A binary code that progresses such that only one bit changes between two successive codes is _________.'),
  (3, 2, '4236t', '4306', '4336', '5336', 'd', 'In a division sum, the divisor is 10 times the quotient and 5 times the remainder. If the remainder is 46, what is the dividend ?'),
  (3, 7, '0', '1', '2', '3', 'b', 'On dividing a number by 68, we get 269 as quotient and 0 as remainder. On dividing the same number by 67, what will the remainder ?'),
  (3, 4, '4508', '450', '-4690', '-4508', 'd', 'Find the quotient of 202860/-45'),
  (3, 5, '0', '1', '2', '3', 'd', 'A number when divide by 6 leaves a remainder 3. When the square of the number is divided by 6, the remainder is'),
  (1, 10, '11010001001000', '11010000101000', '011010010000010', '110100001101010', 'b', '3428 is the decimal value for which of the following binary coded decimal (BCD) groupings?'),
  (3, 9, '4236', '4306', '4336', '5336', 'd', 'In a division sum, the divisor is 10 times the quotient and 5 times the remainder. If the remainder is 46, what is the dividend ?'),
  (1, 9, '8', '4', '1', '2', 'b', 'The number of bits used to store a BCD digit is:'),
  (1, 7, '01010101', '00110101', '00110010', '00110001', 'c', 'What is the resultant binary of the decimal problem 49   1 = ?'),
  (1, 8, 'Binary coding is pure binary.', 'BCD is pure binary.', 'Binary coding has a decimal format.', 'BCD has no decimal format.', 'a', 'What is the difference between binary coding and binary coded decimal?'),
  (2, 10, '4-bit binary code', '8-bit binary code', '16-bit binary code', 'ASCII code', 'a', 'The binary coded decimal (BCD) code is a system that represents each of the 10 decimal digits as a(n) ____________.'),
  (2, 7, 'True', 'False', '', '', 'b', 'ASCII codes are used strictly for representing the letters in the alphabet.'),
  (1, 5, '125	', '12.5', '90.125	', '9.125', 'd', 'Convert the binary number 1001.0010 to decimal.'),
  (1, 6, '191', '1911', '19', '19111', 'b', 'What is the decimal value of the hexadecimal number 777?'),
  (3, 6, '3572404', '135792', '913464', '114345', 'd', 'Which one of the following numbers when divided by 99 leaves remainder as 0?'),
  (3, 3, '639', '2079', '3791', '37911', 'b', 'Which of the following numbers is divisible by each one of 3, 7, 9 and 11 ?'),
  (3, 1, '1', '2', '8', '11', 'a', 'A number when divided by 296 leaves 75 as remainder. When the same number is divided by 37, the remainder will be:'),
  (2, 8, 'True', 'False', '', '', 'a', 'If you borrow from a position that contains a 0, you must borrow from the more significant bit that contains a 1. All 0s up to that point become 1s, and the digit last borrowed from becomes a 0.'),
  (2, 4, 'True', 'False', '', '', 'b', 'The process of converting a decimal number to its binary equivalent is called binary conversion.'),
  (4, 1, '95', '93', '49', '45', 'c', 'Find the quotient of 495/10'),
  (4, 2, '-5', '5', '0', '-1', 'a', 'find the quotient of -505/100'),
  (4, 3, '9', '-7', '5', '79', 'b', 'Find the quotient of 7995/-1000'),
  (4, 4, '6', '60', '61', '10', 'c', 'Find the quotient of 6100/100'),
  (4, 5, '100', '-82', '82', '-100', 'c', 'Find the quotient of -8200/-100'),
  (4, 6, '49', '9', '5', '-5', 'c', 'Find the remainder of 495/-10'),
  (4, 7, '5', '-5', '10', '-10', 'b', 'Find the remainder of -505/100'),
  (4, 8, '95', '90', '-95', '995', 'a', 'Find the remainder of 7995/-100'),
  (4, 9, '10', '-10', '1', '-1', 'd', 'Find the remainder of -6101/100'),
  (4, 10, '0', '5', '-5', '10', 'c', 'Find the remainder of -8205/-100'),
  (5, 1, '0.0485', '20.6', '330', '624', 'd', 'If a can of soup contains 22.0 oz (ounces) of soup, how many grams of soup is that? (1 lb = 16 oz, 1 lb = 454 g)'),
  (5, 2, '250 000 mg', '25 000 mg', '2500 mg', '250mg', 'b', 'Convert 25 g to mg.'),
  (5, 3, ' 1 m / 1000 cm and 1000 cm / 1 m', ' 1 m / 100 cm and 100 cm / 1 m', ' 100 m / 1 cm and 1 cm / 100 m', ' 1 m / 100 cm and 1 cm / 100 m', 'd', 'The conversion factors from meter to centimeters are:'),
  (5, 4, '0.0133', '0.207', '20.7', '52.5', 'c', 'A length of glass tubing is 0.525 m. How many inches long is the tubing? (2.54 cm = 1 inch)'),
  (5, 5, '0.0092', '0.92', '5.8', '10.9', 'b', 'What is the density (g/mL) of a sample of mineral oil if 250 mL has a mass of 0.23 kg?'),
  (5, 6, '9.906km', '10km', '9km', '8km', 'a', 'The average commercial jet flies around an altitude of 32,500 feet. How high is this in kilometers?'),
  (5, 7, 'true', 'false', '', '', 'b', 'Robert is 5 feet 3 inches tall.  Mark is 65 inches tall.  Robert says he is taller.  Is he right?'),
  (5, 8, '3', '0.3', '30', '300', 'a', '300cm equals how many metres?'),
  (6, 1, '100ft', '30ft', '200ft', '300ft', 'd', 'The length of the playground is 100 yards.  What is the length in feet?(1yard=3feet)'),
  (6, 2, '1200cm', '120cm', '12000cm', '1000cm', 'a', 'Miguel kicked the soccer ball 12 meters.  How many centimeters did he kick the soccer ball?(1cm=0.01m)'),
  (6, 3, '100ft', '30ft', '200ft', '300ft', 'd', 'The length of the playground is 100 yards.  What is the length in feet?(1yard=3feet)'),
  (6, 4, '1000ml', '100ml', '10ml', '1ml', 'a', '1 liter is:'),
  (6, 5, 'litre', 'metre', 'gram', 'kelvin', 'a', 'What is the metric unit for volume?'),
  (6, 6, '535,6', '535', '536', '0.602', 'c', 'The number 535.602 rounded to 3 significant figures is:'),
  (6, 7, '0.007', '0.7', '700', '7', 'd', 'When converting 7,000 nA to microamperes, the result is'),
  (6, 8, '31.24', '32', '31', '33', 'a', 'Express 3,124 centimeters in meters.'),
  (6, 9, 'volts', 'watts', 'amperes', 'joules', 'c', 'Intensity of electric current is expressed in'),
  (6, 10, 'kilogram.', 'metres', 'kilometres', 'joules', 'a', 'In the SI system, the basic unit of measurement for mass is the:'),
  (7, 1, '157.24', '156', '160', '165.3', 'a', 'A man travels first 900 kms. ef his journey by train at an average speed of 80 kms. per hour, next 2,000 kms. by plane at an average speed of 300 kms. per hour and finally, 20 kms. by Taxi at an average speed of 30 kms per hour. What is his average speed for the entire journey ?'),
  (7, 2, '42mph', '55mph', '38.92mph', '28mph', 'c', 'A man travelled by car for 3 days. He covered 480 miles each day, on the first day he drove for 10 hours at 48 miles an hour, on the second day he drove for 12 hours at 40 miles an hour and on the last day he drove 15 hours at 32 miles per hour. What was his average speed ?'),
  (7, 3, '50%', '40.5%', '45%', '55%', 'b', 'Compared to the previous year the overhead expenses went up by 32% in 1961 ; they increased by 44% in the next year and 50% in the following year. Calculate the average rate of increase in overhead expenses over the three years. Explain clearly the reason for the choice of average.'),
  (7, 4, '$14.63', '$17', '$13', '$18', 'a', 'An investor buys $ 1,200 worth of shares in a company each month. During the first 5 months he bought the shares at a price of $ 10, 12,15, 20 and 24 per share. After 5 months what is the average price paid for the Shares by him '),
  (7, 5, '6.25', '6.5', '6.75', '7', 'a', 'In the first 10 overs of a cricket game, the run rate was only 3.2. What should be the run rate in the remaining 40 overs to reach the target of 282 runs'),
  (7, 6, '35', '40', '50', 'none', 'b', 'The average age of husband, wife and their child 3 years ago was 27 years and that of wife and the child 5 years ago was 20 years. The present age of the husband is'),
  (7, 7, '0', '1', '10', '19', 'd', 'The average of 20 numbers is zero. Of them, at the most, how many may be greater than zero'),
  (7, 8, '76kg', '76.5kg', '85kg', 'Data inadequate', 'c', 'The average weight of 8 person\'s increases by 2.5 kg when a new person comes in place of one of them weighing 65 kg. What might be the weight of the new person'),
  (7, 9, '23years', '24years', '25years', 'None of these', 'a', 'The captain of a cricket team of 11 members is 26 years old and the wicket keeper is 3 years older. If the ages of these two are excluded, the average age of the remaining players is one year less than the average age of the whole team. What is the average age of the team'),
  (7, 10, '3500', '4000', '4050', '5000', 'b', 'The average monthly income of P and Q is Rs. 5050. The average monthly income of Q and R is Rs. 6250 and the average monthly income of P and R is Rs. 5200. The monthly income of P is'),
  (8, 1, '58', '68', '45', '90', 'b', 'A class of 25 students took a science test. 10 students had an average score of 80. The other students had an average score of 60. What is the average score of the whole class?'),
  (8, 2, '200/5', '220/7', '222/7', '200/7', 'c', 'A family consists of two grandparents, two parents and three grandchildren. The average age of the grandparents is 67 years, that of the parents is 35 years and that of the grandchildren is 6 years. What is the average age of the family?'),
  (8, 3, 'Rs4991', 'Rs5991', 'Rs6001', 'Rs6991', 'a', 'A grocer has a sale of Rs. 6435, Rs. 6927, Rs. 6855, Rs. 7230 and Rs. 6562 for 5 consecutive months. How much sale must he have in the sixth month so that he gets an average sale of Rs. 6500?'),
  (8, 4, '67kg', '68kg', '69kg', 'none', 'a',
   'In John\'s opinion, his weight is greater than 65 kg but less than 72 kg. His brother doesnt not agree with John and he thinks that John\'s weight is greater than 60 kg but less than 70 kg. His mother\'s view is that his weight ncannot be greater than 68 kg. If all are them are correct in their estimation, what is the average of different probable weights of John?'),
  (8, 5, '17kg', '20kg', '26kg', '31kg', 'd',
   'The average weight of A, B and C is 45 kg. If the average weight of A and B be 40 kg and that of B and C be 43 kg, then the weight of B is'),
  (8, 6, '47.55kg', '48kg', '48.55kg', '49.25kg', 'c',
   'The average weight of 16 boys in a class is 50.25 kg and that of the remaining 8 boys is 45.15 kg. Find the average weights of all the boys in the class'),
  (8, 7, '250', '276', '280', '285', 'd',
   'A library has an average of 510 visitors on Sundays and 240 on other days. The average number of visitors per day in a month of 30 days beginning with a Sunday is'),
  (8, 8, '53.33', '54.68', '55', 'none', 'b',
   'If the average marks of three batches of 55, 60 and 45 students respectively is 50, 55, 60, then the average marks of all the students is'),
  (8, 9, '10', '20', '40', '73', 'c',
   'A pupil\'s marks were wrongly entered as 83 instead of 63. Due to that the average marks for the class got increased by half (1/2). The number of pupils in the class is'),
  (8, 10, '14', '16', '19', '17', 'a',
   'How many candidates were interviewed everyday by the panel A out of the three panels A, B and C?nOut of a total of 45 candidates interviewed everyday by the three panels, the number of candidates interviewed by panel A is more by 2 than the candidates interviewed by panel c and is more by 1 than the candidates ninterviewed by panel B.');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `userId`           INT(11)        NOT NULL,
  `testId`           INT(11)        NOT NULL          DEFAULT '0',
  `attempt`          INT(11)        NOT NULL          DEFAULT '0',
  `grade`            CHAR(1)
                     CHARACTER SET latin1
                     COLLATE latin1_general_ci        DEFAULT NULL,
  `submitted`        TIMESTAMP      NOT NULL          DEFAULT CURRENT_TIMESTAMP,
  `number_correct`   INT(11)        NOT NULL,
  `number_incorrect` INT(11)        NOT NULL,
  `percentage`       DECIMAL(10, 2) NOT NULL
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results`
VALUES (23, 4, 1, 'F', '2014-08-26 00:04:56', 4, 6, '0.40'), (23, 4, 2, 'D', '2014-08-26 00:06:55', 6, 4, '0.60');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id`       INT(11) NOT NULL
  COMMENT 'User Id',
  `courseId` VARCHAR(20)
             COLLATE utf8_bin DEFAULT NULL
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (30, 'CSC195'), (30, 'CSC185');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `testId`     INT(11) NOT NULL AUTO_INCREMENT,
  `courseId`   VARCHAR(20)
               COLLATE utf8_bin DEFAULT NULL,
  `testNumber` INT(11)          DEFAULT NULL,
  `testName`   TEXT
               COLLATE utf8_bin,
  PRIMARY KEY (`testId`)
)
  ENGINE =MyISAM
  AUTO_INCREMENT =11
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES (1, 'CSC185', 1, 'Binary Number System'), (2, 'CSC185', 2, 'Binary Number System1'),
  (3, 'CSC185', 3, 'Integer Division-Challenging'), (4, 'CSC185', 4, 'Integer Division-Introductory'),
  (5, 'CSC185', 5, 'Unit Conversions-Challenging'), (6, 'CSC185', 6, 'Unit Conversions-Introductory'),
  (7, 'CSC185', 7, 'Weighted Average-Challenging'), (8, 'CSC185', 8, 'Weighted Average-Introductory');
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id`              INT(11)    NOT NULL AUTO_INCREMENT,
  `userName`        VARCHAR(255)
                    COLLATE utf8_bin    DEFAULT NULL,
  `password`        VARCHAR(255)
                    COLLATE utf8_bin    DEFAULT NULL,
  `admin`           TINYINT(1) NOT NULL DEFAULT '0',
  `date_created`    DATETIME   NOT NULL,
  `last_logged_in`  DATETIME   NOT NULL,
  `last_logged_out` TIMESTAMP  NULL     DEFAULT NULL,
  `reset_key`       VARCHAR(255)
                    COLLATE utf8_bin    DEFAULT NULL,
  `email`           VARCHAR(255)
                    COLLATE utf8_bin    DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  AUTO_INCREMENT =31
  DEFAULT CHARSET =utf8
  COLLATE =utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
  (28, 'wes-g', '$2y$10$QMpH81/GR7kPlsddUmltMeJGtS/Qio3z3Pc2pe9TITpM7iAvBd4sm', 0, '2014-09-12 05:55:53',
   '2014-09-12 06:05:01', '2014-09-12 10:05:53', NULL, '$2y$10$.dXG9IXkzgW.3j1C5X1gr.sEp1sBi7S6lrK1qWtK0GBJPvwqiPftK'),
  (26, 'admin', '$2y$10$QibVYb7DVR2ULYbwQA7qwu.6HINMPQ3YlL7RTO.lFBz8dY.c2Jtua', 1, '2014-09-12 05:03:15',
   '2014-09-12 05:03:15', NULL, NULL, '$2y$10$4P5g21yr/1GI8/3MKU5iiuftny3ygFMMzr1pkLIlDW6L71OpBThQ6'),
  (29, 'wg2', '$2y$10$WBALzmprcICeqZx6cT4COuZvbXeWExtjrN5ZpJEsYVMyO0ylvMgOy', 0, '2014-09-12 16:47:36',
   '2014-09-12 16:50:41', NULL, NULL, '$2y$10$HEC2295MgxEWeyntQLa2xu0CGE6LDdD2wQWOT.HzYNXO5YBHiBnke'),
  (30, 'wes', '$2y$10$Ru4cz6h2rdpMvR3aHTJQ0.IUjfYckIu7w3o7JyG9RyfzyY95JLiGK', 0, '2014-09-15 15:44:23',
   '2014-09-15 16:27:21', '2014-09-15 20:26:34', NULL, '$2y$10$gaaEfA34e9jRg4rW7PAEj.xwD5.PueAK8zz9qz/d2Y.i1TTFiyB0.');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2014-09-15 17:21:39
