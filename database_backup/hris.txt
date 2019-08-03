-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: easyb_web
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.9-MariaDB

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
-- Table structure for table `lk_accesscontrol`
--

DROP TABLE IF EXISTS `lk_accesscontrol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_accesscontrol` (
  `accessID` varchar(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `node` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  PRIMARY KEY (`accessID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_accesscontrol`
--

LOCK TABLES `lk_accesscontrol` WRITE;
/*!40000 ALTER TABLE `lk_accesscontrol` DISABLE KEYS */;
INSERT INTO `lk_accesscontrol` VALUES ('','','',''),('A','Personnel','Personnel','fa-database'),('A.1','Profile Data','/personnel-head','fa-user'),('B','Attendance','Attendance','fa-tasks'),('B.1','Shift Parameter','/attendance-shift','fa-wrench'),('B.2','Overtime Parameter','/attendance-overtime','fa-wrench'),('B.3','Holiday Date','/attendance-holiday','fa-wrench'),('B.7','Work Schedule','/attendance-w-calc-head','fa-calendar-o'),('B.8','Work Schedule Actual ','/attendance-w-calc-actual-head','fa-calendar-o'),('B.9','Leave','/leave','fa-plane'),('C','Payroll','Payroll','fa-money'),('C.1','Payroll Component','/payroll-component','fa-wrench'),('C.2','Tax Rate','/payroll-tax-rate','fa-wrench'),('C.3','PTKP Rate','/payroll-ptkp','fa-wrench'),('C.4','Jamsostek Parameter','/payroll-jamsostek','fa-wrench'),('C.5','Prorate Parameter','/payroll-prorate','fa-wrench'),('C.6','Functional Expenses','/payroll-functional-expenses','fa-wrench'),('C.7','Income','/payroll-income','fa-cube'),('C.8','Income Tax Before','/payroll-tax-before','fa-cube'),('C.9','Payroll Process','/payroll-proc','fa-gears'),('D','Loan','Loan','fa-paperclip'),('D.1','Loan Transaction','/loan','fa-cube'),('E','Medical','Medical',' fa-wheelchair'),('E.1','Medical Type','/medical-type','fa-wrench'),('E.2','Medical Transaction','/medical-income','fa-cube'),('Y','Master Data','Master','fa-archive'),('Y.1','Bank','/bank','fa-bank'),('Y.2','Company','/company','fa-tachometer'),('Y.3','User','/user','fa-users'),('Y.4','User Role','/user-role','fa-users'),('Y.5','Tax Location','/tax-location','fa-building-o'),('Y.6','Division','/personnel-division','fa-th-large'),('Y.7','Department','/personnel-department','fa-th'),('Y.8','Position','/personnel-position','fa-suitcase'),('Z','Reporting','Reporting','fa-print'),('Z.1','1721-A1','/report-pph','fa-file'),('Z.2','Payslip','/payslip','fa-binoculars'),('Z.3','Tax Monthly','/report-tax','fa-file');
/*!40000 ALTER TABLE `lk_accesscontrol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_calendar`
--

DROP TABLE IF EXISTS `lk_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_calendar` (
  `date` date NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_calendar`
--

LOCK TABLES `lk_calendar` WRITE;
/*!40000 ALTER TABLE `lk_calendar` DISABLE KEYS */;
INSERT INTO `lk_calendar` VALUES ('2016-01-01'),('2016-01-02'),('2016-01-03'),('2016-01-04'),('2016-01-05'),('2016-01-06'),('2016-01-07'),('2016-01-08'),('2016-01-09'),('2016-01-10'),('2016-01-11'),('2016-01-12'),('2016-01-13'),('2016-01-14'),('2016-01-15'),('2016-01-16'),('2016-01-17'),('2016-01-18'),('2016-01-19'),('2016-01-20'),('2016-01-21'),('2016-01-22'),('2016-01-23'),('2016-01-24'),('2016-01-25'),('2016-01-26'),('2016-01-27'),('2016-01-28'),('2016-01-29'),('2016-01-30'),('2016-01-31'),('2016-02-01'),('2016-02-02'),('2016-02-03'),('2016-02-04'),('2016-02-05'),('2016-02-06'),('2016-02-07'),('2016-02-08'),('2016-02-09'),('2016-02-10'),('2016-02-11'),('2016-02-12'),('2016-02-13'),('2016-02-14'),('2016-02-15'),('2016-02-16'),('2016-02-17'),('2016-02-18'),('2016-02-19'),('2016-02-20'),('2016-02-21'),('2016-02-22'),('2016-02-23'),('2016-02-24'),('2016-02-25'),('2016-02-26'),('2016-02-27'),('2016-02-28'),('2016-02-29'),('2016-03-01'),('2016-03-02'),('2016-03-03'),('2016-03-04'),('2016-03-05'),('2016-03-06'),('2016-03-07'),('2016-03-08'),('2016-03-09'),('2016-03-10'),('2016-03-11'),('2016-03-12'),('2016-03-13'),('2016-03-14'),('2016-03-15'),('2016-03-16'),('2016-03-17'),('2016-03-18'),('2016-03-19'),('2016-03-20'),('2016-03-21'),('2016-03-22'),('2016-03-23'),('2016-03-24'),('2016-03-25'),('2016-03-26'),('2016-03-27'),('2016-03-28'),('2016-03-29'),('2016-03-30'),('2016-03-31'),('2016-04-01'),('2016-04-02'),('2016-04-03'),('2016-04-04'),('2016-04-05'),('2016-04-06'),('2016-04-07'),('2016-04-08'),('2016-04-09'),('2016-04-10'),('2016-04-11'),('2016-04-12'),('2016-04-13'),('2016-04-14'),('2016-04-15'),('2016-04-16'),('2016-04-17'),('2016-04-18'),('2016-04-19'),('2016-04-20'),('2016-04-21'),('2016-04-22'),('2016-04-23'),('2016-04-24'),('2016-04-25'),('2016-04-26'),('2016-04-27'),('2016-04-28'),('2016-04-29'),('2016-04-30'),('2016-05-01'),('2016-05-02'),('2016-05-03'),('2016-05-04'),('2016-05-05'),('2016-05-06'),('2016-05-07'),('2016-05-08'),('2016-05-09'),('2016-05-10'),('2016-05-11'),('2016-05-12'),('2016-05-13'),('2016-05-14'),('2016-05-15'),('2016-05-16'),('2016-05-17'),('2016-05-18'),('2016-05-19'),('2016-05-20'),('2016-05-21'),('2016-05-22'),('2016-05-23'),('2016-05-24'),('2016-05-25'),('2016-05-26'),('2016-05-27'),('2016-05-28'),('2016-05-29'),('2016-05-30'),('2016-05-31'),('2016-06-01'),('2016-06-02'),('2016-06-03'),('2016-06-04'),('2016-06-05'),('2016-06-06'),('2016-06-07'),('2016-06-08'),('2016-06-09'),('2016-06-10'),('2016-06-11'),('2016-06-12'),('2016-06-13'),('2016-06-14'),('2016-06-15'),('2016-06-16'),('2016-06-17'),('2016-06-18'),('2016-06-19'),('2016-06-20'),('2016-06-21'),('2016-06-22'),('2016-06-23'),('2016-06-24'),('2016-06-25'),('2016-06-26'),('2016-06-27'),('2016-06-28'),('2016-06-29'),('2016-06-30'),('2016-07-01'),('2016-07-02'),('2016-07-03'),('2016-07-04'),('2016-07-05'),('2016-07-06'),('2016-07-07'),('2016-07-08'),('2016-07-09'),('2016-07-10'),('2016-07-11'),('2016-07-12'),('2016-07-13'),('2016-07-14'),('2016-07-15'),('2016-07-16'),('2016-07-17'),('2016-07-18'),('2016-07-19'),('2016-07-20'),('2016-07-21'),('2016-07-22'),('2016-07-23'),('2016-07-24'),('2016-07-25'),('2016-07-26'),('2016-07-27'),('2016-07-28'),('2016-07-29'),('2016-07-30'),('2016-07-31'),('2016-08-01'),('2016-08-02'),('2016-08-03'),('2016-08-04'),('2016-08-05'),('2016-08-06'),('2016-08-07'),('2016-08-08'),('2016-08-09'),('2016-08-10'),('2016-08-11'),('2016-08-12'),('2016-08-13'),('2016-08-14'),('2016-08-15'),('2016-08-16'),('2016-08-17'),('2016-08-18'),('2016-08-19'),('2016-08-20'),('2016-08-21'),('2016-08-22'),('2016-08-23'),('2016-08-24'),('2016-08-25'),('2016-08-26'),('2016-08-27'),('2016-08-28'),('2016-08-29'),('2016-08-30'),('2016-08-31'),('2016-09-01'),('2016-09-02'),('2016-09-03'),('2016-09-04'),('2016-09-05'),('2016-09-06'),('2016-09-07'),('2016-09-08'),('2016-09-09'),('2016-09-10'),('2016-09-11'),('2016-09-12'),('2016-09-13'),('2016-09-14'),('2016-09-15'),('2016-09-16'),('2016-09-17'),('2016-09-18'),('2016-09-19'),('2016-09-20'),('2016-09-21'),('2016-09-22'),('2016-09-23'),('2016-09-24'),('2016-09-25'),('2016-09-26'),('2016-09-27'),('2016-09-28'),('2016-09-29'),('2016-09-30'),('2016-10-01'),('2016-10-02'),('2016-10-03'),('2016-10-04'),('2016-10-05'),('2016-10-06'),('2016-10-07'),('2016-10-08'),('2016-10-09'),('2016-10-10'),('2016-10-11'),('2016-10-12'),('2016-10-13'),('2016-10-14'),('2016-10-15'),('2016-10-16'),('2016-10-17'),('2016-10-18'),('2016-10-19'),('2016-10-20'),('2016-10-21'),('2016-10-22'),('2016-10-23'),('2016-10-24'),('2016-10-25'),('2016-10-26'),('2016-10-27'),('2016-10-28'),('2016-10-29'),('2016-10-30'),('2016-10-31'),('2016-11-01'),('2016-11-02'),('2016-11-03'),('2016-11-04'),('2016-11-05'),('2016-11-06'),('2016-11-07'),('2016-11-08'),('2016-11-09'),('2016-11-10'),('2016-11-11'),('2016-11-12'),('2016-11-13'),('2016-11-14'),('2016-11-15'),('2016-11-16'),('2016-11-17'),('2016-11-18'),('2016-11-19'),('2016-11-20'),('2016-11-21'),('2016-11-22'),('2016-11-23'),('2016-11-24'),('2016-11-25'),('2016-11-26'),('2016-11-27'),('2016-11-28'),('2016-11-29'),('2016-11-30'),('2016-12-01'),('2016-12-02'),('2016-12-03'),('2016-12-04'),('2016-12-05'),('2016-12-06'),('2016-12-07'),('2016-12-08'),('2016-12-09'),('2016-12-10'),('2016-12-11'),('2016-12-12'),('2016-12-13'),('2016-12-14'),('2016-12-15'),('2016-12-16'),('2016-12-17'),('2016-12-18'),('2016-12-19'),('2016-12-20'),('2016-12-21'),('2016-12-22'),('2016-12-23'),('2016-12-24'),('2016-12-25'),('2016-12-26'),('2016-12-27'),('2016-12-28'),('2016-12-29'),('2016-12-30'),('2016-12-31');
/*!40000 ALTER TABLE `lk_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_currency`
--

DROP TABLE IF EXISTS `lk_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_currency` (
  `currencyID` varchar(5) NOT NULL,
  `currencyName` varchar(50) NOT NULL,
  `currencySign` varchar(3) NOT NULL,
  `rate` decimal(18,2) NOT NULL,
  PRIMARY KEY (`currencyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_currency`
--

LOCK TABLES `lk_currency` WRITE;
/*!40000 ALTER TABLE `lk_currency` DISABLE KEYS */;
INSERT INTO `lk_currency` VALUES ('AUD','Australian Dollar','AUD',1.00),('GBP','British Pound','GBP',1.00),('IDR','Indonesian Rupiah','IDR',1.00),('JPY','Japanese Yen','JPY',1.00),('MYR','Malaysian Ringgit','MYR',1.00),('SGD','Singapore Dollar','SGD',1.00),('USD','United States Dollar','USD',13800.00);
/*!40000 ALTER TABLE `lk_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_education`
--

DROP TABLE IF EXISTS `lk_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_education` (
  `educationId` int(11) NOT NULL AUTO_INCREMENT,
  `educationDescription` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`educationId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_education`
--

LOCK TABLES `lk_education` WRITE;
/*!40000 ALTER TABLE `lk_education` DISABLE KEYS */;
INSERT INTO `lk_education` VALUES (1,'No Education'),(2,'Elementary School'),(3,'Midle School'),(4,'High School'),(5,'High School - Vocational'),(6,'Diploma'),(7,'College - Bachelor'),(8,'College - Doctorate'),(9,'College - Professor');
/*!40000 ALTER TABLE `lk_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_filteraccess`
--

DROP TABLE IF EXISTS `lk_filteraccess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_filteraccess` (
  `accessID` varchar(10) NOT NULL,
  `insertAcc` bit(1) NOT NULL,
  `updateAcc` bit(1) NOT NULL,
  `deleteAcc` bit(1) NOT NULL,
  `authorizeAcc` bit(1) NOT NULL,
  `viewAcc` bit(1) NOT NULL,
  PRIMARY KEY (`accessID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_filteraccess`
--

LOCK TABLES `lk_filteraccess` WRITE;
/*!40000 ALTER TABLE `lk_filteraccess` DISABLE KEYS */;
INSERT INTO `lk_filteraccess` VALUES ('A','','','','',''),('A.1','','','','',''),('A.2','','','','',''),('A.3','','','','',''),('A.4','','','','',''),('B','','','','',''),('B.1','','','','',''),('B.10','','','','',''),('B.2','','','','',''),('B.3','','','','',''),('B.7','','','','',''),('B.8','','','','',''),('B.9','','','','',''),('C','','','','',''),('C.1','','','','',''),('C.2','','','','',''),('C.3','','','','',''),('C.4','','','','',''),('C.5','','','','',''),('C.6','','','','',''),('C.7','','','','',''),('C.8','','','','',''),('C.9','','','','',''),('D','','','','',''),('D.1','','','','',''),('E','','','','',''),('E.1','','','','',''),('E.2','','','','',''),('Y','','','','',''),('Y.1','','','','',''),('Y.2','','','','',''),('Y.3','','','','',''),('Y.4','','','','',''),('Y.5','','','','',''),('Y.6','','','','',''),('Y.7','','','','',''),('Y.8','','','','',''),('Z','','','','',''),('Z.1','','','','',''),('Z.2','','','','',''),('Z.3','','','','','');
/*!40000 ALTER TABLE `lk_filteraccess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_gender`
--

DROP TABLE IF EXISTS `lk_gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_gender` (
  `id` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_gender`
--

LOCK TABLES `lk_gender` WRITE;
/*!40000 ALTER TABLE `lk_gender` DISABLE KEYS */;
INSERT INTO `lk_gender` VALUES (1,'MALE'),(2,'FEMALE');
/*!40000 ALTER TABLE `lk_gender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_leave`
--

DROP TABLE IF EXISTS `lk_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_leave` (
  `leaveId` int(11) NOT NULL AUTO_INCREMENT,
  `leaveName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`leaveId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_leave`
--

LOCK TABLES `lk_leave` WRITE;
/*!40000 ALTER TABLE `lk_leave` DISABLE KEYS */;
INSERT INTO `lk_leave` VALUES (1,'Anual Leave'),(2,'Special Leave');
/*!40000 ALTER TABLE `lk_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_taxarticle`
--

DROP TABLE IF EXISTS `lk_taxarticle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_taxarticle` (
  `articleId` varchar(50) NOT NULL,
  `articleDesc` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_taxarticle`
--

LOCK TABLES `lk_taxarticle` WRITE;
/*!40000 ALTER TABLE `lk_taxarticle` DISABLE KEYS */;
INSERT INTO `lk_taxarticle` VALUES ('Article01','GAJI/PENSIUN ATAU THT/JHT'),('Article02','TUNJANGAN PPh'),('Article03','TUNJANGAN LAINNYA, UANG LEMBUR DAN SEBAGAINYA'),('Article04','HONORARIUM DAN IMBALAN LAIN SEJENISNYA'),('Article05','PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA'),('Article06','PENERIMAAN DALAM BENTUK NATURA DAN KENIKMATAN LAINNYA YANG DIKENAKAN PEMOTONGAN PPh PASAL 21'),('Article07','TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR');
/*!40000 ALTER TABLE `lk_taxarticle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_time`
--

DROP TABLE IF EXISTS `lk_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_time` (
  `timeID` int(11) NOT NULL AUTO_INCREMENT,
  `unit` varchar(50) DEFAULT NULL,
  `unitValue` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`timeID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_time`
--

LOCK TABLES `lk_time` WRITE;
/*!40000 ALTER TABLE `lk_time` DISABLE KEYS */;
INSERT INTO `lk_time` VALUES (1,'Hour',1.00),(2,'Day',8.00),(3,'Week',40.00),(4,'Month',168.00);
/*!40000 ALTER TABLE `lk_time` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_topupamount`
--

DROP TABLE IF EXISTS `lk_topupamount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_topupamount` (
  `topupAmountID` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`topupAmountID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_topupamount`
--

LOCK TABLES `lk_topupamount` WRITE;
/*!40000 ALTER TABLE `lk_topupamount` DISABLE KEYS */;
INSERT INTO `lk_topupamount` VALUES (1,25000.00),(2,50000.00),(3,100000.00),(4,200000.00),(5,300000.00),(6,500000.00),(7,1000000.00),(8,2000000.00);
/*!40000 ALTER TABLE `lk_topupamount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lk_userrole`
--

DROP TABLE IF EXISTS `lk_userrole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lk_userrole` (
  `userRoleID` int(11) NOT NULL AUTO_INCREMENT,
  `userRole` varchar(100) NOT NULL DEFAULT '',
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`userRoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lk_userrole`
--

LOCK TABLES `lk_userrole` WRITE;
/*!40000 ALTER TABLE `lk_userrole` DISABLE KEYS */;
INSERT INTO `lk_userrole` VALUES (1,'ADMIN','','SYSTEM','2016-01-01 00:00:00','admin','2019-07-20 07:39:08'),(8,'HRD','\0','admin','2016-03-23 14:35:02','','0000-00-00 00:00:00'),(36,'Asas','\0','admin','2016-03-24 13:21:37','','0000-00-00 00:00:00'),(37,'SUPERVISOR','\0','admin','2016-03-24 13:23:22','','0000-00-00 00:00:00'),(38,'SUPERTADMIN','\0','admin','2016-03-24 13:27:23','','0000-00-00 00:00:00'),(40,'HO','\0','admin','2016-03-24 13:35:05','','0000-00-00 00:00:00'),(48,'HNB','\0','admin','2016-03-24 16:29:21','admin','2016-03-24 16:44:25'),(49,'ADM','\0','admin','2016-03-24 16:44:53','admin','2016-03-24 16:50:51'),(50,'SUPERVISOR','','admin','2016-03-24 17:11:45','admin','2016-03-28 09:31:50');
/*!40000 ALTER TABLE `lk_userrole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_alert`
--

DROP TABLE IF EXISTS `ms_alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `query` varchar(9999) DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_alert`
--

LOCK TABLES `ms_alert` WRITE;
/*!40000 ALTER TABLE `ms_alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendanceholiday`
--

DROP TABLE IF EXISTS `ms_attendanceholiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendanceholiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `holidayDescription` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendanceholiday`
--

LOCK TABLES `ms_attendanceholiday` WRITE;
/*!40000 ALTER TABLE `ms_attendanceholiday` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendanceholiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendanceovertime`
--

DROP TABLE IF EXISTS `ms_attendanceovertime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendanceovertime` (
  `overtimeId` varchar(20) NOT NULL,
  `rate1` decimal(18,2) DEFAULT NULL,
  `rate2` decimal(18,2) DEFAULT NULL,
  `rate3` decimal(18,2) DEFAULT NULL,
  `rate4` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  PRIMARY KEY (`overtimeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendanceovertime`
--

LOCK TABLES `ms_attendanceovertime` WRITE;
/*!40000 ALTER TABLE `ms_attendanceovertime` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendanceovertime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendanceshift`
--

DROP TABLE IF EXISTS `ms_attendanceshift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendanceshift` (
  `shitCode` varchar(40) NOT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `overnight` smallint(6) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`shitCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendanceshift`
--

LOCK TABLES `ms_attendanceshift` WRITE;
/*!40000 ALTER TABLE `ms_attendanceshift` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendanceshift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendancewcalcactualdetail`
--

DROP TABLE IF EXISTS `ms_attendancewcalcactualdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendancewcalcactualdetail` (
  `id` varchar(20) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendancewcalcactualdetail`
--

LOCK TABLES `ms_attendancewcalcactualdetail` WRITE;
/*!40000 ALTER TABLE `ms_attendancewcalcactualdetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendancewcalcactualdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendancewcalcactualhead`
--

DROP TABLE IF EXISTS `ms_attendancewcalcactualhead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendancewcalcactualhead` (
  `id` varchar(20) NOT NULL,
  `period` varchar(45) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendancewcalcactualhead`
--

LOCK TABLES `ms_attendancewcalcactualhead` WRITE;
/*!40000 ALTER TABLE `ms_attendancewcalcactualhead` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendancewcalcactualhead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendancewcalcdet`
--

DROP TABLE IF EXISTS `ms_attendancewcalcdet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendancewcalcdet` (
  `id` varchar(20) DEFAULT NULL,
  `period` varchar(15) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `shiftCode` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendancewcalcdet`
--

LOCK TABLES `ms_attendancewcalcdet` WRITE;
/*!40000 ALTER TABLE `ms_attendancewcalcdet` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendancewcalcdet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_attendancewcalchead`
--

DROP TABLE IF EXISTS `ms_attendancewcalchead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_attendancewcalchead` (
  `id` varchar(20) NOT NULL,
  `period` varchar(11) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_attendancewcalchead`
--

LOCK TABLES `ms_attendancewcalchead` WRITE;
/*!40000 ALTER TABLE `ms_attendancewcalchead` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_attendancewcalchead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_bank`
--

DROP TABLE IF EXISTS `ms_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_bank` (
  `bankId` varchar(50) NOT NULL,
  `bankDesc` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`bankId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_bank`
--

LOCK TABLES `ms_bank` WRITE;
/*!40000 ALTER TABLE `ms_bank` DISABLE KEYS */;
INSERT INTO `ms_bank` VALUES ('BCA','BANK CENTRAL ASIA','admin','2016-08-23 14:28:13','admin','2016-09-01 13:49:03',''),('BNI','BANK NASIONAL INDONESIA','admin','2016-09-02 08:23:56',NULL,NULL,''),('BNIS','BNI SYARIAH','admin','2017-02-01 09:50:06',NULL,NULL,''),('BRI','BANK RAKYAT INDONESIA','admin','2016-08-12 13:51:54',NULL,NULL,''),('CIMB','CIMB NIAGA','admin','2017-02-01 09:49:26',NULL,NULL,''),('IDX','BANK INDEX','admin','2016-12-05 15:05:03',NULL,NULL,''),('MAS','BANK MAS','admin','2016-10-06 11:17:12',NULL,NULL,''),('NOBU','BANK NOBU','admin','2016-12-05 15:03:51',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_company`
--

DROP TABLE IF EXISTS `ms_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_company` (
  `companyID` int(11) NOT NULL,
  `companyName` varchar(100) NOT NULL,
  `companyAddress` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `taxSetting` varchar(20) DEFAULT NULL,
  `startPayrollPeriod` varchar(20) DEFAULT NULL,
  `dateStart` int(11) DEFAULT NULL,
  `dateEnd` int(11) DEFAULT NULL,
  `overMonth` bit(1) DEFAULT NULL,
  `incHolidayDate` bit(1) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`companyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_company`
--

LOCK TABLES `ms_company` WRITE;
/*!40000 ALTER TABLE `ms_company` DISABLE KEYS */;
INSERT INTO `ms_company` VALUES (1,'EASYB','GADING SERPONG','W-DAY','1','2016/01',1,30,'\0','','admin',NULL,'admin','2017-02-02 09:17:13');
/*!40000 ALTER TABLE `ms_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_loan`
--

DROP TABLE IF EXISTS `ms_loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(45) DEFAULT NULL,
  `registrationPeriod` varchar(10) DEFAULT NULL,
  `principal` decimal(18,2) DEFAULT NULL,
  `term` int(2) DEFAULT NULL,
  `downPayment` decimal(18,2) DEFAULT NULL,
  `principalPaid` decimal(18,2) DEFAULT NULL,
  `remarks` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_loan`
--

LOCK TABLES `ms_loan` WRITE;
/*!40000 ALTER TABLE `ms_loan` DISABLE KEYS */;
INSERT INTO `ms_loan` VALUES (1,'1','2017/01',1000000.00,5,500000.00,100000.00,'','admin','2016-09-19 09:08:54','admin','2016-09-21 10:39:00','\0'),(2,'1','2016/01',8000000.00,8,0.00,1000000.00,'','admin','2016-10-03 10:07:34',NULL,NULL,'\0');
/*!40000 ALTER TABLE `ms_loan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_location`
--

DROP TABLE IF EXISTS `ms_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_location` (
  `locationID` int(11) NOT NULL AUTO_INCREMENT,
  `locationCode` varchar(20) DEFAULT NULL,
  `locationName` varchar(50) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`locationID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_location`
--

LOCK TABLES `ms_location` WRITE;
/*!40000 ALTER TABLE `ms_location` DISABLE KEYS */;
INSERT INTO `ms_location` VALUES (1,'','Tangerang','Jalan Boulevard Gading Serpong Blok B No. 8','02187248572','','SYSTEM','2015-12-10 10:00:00','admin','2015-12-10 10:39:07'),(2,'','Jakarta','Jalan Boulevard Gading Serpong Blok B No. 8','02187248572','','SYSTEM','2015-12-10 10:00:00','admin','2015-12-10 10:39:07');
/*!40000 ALTER TABLE `ms_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_medicalincome`
--

DROP TABLE IF EXISTS `ms_medicalincome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_medicalincome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(45) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_medicalincome`
--

LOCK TABLES `ms_medicalincome` WRITE;
/*!40000 ALTER TABLE `ms_medicalincome` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_medicalincome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_medicalincomedetail`
--

DROP TABLE IF EXISTS `ms_medicalincomedetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_medicalincomedetail` (
  `id` int(11) DEFAULT NULL,
  `claimDate` date DEFAULT NULL,
  `claimType` varchar(20) DEFAULT NULL,
  `inAmount` decimal(18,2) DEFAULT NULL,
  `outAmount` decimal(18,2) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  `flagActive` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_medicalincomedetail`
--

LOCK TABLES `ms_medicalincomedetail` WRITE;
/*!40000 ALTER TABLE `ms_medicalincomedetail` DISABLE KEYS */;
INSERT INTO `ms_medicalincomedetail` VALUES (1,'2016-09-01','4',6000000.00,0.00,'',NULL,NULL,'admin','2016-09-21',NULL),(1,'2016-09-02','2',0.00,2000000.00,'Siloam Hospital',NULL,NULL,'admin','2016-09-21',NULL);
/*!40000 ALTER TABLE `ms_medicalincomedetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_medicaltype`
--

DROP TABLE IF EXISTS `ms_medicaltype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_medicaltype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeDescription` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_medicaltype`
--

LOCK TABLES `ms_medicaltype` WRITE;
/*!40000 ALTER TABLE `ms_medicaltype` DISABLE KEYS */;
INSERT INTO `ms_medicaltype` VALUES (1,'Opening Balance','admin','2016-09-20 08:33:20',NULL,NULL,''),(2,'Rawat Inap','admin','2016-09-19 12:23:22',NULL,NULL,''),(3,'Rawat Jalan','admin','2016-09-19 12:23:29',NULL,NULL,''),(4,'Gigi Umum','admin','2016-09-29 11:29:12','admin','2016-09-29 11:29:21',''),(5,'Gigi Khusus','admin','2016-09-29 11:29:28',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_medicaltype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollcomponent`
--

DROP TABLE IF EXISTS `ms_payrollcomponent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollcomponent` (
  `payrollCode` varchar(20) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `parameter` varchar(45) DEFAULT NULL,
  `payrollDesc` varchar(45) DEFAULT NULL,
  `formula` varchar(45) DEFAULT NULL,
  `articleId` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`payrollCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollcomponent`
--

LOCK TABLES `ms_payrollcomponent` WRITE;
/*!40000 ALTER TABLE `ms_payrollcomponent` DISABLE KEYS */;
INSERT INTO `ms_payrollcomponent` VALUES ('A01','1','2','SALARY','','Article01','admin','2016-06-14 09:22:54',NULL,NULL,''),('A02','1','2','TRANSPORTASI','','Article01','admin','2016-06-15 10:17:40','admin','2016-08-25 10:48:05',''),('A03','1','2','UANG MAKAN','','Article01','admin','2016-06-15 10:23:44','admin','2016-09-21 09:22:08',''),('A04','1','2','UANG DRIVER','','','admin','2016-06-15 10:33:52','admin','2016-09-21 11:25:01',''),('B02','2','2','TUNJANGAN HARI RAYA','','Article07','admin','2016-06-15 13:14:36','admin','2016-09-22 13:59:05',''),('D01','2','1','HUTANG','',NULL,'admin','2016-06-14 09:23:21','admin','2016-06-14 09:23:41',''),('JHTCom','3','1','JHT Company','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JHTEmp','3','1','JHT Employee','','Article10','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JKKCom','3','1','JKK Company','','Article05','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JKKEmp','3','1','JKK Employee','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JKMCom','3','1','JKM Company','','Article05','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JKMEmp','3','1','JKM Employee','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JPKCom','3','1','JPK Company','','Article05','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JPKEmp','3','1','JPK Employee','','Article10','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JPNCom','3','1','JPN Company','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',''),('JPNEmp','3','1','JPN Employee','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','');
/*!40000 ALTER TABLE `ms_payrollcomponent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollfix`
--

DROP TABLE IF EXISTS `ms_payrollfix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollfix` (
  `nik` varchar(20) NOT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollfix`
--

LOCK TABLES `ms_payrollfix` WRITE;
/*!40000 ALTER TABLE `ms_payrollfix` DISABLE KEYS */;
INSERT INTO `ms_payrollfix` VALUES ('1');
/*!40000 ALTER TABLE `ms_payrollfix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollfixdetail`
--

DROP TABLE IF EXISTS `ms_payrollfixdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollfixdetail` (
  `nik` int(11) DEFAULT NULL,
  `payrollCode` varchar(10) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollfixdetail`
--

LOCK TABLES `ms_payrollfixdetail` WRITE;
/*!40000 ALTER TABLE `ms_payrollfixdetail` DISABLE KEYS */;
INSERT INTO `ms_payrollfixdetail` VALUES (1,'A01',2000000.00,'admin','2016-06-17 08:14:51',NULL,NULL);
/*!40000 ALTER TABLE `ms_payrollfixdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollfunctionalexpenses`
--

DROP TABLE IF EXISTS `ms_payrollfunctionalexpenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollfunctionalexpenses` (
  `id` int(11) NOT NULL,
  `rate` decimal(18,2) DEFAULT NULL,
  `maxAmount` decimal(18,2) DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollfunctionalexpenses`
--

LOCK TABLES `ms_payrollfunctionalexpenses` WRITE;
/*!40000 ALTER TABLE `ms_payrollfunctionalexpenses` DISABLE KEYS */;
INSERT INTO `ms_payrollfunctionalexpenses` VALUES (1,6.00,6000000.00,'admin','2016-09-27');
/*!40000 ALTER TABLE `ms_payrollfunctionalexpenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollincome`
--

DROP TABLE IF EXISTS `ms_payrollincome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollincome` (
  `nik` varchar(20) NOT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollincome`
--

LOCK TABLES `ms_payrollincome` WRITE;
/*!40000 ALTER TABLE `ms_payrollincome` DISABLE KEYS */;
INSERT INTO `ms_payrollincome` VALUES ('1');
/*!40000 ALTER TABLE `ms_payrollincome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollincomedetail`
--

DROP TABLE IF EXISTS `ms_payrollincomedetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollincomedetail` (
  `nik` int(11) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollincomedetail`
--

LOCK TABLES `ms_payrollincomedetail` WRITE;
/*!40000 ALTER TABLE `ms_payrollincomedetail` DISABLE KEYS */;
INSERT INTO `ms_payrollincomedetail` VALUES (1,'A01',20000000.00,'2016-01-01','2019-01-01','admin','2016-11-02',NULL,NULL,'\0'),(1,'A01',30000000.00,'2016-01-01','2017-12-31','admin','2016-11-30',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_payrollincomedetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrolljamsostek`
--

DROP TABLE IF EXISTS `ms_payrolljamsostek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrolljamsostek` (
  `jamsostekCode` varchar(50) NOT NULL,
  `payrollCodeSource` varchar(20) DEFAULT NULL,
  `jkkCom` decimal(18,2) DEFAULT NULL,
  `jkkEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJkk` decimal(18,2) DEFAULT NULL,
  `jkmCom` decimal(18,2) DEFAULT NULL,
  `jkmEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJkm` decimal(18,2) DEFAULT NULL,
  `jhtCom` decimal(18,2) DEFAULT NULL,
  `jhtEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJht` decimal(18,2) DEFAULT NULL,
  `jpkCom` decimal(18,2) DEFAULT NULL,
  `jpkEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJpk` decimal(18,2) DEFAULT NULL,
  `jpnCom` decimal(18,2) DEFAULT NULL,
  `jpnEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJpn` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`jamsostekCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrolljamsostek`
--

LOCK TABLES `ms_payrolljamsostek` WRITE;
/*!40000 ALTER TABLE `ms_payrolljamsostek` DISABLE KEYS */;
INSERT INTO `ms_payrolljamsostek` VALUES ('J01','A01',240.00,0.00,9000.00,300.00,0.00,9000.00,370.00,200.00,9000.00,400.00,100.00,9000.00,200.00,100.00,9000.00,'admin','2016-11-11 14:52:51','admin','2016-10-10 11:07:35','');
/*!40000 ALTER TABLE `ms_payrolljamsostek` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollnonfix`
--

DROP TABLE IF EXISTS `ms_payrollnonfix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollnonfix` (
  `nik` varchar(20) NOT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollnonfix`
--

LOCK TABLES `ms_payrollnonfix` WRITE;
/*!40000 ALTER TABLE `ms_payrollnonfix` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_payrollnonfix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollnonfixdetail`
--

DROP TABLE IF EXISTS `ms_payrollnonfixdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollnonfixdetail` (
  `nik` varchar(20) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollnonfixdetail`
--

LOCK TABLES `ms_payrollnonfixdetail` WRITE;
/*!40000 ALTER TABLE `ms_payrollnonfixdetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_payrollnonfixdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollprorate`
--

DROP TABLE IF EXISTS `ms_payrollprorate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollprorate` (
  `prorateId` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `day` varchar(50) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`prorateId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollprorate`
--

LOCK TABLES `ms_payrollprorate` WRITE;
/*!40000 ALTER TABLE `ms_payrollprorate` DISABLE KEYS */;
INSERT INTO `ms_payrollprorate` VALUES ('CD-01','3','','admin','2016-06-16 15:32:52','admin','2016-06-20 14:57:53'),('FD-001','1','22','admin',NULL,'admin','2018-05-03 20:23:37'),('W-DAY','2','','admin','2016-06-16 15:33:01','admin','2016-06-20 14:58:05');
/*!40000 ALTER TABLE `ms_payrollprorate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollptkp`
--

DROP TABLE IF EXISTS `ms_payrollptkp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollptkp` (
  `id` int(11) NOT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `rate` decimal(18,2) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollptkp`
--

LOCK TABLES `ms_payrollptkp` WRITE;
/*!40000 ALTER TABLE `ms_payrollptkp` DISABLE KEYS */;
INSERT INTO `ms_payrollptkp` VALUES (1,54000000.00,4500000.00,'2016-09-01 15:30:10','admin');
/*!40000 ALTER TABLE `ms_payrollptkp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrollsetting`
--

DROP TABLE IF EXISTS `ms_payrollsetting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrollsetting` (
  `Id` int(11) NOT NULL,
  `companyName` varchar(20) DEFAULT NULL,
  `companyAddress` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `taxSetting` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrollsetting`
--

LOCK TABLES `ms_payrollsetting` WRITE;
/*!40000 ALTER TABLE `ms_payrollsetting` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_payrollsetting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrolltaxbefore`
--

DROP TABLE IF EXISTS `ms_payrolltaxbefore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrolltaxbefore` (
  `id` varchar(45) NOT NULL,
  `nik` int(11) DEFAULT NULL,
  `year` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrolltaxbefore`
--

LOCK TABLES `ms_payrolltaxbefore` WRITE;
/*!40000 ALTER TABLE `ms_payrolltaxbefore` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_payrolltaxbefore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrolltaxbeforedetail`
--

DROP TABLE IF EXISTS `ms_payrolltaxbeforedetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrolltaxbeforedetail` (
  `id` varchar(20) DEFAULT NULL,
  `nomor` varchar(20) DEFAULT NULL,
  `periodStart` date DEFAULT NULL,
  `periodEnd` date DEFAULT NULL,
  `npwpCompany` varchar(45) DEFAULT NULL,
  `company` varchar(45) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `taxPaid` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrolltaxbeforedetail`
--

LOCK TABLES `ms_payrolltaxbeforedetail` WRITE;
/*!40000 ALTER TABLE `ms_payrolltaxbeforedetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_payrolltaxbeforedetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_payrolltaxrate`
--

DROP TABLE IF EXISTS `ms_payrolltaxrate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_payrolltaxrate` (
  `tieringCode` varchar(10) NOT NULL,
  `start` decimal(18,2) DEFAULT NULL,
  `end` decimal(18,2) DEFAULT NULL,
  `npwpRate` decimal(18,2) DEFAULT NULL,
  `nonNpwpRate` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`tieringCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_payrolltaxrate`
--

LOCK TABLES `ms_payrolltaxrate` WRITE;
/*!40000 ALTER TABLE `ms_payrolltaxrate` DISABLE KEYS */;
INSERT INTO `ms_payrolltaxrate` VALUES ('T1',0.00,50000000.00,5.00,6.00),('T2',50000000.00,200000000.00,15.00,18.00),('T3',200000000.00,250000000.00,25.00,30.00),('T4',250000000.00,999999999.00,30.00,36.00);
/*!40000 ALTER TABLE `ms_payrolltaxrate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personnelcontract`
--

DROP TABLE IF EXISTS `ms_personnelcontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personnelcontract` (
  `nik` int(11) DEFAULT NULL,
  `startWorking` date DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `docNo` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personnelcontract`
--

LOCK TABLES `ms_personnelcontract` WRITE;
/*!40000 ALTER TABLE `ms_personnelcontract` DISABLE KEYS */;
INSERT INTO `ms_personnelcontract` VALUES (1,'2019-07-01','2019-07-31','2019-07-01','123','5','1');
/*!40000 ALTER TABLE `ms_personnelcontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personneldepartment`
--

DROP TABLE IF EXISTS `ms_personneldepartment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personneldepartment` (
  `departmentCode` int(11) NOT NULL AUTO_INCREMENT,
  `departmentDesc` varchar(50) DEFAULT NULL,
  `divisionId` varchar(50) DEFAULT NULL,
  `shiftParm` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`departmentCode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personneldepartment`
--

LOCK TABLES `ms_personneldepartment` WRITE;
/*!40000 ALTER TABLE `ms_personneldepartment` DISABLE KEYS */;
INSERT INTO `ms_personneldepartment` VALUES (1,'DEVELOPMENT','1',NULL,'CD-01','admin','2019-07-20 16:25:23',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_personneldepartment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personneldivision`
--

DROP TABLE IF EXISTS `ms_personneldivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personneldivision` (
  `divisionId` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`divisionId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personneldivision`
--

LOCK TABLES `ms_personneldivision` WRITE;
/*!40000 ALTER TABLE `ms_personneldivision` DISABLE KEYS */;
INSERT INTO `ms_personneldivision` VALUES (1,'IT','admin','2019-07-20 16:25:08',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_personneldivision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personnelfamily`
--

DROP TABLE IF EXISTS `ms_personnelfamily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personnelfamily` (
  `id` int(11) DEFAULT NULL,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `relationship` varchar(20) DEFAULT NULL,
  `idNumber` varchar(20) DEFAULT NULL,
  `birthPlace` varchar(25) DEFAULT NULL,
  `birthDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personnelfamily`
--

LOCK TABLES `ms_personnelfamily` WRITE;
/*!40000 ALTER TABLE `ms_personnelfamily` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_personnelfamily` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personnelhead`
--

DROP TABLE IF EXISTS `ms_personnelhead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personnelhead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employeeNo` varchar(50) DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `fullName` varchar(50) DEFAULT NULL,
  `birthPlace` varchar(50) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phoneNo` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `education` varchar(45) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `maritalStatus` varchar(20) DEFAULT NULL,
  `dependent` varchar(2) DEFAULT NULL,
  `empStatus` varchar(30) DEFAULT NULL,
  `jamsostekParm` varchar(30) DEFAULT NULL,
  `divisionId` varchar(45) DEFAULT NULL,
  `departmentId` varchar(45) DEFAULT NULL,
  `npwpNo` varchar(25) DEFAULT NULL,
  `bpjskNo` varchar(25) DEFAULT NULL,
  `bpkstkNo` varchar(25) DEFAULT NULL,
  `paymentMethod` varchar(10) DEFAULT NULL,
  `bankName` varchar(25) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `bankNo` varchar(25) DEFAULT NULL,
  `curency` varchar(8) DEFAULT NULL,
  `swiftCode` varchar(50) DEFAULT NULL,
  `ecFirstName` varchar(45) DEFAULT NULL,
  `ecLastName` varchar(45) DEFAULT NULL,
  `ecRelationShip` varchar(45) DEFAULT NULL,
  `ecPhone1` varchar(45) DEFAULT NULL,
  `ecPhone2` varchar(45) DEFAULT NULL,
  `npwpName` varchar(45) DEFAULT NULL,
  `npwpAddress` varchar(100) DEFAULT NULL,
  `taxId` int(11) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `taxSetting` varchar(20) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  `imageKTP` varchar(200) DEFAULT NULL,
  `imagePhoto` varchar(200) DEFAULT NULL,
  `imageNPWP` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personnelhead`
--

LOCK TABLES `ms_personnelhead` WRITE;
/*!40000 ALTER TABLE `ms_personnelhead` DISABLE KEYS */;
INSERT INTO `ms_personnelhead` VALUES (1,'201701012003','CHARLIE','SETIONO','CHARLIE SETIONO','BOGOR','2019-04-09','GAMA 17 NO 14 RT.002 RW.008 KEC. KARAWACI','TANGERANG','+62-877-71161657','CHARLIE_EVOLUTION15@YAHOO.COM','1','7','SYSTEM INFORMATION','1','0',NULL,'J01','1','1','00.000.000.0-000.000','123456789','1122334455','2','BCA','TANGERANG KOTA','888331124','IDR','','FIRSTNAME','LASTNAME','WIFE','+11-111-111111111','+22-222-22222222','CHARLIE SETIONO','GAMA 17 NO 14 RT.002 RW.008 KEC. KARAWACI TANGERANG',NULL,'1','INDONESIA','CD-01','3','admin','2019-07-21 09:24:32',NULL,NULL,'',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ms_personnelhead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_personnelposition`
--

DROP TABLE IF EXISTS `ms_personnelposition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_personnelposition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `positionDescription` varchar(100) DEFAULT NULL,
  `jobDescription` varchar(1000) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_personnelposition`
--

LOCK TABLES `ms_personnelposition` WRITE;
/*!40000 ALTER TABLE `ms_personnelposition` DISABLE KEYS */;
INSERT INTO `ms_personnelposition` VALUES (1,'DEVELOPER MANAGER','','admin','2019-07-20 16:26:30',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_personnelposition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_setting`
--

DROP TABLE IF EXISTS `ms_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_setting` (
  `key1` varchar(100) NOT NULL,
  `key2` varchar(100) DEFAULT NULL,
  `value1` varchar(100) DEFAULT NULL,
  `value2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_setting`
--

LOCK TABLES `ms_setting` WRITE;
/*!40000 ALTER TABLE `ms_setting` DISABLE KEYS */;
INSERT INTO `ms_setting` VALUES ('create','USER','100000',NULL),('job','YEAR','1920',NULL),('PayrollParm','DEDUCTION','1','1'),('PayrollParm','ALLOWANCE','2','1'),('PayrollType','FIX','1','1'),('PayrollType','NON FIX','2','1'),('ProrateParm','FIX DAY','1',''),('ProrateParm','WORKING DAY','2',''),('ProrateParm','CALENDAR DAY','3',''),('TaxParm','GROSS','1','1'),('TaxParm','NETT','2','2'),('TaxParm','GROSS UP','3','3'),('MaritalStatus','MARRIED','1',''),('MaritalStatus','SINGLE','2',''),('Nationality','LOCAL','1',''),('Nationality','FOREIGNER','2',''),('paymentMethod','CASH','1','0'),('paymentMethod','TRANSFER','2','0'),('Status','INTERNSHIP','1',''),('Status','OUTSOURCE','2',''),('Status','CONTRACT','3',''),('Status','PROBATION','4',''),('Status','PERMANENT','5',''),('Status','PIECE WORKER','6',''),('PayrollType','FORMULA','4','1');
/*!40000 ALTER TABLE `ms_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_taxlocation`
--

DROP TABLE IF EXISTS `ms_taxlocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_taxlocation` (
  `id` varchar(50) NOT NULL,
  `npwpNo` varchar(50) DEFAULT NULL,
  `officeName` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zipCode` varchar(45) DEFAULT NULL,
  `phone1` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `taxSigner_1` varchar(50) DEFAULT NULL,
  `position_1` varchar(50) DEFAULT NULL,
  `npwpSigner_1` varchar(50) DEFAULT NULL,
  `phone1_1` varchar(50) DEFAULT NULL,
  `phone2_1` varchar(45) DEFAULT NULL,
  `email_1` varchar(50) DEFAULT NULL,
  `taxSigner_2` varchar(50) DEFAULT NULL,
  `position_2` varchar(50) DEFAULT NULL,
  `npwpSigner_2` varchar(50) DEFAULT NULL,
  `phone1_2` varchar(50) DEFAULT NULL,
  `phone2_2` varchar(50) DEFAULT NULL,
  `email_2` varchar(45) DEFAULT NULL,
  `taxSigner_3` varchar(45) DEFAULT NULL,
  `position_3` varchar(45) DEFAULT NULL,
  `npwpSigner_3` varchar(45) DEFAULT NULL,
  `phone1_3` varchar(45) DEFAULT NULL,
  `phone2_3` varchar(45) DEFAULT NULL,
  `email_3` varchar(45) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_taxlocation`
--

LOCK TABLES `ms_taxlocation` WRITE;
/*!40000 ALTER TABLE `ms_taxlocation` DISABLE KEYS */;
INSERT INTO `ms_taxlocation` VALUES ('KP TIGARAKSA','00.000.000.0-000.000','PASAR KEMIS','POS TANGERANG','TANGERANG',NULL,'+00-000-0000000000','','JOKO','PRESIDENT DIRECTOR','','','','','','','','','','','','','','','','','admin','2019-07-20 16:28:42',NULL,NULL,'');
/*!40000 ALTER TABLE `ms_taxlocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_user`
--

DROP TABLE IF EXISTS `ms_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_user` (
  `username` varchar(50) NOT NULL DEFAULT '',
  `fullName` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `salt` varchar(45) NOT NULL,
  `userRoleID` int(11) NOT NULL DEFAULT '0',
  `locationID` int(11) NOT NULL,
  `dbName` varchar(100) DEFAULT NULL,
  `companyID` int(11) DEFAULT NULL,
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL DEFAULT '0',
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `userRoleConstrain_idx` (`userRoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_user`
--

LOCK TABLES `ms_user` WRITE;
/*!40000 ALTER TABLE `ms_user` DISABLE KEYS */;
INSERT INTO `ms_user` VALUES ('admin','Administrator','deb384376e3da17fb354a0b697a89c51','QpZ25sb8Vn-B3nDOY2WuvO8s9-Okm9Hk19cqW5OyXWU6v',1,1,'easyb_web',1,'','admin','2015-08-04 12:21:49','admin','2016-01-18 09:05:03');
/*!40000 ALTER TABLE `ms_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_useraccess`
--

DROP TABLE IF EXISTS `ms_useraccess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_useraccess` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userRoleID` int(11) NOT NULL,
  `accessID` varchar(10) NOT NULL,
  `indexAcc` bit(1) NOT NULL,
  `viewAcc` bit(1) NOT NULL,
  `insertAcc` bit(1) NOT NULL,
  `updateAcc` bit(1) NOT NULL,
  `deleteAcc` bit(1) NOT NULL,
  `authorizeAcc` bit(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_useraccessfilter` (`accessID`)
) ENGINE=InnoDB AUTO_INCREMENT=390 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_useraccess`
--

LOCK TABLES `ms_useraccess` WRITE;
/*!40000 ALTER TABLE `ms_useraccess` DISABLE KEYS */;
INSERT INTO `ms_useraccess` VALUES (352,1,'Z.1','','','','','',''),(353,1,'B','\0','\0','\0','\0','\0','\0'),(354,1,'Y.1','','','','','',''),(355,1,'Y.2','','','','','',''),(356,1,'Y.7','','','','','',''),(357,1,'Y.6','','','','','',''),(358,1,'C.6','','','','','',''),(359,1,'B.3','','','','','',''),(360,1,'C.7','','','','','',''),(361,1,'C.8','','','','','',''),(362,1,'C.4','','','','','',''),(363,1,'B.9','','','','','',''),(364,1,'B.10','','','','','',''),(365,1,'D','\0','\0','\0','\0','\0','\0'),(366,1,'D.1','','','','','',''),(367,1,'Y','\0','\0','\0','\0','\0','\0'),(368,1,'E','\0','\0','\0','\0','\0','\0'),(369,1,'E.2','','','','','',''),(370,1,'E.1','','','','','',''),(371,1,'B.2','','','','','',''),(372,1,'C','\0','\0','\0','\0','\0','\0'),(373,1,'C.1','','','','','',''),(374,1,'C.9','','','','','',''),(375,1,'Z.2','','','','','',''),(376,1,'A','\0','\0','\0','\0','\0','\0'),(377,1,'Y.8','','','','','',''),(378,1,'A.1','','','','','',''),(379,1,'C.5','','','','','',''),(380,1,'C.3','','','','','',''),(381,1,'Z','\0','\0','\0','\0','\0','\0'),(382,1,'B.1','','','','','',''),(383,1,'Y.5','','','','','',''),(384,1,'Z.3','','','','','',''),(385,1,'C.2','','','','','',''),(386,1,'Y.3','','','','','',''),(387,1,'Y.4','','','','','',''),(388,1,'B.7','','','','','',''),(389,1,'B.8','','','','','','');
/*!40000 ALTER TABLE `ms_useraccess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_companybalance`
--

DROP TABLE IF EXISTS `tr_companybalance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_companybalance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `companyID` int(11) NOT NULL,
  `balanceDate` datetime NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_companybalance`
--

LOCK TABLES `tr_companybalance` WRITE;
/*!40000 ALTER TABLE `tr_companybalance` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_companybalance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_confirmationtopup`
--

DROP TABLE IF EXISTS `tr_confirmationtopup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_confirmationtopup` (
  `confirmationID` int(11) NOT NULL AUTO_INCREMENT,
  `confirmationDate` datetime NOT NULL,
  `topupID` int(11) NOT NULL,
  `methodID` int(11) NOT NULL,
  `bankAccount` varchar(50) NOT NULL,
  `bankName` varchar(50) NOT NULL,
  `accountName` varchar(50) NOT NULL,
  `subTotal` decimal(18,2) NOT NULL,
  PRIMARY KEY (`confirmationID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_confirmationtopup`
--

LOCK TABLES `tr_confirmationtopup` WRITE;
/*!40000 ALTER TABLE `tr_confirmationtopup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_confirmationtopup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_leave`
--

DROP TABLE IF EXISTS `tr_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employeeId` int(11) DEFAULT NULL,
  `leaveId` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `notes` varchar(200) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_leave`
--

LOCK TABLES `tr_leave` WRITE;
/*!40000 ALTER TABLE `tr_leave` DISABLE KEYS */;
INSERT INTO `tr_leave` VALUES (1,1,1,'2019-07-01','2019-07-03','JALAN JALAN','admin','2019-07-21','admin','2019-07-21'),(2,1,1,'2019-07-01','2019-07-16','','admin','2019-07-21','admin','2019-07-21'),(3,1,1,'2019-07-01','2019-07-16','','admin','2019-07-21','admin','2019-07-21');
/*!40000 ALTER TABLE `tr_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_loanproc`
--

DROP TABLE IF EXISTS `tr_loanproc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_loanproc` (
  `id` int(11) NOT NULL,
  `paymentPeriod` varchar(20) DEFAULT NULL,
  `principalPaid` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_loanproc`
--

LOCK TABLES `tr_loanproc` WRITE;
/*!40000 ALTER TABLE `tr_loanproc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_loanproc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payroll`
--

DROP TABLE IF EXISTS `tr_payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payroll` (
  `period` varchar(20) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payroll`
--

LOCK TABLES `tr_payroll` WRITE;
/*!40000 ALTER TABLE `tr_payroll` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payrollproc`
--

DROP TABLE IF EXISTS `tr_payrollproc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payrollproc` (
  `period` varchar(45) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`period`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payrollproc`
--

LOCK TABLES `tr_payrollproc` WRITE;
/*!40000 ALTER TABLE `tr_payrollproc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payrollproc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payrolltaxfinalproc`
--

DROP TABLE IF EXISTS `tr_payrolltaxfinalproc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payrolltaxfinalproc` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payrolltaxfinalproc`
--

LOCK TABLES `tr_payrolltaxfinalproc` WRITE;
/*!40000 ALTER TABLE `tr_payrolltaxfinalproc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payrolltaxfinalproc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payrolltaxincome`
--

DROP TABLE IF EXISTS `tr_payrolltaxincome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payrolltaxincome` (
  `period` varchar(8) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `dependent` varchar(45) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `NettoBefore` decimal(18,2) DEFAULT NULL,
  `PPhBefore` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payrolltaxincome`
--

LOCK TABLES `tr_payrolltaxincome` WRITE;
/*!40000 ALTER TABLE `tr_payrolltaxincome` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payrolltaxincome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payrolltaxmonthlyproc`
--

DROP TABLE IF EXISTS `tr_payrolltaxmonthlyproc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payrolltaxmonthlyproc` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payrolltaxmonthlyproc`
--

LOCK TABLES `tr_payrolltaxmonthlyproc` WRITE;
/*!40000 ALTER TABLE `tr_payrolltaxmonthlyproc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payrolltaxmonthlyproc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_payrolltaxmonthlyprocdummy`
--

DROP TABLE IF EXISTS `tr_payrolltaxmonthlyprocdummy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_payrolltaxmonthlyprocdummy` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_payrolltaxmonthlyprocdummy`
--

LOCK TABLES `tr_payrolltaxmonthlyprocdummy` WRITE;
/*!40000 ALTER TABLE `tr_payrolltaxmonthlyprocdummy` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_payrolltaxmonthlyprocdummy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_topup`
--

DROP TABLE IF EXISTS `tr_topup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_topup` (
  `topupID` int(11) NOT NULL AUTO_INCREMENT,
  `topupDate` datetime NOT NULL,
  `companyID` int(11) NOT NULL,
  `bankID` int(11) NOT NULL,
  `totalTopup` decimal(18,2) NOT NULL,
  `totalPayment` decimal(18,2) DEFAULT NULL,
  `additionalInfo` varchar(200) DEFAULT NULL,
  `createdBy` varchar(50) NOT NULL,
  `topupName` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `status` bit(1) NOT NULL,
  PRIMARY KEY (`topupID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_topup`
--

LOCK TABLES `tr_topup` WRITE;
/*!40000 ALTER TABLE `tr_topup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_topup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_transactionlog`
--

DROP TABLE IF EXISTS `tr_transactionlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_transactionlog` (
  `transactionLogID` int(11) NOT NULL AUTO_INCREMENT,
  `transactionLogDate` datetime NOT NULL,
  `transactionLogDesc` varchar(100) NOT NULL,
  `refNum` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`transactionLogID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_transactionlog`
--

LOCK TABLES `tr_transactionlog` WRITE;
/*!40000 ALTER TABLE `tr_transactionlog` DISABLE KEYS */;
INSERT INTO `tr_transactionlog` VALUES (1,'2019-07-20 07:39:08','Edit Master User Role','ADMIN','admin'),(2,'2019-07-20 16:26:30','Insert Master Position','DEVELOPER MANAGER','admin'),(3,'2019-07-20 16:28:42','Insert Master Bank','KP TIGARAKSA','admin');
/*!40000 ALTER TABLE `tr_transactionlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_working`
--

DROP TABLE IF EXISTS `tr_working`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_working` (
  `nik` varchar(10) DEFAULT NULL,
  `period` varchar(20) DEFAULT NULL,
  `Schedule` int(11) DEFAULT NULL,
  `Actual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_working`
--

LOCK TABLES `tr_working` WRITE;
/*!40000 ALTER TABLE `tr_working` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_working` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_workingtime`
--

DROP TABLE IF EXISTS `tr_workingtime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_workingtime` (
  `nik` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  `shiftCode` varchar(30) DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `gapAct` time DEFAULT NULL,
  `gapSch` time DEFAULT NULL,
  `gap` time DEFAULT NULL,
  `OT1` float DEFAULT NULL,
  `OT2` float DEFAULT NULL,
  `OT3` float DEFAULT NULL,
  `OT4` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_workingtime`
--

LOCK TABLES `tr_workingtime` WRITE;
/*!40000 ALTER TABLE `tr_workingtime` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_workingtime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_workingtimecalc`
--

DROP TABLE IF EXISTS `tr_workingtimecalc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tr_workingtimecalc` (
  `period` varchar(10) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `OT1` float DEFAULT NULL,
  `OT2` float DEFAULT NULL,
  `OT3` float DEFAULT NULL,
  `OT4` float DEFAULT NULL,
  `Total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_workingtimecalc`
--

LOCK TABLES `tr_workingtimecalc` WRITE;
/*!40000 ALTER TABLE `tr_workingtimecalc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_workingtimecalc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vr_crosstab`
--

DROP TABLE IF EXISTS `vr_crosstab`;
/*!50001 DROP VIEW IF EXISTS `vr_crosstab`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vr_crosstab` AS SELECT 
 1 AS `period`,
 1 AS `NIK`,
 1 AS `A01`,
 1 AS `A02`,
 1 AS `A03`,
 1 AS `A04`,
 1 AS `B02`,
 1 AS `D01`,
 1 AS `D02`,
 1 AS `D03`,
 1 AS `JHTCom`,
 1 AS `JHTEmp`,
 1 AS `JKKCom`,
 1 AS `JKKEmp`,
 1 AS `JKMCom`,
 1 AS `JKMEmp`,
 1 AS `JPKCom`,
 1 AS `JPKEmp`,
 1 AS `JPNCom`,
 1 AS `JPNEmp`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vr_crosstab`
--

/*!50001 DROP VIEW IF EXISTS `vr_crosstab`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vr_crosstab` AS select `tr_payroll`.`period` AS `period`,`tr_payroll`.`nik` AS `NIK`,sum((case `tr_payroll`.`payrollCode` when 'A01' then `tr_payroll`.`amount` else 0 end)) AS `A01`,sum((case `tr_payroll`.`payrollCode` when 'A02' then `tr_payroll`.`amount` else 0 end)) AS `A02`,sum((case `tr_payroll`.`payrollCode` when 'A03' then `tr_payroll`.`amount` else 0 end)) AS `A03`,sum((case `tr_payroll`.`payrollCode` when 'A04' then `tr_payroll`.`amount` else 0 end)) AS `A04`,sum((case `tr_payroll`.`payrollCode` when 'B02' then `tr_payroll`.`amount` else 0 end)) AS `B02`,sum((case `tr_payroll`.`payrollCode` when 'D01' then `tr_payroll`.`amount` else 0 end)) AS `D01`,sum((case `tr_payroll`.`payrollCode` when 'D02' then `tr_payroll`.`amount` else 0 end)) AS `D02`,sum((case `tr_payroll`.`payrollCode` when 'D03' then `tr_payroll`.`amount` else 0 end)) AS `D03`,sum((case `tr_payroll`.`payrollCode` when 'JHTCom' then `tr_payroll`.`amount` else 0 end)) AS `JHTCom`,sum((case `tr_payroll`.`payrollCode` when 'JHTEmp' then `tr_payroll`.`amount` else 0 end)) AS `JHTEmp`,sum((case `tr_payroll`.`payrollCode` when 'JKKCom' then `tr_payroll`.`amount` else 0 end)) AS `JKKCom`,sum((case `tr_payroll`.`payrollCode` when 'JKKEmp' then `tr_payroll`.`amount` else 0 end)) AS `JKKEmp`,sum((case `tr_payroll`.`payrollCode` when 'JKMCom' then `tr_payroll`.`amount` else 0 end)) AS `JKMCom`,sum((case `tr_payroll`.`payrollCode` when 'JKMEmp' then `tr_payroll`.`amount` else 0 end)) AS `JKMEmp`,sum((case `tr_payroll`.`payrollCode` when 'JPKCom' then `tr_payroll`.`amount` else 0 end)) AS `JPKCom`,sum((case `tr_payroll`.`payrollCode` when 'JPKEmp' then `tr_payroll`.`amount` else 0 end)) AS `JPKEmp`,sum((case `tr_payroll`.`payrollCode` when 'JPNCom' then `tr_payroll`.`amount` else 0 end)) AS `JPNCom`,sum((case `tr_payroll`.`payrollCode` when 'JPNEmp' then `tr_payroll`.`amount` else 0 end)) AS `JPNEmp` from `tr_payroll` group by `tr_payroll`.`nik`,`tr_payroll`.`period` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-22  7:33:01
