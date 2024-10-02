-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: reseaux_sociaux
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_publication` int NOT NULL,
  `id_compte` int NOT NULL,
  `contenu` text NOT NULL,
  `date_coms` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_publication` (`id_publication`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_publication`) REFERENCES `publication` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,2,1,'koooo','2024-10-02 05:26:06'),(2,2,3,'XD','2024-10-02 09:45:16'),(3,8,3,'kjh','2024-10-02 11:58:42');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compte`
--

DROP TABLE IF EXISTS `compte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compte`
--

LOCK TABLES `compte` WRITE;
/*!40000 ALTER TABLE `compte` DISABLE KEYS */;
INSERT INTO `compte` VALUES (1,'Mino','Henintsoa','mogla23','123@gmail.com'),(2,'Andria','Mifidy','hents45','aro@gmail.com'),(3,'Henintsoa','Mino','123456','andiamifidyhenintsoa@gmail.com'),(4,'Lova','Tiana','789012','lova123@gmail.com'),(5,'Mana','Njara','09876','njara56@gmail.com'),(6,'Tsinjo','Nantosoa','azerty','nanto@gmail.com'),(7,'Jen','Fer','5643','jen56@gmail.com'),(8,'Jen','Fer','5678','jen56@gmail.com'),(9,'Jen','Fer','poiuy','jen56@gmail.com'),(10,'Faly','Soa','RTYUI','soa@gmail.com');
/*!40000 ALTER TABLE `compte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publication` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compte` int NOT NULL,
  `contenu` text NOT NULL,
  `date_pub` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
INSERT INTO `publication` VALUES (2,2,'Kaizaa','2024-09-25 07:49:53'),(3,2,'Salama oh','2024-09-25 07:50:31'),(4,3,'test','2024-10-01 07:45:23'),(5,3,'mety eh','2024-10-01 07:46:29'),(6,3,'Andrana','2024-10-01 07:53:32'),(7,3,'Efa mety ve','2024-10-01 18:39:25'),(8,3,'ary izao? ','2024-10-01 18:40:10');
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_comment`
--

DROP TABLE IF EXISTS `reaction_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_comment` int NOT NULL,
  `id_compte` int NOT NULL,
  `type` enum('like','love','haha','wow','sad','angry') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_comment` (`id_comment`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `reaction_comment_ibfk_1` FOREIGN KEY (`id_comment`) REFERENCES `comments` (`id`),
  CONSTRAINT `reaction_comment_ibfk_2` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_comment`
--

LOCK TABLES `reaction_comment` WRITE;
/*!40000 ALTER TABLE `reaction_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `reaction_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_pub`
--

DROP TABLE IF EXISTS `reaction_pub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction_pub` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_publication` int NOT NULL,
  `id_compte` int NOT NULL,
  `type` enum('like','dislike') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_publication` (`id_publication`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `reaction_pub_ibfk_1` FOREIGN KEY (`id_publication`) REFERENCES `publication` (`id`),
  CONSTRAINT `reaction_pub_ibfk_2` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_pub`
--

LOCK TABLES `reaction_pub` WRITE;
/*!40000 ALTER TABLE `reaction_pub` DISABLE KEYS */;
INSERT INTO `reaction_pub` VALUES (1,2,1,'like'),(3,7,3,'dislike');
/*!40000 ALTER TABLE `reaction_pub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'henintsoa','azert','test@gmail.com');
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

-- Dump completed on 2024-10-02 16:48:38
