-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: panaderia-otto
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `almacen_item`
--

DROP TABLE IF EXISTS `almacen_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacen_item` (
  `id_almacen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_almacen`,`id_item`),
  KEY `almacen_item_id_item_foreign` (`id_item`),
  CONSTRAINT `almacen_item_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id_almacen`) ON DELETE CASCADE,
  CONSTRAINT `almacen_item_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen_item`
--

LOCK TABLES `almacen_item` WRITE;
/*!40000 ALTER TABLE `almacen_item` DISABLE KEYS */;
INSERT INTO `almacen_item` VALUES (1,1,1,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(1,2,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(1,3,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(1,4,30,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(1,5,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(1,6,931,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(1,7,25,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(1,8,900,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(1,9,200,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(1,10,320,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(1,11,122,'2026-05-27 06:31:46','2026-05-27 06:31:46'),(2,1,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(2,2,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(2,3,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(2,4,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(2,5,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(2,6,2802,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(2,7,600,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(2,8,47,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(2,9,1150,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(2,10,320,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,1,1464,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,2,3131,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,3,4370,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,4,392,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,5,5618,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(3,6,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(3,7,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(3,8,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(3,9,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(3,10,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(3,11,212,'2026-05-21 04:19:41','2026-05-21 04:19:41'),(4,1,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,2,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,3,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,4,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,5,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,6,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,7,410,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(4,8,1800,'2026-05-21 00:28:25','2026-05-21 00:33:59'),(4,9,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(4,10,0,'2026-05-21 00:28:25','2026-05-21 00:28:25'),(5,10,450,'2026-05-27 01:17:59','2026-05-27 01:17:59');
/*!40000 ALTER TABLE `almacen_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacenes`
--

DROP TABLE IF EXISTS `almacenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacenes` (
  `id_almacen` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `ubicacion` varchar(35) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `tipo_almacen` enum('insumo','producto','mixto') NOT NULL DEFAULT 'mixto',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_almacen`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacenes`
--

LOCK TABLES `almacenes` WRITE;
/*!40000 ALTER TABLE `almacenes` DISABLE KEYS */;
INSERT INTO `almacenes` VALUES (1,'Almacén Central','Zona Norte',5000,'mixto','2026-05-21 04:52:05','2026-05-21 04:52:05'),(2,'Almacén Insumos','Zona Sur',3000,'insumo','2026-05-21 04:52:05','2026-05-21 04:52:05'),(3,'Almacén Productos','Zona Este',2000,'producto','2026-05-21 04:52:05','2026-05-21 04:52:05'),(4,'Almacén Refrigerado','Zona Oeste',1500,'mixto','2026-05-21 04:52:05','2026-05-21 04:52:05'),(5,'reña','123',21300,'insumo','2026-05-27 01:15:14','2026-05-27 01:15:14'),(6,'remoli','calle sea',213213,'mixto','2026-05-27 06:29:38','2026-05-27 06:29:38'),(7,'z almacen','1212',2131321,'producto','2026-05-27 06:32:58','2026-05-27 06:32:58'),(8,'Regia','calle tipica',10000,'mixto','2026-05-27 17:00:25','2026-05-27 17:00:25');
/*!40000 ALTER TABLE `almacenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_insumo`
--

DROP TABLE IF EXISTS `categoria_insumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_insumo` (
  `id_cat_insumo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(35) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cat_insumo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_insumo`
--

LOCK TABLES `categoria_insumo` WRITE;
/*!40000 ALTER TABLE `categoria_insumo` DISABLE KEYS */;
INSERT INTO `categoria_insumo` VALUES (1,'Harinas','Harinas de trigo, maíz, etc.','2026-05-21 04:51:56','2026-05-21 04:51:56'),(2,'Lácteos','Leche, mantequilla, crema','2026-05-21 04:51:56','2026-05-21 04:51:56'),(3,'Huevos','Huevos frescos','2026-05-21 04:51:56','2026-05-21 04:51:56'),(4,'Azúcares','Azúcar, miel, edulcorantes','2026-05-21 04:51:56','2026-05-21 04:51:56'),(5,'Levaduras','Levadura fresca y seca','2026-05-21 04:51:56','2026-05-21 04:51:56'),(6,'asdf','fas','2026-05-27 06:30:17','2026-05-27 06:30:17'),(7,'dafsf','asdf','2026-05-27 06:30:26','2026-05-27 06:30:26'),(8,'Leudados','dsafs','2026-05-27 06:30:52','2026-05-27 06:30:52'),(9,'zetajines','fadsf','2026-05-27 06:31:09','2026-05-27 06:31:09');
/*!40000 ALTER TABLE `categoria_insumo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_producto`
--

DROP TABLE IF EXISTS `categoria_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_producto` (
  `id_cat_producto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(35) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cat_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_producto`
--

LOCK TABLES `categoria_producto` WRITE;
/*!40000 ALTER TABLE `categoria_producto` DISABLE KEYS */;
INSERT INTO `categoria_producto` VALUES (1,'Pan Dulce','Panes dulces tradicionales','2026-05-21 04:51:56','2026-05-21 04:51:56'),(2,'Pan Salado','Panes salados y empanadas','2026-05-21 04:51:56','2026-05-21 04:51:56'),(3,'Pastelería','Pasteles, tartas y postres','2026-05-21 04:51:56','2026-05-21 04:51:56'),(4,'Galletas','Galletas y bizcochos','2026-05-21 04:51:56','2026-05-21 04:51:56'),(5,'dasf2','ewe','2026-05-27 06:32:37','2026-05-27 06:32:37');
/*!40000 ALTER TABLE `categoria_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `telefono` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Ana','González',70001001,'2026-05-21 04:52:12','2026-05-21 04:52:12'),(2,'Luis','Ramírez',70001002,'2026-05-21 04:52:12','2026-05-21 04:52:12'),(3,'Martha','Sánchez',70001003,'2026-05-21 04:52:13','2026-05-21 04:52:13'),(4,'marcos','jaci',12312,'2026-05-21 02:07:54','2026-05-21 02:07:54'),(5,'Cliente','Anónimo',0,'2026-05-21 04:20:32','2026-05-21 04:20:32'),(6,'Carlos','Mendoza',70000000,'2026-05-26 04:58:47','2026-05-26 04:58:47'),(7,'Marcos','fdsasdf',212421321,'2026-05-26 17:33:46','2026-05-26 17:33:46'),(8,'federico','sebastian',21312,'2026-05-28 02:50:39','2026-05-28 02:50:39'),(9,'asdf','312',1231,'2026-05-28 02:52:18','2026-05-28 02:52:18');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_inventario`
--

DROP TABLE IF EXISTS `configuracion_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_inventario` (
  `id_config` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `metodo_valuacion_predeterminado` enum('PEPS','UEPS') NOT NULL DEFAULT 'PEPS',
  `automatizar_movimientos` tinyint(1) NOT NULL DEFAULT 1,
  `requerir_aprobacion` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_inventario`
--

LOCK TABLES `configuracion_inventario` WRITE;
/*!40000 ALTER TABLE `configuracion_inventario` DISABLE KEYS */;
INSERT INTO `configuracion_inventario` VALUES (1,'PEPS',1,0,'2026-05-21 01:07:56','2026-05-21 01:07:56');
/*!40000 ALTER TABLE `configuracion_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_produccion`
--

DROP TABLE IF EXISTS `detalle_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_produccion` (
  `id_detalle_produccion` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produccion` bigint(20) unsigned NOT NULL,
  `id_detalle_receta` bigint(20) unsigned DEFAULT NULL,
  `id_almacen` bigint(20) unsigned DEFAULT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `tipo_movimiento` enum('ingreso','egreso') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle_produccion`),
  KEY `detalle_produccion_id_detalle_receta_foreign` (`id_detalle_receta`),
  KEY `detalle_produccion_id_almacen_id_item_foreign` (`id_almacen`,`id_item`),
  KEY `detalle_produccion_id_produccion_tipo_movimiento_index` (`id_produccion`,`tipo_movimiento`),
  CONSTRAINT `detalle_produccion_id_detalle_receta_foreign` FOREIGN KEY (`id_detalle_receta`) REFERENCES `detalle_receta` (`id_detalle_receta`),
  CONSTRAINT `detalle_produccion_id_produccion_foreign` FOREIGN KEY (`id_produccion`) REFERENCES `producciones` (`id_produccion`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_produccion`
--

LOCK TABLES `detalle_produccion` WRITE;
/*!40000 ALTER TABLE `detalle_produccion` DISABLE KEYS */;
INSERT INTO `detalle_produccion` VALUES (1,1,3,2,8,1500,'egreso','2026-01-20 12:00:00','2026-05-21 00:52:14'),(2,1,NULL,3,1,500,'ingreso','2026-01-20 12:00:00','2026-05-21 00:52:14'),(3,2,3,2,8,900,'egreso','2026-02-15 13:30:00','2026-05-21 00:52:14'),(4,2,NULL,3,1,300,'ingreso','2026-02-15 13:30:00','2026-05-21 00:52:14'),(5,3,NULL,3,2,800,'ingreso','2026-03-05 11:00:00','2026-05-21 00:52:14'),(6,4,NULL,3,3,2000,'ingreso','2026-04-10 08:00:00','2026-05-21 00:52:14'),(7,5,18,2,8,750,'egreso','2026-05-01 14:00:00','2026-05-21 00:52:14'),(8,5,NULL,3,4,150,'ingreso','2026-05-01 14:00:00','2026-05-21 00:52:14'),(9,6,NULL,3,5,3000,'ingreso','2026-06-20 10:00:00','2026-05-21 00:52:14'),(10,10,NULL,3,4,200,'ingreso','2026-10-20 15:00:00','2026-05-21 00:52:14'),(11,11,NULL,3,5,2500,'ingreso','2026-11-15 09:00:00','2026-05-21 00:52:14'),(12,12,NULL,3,2,1200,'ingreso','2026-12-10 11:30:00','2026-05-21 00:52:14'),(13,13,1,2,6,500,'egreso','2026-05-21 05:12:59','2026-05-21 05:13:10'),(14,13,2,2,7,150,'egreso','2026-05-21 05:12:59','2026-05-21 05:13:10'),(15,13,3,2,8,3,'egreso','2026-05-21 05:12:59','2026-05-21 05:13:10'),(16,13,4,2,9,200,'egreso','2026-05-21 05:12:59','2026-05-21 05:13:10'),(17,13,5,2,10,10,'egreso','2026-05-21 05:12:59','2026-05-21 05:13:10'),(18,13,NULL,1,1,1,'ingreso','2026-05-21 05:12:59','2026-05-21 05:13:10');
/*!40000 ALTER TABLE `detalle_produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_receta`
--

DROP TABLE IF EXISTS `detalle_receta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_receta` (
  `id_detalle_receta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_receta` bigint(20) unsigned NOT NULL,
  `id_insumo` bigint(20) unsigned NOT NULL,
  `cantidad_requerida` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle_receta`),
  UNIQUE KEY `detalle_receta_id_receta_id_insumo_unique` (`id_receta`,`id_insumo`),
  KEY `detalle_receta_id_insumo_foreign` (`id_insumo`),
  CONSTRAINT `detalle_receta_id_insumo_foreign` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id_insumo`) ON DELETE CASCADE,
  CONSTRAINT `detalle_receta_id_receta_foreign` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_receta`
--

LOCK TABLES `detalle_receta` WRITE;
/*!40000 ALTER TABLE `detalle_receta` DISABLE KEYS */;
INSERT INTO `detalle_receta` VALUES (1,1,1,500,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(2,1,2,150,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(3,1,3,3,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(4,1,4,200,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(5,1,5,10,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(6,2,1,400,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(7,2,2,100,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(8,2,3,2,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(9,2,4,120,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(10,2,5,8,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(12,3,2,50,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(13,3,3,1,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(14,3,4,10,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(15,3,5,5,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(16,4,1,800,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(17,4,2,300,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(18,4,3,5,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(19,4,4,400,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(20,4,5,15,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(21,5,1,250,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(22,5,2,80,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(23,5,3,1,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(24,5,4,100,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(25,5,5,4,'2026-05-21 04:52:05','2026-05-21 04:52:05'),(26,6,1,1,'2026-05-28 02:56:17','2026-05-28 02:56:17'),(27,6,4,1,'2026-05-28 02:56:17','2026-05-28 02:56:17'),(28,7,1,1,'2026-05-28 02:57:53','2026-05-28 02:57:53'),(29,7,3,1,'2026-05-28 02:57:53','2026-05-28 02:57:53');
/*!40000 ALTER TABLE `detalle_receta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_compra`
--

DROP TABLE IF EXISTS `detalles_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_compra` (
  `id_detalle_compra` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_nota_compra` bigint(20) unsigned NOT NULL,
  `id_almacen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `precio`) STORED,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle_compra`),
  UNIQUE KEY `detalles_compra_unique` (`id_nota_compra`,`id_almacen`,`id_item`),
  KEY `detalles_compra_id_item_foreign` (`id_item`),
  KEY `detalles_compra_id_almacen_id_item_foreign` (`id_almacen`,`id_item`),
  CONSTRAINT `detalles_compra_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id_almacen`),
  CONSTRAINT `detalles_compra_id_almacen_id_item_foreign` FOREIGN KEY (`id_almacen`, `id_item`) REFERENCES `almacen_item` (`id_almacen`, `id_item`),
  CONSTRAINT `detalles_compra_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`),
  CONSTRAINT `detalles_compra_id_nota_compra_foreign` FOREIGN KEY (`id_nota_compra`) REFERENCES `notas_compra` (`id_nota_compra`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_compra`
--

LOCK TABLES `detalles_compra` WRITE;
/*!40000 ALTER TABLE `detalles_compra` DISABLE KEYS */;
INSERT INTO `detalles_compra` VALUES (5,4,2,6,1500,17.80,26700.00,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(6,4,2,9,800,21.20,16960.00,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(7,4,2,10,350,14.70,5145.00,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(8,4,2,7,500,82.50,41250.00,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(9,4,2,8,1800,4.25,7650.00,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(10,5,2,6,800,18.00,14400.00,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(11,5,2,9,400,22.00,8800.00,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(12,5,2,10,180,15.00,2700.00,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(13,5,2,7,250,84.00,21000.00,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(14,5,2,8,1200,4.40,5280.00,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(15,6,4,7,180,85.00,15300.00,'2026-03-05 13:45:00','2026-05-21 00:33:59'),(16,6,4,8,1500,4.50,6750.00,'2026-03-05 13:45:00','2026-05-21 00:33:59'),(17,7,1,6,600,19.00,11400.00,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(18,7,1,9,350,23.00,8050.00,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(19,7,1,10,120,16.00,1920.00,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(20,7,1,7,150,88.00,13200.00,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(21,7,1,8,900,4.80,4320.00,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(22,8,2,6,400,19.50,7800.00,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(23,8,2,9,250,23.50,5875.00,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(24,8,2,10,100,16.50,1650.00,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(25,8,2,7,100,90.00,9000.00,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(26,8,2,8,600,5.00,3000.00,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(27,9,3,1,500,25.00,12500.00,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(28,9,3,2,1000,12.00,12000.00,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(29,9,3,3,2000,4.50,9000.00,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(30,9,3,4,100,85.00,8500.00,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(31,9,3,5,800,8.00,6400.00,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(32,10,3,1,300,26.00,7800.00,'2026-03-15 14:00:00','2026-05-21 00:33:59'),(33,10,3,2,800,12.50,10000.00,'2026-03-15 14:00:00','2026-05-21 00:33:59'),(34,10,3,3,1500,4.80,7200.00,'2026-03-15 14:00:00','2026-05-21 00:33:59'),(35,10,3,5,600,8.50,5100.00,'2026-03-15 14:00:00','2026-05-21 00:33:59'),(36,11,2,6,300,20.00,6000.00,'2026-04-25 13:00:00','2026-05-21 00:33:59'),(37,11,2,9,200,24.00,4800.00,'2026-04-25 13:00:00','2026-05-21 00:33:59'),(38,12,2,8,500,5.10,2550.00,'2026-05-19 00:33:59','2026-05-21 00:33:59'),(39,12,2,7,80,92.00,7360.00,'2026-05-19 00:33:59','2026-05-21 00:33:59'),(40,13,4,7,120,91.00,10920.00,'2026-05-26 00:33:59','2026-05-21 00:33:59'),(41,13,4,8,800,5.20,4160.00,'2026-05-26 00:33:59','2026-05-21 00:33:59'),(42,13,4,10,150,17.00,2550.00,'2026-05-26 00:33:59','2026-05-21 00:33:59'),(43,14,1,7,25,12.00,300.00,'2026-05-27 01:09:49','2026-05-27 01:09:49'),(44,15,1,6,333,12.00,3996.00,'2026-05-27 01:11:08','2026-05-27 01:11:08'),(45,16,5,10,129,12.00,1548.00,'2026-05-27 01:17:59','2026-05-27 01:17:59'),(46,17,5,10,321,12.00,3852.00,'2026-05-27 01:21:34','2026-05-27 01:21:34');
/*!40000 ALTER TABLE `detalles_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_venta`
--

DROP TABLE IF EXISTS `detalles_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_venta` (
  `id_detalle_venta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_nota_venta` bigint(20) unsigned NOT NULL,
  `id_almacen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `precio`) STORED,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle_venta`),
  UNIQUE KEY `detalles_venta_unique` (`id_nota_venta`,`id_almacen`,`id_item`),
  KEY `detalles_venta_id_item_foreign` (`id_item`),
  KEY `detalles_venta_id_almacen_id_item_foreign` (`id_almacen`,`id_item`),
  CONSTRAINT `detalles_venta_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id_almacen`),
  CONSTRAINT `detalles_venta_id_almacen_id_item_foreign` FOREIGN KEY (`id_almacen`, `id_item`) REFERENCES `almacen_item` (`id_almacen`, `id_item`),
  CONSTRAINT `detalles_venta_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`),
  CONSTRAINT `detalles_venta_id_nota_venta_foreign` FOREIGN KEY (`id_nota_venta`) REFERENCES `notas_venta` (`id_nota_venta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_venta`
--

LOCK TABLES `detalles_venta` WRITE;
/*!40000 ALTER TABLE `detalles_venta` DISABLE KEYS */;
INSERT INTO `detalles_venta` VALUES (1,1,3,1,10,25.50,255.00,'2026-01-18 14:30:00','2026-05-21 01:03:42'),(2,1,3,2,20,12.00,240.00,'2026-01-18 14:30:00','2026-05-21 01:03:42'),(3,1,3,3,30,4.50,135.00,'2026-01-18 14:30:00','2026-05-21 01:03:42'),(4,2,3,4,2,85.00,170.00,'2026-01-25 19:45:00','2026-05-21 01:03:42'),(5,2,3,5,50,8.00,400.00,'2026-01-25 19:45:00','2026-05-21 01:03:42'),(6,2,3,2,15,12.00,180.00,'2026-01-25 19:45:00','2026-05-21 01:03:42'),(7,3,3,1,5,25.50,127.50,'2026-02-14 13:15:00','2026-05-21 01:03:42'),(8,3,3,3,40,4.50,180.00,'2026-02-14 13:15:00','2026-05-21 01:03:42'),(9,3,3,5,30,8.00,240.00,'2026-02-14 13:15:00','2026-05-21 01:03:42'),(10,5,3,3,100,4.50,450.00,'2026-04-12 18:20:00','2026-05-21 01:03:42'),(11,5,3,2,50,12.00,600.00,'2026-04-12 18:20:00','2026-05-21 01:03:42'),(12,5,3,1,20,25.50,510.00,'2026-04-12 18:20:00','2026-05-21 01:03:42'),(13,5,3,5,80,8.00,640.00,'2026-04-12 18:20:00','2026-05-21 01:03:42'),(14,6,3,4,5,85.00,425.00,'2026-05-20 20:30:00','2026-05-21 01:03:42'),(15,6,3,5,200,8.00,1600.00,'2026-05-20 20:30:00','2026-05-21 01:03:42'),(16,6,3,2,100,12.00,1200.00,'2026-05-20 20:30:00','2026-05-21 01:03:42'),(17,6,3,3,150,4.50,675.00,'2026-05-20 20:30:00','2026-05-21 01:03:42'),(18,7,3,1,8,25.50,204.00,'2026-06-05 12:45:00','2026-05-21 01:03:42'),(19,7,3,3,60,4.50,270.00,'2026-06-05 12:45:00','2026-05-21 01:03:42'),(20,8,3,4,3,85.00,255.00,'2026-07-19 16:00:00','2026-05-21 01:03:42'),(21,8,3,5,120,8.00,960.00,'2026-07-19 16:00:00','2026-05-21 01:03:42'),(22,8,3,2,40,12.00,480.00,'2026-07-19 16:00:00','2026-05-21 01:03:42'),(23,10,3,3,200,4.50,900.00,'2026-09-10 14:30:00','2026-05-21 01:03:42'),(24,10,3,2,80,12.00,960.00,'2026-09-10 14:30:00','2026-05-21 01:03:42'),(25,11,3,5,300,8.00,2400.00,'2026-10-15 13:00:00','2026-05-21 01:03:42'),(26,11,3,1,25,25.50,637.50,'2026-10-15 13:00:00','2026-05-21 01:03:42'),(27,12,3,4,8,85.00,680.00,'2026-11-25 18:00:00','2026-05-21 01:03:42'),(28,12,3,2,150,12.00,1800.00,'2026-11-25 18:00:00','2026-05-21 01:03:42'),(29,12,3,3,250,4.50,1125.00,'2026-11-25 18:00:00','2026-05-21 01:03:42'),(30,13,3,4,10,85.00,850.00,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(31,13,3,5,500,8.00,4000.00,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(32,13,3,1,50,25.50,1275.00,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(33,13,3,2,200,12.00,2400.00,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(34,13,3,3,300,4.50,1350.00,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(35,14,3,11,1,0.10,0.10,'2026-05-21 04:20:32','2026-05-21 04:20:32'),(36,15,3,2,1,0.10,0.10,'2026-05-21 04:29:18','2026-05-21 04:29:18'),(37,16,3,2,1,0.10,0.10,'2026-05-21 04:35:39','2026-05-21 04:35:39'),(38,17,3,1,1,0.10,0.10,'2026-05-26 04:45:21','2026-05-26 04:45:21'),(39,18,3,5,1,0.10,0.10,'2026-05-26 04:50:42','2026-05-26 04:50:42'),(40,19,3,1,1,0.10,0.10,'2026-05-26 04:58:47','2026-05-26 04:58:47'),(41,20,3,1,1,0.10,0.10,'2026-05-26 05:01:27','2026-05-26 05:01:27'),(42,21,3,1,1,0.10,0.10,'2026-05-26 05:28:09','2026-05-26 05:28:09'),(43,22,3,1,1,0.10,0.10,'2026-05-26 17:36:51','2026-05-26 17:36:51'),(44,23,3,1,1,0.10,0.10,'2026-05-27 05:45:51','2026-05-27 05:45:51'),(45,24,3,1,1,0.10,0.10,'2026-05-27 05:46:14','2026-05-27 05:46:14'),(46,25,3,5,1,0.10,0.10,'2026-05-27 16:41:50','2026-05-27 16:41:50'),(47,26,3,1,12,0.10,1.20,'2026-05-27 17:37:56','2026-05-27 17:37:56'),(48,26,3,2,12,12.00,144.00,'2026-05-27 17:37:56','2026-05-27 17:37:56');
/*!40000 ALTER TABLE `detalles_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados` (
  `id_empleado` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(35) NOT NULL,
  `fecha_nac` date NOT NULL,
  `sueldo` int(11) NOT NULL,
  `edad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Carlos','Mendoza',70000000,'Av. Principal #100','1990-05-15',5000,36,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(2,'Lizeth','García',70000002,'Calle 2','1995-08-22',3200,30,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(3,'Roberto','Flores',70000003,'Calle 3','1992-03-10',3300,34,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(4,'Dennis','Rodríguez',70000001,'Calle 1','1993-11-28',3500,32,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(5,'Mario','López',70000004,'Calle 4','1991-07-05',3400,34,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(6,'Juan','Pérez',70000005,'Calle 5','1998-01-19',2800,28,'2026-05-20 23:46:52','2026-05-20 23:46:52'),(7,'jalisco','ekfjs',213123,'calle seja','2009-12-12',1212,21,'2026-05-21 01:41:44','2026-05-21 01:41:44'),(8,'jalisco','ekfjs',213123,'calle seja','2009-12-12',1212,21,'2026-05-21 01:41:44','2026-05-21 01:41:44'),(9,'rafae','joes',121,'calle rebales','2009-12-12',202,19,'2026-05-21 01:47:26','2026-05-21 01:47:26'),(10,'rafae','joes',121,'calle rebales','2009-12-12',202,19,'2026-05-21 01:47:26','2026-05-21 01:47:26'),(11,'Pan de leche','cuevas',1231,'caselle','2002-12-12',12012,21,'2026-05-21 01:55:44','2026-05-21 01:55:44'),(12,'Pan de leche','cuevas',1231,'caselle','2002-12-12',12012,21,'2026-05-21 01:55:44','2026-05-21 01:55:44'),(13,'Pan de leche','cuevas',1231,'caselle','2002-12-12',12012,21,'2026-05-21 01:55:44','2026-05-21 01:55:44'),(14,'romulo','dias',32321,'callese','2009-12-12',122,21,'2026-05-21 01:59:13','2026-05-21 01:59:13'),(15,'romulo','dias',32321,'callese','2009-12-12',122,21,'2026-05-21 01:59:13','2026-05-21 01:59:13'),(16,'romulo','dias',32321,'callese','2009-12-12',122,21,'2026-05-21 01:59:13','2026-05-21 01:59:13'),(17,'jacinto','bena',123123,'alle 121','2009-12-12',122,34,'2026-05-21 02:06:54','2026-05-21 02:06:54'),(18,'zinai','zs',231312,'calle lepra','2009-12-12',212,55,'2026-05-21 02:07:34','2026-05-21 02:07:34');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insumos` (
  `id_insumo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_item` bigint(20) unsigned NOT NULL,
  `id_cat_insumo` bigint(20) unsigned NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_insumo`),
  KEY `insumos_id_item_foreign` (`id_item`),
  KEY `insumos_id_cat_insumo_foreign` (`id_cat_insumo`),
  CONSTRAINT `insumos_id_cat_insumo_foreign` FOREIGN KEY (`id_cat_insumo`) REFERENCES `categoria_insumo` (`id_cat_insumo`),
  CONSTRAINT `insumos_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES (1,6,1,18.50,'2026-05-21 04:51:56','2026-05-21 04:51:56'),(2,7,2,85.00,'2026-05-21 04:51:56','2026-05-21 04:51:56'),(3,8,3,32.00,'2026-05-21 04:51:56','2026-05-21 04:51:56'),(4,9,4,22.00,'2026-05-21 04:51:56','2026-05-21 04:51:56'),(5,10,5,15.00,'2026-05-21 04:51:56','2026-05-21 04:51:56'),(6,11,9,123.00,'2026-05-27 06:31:32','2026-05-27 06:31:32');
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id_item` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_item` varchar(25) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `unidad_medida` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'producto','Pan de Muerto','kg','2026-05-21 04:51:56','2026-05-26 04:36:45'),(2,'producto','Concha','pieza','2026-05-21 04:51:56','2026-05-21 04:51:56'),(3,'producto','Bolillo','pieza','2026-05-21 04:51:56','2026-05-21 04:51:56'),(4,'producto','Pastel de Chocolate','porción','2026-05-21 04:51:56','2026-05-21 04:51:56'),(5,'producto','Galleta María','kg','2026-05-21 04:51:56','2026-05-26 04:36:57'),(6,'insumo','Harina de Trigo','kg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(7,'insumo','Mantequilla','kg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(8,'insumo','Huevo','pieza','2026-05-21 04:51:56','2026-05-21 04:51:56'),(9,'insumo','Azúcar Estándar','kg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(10,'insumo','Levadura Seca','sobre','2026-05-21 04:51:56','2026-05-21 04:51:56'),(11,'insumo','zroleadas','kg','2026-05-27 06:31:32','2026-05-27 06:31:32'),(12,'producto','asfas','unidad','2026-05-27 06:32:27','2026-05-27 06:32:27');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes_inventario`
--

DROP TABLE IF EXISTS `lotes_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotes_inventario` (
  `id_lote` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_almacen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad_inicial` decimal(12,2) NOT NULL,
  `cantidad_disponible` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `metodo_valuacion` enum('PEPS','UEPS') NOT NULL DEFAULT 'PEPS',
  `estado` enum('disponible','consumido','anulado') NOT NULL DEFAULT 'disponible',
  `referencia_id` bigint(20) unsigned DEFAULT NULL,
  `referencia_tipo` enum('compra','produccion','traspaso') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_lote`),
  KEY `lotes_inventario_id_almacen_id_item_estado_index` (`id_almacen`,`id_item`,`estado`),
  KEY `lotes_inventario_id_item_metodo_valuacion_estado_index` (`id_item`,`metodo_valuacion`,`estado`),
  KEY `lotes_inventario_fecha_entrada_index` (`fecha_entrada`),
  KEY `lotes_inventario_referencia_tipo_referencia_id_index` (`referencia_tipo`,`referencia_id`),
  CONSTRAINT `lotes_inventario_id_almacen_id_item_foreign` FOREIGN KEY (`id_almacen`, `id_item`) REFERENCES `almacen_item` (`id_almacen`, `id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes_inventario`
--

LOCK TABLES `lotes_inventario` WRITE;
/*!40000 ALTER TABLE `lotes_inventario` DISABLE KEYS */;
INSERT INTO `lotes_inventario` VALUES (2,2,6,1500.00,500.00,17.80,'2026-01-15 10:30:00','2026-05-26 15:01:15',NULL,'PEPS','disponible',4,'compra','2026-01-15 14:30:00','2026-05-26 19:01:15'),(3,2,9,800.00,600.00,21.20,'2026-01-15 10:30:00','2026-05-21 01:13:10',NULL,'PEPS','disponible',4,'compra','2026-01-15 14:30:00','2026-05-21 05:13:10'),(4,2,10,350.00,140.00,14.70,'2026-01-15 10:30:00','2026-05-21 01:13:10',NULL,'PEPS','disponible',4,'compra','2026-01-15 14:30:00','2026-05-21 05:13:10'),(5,2,7,500.00,270.00,82.50,'2026-01-15 10:30:00','2026-05-21 01:13:10',NULL,'PEPS','disponible',4,'compra','2026-01-15 14:30:00','2026-05-21 05:13:10'),(6,2,8,1800.00,0.00,4.25,'2026-01-15 10:30:00',NULL,NULL,'PEPS','consumido',4,'compra','2026-01-15 14:30:00','2026-05-21 00:52:14'),(7,2,6,800.00,800.00,18.00,'2026-02-10 11:00:00',NULL,NULL,'PEPS','disponible',5,'compra','2026-02-10 15:00:00','2026-05-21 00:33:59'),(8,2,9,400.00,400.00,22.00,'2026-02-10 11:00:00',NULL,NULL,'PEPS','disponible',5,'compra','2026-02-10 15:00:00','2026-05-21 00:33:59'),(9,2,10,180.00,180.00,15.00,'2026-02-10 11:00:00',NULL,NULL,'PEPS','disponible',5,'compra','2026-02-10 15:00:00','2026-05-21 00:33:59'),(10,2,7,250.00,250.00,84.00,'2026-02-10 11:00:00',NULL,NULL,'PEPS','disponible',5,'compra','2026-02-10 15:00:00','2026-05-21 00:33:59'),(11,2,8,1200.00,0.00,4.40,'2026-02-10 11:00:00',NULL,NULL,'PEPS','consumido',5,'compra','2026-02-10 15:00:00','2026-05-21 00:52:14'),(12,4,7,180.00,180.00,85.00,'2026-03-05 09:45:00',NULL,NULL,'PEPS','disponible',6,'compra','2026-03-05 13:45:00','2026-05-21 00:33:59'),(13,4,8,1500.00,1500.00,4.50,'2026-03-05 09:45:00',NULL,NULL,'PEPS','disponible',6,'compra','2026-03-05 13:45:00','2026-05-21 00:33:59'),(14,1,6,600.00,98.00,19.00,'2026-04-12 14:20:00','2026-05-20 21:10:04',NULL,'PEPS','disponible',7,'compra','2026-04-12 18:20:00','2026-05-21 01:16:54'),(15,1,9,350.00,200.00,23.00,'2026-04-12 14:20:00',NULL,NULL,'PEPS','disponible',7,'compra','2026-04-12 18:20:00','2026-05-21 01:23:51'),(16,1,10,120.00,120.00,16.00,'2026-04-12 14:20:00',NULL,NULL,'PEPS','disponible',7,'compra','2026-04-12 18:20:00','2026-05-21 00:33:59'),(17,1,7,150.00,0.00,88.00,'2026-04-12 14:20:00',NULL,NULL,'PEPS','consumido',7,'compra','2026-04-12 18:20:00','2026-05-21 01:18:59'),(18,1,8,900.00,900.00,4.80,'2026-04-12 14:20:00',NULL,NULL,'PEPS','disponible',7,'compra','2026-04-12 18:20:00','2026-05-21 00:33:59'),(19,3,1,500.00,500.00,25.00,'2026-01-20 12:00:00',NULL,NULL,'PEPS','disponible',9,'compra','2026-01-20 16:00:00','2026-05-21 00:33:59'),(20,3,2,1000.00,331.00,12.00,'2026-01-20 12:00:00','2026-05-27 13:37:56',NULL,'PEPS','disponible',9,'compra','2026-01-20 16:00:00','2026-05-27 17:37:56'),(21,3,3,2000.00,870.00,4.50,'2026-01-20 12:00:00',NULL,NULL,'PEPS','disponible',9,'compra','2026-01-20 16:00:00','2026-05-21 01:03:42'),(22,3,4,100.00,42.00,85.00,'2026-01-20 12:00:00',NULL,NULL,'PEPS','disponible',9,'compra','2026-01-20 16:00:00','2026-05-21 01:23:51'),(23,3,5,800.00,0.00,8.00,'2026-01-20 12:00:00',NULL,NULL,'PEPS','consumido',9,'compra','2026-01-20 16:00:00','2026-05-21 01:03:42'),(24,3,1,300.00,300.00,26.00,'2026-03-15 10:00:00',NULL,NULL,'PEPS','disponible',10,'compra','2026-03-15 14:00:00','2026-05-21 00:33:59'),(25,3,2,800.00,800.00,12.50,'2026-03-15 10:00:00',NULL,NULL,'PEPS','disponible',10,'compra','2026-03-15 14:00:00','2026-05-21 00:33:59'),(26,3,3,1500.00,1500.00,4.80,'2026-03-15 10:00:00',NULL,NULL,'PEPS','disponible',10,'compra','2026-03-15 14:00:00','2026-05-21 00:33:59'),(27,3,5,600.00,118.00,8.50,'2026-03-15 10:00:00','2026-05-27 13:00:07',NULL,'PEPS','disponible',10,'compra','2026-03-15 14:00:00','2026-05-27 17:00:07'),(28,2,8,500.00,47.00,5.10,'2026-05-18 20:33:59','2026-05-21 01:13:10',NULL,'PEPS','disponible',12,'compra','2026-05-19 00:33:59','2026-05-21 05:13:10'),(29,2,7,80.00,80.00,92.00,'2026-05-18 20:33:59',NULL,NULL,'PEPS','disponible',12,'compra','2026-05-19 00:33:59','2026-05-21 00:33:59'),(30,3,1,500.00,364.00,25.50,'2026-01-20 08:00:00','2026-05-27 13:37:56',NULL,'PEPS','disponible',1,'produccion','2026-01-20 12:00:00','2026-05-27 17:37:56'),(31,3,1,300.00,300.00,25.50,'2026-02-15 09:30:00','2026-02-25 09:30:00',NULL,'PEPS','disponible',2,'produccion','2026-02-15 13:30:00','2026-05-21 00:52:14'),(32,3,2,800.00,800.00,12.00,'2026-03-05 07:00:00','2026-03-10 07:00:00',NULL,'PEPS','disponible',3,'produccion','2026-03-05 11:00:00','2026-05-21 00:52:14'),(33,3,3,2000.00,2000.00,4.50,'2026-04-10 04:00:00','2026-04-12 04:00:00',NULL,'PEPS','disponible',4,'produccion','2026-04-10 08:00:00','2026-05-21 00:52:14'),(34,3,4,150.00,150.00,85.00,'2026-05-01 10:00:00','2026-05-15 10:00:00',NULL,'PEPS','disponible',5,'produccion','2026-05-01 14:00:00','2026-05-21 00:52:14'),(35,3,5,3000.00,3000.00,8.00,'2026-06-20 06:00:00','2026-07-20 06:00:00',NULL,'PEPS','disponible',6,'produccion','2026-06-20 10:00:00','2026-05-21 00:52:14'),(36,3,4,200.00,200.00,85.00,'2026-10-20 11:00:00','2026-11-03 11:00:00',NULL,'PEPS','disponible',10,'produccion','2026-10-20 15:00:00','2026-05-21 00:52:14'),(37,3,5,2500.00,2500.00,8.00,'2026-11-15 05:00:00','2026-12-15 05:00:00',NULL,'PEPS','disponible',11,'produccion','2026-11-15 09:00:00','2026-05-21 00:52:14'),(38,3,2,1200.00,1200.00,12.00,'2026-12-10 07:30:00','2026-12-15 07:30:00',NULL,'PEPS','disponible',12,'produccion','2026-12-10 11:30:00','2026-05-21 00:52:14'),(39,2,6,2.00,2.00,0.00,'2026-05-20 21:10:04',NULL,NULL,'PEPS','disponible',2,'traspaso','2026-05-21 01:10:04','2026-05-21 01:10:04'),(40,1,10,100.00,100.00,14.70,'2026-02-05 11:15:00',NULL,NULL,'PEPS','disponible',6,'compra','2026-02-05 15:15:00','2026-05-21 01:23:51'),(41,4,7,80.00,80.00,82.50,'2026-04-12 09:30:00',NULL,NULL,'PEPS','disponible',7,'compra','2026-04-12 13:30:00','2026-05-21 01:23:51'),(42,4,8,300.00,300.00,5.10,'2026-04-12 09:30:00',NULL,NULL,'PEPS','disponible',7,'compra','2026-04-12 13:30:00','2026-05-21 01:23:51'),(43,1,4,30.00,30.00,85.00,'2026-05-20 12:00:00',NULL,NULL,'PEPS','disponible',8,'compra','2026-05-20 16:00:00','2026-05-21 01:23:51'),(44,2,9,150.00,150.00,23.00,'2026-07-08 10:00:00',NULL,NULL,'PEPS','disponible',9,'compra','2026-07-08 14:00:00','2026-05-21 01:23:51'),(45,1,1,1.00,1.00,0.00,'2026-05-21 01:13:10',NULL,'2029-12-12','PEPS','disponible',13,'produccion','2026-05-21 05:13:10','2026-05-21 05:13:10'),(55,1,6,250.00,250.00,0.00,'2026-05-26 15:01:15',NULL,NULL,'PEPS','disponible',18,'traspaso','2026-05-26 19:01:15','2026-05-26 19:01:15'),(56,1,6,250.00,250.00,17.80,'2026-01-15 10:30:00',NULL,NULL,'PEPS','disponible',18,'traspaso','2026-05-26 19:01:15','2026-05-26 19:01:15'),(57,1,7,25.00,25.00,12.00,'2026-05-26 21:09:49',NULL,'2029-12-12','PEPS','disponible',14,'compra','2026-05-27 01:09:49','2026-05-27 01:09:49'),(58,1,6,333.00,333.00,12.00,'2026-05-26 21:11:08',NULL,'2030-12-12','PEPS','disponible',15,'compra','2026-05-27 01:11:08','2026-05-27 01:11:08'),(59,5,10,129.00,129.00,12.00,'2026-05-26 21:17:59',NULL,'2029-12-12','PEPS','disponible',16,'compra','2026-05-27 01:17:59','2026-05-27 01:17:59'),(60,5,10,321.00,321.00,12.00,'2026-05-26 21:21:34',NULL,'2030-01-01','PEPS','disponible',17,'compra','2026-05-27 01:21:34','2026-05-27 01:21:34');
/*!40000 ALTER TABLE `lotes_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'0001_01_01_000003_create_clientes_table',1),(5,'0001_01_01_000004_create_empleados_table',1),(6,'0001_01_01_000006.9_create_categoria_producto_table',1),(7,'0001_01_01_000006_create_items_table',1),(8,'0001_01_01_000007.9_create_categoria_insumo_table',1),(9,'0001_01_01_000007_create_productos_table',1),(10,'0001_01_01_000008.9_create_recetas_table',1),(11,'0001_01_01_000008_create_insumos_table',1),(12,'0001_01_01_000009_create_proveedores_table',1),(13,'0001_01_01_000010_create_pempresa_table',1),(14,'0001_01_01_000011_create_ppersona_table',1),(15,'0001_01_01_000012_create_roles_table',1),(16,'0001_01_01_000013_create_permisos_table',1),(17,'0001_01_01_000014_create_rol_permiso_table',1),(18,'0001_01_01_000015_create_usuarios_table',1),(19,'0001_01_01_000016_create_rol_permiso_usuario_table',1),(20,'0001_01_01_000017_create_almacenes_table',1),(21,'0001_01_01_000018_create_almacen_item_table',1),(22,'0001_01_01_000019_create_notas_venta_table',1),(23,'0001_01_01_000020_create_movimientos_inventario_table',1),(24,'0001_01_01_000020_create_notas_compra_table',1),(25,'0001_01_01_000021_create_detalles_venta_table',1),(26,'0001_01_01_000021_create_traspasos_inventario_table',1),(27,'0001_01_01_000022_create_detalles_compra_table',1),(28,'0001_01_01_000022_create_lotes_inventario_table',1),(29,'0001_01_01_000023_create_configuracion_inventario_table',1),(30,'0001_01_01_000023_create_traspasos_table',1),(31,'0001_01_01_000024_create_traspaso_almacen_item_table',1),(32,'0001_01_01_000025_create_detalle_receta_table',1),(33,'0001_01_01_000026_create_producciones_table',1),(34,'0001_01_01_000027_create_detalle_produccion_table',1),(35,'2026_04_29_140734_create_transacciones_libelula_table',1),(36,'2026_04_29_185309_modify_url_pasarela_to_text_in_transacciones_libelula',1),(37,'2026_04_29_231447_make_id_almacen_nullable_in_detalle_produccion',1),(38,'2026_05_04_214337_add_traspaso_to_lotes_referencia_tipo',1),(39,'2026_05_11_224313_add_fecha_vencimiento_to_lotes_inventario',1),(40,'2026_05_20_140124_create_page_visits_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_inventario`
--

DROP TABLE IF EXISTS `movimientos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos_inventario` (
  `id_movimiento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_movimiento` enum('ingreso','egreso','traspaso_origen','traspaso_destino','ajuste') NOT NULL,
  `id_almacen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `costo_total` decimal(14,2) NOT NULL,
  `fecha_movimiento` datetime NOT NULL,
  `referencia_id` bigint(20) unsigned DEFAULT NULL,
  `referencia_tipo` enum('compra','venta','produccion','ajuste','traspaso') DEFAULT NULL,
  `estado` enum('completado','pendiente','cancelado') NOT NULL DEFAULT 'completado',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `movimientos_inventario_id_item_foreign` (`id_item`),
  KEY `movimientos_inventario_id_almacen_id_item_index` (`id_almacen`,`id_item`),
  KEY `movimientos_inventario_tipo_movimiento_index` (`tipo_movimiento`),
  KEY `movimientos_inventario_fecha_movimiento_index` (`fecha_movimiento`),
  KEY `movimientos_inventario_referencia_tipo_referencia_id_index` (`referencia_tipo`,`referencia_id`),
  CONSTRAINT `movimientos_inventario_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id_almacen`),
  CONSTRAINT `movimientos_inventario_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
INSERT INTO `movimientos_inventario` VALUES (2,'ingreso',2,6,1500.00,17.80,26700.00,'2026-01-15 10:30:00',4,'compra','completado','Compra masiva de enero - inicio de año','2026-01-15 14:30:00','2026-05-21 00:33:59'),(3,'ingreso',2,9,800.00,21.20,16960.00,'2026-01-15 10:30:00',4,'compra','completado','Compra masiva de enero - inicio de año','2026-01-15 14:30:00','2026-05-21 00:33:59'),(4,'ingreso',2,10,350.00,14.70,5145.00,'2026-01-15 10:30:00',4,'compra','completado','Compra masiva de enero - inicio de año','2026-01-15 14:30:00','2026-05-21 00:33:59'),(5,'ingreso',2,7,500.00,82.50,41250.00,'2026-01-15 10:30:00',4,'compra','completado','Compra masiva de enero - inicio de año','2026-01-15 14:30:00','2026-05-21 00:33:59'),(6,'ingreso',2,8,1800.00,4.25,7650.00,'2026-01-15 10:30:00',4,'compra','completado','Compra masiva de enero - inicio de año','2026-01-15 14:30:00','2026-05-21 00:33:59'),(7,'ingreso',2,6,800.00,18.00,14400.00,'2026-02-10 11:00:00',5,'compra','completado','Compra febrero - temporada de pan dulce','2026-02-10 15:00:00','2026-05-21 00:33:59'),(8,'ingreso',2,9,400.00,22.00,8800.00,'2026-02-10 11:00:00',5,'compra','completado','Compra febrero - temporada de pan dulce','2026-02-10 15:00:00','2026-05-21 00:33:59'),(9,'ingreso',2,10,180.00,15.00,2700.00,'2026-02-10 11:00:00',5,'compra','completado','Compra febrero - temporada de pan dulce','2026-02-10 15:00:00','2026-05-21 00:33:59'),(10,'ingreso',2,7,250.00,84.00,21000.00,'2026-02-10 11:00:00',5,'compra','completado','Compra febrero - temporada de pan dulce','2026-02-10 15:00:00','2026-05-21 00:33:59'),(11,'ingreso',2,8,1200.00,4.40,5280.00,'2026-02-10 11:00:00',5,'compra','completado','Compra febrero - temporada de pan dulce','2026-02-10 15:00:00','2026-05-21 00:33:59'),(12,'ingreso',4,7,180.00,85.00,15300.00,'2026-03-05 09:45:00',6,'compra','completado','Compra marzo - lácteos refrigerados','2026-03-05 13:45:00','2026-05-21 00:33:59'),(13,'ingreso',4,8,1500.00,4.50,6750.00,'2026-03-05 09:45:00',6,'compra','completado','Compra marzo - lácteos refrigerados','2026-03-05 13:45:00','2026-05-21 00:33:59'),(14,'ingreso',1,6,600.00,19.00,11400.00,'2026-04-12 14:20:00',7,'compra','completado','Compra abril - precios elevados por temporada','2026-04-12 18:20:00','2026-05-21 00:33:59'),(15,'ingreso',1,9,350.00,23.00,8050.00,'2026-04-12 14:20:00',7,'compra','completado','Compra abril - precios elevados por temporada','2026-04-12 18:20:00','2026-05-21 00:33:59'),(16,'ingreso',1,10,120.00,16.00,1920.00,'2026-04-12 14:20:00',7,'compra','completado','Compra abril - precios elevados por temporada','2026-04-12 18:20:00','2026-05-21 00:33:59'),(17,'ingreso',1,7,150.00,88.00,13200.00,'2026-04-12 14:20:00',7,'compra','completado','Compra abril - precios elevados por temporada','2026-04-12 18:20:00','2026-05-21 00:33:59'),(18,'ingreso',1,8,900.00,4.80,4320.00,'2026-04-12 14:20:00',7,'compra','completado','Compra abril - precios elevados por temporada','2026-04-12 18:20:00','2026-05-21 00:33:59'),(19,'ingreso',3,1,500.00,25.00,12500.00,'2026-01-20 12:00:00',9,'compra','completado','Compra de productos terminados para venta','2026-01-20 16:00:00','2026-05-21 00:33:59'),(20,'ingreso',3,2,1000.00,12.00,12000.00,'2026-01-20 12:00:00',9,'compra','completado','Compra de productos terminados para venta','2026-01-20 16:00:00','2026-05-21 00:33:59'),(21,'ingreso',3,3,2000.00,4.50,9000.00,'2026-01-20 12:00:00',9,'compra','completado','Compra de productos terminados para venta','2026-01-20 16:00:00','2026-05-21 00:33:59'),(22,'ingreso',3,4,100.00,85.00,8500.00,'2026-01-20 12:00:00',9,'compra','completado','Compra de productos terminados para venta','2026-01-20 16:00:00','2026-05-21 00:33:59'),(23,'ingreso',3,5,800.00,8.00,6400.00,'2026-01-20 12:00:00',9,'compra','completado','Compra de productos terminados para venta','2026-01-20 16:00:00','2026-05-21 00:33:59'),(24,'ingreso',3,1,300.00,26.00,7800.00,'2026-03-15 10:00:00',10,'compra','completado','Compra de productos - temporada baja','2026-03-15 14:00:00','2026-05-21 00:33:59'),(25,'ingreso',3,2,800.00,12.50,10000.00,'2026-03-15 10:00:00',10,'compra','completado','Compra de productos - temporada baja','2026-03-15 14:00:00','2026-05-21 00:33:59'),(26,'ingreso',3,3,1500.00,4.80,7200.00,'2026-03-15 10:00:00',10,'compra','completado','Compra de productos - temporada baja','2026-03-15 14:00:00','2026-05-21 00:33:59'),(27,'ingreso',3,5,600.00,8.50,5100.00,'2026-03-15 10:00:00',10,'compra','completado','Compra de productos - temporada baja','2026-03-15 14:00:00','2026-05-21 00:33:59'),(28,'ingreso',2,8,500.00,5.10,2550.00,'2026-05-18 20:33:59',12,'compra','completado','Compra exprés por alta demanda','2026-05-19 00:33:59','2026-05-21 00:33:59'),(29,'ingreso',2,7,80.00,92.00,7360.00,'2026-05-18 20:33:59',12,'compra','completado','Compra exprés por alta demanda','2026-05-19 00:33:59','2026-05-21 00:33:59'),(30,'egreso',2,8,1500.00,0.00,0.00,'2026-01-20 08:00:00',1,'produccion','completado','Consumo para producción #1','2026-01-20 12:00:00','2026-05-21 00:52:14'),(31,'ingreso',3,1,500.00,25.50,12750.00,'2026-01-20 08:00:00',1,'produccion','completado','Producción #1','2026-01-20 12:00:00','2026-05-21 00:52:14'),(32,'egreso',2,8,900.00,0.00,0.00,'2026-02-15 09:30:00',2,'produccion','completado','Consumo para producción #2','2026-02-15 13:30:00','2026-05-21 00:52:14'),(33,'ingreso',3,1,300.00,25.50,7650.00,'2026-02-15 09:30:00',2,'produccion','completado','Producción #2','2026-02-15 13:30:00','2026-05-21 00:52:14'),(34,'ingreso',3,2,800.00,12.00,9600.00,'2026-03-05 07:00:00',3,'produccion','completado','Producción #3','2026-03-05 11:00:00','2026-05-21 00:52:14'),(35,'ingreso',3,3,2000.00,4.50,9000.00,'2026-04-10 04:00:00',4,'produccion','completado','Producción #4','2026-04-10 08:00:00','2026-05-21 00:52:14'),(36,'egreso',2,8,750.00,0.00,0.00,'2026-05-01 10:00:00',5,'produccion','completado','Consumo para producción #5','2026-05-01 14:00:00','2026-05-21 00:52:14'),(37,'ingreso',3,4,150.00,85.00,12750.00,'2026-05-01 10:00:00',5,'produccion','completado','Producción #5','2026-05-01 14:00:00','2026-05-21 00:52:14'),(38,'ingreso',3,5,3000.00,8.00,24000.00,'2026-06-20 06:00:00',6,'produccion','completado','Producción #6','2026-06-20 10:00:00','2026-05-21 00:52:14'),(39,'ingreso',3,4,200.00,85.00,17000.00,'2026-10-20 11:00:00',10,'produccion','completado','Producción #10','2026-10-20 15:00:00','2026-05-21 00:52:14'),(40,'ingreso',3,5,2500.00,8.00,20000.00,'2026-11-15 05:00:00',11,'produccion','completado','Producción #11','2026-11-15 09:00:00','2026-05-21 00:52:14'),(41,'ingreso',3,2,1200.00,12.00,14400.00,'2026-12-10 07:30:00',12,'produccion','completado','Producción #12','2026-12-10 11:30:00','2026-05-21 00:52:14'),(42,'egreso',3,1,10.00,25.50,255.00,'2026-01-18 10:30:00',1,'venta','completado','Venta #1 - Cliente: Ana González','2026-01-18 14:30:00','2026-05-21 01:03:42'),(43,'egreso',3,2,20.00,12.00,240.00,'2026-01-18 10:30:00',1,'venta','completado','Venta #1 - Cliente: Ana González','2026-01-18 14:30:00','2026-05-21 01:03:42'),(44,'egreso',3,3,30.00,4.50,135.00,'2026-01-18 10:30:00',1,'venta','completado','Venta #1 - Cliente: Ana González','2026-01-18 14:30:00','2026-05-21 01:03:42'),(45,'egreso',3,4,2.00,85.00,170.00,'2026-01-25 15:45:00',2,'venta','completado','Venta #2 - Cliente: Luis Ramírez','2026-01-25 19:45:00','2026-05-21 01:03:42'),(46,'egreso',3,5,50.00,8.00,400.00,'2026-01-25 15:45:00',2,'venta','completado','Venta #2 - Cliente: Luis Ramírez','2026-01-25 19:45:00','2026-05-21 01:03:42'),(47,'egreso',3,2,15.00,12.00,180.00,'2026-01-25 15:45:00',2,'venta','completado','Venta #2 - Cliente: Luis Ramírez','2026-01-25 19:45:00','2026-05-21 01:03:42'),(48,'egreso',3,1,5.00,25.50,127.50,'2026-02-14 09:15:00',3,'venta','completado','Venta #3 - Cliente: Martha Sánchez','2026-02-14 13:15:00','2026-05-21 01:03:42'),(49,'egreso',3,3,40.00,4.50,180.00,'2026-02-14 09:15:00',3,'venta','completado','Venta #3 - Cliente: Martha Sánchez','2026-02-14 13:15:00','2026-05-21 01:03:42'),(50,'egreso',3,5,30.00,8.00,240.00,'2026-02-14 09:15:00',3,'venta','completado','Venta #3 - Cliente: Martha Sánchez','2026-02-14 13:15:00','2026-05-21 01:03:42'),(51,'egreso',3,3,100.00,4.50,450.00,'2026-04-12 14:20:00',5,'venta','completado','Venta #5 - Cliente: Luis Ramírez','2026-04-12 18:20:00','2026-05-21 01:03:42'),(52,'egreso',3,2,50.00,12.00,600.00,'2026-04-12 14:20:00',5,'venta','completado','Venta #5 - Cliente: Luis Ramírez','2026-04-12 18:20:00','2026-05-21 01:03:42'),(53,'egreso',3,1,20.00,25.50,510.00,'2026-04-12 14:20:00',5,'venta','completado','Venta #5 - Cliente: Luis Ramírez','2026-04-12 18:20:00','2026-05-21 01:03:42'),(54,'egreso',3,5,80.00,8.00,640.00,'2026-04-12 14:20:00',5,'venta','completado','Venta #5 - Cliente: Luis Ramírez','2026-04-12 18:20:00','2026-05-21 01:03:42'),(55,'egreso',3,4,5.00,85.00,425.00,'2026-05-20 16:30:00',6,'venta','completado','Venta #6 - Cliente: Martha Sánchez','2026-05-20 20:30:00','2026-05-21 01:03:42'),(56,'egreso',3,5,200.00,8.00,1600.00,'2026-05-20 16:30:00',6,'venta','completado','Venta #6 - Cliente: Martha Sánchez','2026-05-20 20:30:00','2026-05-21 01:03:42'),(57,'egreso',3,2,100.00,12.00,1200.00,'2026-05-20 16:30:00',6,'venta','completado','Venta #6 - Cliente: Martha Sánchez','2026-05-20 20:30:00','2026-05-21 01:03:42'),(58,'egreso',3,3,150.00,4.50,675.00,'2026-05-20 16:30:00',6,'venta','completado','Venta #6 - Cliente: Martha Sánchez','2026-05-20 20:30:00','2026-05-21 01:03:42'),(59,'egreso',3,1,8.00,25.50,204.00,'2026-06-05 08:45:00',7,'venta','completado','Venta #7 - Cliente: Ana González','2026-06-05 12:45:00','2026-05-21 01:03:42'),(60,'egreso',3,3,60.00,4.50,270.00,'2026-06-05 08:45:00',7,'venta','completado','Venta #7 - Cliente: Ana González','2026-06-05 12:45:00','2026-05-21 01:03:42'),(61,'egreso',3,4,3.00,85.00,255.00,'2026-07-19 12:00:00',8,'venta','completado','Venta #8 - Cliente: Luis Ramírez','2026-07-19 16:00:00','2026-05-21 01:03:42'),(62,'egreso',3,5,120.00,8.00,960.00,'2026-07-19 12:00:00',8,'venta','completado','Venta #8 - Cliente: Luis Ramírez','2026-07-19 16:00:00','2026-05-21 01:03:42'),(63,'egreso',3,2,40.00,12.00,480.00,'2026-07-19 12:00:00',8,'venta','completado','Venta #8 - Cliente: Luis Ramírez','2026-07-19 16:00:00','2026-05-21 01:03:42'),(64,'egreso',3,3,200.00,4.50,900.00,'2026-09-10 10:30:00',10,'venta','completado','Venta #10 - Cliente: Ana González','2026-09-10 14:30:00','2026-05-21 01:03:42'),(65,'egreso',3,2,80.00,12.00,960.00,'2026-09-10 10:30:00',10,'venta','completado','Venta #10 - Cliente: Ana González','2026-09-10 14:30:00','2026-05-21 01:03:42'),(66,'egreso',3,5,300.00,8.00,2400.00,'2026-10-15 09:00:00',11,'venta','completado','Venta #11 - Cliente: Luis Ramírez','2026-10-15 13:00:00','2026-05-21 01:03:42'),(67,'egreso',3,1,25.00,25.50,637.50,'2026-10-15 09:00:00',11,'venta','completado','Venta #11 - Cliente: Luis Ramírez','2026-10-15 13:00:00','2026-05-21 01:03:42'),(68,'egreso',3,4,8.00,85.00,680.00,'2026-11-25 14:00:00',12,'venta','completado','Venta #12 - Cliente: Martha Sánchez','2026-11-25 18:00:00','2026-05-21 01:03:42'),(69,'egreso',3,2,150.00,12.00,1800.00,'2026-11-25 14:00:00',12,'venta','completado','Venta #12 - Cliente: Martha Sánchez','2026-11-25 18:00:00','2026-05-21 01:03:42'),(70,'egreso',3,3,250.00,4.50,1125.00,'2026-11-25 14:00:00',12,'venta','completado','Venta #12 - Cliente: Martha Sánchez','2026-11-25 18:00:00','2026-05-21 01:03:42'),(71,'egreso',3,4,10.00,85.00,850.00,'2026-12-20 11:30:00',13,'venta','completado','Venta #13 - Cliente: Ana González','2026-12-20 15:30:00','2026-05-21 01:03:42'),(72,'egreso',3,5,500.00,8.00,4000.00,'2026-12-20 11:30:00',13,'venta','completado','Venta #13 - Cliente: Ana González','2026-12-20 15:30:00','2026-05-21 01:03:42'),(73,'egreso',3,1,50.00,25.50,1275.00,'2026-12-20 11:30:00',13,'venta','completado','Venta #13 - Cliente: Ana González','2026-12-20 15:30:00','2026-05-21 01:03:42'),(74,'egreso',3,2,200.00,12.00,2400.00,'2026-12-20 11:30:00',13,'venta','completado','Venta #13 - Cliente: Ana González','2026-12-20 15:30:00','2026-05-21 01:03:42'),(75,'egreso',3,3,300.00,4.50,1350.00,'2026-12-20 11:30:00',13,'venta','completado','Venta #13 - Cliente: Ana González','2026-12-20 15:30:00','2026-05-21 01:03:42'),(76,'traspaso_origen',1,6,-2.00,0.00,0.00,'2026-05-20 21:10:04',2,'traspaso','completado','Salida por traspaso #2','2026-05-21 01:10:04','2026-05-21 01:10:04'),(77,'traspaso_destino',2,6,2.00,0.00,0.00,'2026-05-20 21:10:04',2,'traspaso','completado','Entrada por traspaso #2','2026-05-21 01:10:04','2026-05-21 01:10:04'),(78,'traspaso_origen',2,10,100.00,0.00,0.00,'2026-02-05 11:15:00',6,'traspaso','completado','Traspaso a Almacén Central','2026-02-05 15:15:00','2026-05-21 01:23:51'),(79,'traspaso_destino',1,10,100.00,0.00,0.00,'2026-02-05 11:15:00',6,'traspaso','completado','Traspaso desde Almacén Insumos','2026-02-05 15:15:00','2026-05-21 01:23:51'),(80,'traspaso_origen',2,7,80.00,0.00,0.00,'2026-04-12 09:30:00',7,'traspaso','completado','Traspaso a Almacén Refrigerado','2026-04-12 13:30:00','2026-05-21 01:23:51'),(81,'traspaso_destino',4,7,80.00,0.00,0.00,'2026-04-12 09:30:00',7,'traspaso','completado','Traspaso desde Almacén Insumos','2026-04-12 13:30:00','2026-05-21 01:23:51'),(82,'traspaso_origen',2,8,300.00,0.00,0.00,'2026-04-12 09:30:00',7,'traspaso','completado','Traspaso a Almacén Refrigerado','2026-04-12 13:30:00','2026-05-21 01:23:51'),(83,'traspaso_destino',4,8,300.00,0.00,0.00,'2026-04-12 09:30:00',7,'traspaso','completado','Traspaso desde Almacén Insumos','2026-04-12 13:30:00','2026-05-21 01:23:51'),(84,'traspaso_origen',3,4,30.00,0.00,0.00,'2026-05-20 12:00:00',8,'traspaso','completado','Traspaso a Almacén Central','2026-05-20 16:00:00','2026-05-21 01:23:51'),(85,'traspaso_destino',1,4,30.00,0.00,0.00,'2026-05-20 12:00:00',8,'traspaso','completado','Traspaso desde Almacén Productos','2026-05-20 16:00:00','2026-05-21 01:23:51'),(86,'traspaso_origen',1,9,150.00,0.00,0.00,'2026-07-08 10:00:00',9,'traspaso','completado','Traspaso a Almacén Insumos','2026-07-08 14:00:00','2026-05-21 01:23:51'),(87,'traspaso_destino',2,9,150.00,0.00,0.00,'2026-07-08 10:00:00',9,'traspaso','completado','Traspaso desde Almacén Central','2026-07-08 14:00:00','2026-05-21 01:23:51'),(88,'egreso',2,6,-500.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Consumo para producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(89,'egreso',2,7,-150.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Consumo para producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(90,'egreso',2,8,-3.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Consumo para producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(91,'egreso',2,9,-200.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Consumo para producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(92,'egreso',2,10,-10.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Consumo para producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(93,'ingreso',1,1,1.00,0.00,0.00,'2026-05-21 01:13:10',13,'produccion','completado','Ingreso de producción #13','2026-05-21 05:13:10','2026-05-21 05:13:10'),(94,'egreso',3,5,-1.00,0.10,-0.10,'2026-05-26 00:51:24',18,'venta','completado','Egreso automático por pago confirmado - Venta #18','2026-05-26 04:51:24','2026-05-26 04:51:24'),(95,'egreso',3,1,-1.00,0.10,-0.10,'2026-05-26 00:59:05',19,'venta','completado','Egreso automático por pago confirmado - Venta #19','2026-05-26 04:59:05','2026-05-26 04:59:05'),(96,'egreso',3,1,-1.00,0.10,-0.10,'2026-05-26 01:01:53',20,'venta','completado','Egreso automático por pago confirmado - Venta #20','2026-05-26 05:01:53','2026-05-26 05:01:53'),(109,'traspaso_origen',2,6,-250.00,0.00,0.00,'2026-05-26 15:01:15',18,'traspaso','completado','Salida por traspaso #18','2026-05-26 19:01:15','2026-05-26 19:01:15'),(110,'traspaso_destino',1,6,250.00,0.00,0.00,'2026-05-26 15:01:15',18,'traspaso','completado','Entrada por traspaso #18','2026-05-26 19:01:15','2026-05-26 19:01:15'),(111,'traspaso_origen',2,6,-250.00,17.80,-4450.00,'2026-05-26 15:01:15',18,'traspaso','completado','Traspaso #18 hacia almacén 1','2026-05-26 19:01:15','2026-05-26 19:01:15'),(112,'traspaso_destino',1,6,250.00,17.80,4450.00,'2026-05-26 15:01:15',18,'traspaso','completado','Traspaso #18 desde almacén 2','2026-05-26 19:01:15','2026-05-26 19:01:15'),(113,'ingreso',1,7,25.00,12.00,300.00,'2026-05-26 21:09:49',14,'compra','completado','Ingreso por compra #14','2026-05-27 01:09:49','2026-05-27 01:09:49'),(114,'ingreso',1,6,333.00,12.00,3996.00,'2026-05-26 21:11:08',15,'compra','completado','Ingreso por compra #15','2026-05-27 01:11:08','2026-05-27 01:11:08'),(115,'ingreso',5,10,129.00,12.00,1548.00,'2026-05-26 21:17:59',16,'compra','completado','Ingreso por compra #16','2026-05-27 01:17:59','2026-05-27 01:17:59'),(116,'ingreso',5,10,321.00,12.00,3852.00,'2026-05-26 21:21:34',17,'compra','completado','Ingreso por compra #17','2026-05-27 01:21:34','2026-05-27 01:21:34'),(117,'egreso',3,1,-12.00,0.10,-1.20,'2026-05-27 13:37:56',26,'venta','completado','Egreso por venta #26','2026-05-27 17:37:56','2026-05-27 17:37:56'),(118,'egreso',3,2,-12.00,12.00,-144.00,'2026-05-27 13:37:56',26,'venta','completado','Egreso por venta #26','2026-05-27 17:37:56','2026-05-27 17:37:56');
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas_compra`
--

DROP TABLE IF EXISTS `notas_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas_compra` (
  `id_nota_compra` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_compra` date NOT NULL,
  `monto_total` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `id_empleado` bigint(20) unsigned NOT NULL,
  `id_proveedor` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_nota_compra`),
  KEY `notas_compra_id_empleado_foreign` (`id_empleado`),
  KEY `notas_compra_id_proveedor_foreign` (`id_proveedor`),
  CONSTRAINT `notas_compra_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`) ON DELETE CASCADE,
  CONSTRAINT `notas_compra_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas_compra`
--

LOCK TABLES `notas_compra` WRITE;
/*!40000 ALTER TABLE `notas_compra` DISABLE KEYS */;
INSERT INTO `notas_compra` VALUES (4,'2026-01-15',97705,'completado',3,1,'2026-01-15 14:30:00','2026-05-21 00:33:59'),(5,'2026-02-10',52180,'completado',3,2,'2026-02-10 15:00:00','2026-05-21 00:33:59'),(6,'2026-03-05',22050,'completado',5,1,'2026-03-05 13:45:00','2026-05-21 00:33:59'),(7,'2026-04-12',38890,'completado',3,3,'2026-04-12 18:20:00','2026-05-21 00:33:59'),(8,'2026-05-18',27325,'pendiente',3,2,'2026-05-18 19:30:00','2026-05-21 00:33:59'),(9,'2026-01-20',48400,'completado',1,1,'2026-01-20 16:00:00','2026-05-21 00:33:59'),(10,'2026-03-15',30100,'completado',5,2,'2026-03-15 14:00:00','2026-05-21 00:33:59'),(11,'2026-04-25',10800,'cancelado',3,3,'2026-04-25 13:00:00','2026-05-21 00:33:59'),(12,'2026-05-18',9910,'completado',3,1,'2026-05-19 00:33:59','2026-05-21 00:33:59'),(13,'2026-05-25',17630,'pendiente',5,2,'2026-05-26 00:33:59','2026-05-21 00:33:59'),(14,'2026-05-26',300,'completado',1,6,'2026-05-27 01:09:49','2026-05-27 01:09:49'),(15,'2026-05-26',3996,'completado',1,5,'2026-05-27 01:11:08','2026-05-27 01:11:08'),(16,'2026-05-26',1548,'completado',1,5,'2026-05-27 01:17:59','2026-05-27 01:17:59'),(17,'2026-05-26',3852,'completado',1,1,'2026-05-27 01:21:34','2026-05-27 01:21:34');
/*!40000 ALTER TABLE `notas_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas_venta`
--

DROP TABLE IF EXISTS `notas_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas_venta` (
  `id_nota_venta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_venta` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `metodo_pago` varchar(50) DEFAULT NULL,
  `id_transaccion_libelula` varchar(255) DEFAULT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_empleado` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_nota_venta`),
  KEY `notas_venta_id_cliente_foreign` (`id_cliente`),
  KEY `notas_venta_id_empleado_foreign` (`id_empleado`),
  KEY `notas_venta_estado_index` (`estado`),
  KEY `notas_venta_metodo_pago_index` (`metodo_pago`),
  CONSTRAINT `notas_venta_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `notas_venta_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas_venta`
--

LOCK TABLES `notas_venta` WRITE;
/*!40000 ALTER TABLE `notas_venta` DISABLE KEYS */;
INSERT INTO `notas_venta` VALUES (1,'2026-01-18',630.00,'completado','efectivo','TRX-6A0E21AE64A40',1,2,'2026-01-18 14:30:00','2026-05-21 01:03:42'),(2,'2026-01-25',750.00,'completado','tarjeta','TRX-6A0E21AE6EA31',2,6,'2026-01-25 19:45:00','2026-05-21 01:03:42'),(3,'2026-02-14',547.50,'completado','efectivo','TRX-6A0E21AE774BD',3,2,'2026-02-14 13:15:00','2026-05-21 01:03:42'),(4,'2026-03-08',385.00,'completado','transferencia',NULL,1,1,'2026-03-08 15:00:00','2026-05-21 03:14:06'),(5,'2026-04-12',2200.00,'completado','tarjeta','TRX-6A0E21AE7FC7C',2,1,'2026-04-12 18:20:00','2026-05-21 01:03:42'),(6,'2026-05-20',3900.00,'completado','efectivo','TRX-6A0E21AE88F00',3,2,'2026-05-20 20:30:00','2026-05-21 01:03:42'),(7,'2026-06-05',474.00,'completado','tarjeta','TRX-6A0E21AE944BE',1,6,'2026-06-05 12:45:00','2026-05-21 01:03:42'),(8,'2026-07-19',1695.00,'completado','efectivo','TRX-6A0E21AE99DD9',2,2,'2026-07-19 16:00:00','2026-05-21 01:03:42'),(9,'2026-08-22',552.50,'cancelado',NULL,NULL,3,1,'2026-08-22 21:15:00','2026-05-21 01:03:42'),(10,'2026-09-10',1860.00,'completado','transferencia','TRX-6A0E21AEA389F',1,6,'2026-09-10 14:30:00','2026-05-21 01:03:42'),(11,'2026-10-15',3037.50,'completado','tarjeta','TRX-6A0E21AEA8CE5',2,2,'2026-10-15 13:00:00','2026-05-21 01:03:42'),(12,'2026-11-25',3605.00,'completado','efectivo','TRX-6A0E21AEAD32C',3,1,'2026-11-25 18:00:00','2026-05-21 01:03:42'),(13,'2026-12-20',9875.00,'completado','tarjeta','TRX-6A0E21AEB562A',1,2,'2026-12-20 15:30:00','2026-05-21 01:03:42'),(14,'2026-05-21',0.10,'completado',NULL,NULL,5,1,'2026-05-21 04:20:32','2026-05-21 04:21:25'),(15,'2026-05-21',0.10,'completado',NULL,NULL,5,1,'2026-05-21 04:29:18','2026-05-21 04:30:25'),(16,'2026-05-21',0.10,'completado',NULL,NULL,5,1,'2026-05-21 04:35:39','2026-05-21 04:40:07'),(17,'2026-05-26',0.10,'completado',NULL,NULL,5,1,'2026-05-26 04:45:21','2026-05-26 04:49:48'),(18,'2026-05-26',0.10,'completado',NULL,NULL,5,1,'2026-05-26 04:50:42','2026-05-26 04:51:24'),(19,'2026-05-26',0.10,'completado',NULL,NULL,6,1,'2026-05-26 04:58:47','2026-05-26 04:59:05'),(20,'2026-05-26',0.10,'completado',NULL,NULL,5,NULL,'2026-05-26 05:01:27','2026-05-26 05:01:53'),(21,'2026-05-26',0.10,'completado','qr',NULL,3,1,'2026-05-26 05:28:09','2026-05-26 05:28:51'),(22,'2026-05-26',0.10,'completado','qr',NULL,7,1,'2026-05-26 17:36:51','2026-05-26 17:39:48'),(23,'2026-05-27',0.10,'cancelado','qr',NULL,6,1,'2026-05-27 05:45:51','2026-05-27 06:18:52'),(24,'2026-05-27',0.10,'completado','qr',NULL,6,1,'2026-05-27 05:46:14','2026-05-27 06:22:19'),(25,'2026-05-27',0.10,'completado','qr',NULL,3,1,'2026-05-27 16:41:50','2026-05-27 17:00:07'),(26,'2026-05-27',145.20,'completado',NULL,NULL,4,1,'2026-05-27 17:37:56','2026-05-27 17:37:56');
/*!40000 ALTER TABLE `notas_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_visits`
--

DROP TABLE IF EXISTS `page_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_visits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) NOT NULL,
  `visit_count` bigint(20) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_visits_page_url_unique` (`page_url`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_visits`
--

LOCK TABLES `page_visits` WRITE;
/*!40000 ALTER TABLE `page_visits` DISABLE KEYS */;
INSERT INTO `page_visits` VALUES (1,'ventas',137,'2026-05-21 01:07:11','2026-05-28 02:53:50'),(2,'traspasos',21,'2026-05-21 01:07:21','2026-05-27 01:10:39'),(3,'traspasos/create',22,'2026-05-21 01:07:23','2026-05-26 19:00:56'),(4,'lotes',104,'2026-05-21 01:07:56','2026-05-27 01:21:41'),(5,'almacen-items',16,'2026-05-21 01:09:09','2026-05-27 06:30:00'),(6,'traspasos/2',2,'2026-05-21 01:10:04','2026-05-21 01:10:04'),(7,'/',185,'2026-05-21 01:27:29','2026-09-25 04:39:05'),(8,'home',94,'2026-05-21 01:30:16','2026-09-03 05:10:01'),(9,'usuarios-acceso/crear',83,'2026-05-21 01:32:20','2026-09-03 05:25:30'),(10,'producciones',14,'2026-05-21 01:33:49','2026-05-28 03:09:54'),(11,'producciones/9',2,'2026-05-21 01:34:03','2026-05-21 01:34:03'),(12,'producciones/12',2,'2026-05-21 01:34:07','2026-05-21 01:34:07'),(13,'produccion',18,'2026-05-21 01:34:15','2026-05-28 02:58:46'),(14,'detalles-venta',18,'2026-05-21 01:35:30','2026-05-28 02:53:48'),(15,'notas-venta',2,'2026-05-21 01:35:59','2026-05-21 01:35:59'),(16,'personas',32,'2026-05-21 01:36:32','2026-09-02 18:11:51'),(17,'producciones/8',2,'2026-05-21 02:16:49','2026-05-21 02:16:49'),(18,'producciones/6',2,'2026-05-21 02:16:53','2026-05-21 02:16:53'),(19,'producciones/1',2,'2026-05-21 02:16:57','2026-05-21 02:16:57'),(20,'movimientos',39,'2026-05-21 02:20:34','2026-05-27 01:22:16'),(21,'reportes',6,'2026-05-21 02:20:44','2026-05-27 06:29:21'),(22,'reportes/comercial',8,'2026-05-21 02:20:47','2026-05-21 02:36:35'),(23,'reportes/inventario',2,'2026-05-21 02:20:53','2026-05-21 02:20:53'),(24,'reportes/produccion',3,'2026-05-21 02:20:58','2026-05-21 02:23:27'),(25,'modulo-almacen',33,'2026-05-21 04:18:22','2026-06-17 17:26:19'),(26,'items',11,'2026-05-21 04:19:18','2026-05-27 05:51:06'),(27,'items/11/edit',2,'2026-05-21 04:19:50','2026-05-21 04:19:50'),(28,'procesar-pedido',13,'2026-05-21 04:20:32','2026-05-27 16:41:50'),(29,'pago/verificar/14',4,'2026-05-21 04:20:41','2026-05-21 04:20:57'),(30,'pago/exito/14',2,'2026-05-21 04:20:57','2026-05-21 04:20:57'),(31,'items/2/edit',2,'2026-05-21 04:28:16','2026-05-21 04:28:16'),(32,'pago/verificar/15',6,'2026-05-21 04:29:27','2026-05-21 04:29:59'),(33,'pago/exito/15',2,'2026-05-21 04:29:59','2026-05-21 04:29:59'),(34,'lotes/20',3,'2026-05-21 04:31:29','2026-05-21 04:31:47'),(35,'pago/verificar/16',5,'2026-05-21 04:35:49','2026-05-21 04:36:13'),(36,'pago/exito/16',2,'2026-05-21 04:36:13','2026-05-21 04:36:13'),(37,'compras',67,'2026-05-21 04:47:24','2026-05-28 03:06:59'),(38,'producciones/13',3,'2026-05-21 05:12:59','2026-05-21 05:13:10'),(39,'almacen/1/insumos-stock/13',2,'2026-05-21 05:13:03','2026-05-21 05:13:03'),(40,'almacen/2/insumos-stock/13',2,'2026-05-21 05:13:05','2026-05-21 05:13:05'),(41,'almacen/1/capacidad-disponible/13',2,'2026-05-21 05:13:09','2026-05-21 05:13:09'),(42,'items/1/edit',2,'2026-05-26 04:36:34','2026-05-26 04:36:34'),(43,'items/5/edit',2,'2026-05-26 04:36:47','2026-05-26 04:36:48'),(44,'pago/verificar/17',6,'2026-05-26 04:45:31','2026-05-26 04:46:03'),(45,'pago/exito/17',2,'2026-05-26 04:46:03','2026-05-26 04:46:03'),(46,'pago/verificar/18',6,'2026-05-26 04:50:51','2026-05-26 04:51:23'),(47,'pago/exito/18',2,'2026-05-26 04:51:24','2026-05-26 04:51:24'),(48,'pago/verificar/19',3,'2026-05-26 04:58:56','2026-05-26 04:59:04'),(49,'pago/exito/19',2,'2026-05-26 04:59:05','2026-05-26 04:59:05'),(50,'pago/verificar/20',4,'2026-05-26 05:01:37','2026-05-26 05:01:53'),(51,'pago/exito/20',2,'2026-05-26 05:01:53','2026-05-26 05:01:53'),(52,'pago/verificar/21',3,'2026-05-26 05:28:19','2026-05-26 05:28:27'),(53,'pago/exito/21',2,'2026-05-26 05:28:28','2026-05-26 05:28:28'),(54,'insumos',3,'2026-05-26 17:34:57','2026-05-27 05:51:23'),(55,'dashboard',2,'2026-05-26 17:35:55','2026-05-26 17:35:55'),(56,'pago/verificar/22',16,'2026-05-26 17:37:00','2026-05-26 17:38:52'),(57,'pago/exito/22',2,'2026-05-26 17:38:53','2026-05-26 17:38:53'),(58,'lotes/30',3,'2026-05-26 17:41:34','2026-05-26 17:41:48'),(59,'traspasos/18',2,'2026-05-26 19:01:15','2026-05-26 19:01:15'),(60,'detalles-compra',3,'2026-05-27 00:48:03','2026-05-27 17:46:35'),(61,'compras/items-por-almacen',2,'2026-05-27 00:58:36','2026-05-27 00:58:36'),(62,'compras/nota/13',2,'2026-05-27 01:09:23','2026-05-27 01:09:23'),(63,'pago/verificar/23',2,'2026-05-27 05:46:00','2026-05-27 05:46:00'),(64,'productos',2,'2026-05-27 05:51:10','2026-05-27 05:51:10'),(65,'productos/create',2,'2026-05-27 05:51:13','2026-05-27 05:51:13'),(66,'rol-permisos',20,'2026-05-27 05:52:13','2026-09-02 18:07:09'),(67,'mis-pedidos',37,'2026-05-27 06:00:42','2026-05-27 21:51:09'),(68,'pago/reintentar/24',4,'2026-05-27 06:11:52','2026-05-27 06:21:14'),(69,'pago/reintentar/23',2,'2026-05-27 06:12:09','2026-05-27 06:12:09'),(70,'pago/exito/24',2,'2026-05-27 06:21:52','2026-05-27 06:21:52'),(71,'pago/reintentar/12',3,'2026-05-27 16:40:36','2026-05-27 16:40:42'),(72,'pago/verificar/25',3,'2026-05-27 16:42:00','2026-05-27 16:42:08'),(73,'pago/exito/25',2,'2026-05-27 16:42:08','2026-05-27 16:42:08'),(74,'ventas/nota/7',2,'2026-05-27 17:37:35','2026-05-27 17:37:35'),(75,'compras/nota/17',2,'2026-05-27 17:38:09','2026-05-27 17:38:09'),(76,'ventas/nota/26',2,'2026-05-27 17:42:28','2026-05-27 17:42:28'),(77,'compras/nota/14',2,'2026-05-27 17:42:50','2026-05-27 17:42:50'),(78,'proveedores',4,'2026-05-27 17:46:34','2026-05-28 02:59:14'),(79,'notas-venta/18',2,'2026-05-27 17:46:50','2026-05-27 17:46:50'),(80,'recetas',6,'2026-05-27 18:48:41','2026-05-28 02:57:35'),(81,'producciones/7',8,'2026-05-28 02:29:59','2026-05-28 03:10:12'),(82,'rol-permisos/create',2,'2026-05-28 02:35:46','2026-05-28 02:35:46'),(83,'produccion/recetas/6/detalles',2,'2026-05-28 02:56:18','2026-05-28 02:56:18'),(84,'produccion/recetas/3/detalles',2,'2026-05-28 02:57:07','2026-05-28 02:57:07'),(85,'produccion/recetas/7/detalles',2,'2026-05-28 02:57:54','2026-05-28 02:57:54'),(86,'almacen/4/insumos-stock/7',4,'2026-05-28 03:07:09','2026-05-28 03:10:16'),(87,'almacen/2/insumos-stock/7',5,'2026-05-28 03:07:13','2026-05-28 03:10:20'),(88,'almacen/1/insumos-stock/7',5,'2026-05-28 03:07:16','2026-05-28 03:10:22'),(89,'almacen/5/insumos-stock/7',4,'2026-05-28 03:07:19','2026-05-28 03:09:43'),(90,'almacen/8/insumos-stock/7',3,'2026-05-28 03:07:23','2026-05-28 03:10:03'),(91,'almacen/6/insumos-stock/7',5,'2026-05-28 03:07:26','2026-05-28 03:10:18');
/*!40000 ALTER TABLE `page_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pempresa`
--

DROP TABLE IF EXISTS `pempresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pempresa` (
  `id_empresa` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_proveedor` bigint(20) unsigned NOT NULL,
  `razon_social` varchar(35) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  KEY `pempresa_id_proveedor_foreign` (`id_proveedor`),
  CONSTRAINT `pempresa_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pempresa`
--

LOCK TABLES `pempresa` WRITE;
/*!40000 ALTER TABLE `pempresa` DISABLE KEYS */;
INSERT INTO `pempresa` VALUES (1,1,'Distribuidora de Insumos','2026-05-21 04:51:49','2026-05-21 04:51:49'),(2,2,'Materiales Industriales','2026-05-21 04:51:49','2026-05-21 04:51:49'),(3,3,'Tecnologías Avanzadas','2026-05-21 04:51:49','2026-05-21 04:51:49');
/*!40000 ALTER TABLE `pempresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos` (
  `id_permiso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `permisos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'gestion_comercial_ver','2026-05-20 23:46:52','2026-05-20 23:46:52'),(2,'almacen_ver','2026-05-20 23:46:52','2026-05-20 23:46:52'),(3,'inventario_ver','2026-05-20 23:46:52','2026-05-20 23:46:52'),(4,'produccion_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(5,'reportes_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(6,'panel_almacen_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(7,'panel_produccion_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(8,'gestionar_produccion','2026-05-20 23:46:53','2026-05-20 23:46:53'),(9,'notas_venta_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(10,'notas_compra_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(11,'proveedores_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(12,'clientes_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(13,'almacenes_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(14,'productos_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(15,'items_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(16,'insumos_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(17,'movimientos_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(18,'traspasos_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(19,'lotes_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(20,'recetas_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(21,'producciones_ver','2026-05-20 23:46:53','2026-05-20 23:46:53'),(22,'modulo_acceso_ver','2026-05-20 23:46:53','2026-05-20 23:46:53');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ppersona`
--

DROP TABLE IF EXISTS `ppersona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ppersona` (
  `id_persona` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_proveedor` bigint(20) unsigned NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  KEY `ppersona_id_proveedor_foreign` (`id_proveedor`),
  CONSTRAINT `ppersona_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ppersona`
--

LOCK TABLES `ppersona` WRITE;
/*!40000 ALTER TABLE `ppersona` DISABLE KEYS */;
INSERT INTO `ppersona` VALUES (1,4,'Carlos Martínez','2026-05-21 04:51:49','2026-05-21 04:51:49'),(2,5,'María García','2026-05-21 04:51:49','2026-05-21 04:51:49'),(3,6,'Juan Pérez','2026-05-21 04:51:49','2026-05-21 04:51:49'),(4,7,'dfasf','2026-05-28 03:01:16','2026-05-28 03:01:16'),(5,8,'sfds','2026-05-28 03:01:55','2026-05-28 03:01:55'),(6,9,'serafa','2026-05-28 03:06:02','2026-05-28 03:06:02');
/*!40000 ALTER TABLE `ppersona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producciones`
--

DROP TABLE IF EXISTS `producciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producciones` (
  `id_produccion` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_produccion` date NOT NULL,
  `cantidad_producida` int(11) NOT NULL,
  `id_empleado_solicita` bigint(20) unsigned NOT NULL,
  `id_empleado_autoriza` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_autorizacion` timestamp NULL DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_produccion`),
  KEY `producciones_id_empleado_solicita_foreign` (`id_empleado_solicita`),
  KEY `producciones_id_empleado_autoriza_foreign` (`id_empleado_autoriza`),
  CONSTRAINT `producciones_id_empleado_autoriza_foreign` FOREIGN KEY (`id_empleado_autoriza`) REFERENCES `empleados` (`id_empleado`),
  CONSTRAINT `producciones_id_empleado_solicita_foreign` FOREIGN KEY (`id_empleado_solicita`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producciones`
--

LOCK TABLES `producciones` WRITE;
/*!40000 ALTER TABLE `producciones` DISABLE KEYS */;
INSERT INTO `producciones` VALUES (1,'2026-01-20',500,4,5,'aprobado','2026-01-18 14:00:00','2026-01-20 09:00:00','Producción masiva para temporada de Día de Muertos','2026-01-20 12:00:00','2026-05-21 00:52:14'),(2,'2026-02-15',300,4,5,'aprobado','2026-02-15 04:30:00','2026-02-15 04:30:00','Reposición de stock','2026-02-15 13:30:00','2026-05-21 00:52:14'),(3,'2026-03-05',800,4,5,'aprobado','2026-03-04 08:00:00','2026-03-04 17:00:00','Producción para fin de semana','2026-03-05 11:00:00','2026-05-21 00:52:14'),(4,'2026-04-10',2000,4,5,'aprobado','2026-04-08 21:00:00','2026-04-09 22:00:00','Producción diaria de pan para venta','2026-04-10 08:00:00','2026-05-21 00:52:14'),(5,'2026-05-01',150,1,5,'aprobado','2026-04-29 15:00:00','2026-05-01 04:00:00','Producción especial para cumpleaños','2026-05-01 14:00:00','2026-05-21 00:52:14'),(6,'2026-06-20',3000,4,5,'aprobado','2026-06-18 13:00:00','2026-06-19 10:00:00','Producción masiva de galletas','2026-06-20 10:00:00','2026-05-21 00:52:14'),(7,'2026-07-10',400,4,NULL,'pendiente','2026-07-09 00:00:00',NULL,'Esperando autorización de gerencia','2026-07-10 12:00:00','2026-05-21 00:52:14'),(8,'2026-08-15',200,4,1,'cancelado','2026-08-15 05:00:00','2026-08-14 16:00:00','Cancelado por falta de insumos','2026-08-15 13:00:00','2026-05-21 00:52:14'),(9,'2026-09-05',1500,4,5,'rechazado','2026-09-03 09:00:00','2026-09-04 08:00:00','Rechazado por baja demanda','2026-09-05 08:00:00','2026-05-21 00:52:14'),(10,'2026-10-20',200,1,5,'aprobado','2026-10-20 08:00:00','2026-10-19 18:00:00','Producción para eventos especiales','2026-10-20 15:00:00','2026-05-21 00:52:14'),(11,'2026-11-15',2500,4,5,'aprobado','2026-11-14 13:00:00','2026-11-14 13:00:00','Producción para temporada navideña','2026-11-15 09:00:00','2026-05-21 00:52:14'),(12,'2026-12-10',1200,4,5,'aprobado','2026-12-08 16:30:00','2026-12-10 09:30:00','Producción navideña','2026-12-10 11:30:00','2026-05-21 00:52:14'),(13,'2026-05-21',1,1,1,'aprobado','2026-05-21 05:12:59','2026-05-21 05:13:10','212','2026-05-21 05:12:59','2026-05-21 05:13:10');
/*!40000 ALTER TABLE `producciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id_producto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_item` bigint(20) unsigned NOT NULL,
  `id_cat_producto` bigint(20) unsigned NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `productos_id_item_foreign` (`id_item`),
  KEY `productos_id_cat_producto_foreign` (`id_cat_producto`),
  CONSTRAINT `productos_id_cat_producto_foreign` FOREIGN KEY (`id_cat_producto`) REFERENCES `categoria_producto` (`id_cat_producto`),
  CONSTRAINT `productos_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,1,0.10,'https://cdn.pixabay.com/photo/2023/11/15/21/15/bread-8391079_1280.jpg','2026-05-21 04:51:56','2026-05-26 04:36:45'),(2,2,1,12.00,'https://cdn.pixabay.com/photo/2021/01/14/13/10/bread-5916804_1280.jpg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(3,3,2,4.50,'https://cdn.pixabay.com/photo/2019/02/07/21/19/bobbin-lace-3982200_1280.jpg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(4,4,3,85.00,'https://cdn.pixabay.com/photo/2016/11/22/18/52/cake-1850011_1280.jpg','2026-05-21 04:51:56','2026-05-21 04:51:56'),(5,5,4,0.10,'https://cdn.pixabay.com/photo/2014/11/27/14/35/cookies-547636_1280.jpg','2026-05-21 04:51:56','2026-05-26 04:36:57'),(6,12,3,213.00,NULL,'2026-05-27 06:32:27','2026-05-27 06:32:27');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `id_proveedor` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_proveedor` varchar(25) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(35) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'empresa',5550101,'Av. Principal 123, CDMX','ventas@distribuidora.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(2,'empresa',5550102,'Calle Industrial 456, GDL','contacto@insumos.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(3,'empresa',5550103,'Blvd. Tecnológico 789, MTY','ventas@tecnologias.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(4,'persona',5550201,'Calle 5 de Mayo 45, Puebla','carlos.martinez@email.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(5,'persona',5550202,'Av. Reforma 234, Querétaro','maria.garcia@email.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(6,'persona',5550203,'Calle Morelos 78, Cancún','juan.perez@email.com','2026-05-21 04:51:49','2026-05-21 04:51:49'),(7,'persona',123,'21312','das@ads.com','2026-05-28 03:01:16','2026-05-28 03:01:16'),(8,'persona',2123,'21312','das@ads.com','2026-05-28 03:01:55','2026-05-28 03:01:55'),(9,'persona',2123,'21312','sed@ads.com','2026-05-28 03:06:02','2026-05-28 03:06:02');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recetas`
--

DROP TABLE IF EXISTS `recetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recetas` (
  `id_receta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` bigint(20) unsigned NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(40) NOT NULL,
  `cantidad_requerida` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_receta`),
  KEY `recetas_id_producto_foreign` (`id_producto`),
  CONSTRAINT `recetas_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recetas`
--

LOCK TABLES `recetas` WRITE;
/*!40000 ALTER TABLE `recetas` DISABLE KEYS */;
INSERT INTO `recetas` VALUES (1,1,'Receta Pan de Muerto','Pan tradicional de temporada','1 pieza','2026-05-21 04:52:05','2026-05-21 04:52:05'),(2,2,'Receta Concha','Pan dulce con cubierta','1 pieza','2026-05-21 04:52:05','2026-05-21 04:52:05'),(3,3,'Receta Bolillo','Pan salado blanco','1 pieza','2026-05-21 04:52:05','2026-05-21 04:52:05'),(4,4,'Receta Pastel Chocolate','Pastel de 8 porciones','8 porciones','2026-05-21 04:52:05','2026-05-21 04:52:05'),(5,5,'Receta Galleta María','Galleta tipo María','1 pieza','2026-05-21 04:52:05','2026-05-21 04:52:05'),(6,1,'reeta','123','12','2026-05-28 02:56:17','2026-05-28 02:56:17'),(7,2,'1231','21321','12','2026-05-28 02:57:53','2026-05-28 02:57:53');
/*!40000 ALTER TABLE `recetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol_permiso`
--

DROP TABLE IF EXISTS `rol_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol_permiso` (
  `id_rol_permiso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol` bigint(20) unsigned NOT NULL,
  `id_permiso` bigint(20) unsigned NOT NULL,
  `estado` varchar(15) NOT NULL DEFAULT 'activo',
  `descripcion` varchar(90) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol_permiso`),
  UNIQUE KEY `rol_permiso_id_rol_id_permiso_unique` (`id_rol`,`id_permiso`),
  KEY `rol_permiso_id_permiso_foreign` (`id_permiso`),
  CONSTRAINT `rol_permiso_id_permiso_foreign` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE,
  CONSTRAINT `rol_permiso_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_permiso`
--

LOCK TABLES `rol_permiso` WRITE;
/*!40000 ALTER TABLE `rol_permiso` DISABLE KEYS */;
INSERT INTO `rol_permiso` VALUES (1,1,1,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(2,1,2,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(3,1,3,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(4,1,4,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(5,1,5,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(6,1,6,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(7,1,7,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(8,1,8,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(9,1,9,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(10,1,10,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(11,1,11,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(13,1,13,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(14,1,14,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(15,1,15,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(16,1,16,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(17,1,17,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(18,1,18,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(19,1,19,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(20,1,20,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(21,1,21,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(22,1,22,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(23,2,1,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(24,2,2,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(25,2,3,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(26,2,4,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(27,2,5,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(28,2,6,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(29,2,7,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(30,2,8,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(31,3,1,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(32,3,9,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(34,4,1,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(35,4,10,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(36,4,11,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(37,5,4,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(38,5,20,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(39,5,21,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(40,5,7,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(41,6,3,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(42,6,2,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(43,6,17,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(44,6,18,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(45,6,19,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(46,6,16,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(47,6,6,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(48,6,4,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(49,6,8,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(50,7,1,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(51,7,2,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(52,7,3,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(53,7,4,'activo',NULL,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(54,3,12,'activo',NULL,'2026-05-28 02:36:11','2026-05-28 02:36:11');
/*!40000 ALTER TABLE `rol_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol_permiso_usuario`
--

DROP TABLE IF EXISTS `rol_permiso_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol_permiso_usuario` (
  `id_rol_permiso_usuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol_permiso` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `estado` varchar(15) NOT NULL DEFAULT 'activo',
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol_permiso_usuario`),
  UNIQUE KEY `rol_permiso_usuario_id_rol_permiso_id_usuario_unique` (`id_rol_permiso`,`id_usuario`),
  KEY `rol_permiso_usuario_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `rol_permiso_usuario_id_rol_permiso_foreign` FOREIGN KEY (`id_rol_permiso`) REFERENCES `rol_permiso` (`id_rol_permiso`) ON DELETE CASCADE,
  CONSTRAINT `rol_permiso_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_permiso_usuario`
--

LOCK TABLES `rol_permiso_usuario` WRITE;
/*!40000 ALTER TABLE `rol_permiso_usuario` DISABLE KEYS */;
INSERT INTO `rol_permiso_usuario` VALUES (1,1,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(2,2,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(3,3,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(4,4,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(5,5,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(6,6,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(7,7,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(8,8,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(9,9,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(10,10,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(11,11,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(13,13,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(14,14,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(15,15,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(16,16,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(17,17,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(18,18,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(19,19,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(20,20,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(21,21,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(22,22,1,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(23,31,2,'inactivo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 03:09:25'),(24,32,2,'inactivo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 03:09:25'),(26,34,3,'inactivo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:54:26'),(27,35,3,'inactivo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:54:26'),(28,36,3,'inactivo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:54:26'),(29,37,4,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:30:49'),(30,40,4,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:30:49'),(31,38,4,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:30:49'),(32,39,4,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-28 02:30:49'),(33,42,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(34,41,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(35,48,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(36,47,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(37,49,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(38,46,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(39,43,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(40,44,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(41,45,5,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-21 06:09:46'),(42,50,6,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(43,51,6,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(44,52,6,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(45,53,6,'activo','2026-05-20 23:46:54','2026-05-20 23:46:54','2026-05-20 23:46:54'),(47,31,4,'activo','2026-05-28 02:30:49','2026-05-28 02:30:49','2026-05-28 02:30:49'),(48,32,4,'activo','2026-05-28 02:30:49','2026-05-28 02:30:49','2026-05-28 02:30:49'),(49,54,2,'inactivo','2026-05-28 02:36:25','2026-05-28 02:36:25','2026-05-28 03:09:25'),(50,40,2,'inactivo','2026-05-28 02:54:58','2026-05-28 02:54:58','2026-05-28 03:09:25'),(51,37,2,'inactivo','2026-05-28 02:54:58','2026-05-28 02:54:58','2026-05-28 03:09:25'),(52,38,2,'inactivo','2026-05-28 02:54:58','2026-05-28 02:54:58','2026-05-28 03:09:25'),(53,39,2,'inactivo','2026-05-28 02:55:12','2026-05-28 02:55:12','2026-05-28 03:09:25'),(54,34,2,'inactivo','2026-05-28 02:58:25','2026-05-28 02:58:25','2026-05-28 03:09:25'),(55,36,2,'inactivo','2026-05-28 02:58:25','2026-05-28 02:58:25','2026-05-28 03:09:25'),(56,35,2,'inactivo','2026-05-28 02:59:11','2026-05-28 02:59:11','2026-05-28 03:09:25'),(57,42,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:25'),(58,46,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(59,41,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(60,45,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(61,43,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(62,47,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(63,48,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(64,44,2,'activo','2026-05-28 03:06:52','2026-05-28 03:06:52','2026-05-28 03:09:26'),(65,49,2,'activo','2026-05-28 03:09:25','2026-05-28 03:09:25','2026-05-28 03:09:25');
/*!40000 ALTER TABLE `rol_permiso_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id_rol` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador','2026-05-20 23:46:53','2026-05-20 23:46:53'),(2,'Gerente','2026-05-20 23:46:53','2026-05-20 23:46:53'),(3,'Encargado Venta','2026-05-20 23:46:53','2026-05-20 23:46:53'),(4,'Encargado Compra','2026-05-20 23:46:53','2026-05-20 23:46:53'),(5,'Encargado Producción','2026-05-20 23:46:53','2026-05-20 23:46:53'),(6,'Encargado Inventario','2026-05-20 23:46:53','2026-05-20 23:46:53'),(7,'Empleado','2026-05-20 23:46:53','2026-05-20 23:46:53');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('D18kS73RWnhMueYDYFkwtFux51VAaxRBUnkNafZm',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.139.0 Chrome/150.0.7871.250 Electron/43.6.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDJEYmx1VkNLVEpWWHhGR1hzc2liMmpUZ0ZUcmdIQzNzeVdkTXNNbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790296746),('DCbscnKGxn3PSxbb81C8PT19gQuox3yXuNlIjtmU',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibDJnbFozTThXb0NnU2Zybkl1TUoxak1FTmVOQWxxT1hHVFBiYlBjdiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdXN1YXJpb3MtYWNjZXNvL2NyZWFyIjtzOjU6InJvdXRlIjtzOjIyOiJ1c3Vhcmlvcy5jcmVhdGUtYWNjZXNzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1788398730),('EBbMpUkU5fLr9Gz8PQN0OT6ejNMGFqzeWDAMBPRd',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUWE0S2Z5S0JHbzg0TWFyNE56R3R1a0w5OHlIbG9YakE0WVhvVldBSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c3Vhcmlvcy1hY2Nlc28vY3JlYXIiO3M6NToicm91dGUiO3M6MjI6InVzdWFyaW9zLmNyZWF0ZS1hY2Nlc3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoibW9kZSI7czo0OiJkYXJrIjt9',1788358372),('i1ext0bF5YqNd2J3S3LKJgAxtzHZv48XOEt68BWW',4,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:154.0) Gecko/20100101 Firefox/154.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHBLeFA0UEt6eXRoWGlNcGd1Mm1SUnBhYVFZdmJ1MU1zQXphZ2lPNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=',1788397801);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacciones_libelula`
--

DROP TABLE IF EXISTS `transacciones_libelula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transacciones_libelula` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nota_venta_id` bigint(20) unsigned NOT NULL,
  `identificador` varchar(255) NOT NULL,
  `id_transaccion_libelula` varchar(255) DEFAULT NULL,
  `codigo_recaudacion` varchar(255) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `qr_url` text DEFAULT NULL,
  `url_pasarela` text DEFAULT NULL,
  `respuesta_api` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`respuesta_api`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacciones_libelula_nota_venta_id_foreign` (`nota_venta_id`),
  KEY `transacciones_libelula_identificador_index` (`identificador`),
  KEY `transacciones_libelula_id_transaccion_libelula_index` (`id_transaccion_libelula`),
  KEY `transacciones_libelula_estado_index` (`estado`),
  CONSTRAINT `transacciones_libelula_nota_venta_id_foreign` FOREIGN KEY (`nota_venta_id`) REFERENCES `notas_venta` (`id_nota_venta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacciones_libelula`
--

LOCK TABLES `transacciones_libelula` WRITE;
/*!40000 ALTER TABLE `transacciones_libelula` DISABLE KEYS */;
INSERT INTO `transacciones_libelula` VALUES (1,14,'OTTO-14-6a0e4fd07e094','5f447343-2514-4f15-a5df-f6afb57a420e','757313652603',0.10,'pagado','https://pagos.libelula.bo/QrImages/d89af02fa0874ca584dd30b4d4ec8e6f5414e06aa0eb48cbb2205f3ff3716250.png','https://pagos.libelula.bo/?id=568df7ec-fa3a-4c22-b1f3-5d110a92272d','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"757313652603\",\"id_transaccion\":\"5f447343-2514-4f15-a5df-f6afb57a420e\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/d89af02fa0874ca584dd30b4d4ec8e6f5414e06aa0eb48cbb2205f3ff3716250.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=568df7ec-fa3a-4c22-b1f3-5d110a92272d\"}','2026-05-21 04:20:33','2026-05-21 04:20:50'),(2,15,'OTTO-15-6a0e51de443bb','776e06c4-3a19-4156-a52a-34fd2e6c3232','706413652708',0.10,'pagado','https://pagos.libelula.bo/QrImages/b98e4e832fd74268a7ba18ec87e8bd21c0d14e9554c64c80b9f34591247e56d4.png','https://pagos.libelula.bo/?id=730eca54-c47c-4573-9d20-03a2f3716b29','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"706413652708\",\"id_transaccion\":\"776e06c4-3a19-4156-a52a-34fd2e6c3232\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/b98e4e832fd74268a7ba18ec87e8bd21c0d14e9554c64c80b9f34591247e56d4.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=730eca54-c47c-4573-9d20-03a2f3716b29\"}','2026-05-21 04:29:19','2026-05-21 04:29:52'),(3,16,'OTTO-16-6a0e535bd3c67','8227c33c-3e97-48f0-b70d-fa528809f8d5','752113652776',0.10,'pagado','https://pagos.libelula.bo/QrImages/0393164f4b92457db8b70680440865bbdcd9de4fe58e4c758ad18327cf52faa6.png','https://pagos.libelula.bo/?id=28b296fb-af5b-4ce9-8ee2-8e7bc2482163','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"752113652776\",\"id_transaccion\":\"8227c33c-3e97-48f0-b70d-fa528809f8d5\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/0393164f4b92457db8b70680440865bbdcd9de4fe58e4c758ad18327cf52faa6.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=28b296fb-af5b-4ce9-8ee2-8e7bc2482163\"}','2026-05-21 04:35:41','2026-05-21 04:36:05'),(4,17,'OTTO-17-6a14ed21efe08','22190b56-4959-4fa1-b518-ce7a0e5ed82b','758213717366',0.10,'pagado','https://pagos.libelula.bo/QrImages/5e5c900dc08d48f7b228867ffded8a598b0cd8d0099f4d5cb49bd6025dbba624.png','https://pagos.libelula.bo/?id=85524450-e993-406f-a0cf-09e03269e8ae','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"758213717366\",\"id_transaccion\":\"22190b56-4959-4fa1-b518-ce7a0e5ed82b\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/5e5c900dc08d48f7b228867ffded8a598b0cd8d0099f4d5cb49bd6025dbba624.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=85524450-e993-406f-a0cf-09e03269e8ae\"}','2026-05-26 04:45:23','2026-05-26 04:45:56'),(5,18,'OTTO-18-6a14ee6272036','0fabd562-ef46-4916-991f-bce7955de992','796913717408',0.10,'pagado','https://pagos.libelula.bo/QrImages/4d36063e16b349a5ad18d7e9a008391e6283eb2a321844839278255432a4e1e3.png','https://pagos.libelula.bo/?id=27b74bc0-0046-49f2-846a-ad1ad0a63daf','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"796913717408\",\"id_transaccion\":\"0fabd562-ef46-4916-991f-bce7955de992\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/4d36063e16b349a5ad18d7e9a008391e6283eb2a321844839278255432a4e1e3.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=27b74bc0-0046-49f2-846a-ad1ad0a63daf\"}','2026-05-26 04:50:43','2026-05-26 04:51:24'),(6,19,'OTTO-19-6a14f047471c3','640130fd-4c3e-4766-ac74-61ee6b659e47','781813717474',0.10,'pagado','https://pagos.libelula.bo/QrImages/213785972f5c461290b45e4ebe8c6e20bbbe2c00b4c54d4a9cf55656ce1b123f.png','https://pagos.libelula.bo/?id=000b4bb7-55fc-4abc-b58b-071928bb5cf9','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"781813717474\",\"id_transaccion\":\"640130fd-4c3e-4766-ac74-61ee6b659e47\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/213785972f5c461290b45e4ebe8c6e20bbbe2c00b4c54d4a9cf55656ce1b123f.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=000b4bb7-55fc-4abc-b58b-071928bb5cf9\"}','2026-05-26 04:58:48','2026-05-26 04:59:05'),(7,20,'OTTO-20-6a14f0e74bfb7','fc78db2c-21f4-4b46-87a6-6e710fc75585','719813717501',0.10,'pagado','https://pagos.libelula.bo/QrImages/f49783535d2245608de5ca66f70e4db290ae5ea9aa004aeb85c9d30f1ee9f037.png','https://pagos.libelula.bo/?id=034f6e0d-d10d-490a-b0cf-221a48f14269','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"719813717501\",\"id_transaccion\":\"fc78db2c-21f4-4b46-87a6-6e710fc75585\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/f49783535d2245608de5ca66f70e4db290ae5ea9aa004aeb85c9d30f1ee9f037.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=034f6e0d-d10d-490a-b0cf-221a48f14269\"}','2026-05-26 05:01:28','2026-05-26 05:01:53'),(8,21,'OTTO-21-6a14f729cb3c6','79189ae0-6ea5-4d5f-856c-58d847a43f8a','792313717714',0.10,'pagado','https://pagos.libelula.bo/QrImages/c8ca21a412c64472bf015c6a305797e460e3faec0d3e46f3afbfac2098022a7b.png','https://pagos.libelula.bo/?id=20a129f0-7903-4ec4-8238-e620258f2685','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"792313717714\",\"id_transaccion\":\"79189ae0-6ea5-4d5f-856c-58d847a43f8a\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/c8ca21a412c64472bf015c6a305797e460e3faec0d3e46f3afbfac2098022a7b.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=20a129f0-7903-4ec4-8238-e620258f2685\"}','2026-05-26 05:28:11','2026-05-26 05:28:27'),(9,22,'OTTO-22-6a15a1f38839c','1ba9dcd4-fdc5-4968-9a00-14e288d11093','752013721904',0.10,'pagado','https://pagos.libelula.bo/QrImages/407b6255f46b4ec8820c012f3b32ae1bb4e78b1ea6ad4f43948f546278e3317b.png','https://pagos.libelula.bo/?id=d450de94-4d35-4f21-8c5e-78942add4839','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"752013721904\",\"id_transaccion\":\"1ba9dcd4-fdc5-4968-9a00-14e288d11093\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/407b6255f46b4ec8820c012f3b32ae1bb4e78b1ea6ad4f43948f546278e3317b.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=d450de94-4d35-4f21-8c5e-78942add4839\"}','2026-05-26 17:36:52','2026-05-26 17:38:53'),(10,23,'OTTO-23-6a164ccf617ef','117f8c8e-ed16-4bb9-9378-05342e225c31','780613737645',0.10,'pendiente','https://pagos.libelula.bo/QrImages/e89e993acf4c47ab9b8939e0978d2b75565a06ef8d2d411b9bffaef8dc2bd909.png','https://pagos.libelula.bo/?id=6f18ad08-b1e7-4781-8b2b-be459afba4e3','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"780613737645\",\"id_transaccion\":\"117f8c8e-ed16-4bb9-9378-05342e225c31\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/e89e993acf4c47ab9b8939e0978d2b75565a06ef8d2d411b9bffaef8dc2bd909.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=6f18ad08-b1e7-4781-8b2b-be459afba4e3\"}','2026-05-27 05:45:52','2026-05-27 05:45:52'),(11,24,'OTTO-24-6a164ce650a81','fe688e84-2b83-4f60-9202-73d7114e35da','780613737648',0.10,'pagado','https://pagos.libelula.bo/QrImages/86ec7f07bbb24626b41628f7b2ba994ff3879876f1fe48a9982f4a6f31c4b94e.png','https://pagos.libelula.bo/?id=9c6a4002-6349-4c1f-af41-4af6dbd3b77b','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"780613737648\",\"id_transaccion\":\"fe688e84-2b83-4f60-9202-73d7114e35da\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/86ec7f07bbb24626b41628f7b2ba994ff3879876f1fe48a9982f4a6f31c4b94e.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=9c6a4002-6349-4c1f-af41-4af6dbd3b77b\"}','2026-05-27 05:46:15','2026-05-27 06:21:52'),(12,25,'OTTO-25-6a16e68e86136','3b765093-37b0-4ff7-8bd2-ddfe0c89ea77','752713741421',0.10,'pagado','https://pagos.libelula.bo/QrImages/b8bf2889f27f458492f1c810cf2626e1c617174b61e8499093c22b4e8b2e88c5.png','https://pagos.libelula.bo/?id=50acf408-fbe6-4826-bf5e-235c0085c8ba','{\"error\":0,\"existente\":0,\"mensaje\":\"Deuda registrada con \\u00e9xito. Para completar el pago, debe redireccionar al cliente a la pasarela de pagos.\",\"codigo_recaudacion\":\"752713741421\",\"id_transaccion\":\"3b765093-37b0-4ff7-8bd2-ddfe0c89ea77\",\"qr_simple_url\":\"https:\\/\\/pagos.libelula.bo\\/QrImages\\/b8bf2889f27f458492f1c810cf2626e1c617174b61e8499093c22b4e8b2e88c5.png\",\"url_pasarela_pagos\":\"https:\\/\\/pagos.libelula.bo\\/?id=50acf408-fbe6-4826-bf5e-235c0085c8ba\"}','2026-05-27 16:41:52','2026-05-27 16:42:08');
/*!40000 ALTER TABLE `transacciones_libelula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traspaso_almacen_item`
--

DROP TABLE IF EXISTS `traspaso_almacen_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traspaso_almacen_item` (
  `id_detalle_traspaso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_traspaso` bigint(20) unsigned NOT NULL,
  `id_almacen_origen` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `id_almacen_destino` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle_traspaso`),
  UNIQUE KEY `traspaso_item_unico` (`id_traspaso`,`id_almacen_origen`,`id_almacen_destino`,`id_item`),
  KEY `traspaso_almacen_item_id_almacen_origen_id_item_foreign` (`id_almacen_origen`,`id_item`),
  KEY `traspaso_almacen_item_id_almacen_destino_id_item_foreign` (`id_almacen_destino`,`id_item`),
  CONSTRAINT `traspaso_almacen_item_id_almacen_destino_id_item_foreign` FOREIGN KEY (`id_almacen_destino`, `id_item`) REFERENCES `almacen_item` (`id_almacen`, `id_item`),
  CONSTRAINT `traspaso_almacen_item_id_almacen_origen_id_item_foreign` FOREIGN KEY (`id_almacen_origen`, `id_item`) REFERENCES `almacen_item` (`id_almacen`, `id_item`),
  CONSTRAINT `traspaso_almacen_item_id_traspaso_foreign` FOREIGN KEY (`id_traspaso`) REFERENCES `traspasos` (`id_traspaso`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traspaso_almacen_item`
--

LOCK TABLES `traspaso_almacen_item` WRITE;
/*!40000 ALTER TABLE `traspaso_almacen_item` DISABLE KEYS */;
INSERT INTO `traspaso_almacen_item` VALUES (5,6,2,10,1,100,'2026-02-05 15:15:00','2026-05-21 01:23:51'),(6,7,2,7,4,80,'2026-04-12 13:30:00','2026-05-21 01:23:51'),(7,7,2,8,4,300,'2026-04-12 13:30:00','2026-05-21 01:23:51'),(8,8,3,4,1,30,'2026-05-20 16:00:00','2026-05-21 01:23:51'),(9,9,1,9,2,150,'2026-07-08 14:00:00','2026-05-21 01:23:51'),(16,18,2,6,1,250,'2026-05-26 19:01:15','2026-05-26 19:01:15');
/*!40000 ALTER TABLE `traspaso_almacen_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traspasos`
--

DROP TABLE IF EXISTS `traspasos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traspasos` (
  `id_traspaso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_traspaso` date NOT NULL,
  `descripcion` varchar(40) NOT NULL,
  `id_empleado` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_traspaso`),
  KEY `traspasos_id_empleado_foreign` (`id_empleado`),
  CONSTRAINT `traspasos_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traspasos`
--

LOCK TABLES `traspasos` WRITE;
/*!40000 ALTER TABLE `traspasos` DISABLE KEYS */;
INSERT INTO `traspasos` VALUES (6,'2026-02-05','Levadura a central',5,'2026-02-05 15:15:00','2026-05-21 01:23:51'),(7,'2026-04-12','Mantequilla/huevo a refrigerado',5,'2026-04-12 13:30:00','2026-05-21 01:23:51'),(8,'2026-05-20','Pasteles a central',5,'2026-05-20 16:00:00','2026-05-21 01:23:51'),(9,'2026-07-08','Azúcar a insumos',5,'2026-07-08 14:00:00','2026-05-21 01:23:51'),(18,'2026-05-26','fdsfasd',1,'2026-05-26 19:01:15','2026-05-26 19:01:15');
/*!40000 ALTER TABLE `traspasos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traspasos_inventario`
--

DROP TABLE IF EXISTS `traspasos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traspasos_inventario` (
  `id_traspaso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_almacen_origen` bigint(20) unsigned NOT NULL,
  `id_almacen_destino` bigint(20) unsigned NOT NULL,
  `id_item` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `fecha_traspaso` datetime NOT NULL,
  `estado` enum('completado','pendiente','cancelado') NOT NULL DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_traspaso`),
  KEY `traspasos_inventario_id_almacen_destino_foreign` (`id_almacen_destino`),
  KEY `traspasos_inventario_id_almacen_origen_id_almacen_destino_index` (`id_almacen_origen`,`id_almacen_destino`),
  KEY `traspasos_inventario_id_item_index` (`id_item`),
  KEY `traspasos_inventario_estado_index` (`estado`),
  KEY `traspasos_inventario_fecha_traspaso_index` (`fecha_traspaso`),
  CONSTRAINT `traspasos_inventario_id_almacen_destino_foreign` FOREIGN KEY (`id_almacen_destino`) REFERENCES `almacenes` (`id_almacen`),
  CONSTRAINT `traspasos_inventario_id_almacen_origen_foreign` FOREIGN KEY (`id_almacen_origen`) REFERENCES `almacenes` (`id_almacen`),
  CONSTRAINT `traspasos_inventario_id_item_foreign` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traspasos_inventario`
--

LOCK TABLES `traspasos_inventario` WRITE;
/*!40000 ALTER TABLE `traspasos_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `traspasos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `correo` varchar(45) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `estado` varchar(15) NOT NULL DEFAULT 'activo',
  `tipo_usuario` varchar(20) NOT NULL,
  `id_cliente` bigint(20) unsigned DEFAULT NULL,
  `id_empleado` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuarios_correo_unique` (`correo`),
  KEY `usuarios_id_cliente_foreign` (`id_cliente`),
  KEY `usuarios_id_empleado_foreign` (`id_empleado`),
  CONSTRAINT `usuarios_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `usuarios_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin@panaderia.com','$2y$12$.M2Nafm5Elwa09egYRMquO7e1ZTL/zLYsF.Qv0PZ60srHfY1w7YLW','activo','empleado',NULL,1,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(2,'venta@panaderia.com','$2y$12$yn0o0xIgs3tqKDv7MdcQSueqbl0c/c01o6vMEcBhLgAaSRc3mXl9W','activo','empleado',NULL,2,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(3,'compra@panaderia.com','$2y$12$C4wL46Q9UfFwPKRX2/PiV.6h41Cm9x2nt4I04rUdvheK8gKWtSUWu','activo','empleado',NULL,3,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(4,'produccion@panaderia.com','$2y$12$ye2FFhBttzh7u/EjGNrgNeVLNUkCV2R/hYz5KWuws..zPJSLMEAdW','activo','empleado',NULL,4,'2026-05-20 23:46:53','2026-05-20 23:46:53'),(5,'inventario@panaderia.com','$2y$12$xos4f8duCAM/O99aQuZnmeGP/AY4SCkcZw.lsxL7oLyhTgGeraDu6','activo','empleado',NULL,5,'2026-05-20 23:46:54','2026-05-20 23:46:54'),(6,'empleado@panaderia.com','$2y$12$p3iTFHWpPVGFSYj7NHlS8uOAOpwywtYPX0jsxdPQD7dJ/2QYHyHSO','activo','empleado',NULL,6,'2026-05-20 23:46:54','2026-05-20 23:46:54'),(7,'ana.gonzalez@example.com','$2y$12$2Xp39LRs9fSbQ8/gBUaw..b7m1SJv2aMWeeCxoUvTvMdhJ3RDMJpi','activo','cliente',1,NULL,'2026-05-20 23:46:54','2026-05-21 04:52:12'),(8,'luis.ramirez@example.com','$2y$12$jsZVzuTQ2l4KL7xMH3w5MugX.QqTKocAyXxR7GCJvb3u4icSLSvkW','activo','cliente',2,NULL,'2026-05-20 23:46:54','2026-05-21 04:52:12'),(9,'martha.sanchez@example.com','$2y$12$dt8RRP6xqT.fRgwUB/dHVOzIn91o9fxnZXPQiDRsMegtjmuSGHAyq','activo','cliente',3,NULL,'2026-05-20 23:46:54','2026-05-21 04:52:13'),(10,'marcos@gmail.com','$2y$12$dSFW6FmQLCfzFe6GAIY8SewumByryNXDiLL.WolhOrPeNXITKvpe6','activo','cliente',7,NULL,'2026-05-26 17:33:46','2026-05-26 17:33:46');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-24 20:59:48
