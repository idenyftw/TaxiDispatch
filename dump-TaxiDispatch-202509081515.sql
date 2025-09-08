-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: TaxiDispatch
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Drivers`
--

DROP TABLE IF EXISTS `Drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Drivers` (
  `driver_id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`driver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Drivers`
--

LOCK TABLES `Drivers` WRITE;
/*!40000 ALTER TABLE `Drivers` DISABLE KEYS */;
INSERT INTO `Drivers` VALUES (1,'Marc','Dupont'),(2,'Sophie','Lemoine'),(3,'Jean','Morel'),(4,'Claire','Durand'),(5,'Nicolas','Martin'),(6,'Isabelle','Roux'),(7,'Pierre','Girard'),(8,'Julie','Bernard'),(9,'Thomas','Chevalier'),(10,'Camille','Robert'),(11,'David','Lefevre'),(12,'Laura','Fontaine'),(13,'Antoine','Gauthier'),(14,'Charlotte','Blanc'),(15,'Olivier','Renard'),(16,'Pauline','Noel'),(17,'Hugo','Perrot'),(18,'Elodie','Francois'),(19,'Alexandre','Masson'),(20,'Marine','Dubois');
/*!40000 ALTER TABLE `Drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VehicleType`
--

DROP TABLE IF EXISTS `VehicleType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VehicleType` (
  `type_id` int unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(50) NOT NULL,
  `name_fr` varchar(100) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VehicleType`
--

LOCK TABLES `VehicleType` WRITE;
/*!40000 ALTER TABLE `VehicleType` DISABLE KEYS */;
INSERT INTO `VehicleType` VALUES (1,'Sedan','Berline'),(2,'Station Wagon','Break'),(3,'Minivan','Monospace'),(4,'SUV','SUV'),(5,'Luxury Sedan','Berline de luxe'),(6,'Electric Vehicle','Véhicule électrique'),(7,'Hybrid Vehicle','Véhicule hybride'),(8,'Wheelchair Accessible Van','Véhicule accessible aux fauteuils roulants'),(9,'Compact Car','Voiture compacte'),(10,'Executive Car','Voiture exécutive'),(11,'Limousine','Limousine');
/*!40000 ALTER TABLE `VehicleType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Vehicles`
--

DROP TABLE IF EXISTS `Vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Vehicles` (
  `vehicle_id` int unsigned NOT NULL AUTO_INCREMENT,
  `license_plate` varchar(20) NOT NULL,
  `type_id` int unsigned NOT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `Vehicles_UNIQUE` (`license_plate`),
  KEY `Vehicles_VehicleType_FK` (`type_id`),
  CONSTRAINT `Vehicles_VehicleType_FK` FOREIGN KEY (`type_id`) REFERENCES `VehicleType` (`type_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Vehicles`
--

LOCK TABLES `Vehicles` WRITE;
/*!40000 ALTER TABLE `Vehicles` DISABLE KEYS */;
INSERT INTO `Vehicles` VALUES (1,'GE 527',3),(2,'GE 184',1),(3,'GE 692',4),(4,'GE 305',2),(5,'GE 478',1),(6,'GE 849',2),(7,'GE 213',3),(8,'GE 921',4),(9,'GE 356',2),(10,'GE 740',1),(41,'GE 112',2),(42,'GE 459',3),(43,'GE 782',1),(44,'GE 634',4),(45,'GE 298',2),(46,'GE 845',1),(47,'GE 903',3),(48,'GE 341',2),(49,'GE 619',1),(50,'GE 274',4),(51,'GE 785',3),(52,'GE 412',1),(53,'GE 978',2),(54,'GE 563',4),(55,'GE 806',1),(56,'GE 237',3),(57,'GE 652',2),(58,'GE 491',4),(59,'GE 730',1),(60,'GE 853',2),(61,'GE 179',3),(62,'GE 602',4),(63,'GE 918',1),(64,'GE 287',2),(65,'GE 455',3),(66,'GE 724',4),(67,'GE 301',1),(68,'GE 569',2),(69,'GE 842',3);
/*!40000 ALTER TABLE `Vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Zones`
--

DROP TABLE IF EXISTS `Zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Zones` (
  `zone_id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  PRIMARY KEY (`zone_id`),
  UNIQUE KEY `Zones_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Zones`
--

LOCK TABLES `Zones` WRITE;
/*!40000 ALTER TABLE `Zones` DISABLE KEYS */;
INSERT INTO `Zones` VALUES (69,'Genève 1200','1200'),(70,'Genève 1201','1201'),(71,'Genève 1202','1202'),(72,'Genève 1203','1203'),(73,'Genève 1204','1204'),(74,'Genève 1205','1205'),(75,'Genève 1206','1206'),(76,'Genève 1207','1207'),(77,'Genève 1208','1208'),(78,'Genève 1209','1209'),(79,'Genève 1211','1211'),(80,'Lancy','1212'),(81,'Onex','1213'),(82,'Vernier','1214'),(83,'Genève 1215','1215'),(84,'Genève 1216','1216'),(85,'Genève 1217','1217'),(86,'Le Grand-Saconnex','1218'),(87,'Vernier 1219','1219'),(88,'Vernier 1220','1220'),(89,'Genève 1221','1221'),(90,'Collonge-Bellerive','1222'),(91,'Cologny','1223'),(92,'Chêne-Bougeries','1224'),(93,'Chêne-Bourg','1225'),(94,'Thônex','1226'),(95,'Carouge','1227'),(96,'Plan-les-Ouates','1228'),(97,'Genève 1229','1229'),(98,'Genève 1230','1230'),(99,'Chêne-Bougeries 1231','1231'),(100,'Confignon','1232'),(101,'Bernex','1233'),(102,'Veyrier','1234'),(103,'Genève 1235','1235'),(104,'Cartigny','1236'),(105,'Avully','1237'),(106,'Genève 1238','1238'),(107,'Collex-Bossy','1239'),(108,'Genève 1240','1240'),(109,'Puplinge','1241'),(110,'Satigny','1242'),(111,'Presinge','1243'),(112,'Choulex','1244'),(113,'Collonge-Bellerive 1245','1245'),(114,'Corsier','1246'),(115,'Anières','1247'),(116,'Hermance','1248'),(117,'Gy','1251'),(118,'Meinier','1252'),(119,'Vandœuvres','1253'),(120,'Jussy','1254'),(121,'Veyrier 1255','1255'),(122,'Troinex','1256'),(123,'Bardonnex','1257'),(124,'Perly-Certoux','1258'),(125,'Russin','1281'),(126,'Dardagny','1283'),(127,'Chancy','1284'),(128,'Avusy','1285'),(129,'Soral','1286'),(130,'Laconnex','1287'),(131,'Aire-la-Ville','1288'),(132,'Versoix','1290'),(133,'Pregny-Chambésy','1292'),(134,'Bellevue','1293'),(135,'Genthod','1294'),(136,'Céligny','1298');
/*!40000 ALTER TABLE `Zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'TaxiDispatch'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-08 15:15:58
