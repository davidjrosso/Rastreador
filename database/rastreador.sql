-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: rastreador.riotercero.gob.ar    Database: rastreador
-- ------------------------------------------------------
-- Server version	5.5.5-10.5.15-MariaDB-0+deb11u1

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
-- Table structure for table `Acciones`
--

DROP TABLE IF EXISTS `Acciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Acciones` (
  `ID_Accion` int(11) NOT NULL AUTO_INCREMENT,
  `accountid` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `Detalles` varchar(2000) DEFAULT NULL,
  `ID_TipoAccion` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Accion`),
  KEY `FK_TipoAcciones` (`ID_TipoAccion`)
) ENGINE=MyISAM AUTO_INCREMENT=118973 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipoAcciones`
--

DROP TABLE IF EXISTS `TipoAcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TipoAcciones` (
  `ID_TipoAccion` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID_TipoAccion`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Tipo_Usuarios`
--

DROP TABLE IF EXISTS `Tipo_Usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Tipo_Usuarios` (
  `ID_TipoUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `TipoUsuario` varchar(200) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `abreviacion` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_TipoUsuario`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accountid_seq`
--

DROP TABLE IF EXISTS `accountid_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accountid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `accountid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `initials` text DEFAULT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `hintquestion` text DEFAULT NULL,
  `hintanswer` text DEFAULT NULL,
  `expired` tinyint(4) DEFAULT NULL,
  `expireddate` date DEFAULT NULL,
  `tries` int(11) DEFAULT NULL,
  `lasttrieddate` int(11) DEFAULT NULL,
  `matricula` text DEFAULT NULL,
  `iva` text DEFAULT NULL,
  `ID_TipoUsuario` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_centro` int(11) DEFAULT NULL,
  PRIMARY KEY (`accountid`),
  KEY `FK_TipoUsuarios` (`ID_TipoUsuario`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `actionid_seq`
--

DROP TABLE IF EXISTS `actionid_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actionid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actions` (
  `actionid` int(11) NOT NULL AUTO_INCREMENT,
  `actionname` text NOT NULL,
  PRIMARY KEY (`actionid`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity` (
  `activityid` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`activityid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activityid_seq`
--

DROP TABLE IF EXISTS `activityid_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activityid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos` (
  `id_archivo` int(20) NOT NULL AUTO_INCREMENT,
  `centro_salud` int(11) DEFAULT NULL,
  `archivo` varchar(30) DEFAULT NULL,
  `id_file` varchar(50) DEFAULT NULL,
  `enlace` varchar(50) DEFAULT NULL,
  `planilla` varchar(60) DEFAULT NULL,
  `estado` int(2) DEFAULT 0,
  `seccion` varchar(30) DEFAULT NULL,
  `responsable` smallint(5) unsigned DEFAULT NULL,
  `configuracion` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`id_archivo`),
  KEY `centro_salud` (`centro_salud`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `barrios`
--

DROP TABLE IF EXISTS `barrios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barrios` (
  `ID_Barrio` int(11) NOT NULL AUTO_INCREMENT,
  `Barrio` varchar(200) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `georeferencia` point DEFAULT NULL,
  PRIMARY KEY (`ID_Barrio`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bugs` (
  `ID_Bug` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(200) DEFAULT NULL,
  `Descripcion` varchar(2000) DEFAULT NULL,
  `accountid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Bug`),
  KEY `FK_UsuariosBug` (`accountid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calle`
--

DROP TABLE IF EXISTS `calle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calle` (
  `id_calle` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_calle` varchar(100) DEFAULT NULL,
  `calle_nombre` varchar(100) DEFAULT NULL,
  `calle_abreviado` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `calle_open` varchar(100) DEFAULT NULL,
  `geocoder` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_calle`),
  UNIQUE KEY `calle` (`id_calle`) USING BTREE,
  KEY `codigo_calle` (`codigo_calle`) USING BTREE,
  KEY `calle_nombre` (`calle_nombre`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=569 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calles_barrios`
--

DROP TABLE IF EXISTS `calles_barrios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calles_barrios` (
  `id_geo` int(20) NOT NULL AUTO_INCREMENT,
  `id_calle` int(20) NOT NULL,
  `max_num` int(10) DEFAULT NULL,
  `min_num` int(10) DEFAULT NULL,
  `punto_max_num` point DEFAULT NULL,
  `punto_min_num` point DEFAULT NULL,
  `ID_Barrio` int(11) DEFAULT NULL,
  `pendiente` point DEFAULT NULL,
  `punto` point DEFAULT NULL,
  `figura` int(2) DEFAULT NULL,
  `estado` int(2) DEFAULT 0,
  `offset_calle` float DEFAULT 0,
  PRIMARY KEY (`id_geo`),
  KEY `id_calle` (`id_calle`),
  KEY `ID_Barrio` (`ID_Barrio`)
) ENGINE=MyISAM AUTO_INCREMENT=742 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod_categoria` varchar(50) DEFAULT NULL,
  `categoria` varchar(200) DEFAULT NULL,
  `ID_Forma` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `tipo_categoria` int(5) DEFAULT 0,
  `orden` int(5) DEFAULT 0,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `cat` (`id_categoria`) USING BTREE,
  KEY `cod_cat` (`cod_categoria`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categoria_grupo`
--

DROP TABLE IF EXISTS `categoria_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_grupo` (
  `id_categoria` int(10) NOT NULL,
  `groupid` int(10) NOT NULL,
  `estado` int(11) DEFAULT 0,
  PRIMARY KEY (`id_categoria`,`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorias_roles`
--

DROP TABLE IF EXISTS `categorias_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias_roles` (
  `id_categoria_rol` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(20) unsigned NOT NULL,
  `fecha` date DEFAULT NULL,
  `id_tipousuario` int(20) NOT NULL,
  `estado` int(11) DEFAULT 0,
  PRIMARY KEY (`id_categoria_rol`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_tipousuario` (`id_tipousuario`)
) ENGINE=MyISAM AUTO_INCREMENT=283 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `centros_salud`
--

DROP TABLE IF EXISTS `centros_salud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `centros_salud` (
  `id_centro` int(11) NOT NULL AUTO_INCREMENT,
  `centro_salud` varchar(300) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_barrio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_centro`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuration` (
  `md5` tinyint(4) NOT NULL DEFAULT 1,
  `bad_attempts_max` int(11) NOT NULL DEFAULT 5,
  `bad_attempts_wait` int(11) NOT NULL DEFAULT 300,
  `log_activities` tinyint(4) NOT NULL DEFAULT 1,
  `timeout` int(11) NOT NULL DEFAULT 900,
  `error_reporting` tinyint(4) NOT NULL DEFAULT 1,
  `stylesheet` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contactos`
--

DROP TABLE IF EXISTS `contactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contactos` (
  `id_contacto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(40) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_persona` int(20) unsigned NOT NULL,
  `Trabajo` varchar(200) DEFAULT NULL,
  `estado` tinyint(3) unsigned DEFAULT 0,
  PRIMARY KEY (`id_contacto`),
  KEY `id_persona` (`id_persona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domicilios`
--

DROP TABLE IF EXISTS `domicilios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilios` (
  `id_domicilio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manzana` varchar(40) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `id_calle` int(20) DEFAULT NULL,
  `numero` smallint(10) unsigned DEFAULT 0,
  `estado` tinyint(3) unsigned DEFAULT 0,
  `ID_Barrio` int(11) DEFAULT NULL,
  `georeferencia` point DEFAULT NULL,
  `circunscripcion` smallint(10) unsigned DEFAULT NULL,
  `seccion` smallint(10) unsigned DEFAULT NULL,
  `lote` smallint(5) unsigned DEFAULT 0,
  PRIMARY KEY (`id_domicilio`),
  KEY `ID_Barrio` (`ID_Barrio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `escuelas`
--

DROP TABLE IF EXISTS `escuelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `escuelas` (
  `ID_Escuela` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(200) DEFAULT NULL,
  `Escuela` varchar(200) DEFAULT NULL,
  `CUE` varchar(200) DEFAULT NULL,
  `Localidad` varchar(200) DEFAULT NULL,
  `Departamento` varchar(200) DEFAULT NULL,
  `Directora` varchar(200) DEFAULT NULL,
  `Telefono` varchar(200) DEFAULT NULL,
  `Mail` varchar(200) DEFAULT NULL,
  `Observaciones` varchar(500) DEFAULT NULL,
  `ID_Nivel` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Escuela`),
  KEY `FK_Nivel` (`ID_Nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_elements`
--

DROP TABLE IF EXISTS `form_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(5) unsigned NOT NULL DEFAULT 0,
  `ord` int(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(15) NOT NULL DEFAULT '',
  `flags` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `flags` (`flags`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas_categorias`
--

DROP TABLE IF EXISTS `formas_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas_categorias` (
  `ID_Forma` int(11) NOT NULL AUTO_INCREMENT,
  `Figura` varchar(200) DEFAULT NULL,
  `Forma_Categoria` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID_Forma`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formularios`
--

DROP TABLE IF EXISTS `formularios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formularios` (
  `id_formulario` int(20) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `responsable` int(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `persona` int(20) DEFAULT NULL,
  `movimiento` int(20) DEFAULT NULL,
  `estado` int(2) DEFAULT 0,
  PRIMARY KEY (`id_formulario`),
  KEY `movimiento` (`movimiento`),
  KEY `persona` (`persona`),
  KEY `responsable` (`responsable`)
) ENGINE=MyISAM AUTO_INCREMENT=7275 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groupaccounts`
--

DROP TABLE IF EXISTS `groupaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupaccounts` (
  `groupid` int(11) NOT NULL DEFAULT 0,
  `accountid` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groupactions`
--

DROP TABLE IF EXISTS `groupactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupactions` (
  `groupid` int(11) NOT NULL DEFAULT 0,
  `actionid` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groupid_seq`
--

DROP TABLE IF EXISTS `groupid_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` text NOT NULL,
  `hierarchy` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log` (
  `timestamp` int(11) DEFAULT NULL,
  `ip` varchar(16) NOT NULL DEFAULT '----------------',
  `accountid` int(11) DEFAULT NULL,
  `username` text DEFAULT NULL,
  `activityid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `motivo`
--

DROP TABLE IF EXISTS `motivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo` (
  `id_motivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `motivo` varchar(200) DEFAULT NULL,
  `codigo` varchar(200) DEFAULT NULL,
  `cod_categoria` varchar(50) DEFAULT NULL,
  `num_motivo` int(10) DEFAULT NULL,
  `estado` tinyint(3) unsigned DEFAULT 0,
  `orden` tinyint(3) unsigned DEFAULT 0,
  `tipo_motivo` smallint(5) unsigned DEFAULT 0,
  PRIMARY KEY (`id_motivo`),
  UNIQUE KEY `mot` (`id_motivo`) USING BTREE,
  KEY `cat` (`cod_categoria`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento` (
  `id_movimiento` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `id_persona` int(20) DEFAULT NULL,
  `motivo_1` int(10) DEFAULT NULL,
  `motivo_2` int(10) DEFAULT NULL,
  `motivo_3` int(10) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `id_resp` int(10) DEFAULT NULL,
  `id_resp_2` int(11) DEFAULT NULL,
  `id_resp_3` int(11) DEFAULT NULL,
  `id_resp_4` int(11) DEFAULT NULL,
  `id_centro` int(11) DEFAULT NULL,
  `id_otrainstitucion` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `motivo_4` int(10) DEFAULT NULL,
  `motivo_5` int(10) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  UNIQUE KEY `mov` (`id_movimiento`) USING BTREE,
  KEY `persona` (`id_persona`) USING BTREE,
  KEY `m1` (`motivo_1`) USING BTREE,
  KEY `m2` (`motivo_2`) USING BTREE,
  KEY `m3` (`motivo_3`) USING BTREE,
  KEY `resp` (`id_resp`) USING BTREE,
  KEY `FK_OtrasInstituciones` (`id_otrainstitucion`)
) ENGINE=MyISAM AUTO_INCREMENT=15644 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimiento_copy`
--

DROP TABLE IF EXISTS `movimiento_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_copy` (
  `id_movimiento` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `id_persona` int(20) DEFAULT NULL,
  `motivo_1` int(10) DEFAULT NULL,
  `motivo_2` int(10) DEFAULT NULL,
  `motivo_3` int(10) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `id_resp` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  UNIQUE KEY `mov` (`id_movimiento`) USING BTREE,
  KEY `persona` (`id_persona`) USING BTREE,
  KEY `m1` (`motivo_1`) USING BTREE,
  KEY `m2` (`motivo_2`) USING BTREE,
  KEY `m3` (`motivo_3`) USING BTREE,
  KEY `resp` (`id_resp`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9638 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimiento_motivo`
--

DROP TABLE IF EXISTS `movimiento_motivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_motivo` (
  `id_movimiento` int(10) unsigned NOT NULL,
  `id_motivo` int(10) unsigned NOT NULL,
  `nro_motivo` int(2) DEFAULT 1,
  `estado` int(2) DEFAULT 0,
  `id_movimiento_motivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_movimiento_motivo`),
  KEY `fk_id_motivo` (`id_motivo`),
  KEY `fk_id_movimiento` (`id_movimiento`)
) ENGINE=MyISAM AUTO_INCREMENT=25064 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nivel_escuelas`
--

DROP TABLE IF EXISTS `nivel_escuelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivel_escuelas` (
  `ID_Nivel` int(11) NOT NULL AUTO_INCREMENT,
  `Nivel` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID_Nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `ID_Notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `Detalle` varchar(700) NOT NULL,
  `Fecha` datetime DEFAULT NULL,
  `Expira` datetime DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Notificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=1536 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `otras_instituciones`
--

DROP TABLE IF EXISTS `otras_instituciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otras_instituciones` (
  `ID_OtraInstitucion` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(700) DEFAULT NULL,
  `Telefono` varchar(200) DEFAULT NULL,
  `Mail` varchar(500) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_OtraInstitucion`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parametrias`
--

DROP TABLE IF EXISTS `parametrias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametrias` (
  `id_parametria` int(10) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `fecha_validez` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `valor` varchar(1800) DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `estado` int(2) DEFAULT 0,
  PRIMARY KEY (`id_parametria`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `documento` varchar(30) DEFAULT NULL,
  `nro_legajo` varchar(200) DEFAULT NULL,
  `edad` int(10) DEFAULT NULL,
  `fecha_nac` varchar(30) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `nro_carpeta` varchar(150) DEFAULT NULL,
  `obra_social` varchar(10) DEFAULT NULL,
  `domicilio` varchar(100) DEFAULT NULL,
  `ID_Barrio` int(11) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `circunscripcion` int(10) DEFAULT NULL,
  `seccion` int(10) DEFAULT NULL,
  `manzana` varchar(50) DEFAULT NULL,
  `lote` int(10) DEFAULT NULL,
  `familia` varchar(20) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `cambio_domicilio` varchar(999) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `ID_Escuela` int(11) DEFAULT NULL,
  `Trabajo` varchar(300) DEFAULT NULL,
  `meses` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `georeferencia` point DEFAULT NULL,
  `calle` int(20) DEFAULT NULL,
  `nro` int(5) DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `persona` (`id_persona`) USING BTREE,
  KEY `apellido` (`apellido`) USING BTREE,
  KEY `nombre` (`nombre`) USING BTREE,
  KEY `FK_Barrio` (`ID_Barrio`)
) ENGINE=MyISAM AUTO_INCREMENT=6401 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas` (
  `id_persona` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `documento` varchar(30) DEFAULT NULL,
  `edad` smallint(2) unsigned DEFAULT 0,
  `fecha_nac` varchar(20) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `obra_social` varchar(10) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `ID_Escuela` int(11) DEFAULT NULL,
  `meses` smallint(2) unsigned DEFAULT 0,
  `estado` smallint(2) unsigned DEFAULT 0,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `persona` (`id_persona`) USING BTREE,
  KEY `ID_Escuela` (`ID_Escuela`),
  KEY `apellido` (`apellido`) USING BTREE,
  KEY `nombre` (`nombre`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `queries`
--

DROP TABLE IF EXISTS `queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `db` varchar(50) NOT NULL DEFAULT '',
  `query` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `query` (`query`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report_options`
--

DROP TABLE IF EXISTS `report_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(5) unsigned NOT NULL DEFAULT 0,
  `type` varchar(10) NOT NULL DEFAULT '',
  `ord` int(3) unsigned NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(10) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(50) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `flags` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `flags` (`flags`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `responsable`
--

DROP TABLE IF EXISTS `responsable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responsable` (
  `id_resp` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `responsable` varchar(100) DEFAULT NULL,
  `accountid` int(10) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_resp`),
  UNIQUE KEY `resp` (`id_resp`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_crearcategorias`
--

DROP TABLE IF EXISTS `solicitudes_crearcategorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_crearcategorias` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Codigo` varchar(70) DEFAULT NULL,
  `Categoria` varchar(300) DEFAULT NULL,
  `ID_Forma` int(11) DEFAULT NULL,
  `Color` varchar(300) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_crearmotivos`
--

DROP TABLE IF EXISTS `solicitudes_crearmotivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_crearmotivos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Motivo` varchar(300) DEFAULT NULL,
  `Codigo` varchar(200) DEFAULT NULL,
  `Cod_Categoria` varchar(300) DEFAULT NULL,
  `Num_Motivo` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_eliminarcategorias`
--

DROP TABLE IF EXISTS `solicitudes_eliminarcategorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_eliminarcategorias` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Categoria` varchar(700) DEFAULT NULL,
  `Cod_Categoria` varchar(700) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_eliminarmotivos`
--

DROP TABLE IF EXISTS `solicitudes_eliminarmotivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_eliminarmotivos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Motivo` varchar(300) DEFAULT NULL,
  `Cod_Categoria` varchar(300) DEFAULT NULL,
  `Num_Motivo` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Motivo` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_Usuarios` (`ID_Usuario`),
  KEY `FK_Motivos` (`ID_Motivo`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_modificacion`
--

DROP TABLE IF EXISTS `solicitudes_modificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_modificacion` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `id_registro` int(11) NOT NULL,
  `id_tipo` tinyint(4) NOT NULL,
  `valor` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id_solicitud`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_modificarcategorias`
--

DROP TABLE IF EXISTS `solicitudes_modificarcategorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_modificarcategorias` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Codigo` varchar(70) DEFAULT NULL,
  `Categoria` varchar(300) DEFAULT NULL,
  `ID_Forma` int(11) DEFAULT NULL,
  `NuevoColor` varchar(300) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Categoria` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_modificarmotivos`
--

DROP TABLE IF EXISTS `solicitudes_modificarmotivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_modificarmotivos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Motivo` varchar(300) DEFAULT NULL,
  `Codigo` varchar(200) DEFAULT NULL,
  `Cod_Categoria` varchar(300) DEFAULT NULL,
  `Num_Motivo` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Motivo` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_Usuarios` (`ID_Usuario`),
  KEY `FK_Motivos` (`ID_Motivo`)
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_permisos`
--

DROP TABLE IF EXISTS `solicitudes_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_permisos` (
  `id_solicitud_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `ID` int(11) NOT NULL,
  `ID_TipoUsuario` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `estado` int(11) DEFAULT 0,
  PRIMARY KEY (`id_solicitud_permiso`),
  KEY `ID` (`ID`),
  KEY `ID_TipoUsuario` (`ID_TipoUsuario`)
) ENGINE=MyISAM AUTO_INCREMENT=623 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_unificacion`
--

DROP TABLE IF EXISTS `solicitudes_unificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_unificacion` (
  `ID_Solicitud_Unificacion` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `ID_Registro_1` int(11) DEFAULT NULL,
  `ID_Registro_2` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_TipoUnif` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Solicitud_Unificacion`),
  KEY `FK_Registro_1` (`ID_Registro_1`),
  KEY `FK_Registro_2` (`ID_Registro_2`),
  KEY `FK_Usuarios` (`ID_Usuario`),
  KEY `FK_TiposUnif` (`ID_TipoUnif`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes_usuarios`
--

DROP TABLE IF EXISTS `solicitudes_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_usuarios` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `tipo` int(2) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `estado` int(2) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_categorias`
--

DROP TABLE IF EXISTS `tipos_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_categorias` (
  `id_tipo_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `estado` int(2) DEFAULT 1,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_unif`
--

DROP TABLE IF EXISTS `tipos_unif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_unif` (
  `ID_TipoUnif` int(11) NOT NULL AUTO_INCREMENT,
  `TipoUnif` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`ID_TipoUnif`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_tokens`
--

DROP TABLE IF EXISTS `users_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_tokens` (
  `token` varchar(50) NOT NULL,
  `accountid` int(11) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `estado` int(2) DEFAULT 0,
  PRIMARY KEY (`token`),
  KEY `accountid` (`accountid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-19  8:24:51
