-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-12-2022 a las 08:15:38
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rastreador`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acciones`
--

CREATE TABLE `acciones` (
  `ID_Accion` int(11) NOT NULL,
  `accountid` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `Detalles` varchar(400) DEFAULT NULL,
  `ID_TipoAccion` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accountid_seq`
--

CREATE TABLE `accountid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accounts`
--

CREATE TABLE `accounts` (
  `accountid` int(11) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `initials` text DEFAULT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `hintquestion` text DEFAULT NULL,
  `hintanswer` text DEFAULT NULL,
  `expired` tinyint(4) DEFAULT NULL,
  `expireddate` int(11) DEFAULT NULL,
  `tries` int(11) DEFAULT NULL,
  `lasttrieddate` int(11) DEFAULT NULL,
  `matricula` text DEFAULT NULL,
  `iva` text DEFAULT NULL,
  `ID_TipoUsuario` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `accounts`
--

INSERT INTO `accounts` (`accountid`, `firstname`, `lastname`, `initials`, `username`, `password`, `email`, `hintquestion`, `hintanswer`, `expired`, `expireddate`, `tries`, `lasttrieddate`, `matricula`, `iva`, `ID_TipoUsuario`, `estado`) VALUES
(1, 'administrator', 'admin', 'ADM', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'maico.computos@gmail.com', 'how are you?', 'very well', 0, 0, 0, 0, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actionid_seq`
--

CREATE TABLE `actionid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actions`
--

CREATE TABLE `actions` (
  `actionid` int(11) NOT NULL,
  `actionname` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity`
--

CREATE TABLE `activity` (
  `activityid` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activityid_seq`
--

CREATE TABLE `activityid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barrios`
--

CREATE TABLE `barrios` (
  `ID_Barrio` int(11) NOT NULL,
  `Barrio` varchar(200) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bugs`
--

CREATE TABLE `bugs` (
  `ID_Bug` int(11) NOT NULL,
  `Tipo` varchar(200) DEFAULT NULL,
  `Descripcion` varchar(2000) DEFAULT NULL,
  `accountid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `cod_categoria` varchar(50) DEFAULT NULL,
  `categoria` varchar(200) DEFAULT NULL,
  `ID_Forma` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `cod_categoria`, `categoria`, `ID_Forma`, `color`, `estado`) VALUES
(1, 'VC', 'Vacunas Completas', 3, '#00CC00', 1),
(3, 'CNS', 'Control De Niño Sano', 1, '#00FF00', 0),
(4, '', '', 1, NULL, NULL),
(5, 'CDAD', 'Cuidado del crecimiento y el desarrollo al día', 2, '#33CC00', 1),
(6, 'EPAD', 'Cuidado del embarazo y el puerperio al día', 68, '#66CC00', 1),
(7, 'VI', 'Vacunas Incompletas', 65, '#FF3300', 1),
(8, 'VEC', 'Vacunacion En Curso', 65, '#339900', 1),
(9, 'CDAT', 'Cuidado del crecimiento y el desarrollo atrasado', 62, '#FF0000', 1),
(10, 'EPAT', 'Cuidado del embarazo y el puerperio atrasado', 1, '#FF9933', 1),
(11, 'VEC', 'Vacunacion En Curso', 1, NULL, NULL),
(12, 'CDFPO', 'Cuidado del crecimiento y el desarrollo fuera de programa con obra social', 62, '#FF9999', 1),
(13, 'EPFO', 'Cuidado Del Embarazo Y El Puerperio Fuera Del Programa Con Obra Social', 1, '#FF9999', 1),
(14, 'VSD', 'Vacunacion Sin Datos', 3, '#FF0000', 1),
(15, 'CDFS', 'Cuidado Del Crecimiento Y El Desarrollo Fuera Del Programa Sin Obra Social', 2, '#FF3333', 1),
(16, 'EPSO', 'Cuidado Del Embarazo Y El Puerperio Fuera Del Programa Sin Obra Social', 1, '#FF3333', 1),
(17, 'CCCC', 'CCCC', 1, '#646464', 0),
(18, 'CTMM', 'Categoria Manual', 1, '#FFFF00', 0),
(19, 'SO', 'Social', 51, '#339933', 0),
(20, 'FME', 'Ficha Médica Escolar', 1, '#DDDDDD', 1),
(21, 'DRV', 'Derivaciones', 1, '#C0C0C0', 1),
(22, 'ENF.', 'ENFERMERIA', 1, '#0033FF', 1),
(23, 'PXA', 'Próxima Atención', 6, '#000000', 1),
(24, 'FPP', 'Fecha Probable De Parto', 6, '#FF9900', 1),
(25, 'TEST', 'PRUEBA DE TESTEO', 68, '', 0),
(26, 'TEST2', 'TEST PRUEBA', 68, '#00FF99', 0),
(27, 'COVID', 'Covid', 3, NULL, NULL),
(28, 'DIG', 'Dig', 10, '#FF3300', 0),
(29, 'INF ', 'Infecciosas', 4, NULL, NULL),
(30, 'CD', 'Card', 5, NULL, NULL),
(31, 'OMAT', 'Osteomusculartendinoso', 4, NULL, NULL),
(32, 'TEST4', 'TEST NUMERO 4', 4, '#000000', 0),
(33, 'DERM', 'Dermatológicas', 36, '#FF9966', 1),
(34, 'SSRAT', 'Salud Sexual, Reproductiva Y No Reproductiva Control Atrasado', 66, '#FF0000', 1),
(35, 'L', 'Entrega De Leche', 44, '#00FFFF', 0),
(36, 'SSRAD', 'Salud Sexual, Reproductiva Y No Reproductiva Al Día', 4, '#339900', 1),
(37, 'SSRNPSO', 'Salud Sexual Reproductiva Y No Reproductiva No En Programa Sin Obra Social', 4, '#FF3333', 1),
(38, 'SSR', 'Salud Sexual Reproductiva Y No Reproductiva No En Programa Con Obra Social', 60, '#FF9999', 1),
(39, 'PX', 'Próxima Atención', 6, '#000000', 0),
(40, 'PXEP', 'Próxima Atención Embarazada  O Puérpera', 6, '#FFCC66', 0),
(41, 'PXV', 'Próxima Vacuna', 6, '#6600FF', 1),
(42, 'PXV', 'Próxima Vacuna', 6, NULL, NULL),
(43, 'TS', 'Trabajo Social', 47, '#FFCCCC', 0),
(44, 'FICHAS 9944', 'Fichas 9944', 38, '#000033', 1),
(45, 'EDUCACIóN', 'Educación', 37, '#FF66FF', 0),
(46, 'QA12', 'QALOL223', 68, '', 0),
(47, 'QALOL', 'QA', 68, '#0033FF', 0),
(48, 'AS', 'ancianos solos', 33, '#99CCFF', 0),
(49, 'FEP', 'fecha efectiva de parto', 5, '#FF3300', 1),
(50, 'CTS2022', 'CAMPAÑA TRIPLE VIRAL Y SALK 2022', 54, '#00FF00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_grupo`
--

CREATE TABLE `categoria_grupo` (
  `id_categoria` int(10) NOT NULL,
  `groupid` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centros_salud`
--

CREATE TABLE `centros_salud` (
  `id_centro` int(11) NOT NULL,
  `centro_salud` varchar(300) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE `configuration` (
  `md5` tinyint(4) NOT NULL DEFAULT 1,
  `bad_attempts_max` int(11) NOT NULL DEFAULT 5,
  `bad_attempts_wait` int(11) NOT NULL DEFAULT 300,
  `log_activities` tinyint(4) NOT NULL DEFAULT 1,
  `timeout` int(11) NOT NULL DEFAULT 900,
  `error_reporting` tinyint(4) NOT NULL DEFAULT 1,
  `stylesheet` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas`
--

CREATE TABLE `escuelas` (
  `ID_Escuela` int(11) NOT NULL,
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
  `Estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formas_categorias`
--

CREATE TABLE `formas_categorias` (
  `ID_Forma` int(11) NOT NULL,
  `Figura` varchar(200) DEFAULT NULL,
  `Forma_Categoria` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forms`
--

CREATE TABLE `forms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form_elements`
--

CREATE TABLE `form_elements` (
  `id` int(10) UNSIGNED NOT NULL,
  `form_id` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `ord` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(15) NOT NULL DEFAULT '',
  `flags` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groupaccounts`
--

CREATE TABLE `groupaccounts` (
  `groupid` int(11) NOT NULL DEFAULT 0,
  `accountid` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groupactions`
--

CREATE TABLE `groupactions` (
  `groupid` int(11) NOT NULL DEFAULT 0,
  `actionid` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groupid_seq`
--

CREATE TABLE `groupid_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE `groups` (
  `groupid` int(11) NOT NULL,
  `groupname` text NOT NULL,
  `hierarchy` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `timestamp` int(11) DEFAULT NULL,
  `ip` varchar(16) NOT NULL DEFAULT '----------------',
  `accountid` int(11) DEFAULT NULL,
  `username` text DEFAULT NULL,
  `activityid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo`
--

CREATE TABLE `motivo` (
  `id_motivo` int(10) UNSIGNED NOT NULL,
  `motivo` varchar(200) DEFAULT NULL,
  `codigo` varchar(200) DEFAULT NULL,
  `cod_categoria` varchar(50) DEFAULT NULL,
  `num_motivo` int(10) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `motivo`
--

INSERT INTO `motivo` (`id_motivo`, `motivo`, `codigo`, `cod_categoria`, `num_motivo`, `estado`) VALUES
(1, 'Sin Motivo', NULL, NULL, NULL, 1),
(2, 'Control de Niño Sano', NULL, 'CNS', NULL, 0),
(3, 'Triple Viral', NULL, 'VC', NULL, 0),
(4, 'Antri-Gripal', NULL, 'VC', NULL, 0),
(5, 'Cuidado del crecimiento y el desarrollo fuera de programa con obra social', 'CDFPO', 'CDFPO', NULL, 1),
(6, 'Cuidado del crecimiento y el desarrollo al dia', 'CDAD', 'CDAD', NULL, 1),
(7, 'Cuidado del crecimiento y el desarrollo atrasado', 'CDAT', 'CDAT', NULL, 1),
(8, 'Cuidado del crecimiento y el desarrollo fuera de programa sin obra social', 'CDFS', 'CDFS', NULL, 1),
(9, 'Cuidado del embarazo y el puerperio atrasado', 'EPAT', 'EPAT', NULL, 1),
(10, 'Cuidado del embarazo y del puerperio fuera del programa con obra social', 'EPFO', 'EPFO', NULL, 1),
(11, 'Cuidado del embarazo y el puerperio al dia', 'EPAD', 'EPAD', NULL, 1),
(12, 'Cuidado del embarazo y el puerperio fuera del programa sin obra social', 'EPSO', 'EPSO', NULL, 1),
(13, 'Vacunacion en curso', 'VEC', 'VEC', NULL, 1),
(14, 'Vacunacion sin datos', 'VSD', 'VSD', NULL, 1),
(15, 'Vacunas completas', 'VC', 'VC', NULL, 1),
(16, 'Vacunas incompletas', 'VI', 'VI', NULL, 1),
(17, 'fff', NULL, 'CDAD', NULL, 0),
(18, 'CCCCR', NULL, 'CCCC', NULL, 1),
(19, 'Tarjeta Social', NULL, 'SO', NULL, 1),
(20, 'Ficha médica escolar', 'FME', 'FME', NULL, 1),
(21, 'Derivaciones', 'DRV', 'DRV', NULL, 1),
(22, 'Informe socio-económico para alquiler', NULL, 'SO', NULL, 1),
(23, 'INY', NULL, 'ENF.', NULL, 1),
(24, 'Próxima atención CCD', 'PXC', 'PXA', NULL, 1),
(25, 'Próxima atención CEP', 'PXC', 'PXA', NULL, 1),
(26, 'Fecha probable de parto', 'FPP', 'FPP', NULL, 1),
(27, 'Atención especial del crecimiento ', 'AEC', 'CDAD', NULL, 1),
(28, 'vacuna covid 19', NULL, 'VEC', NULL, 1),
(29, 'Cólico', NULL, 'DIG', NULL, 1),
(30, 'prurito', NULL, 'DERM', NULL, 1),
(31, 'salud sexual y reproductiva', NULL, 'SSYR', NULL, 1),
(32, 'entrega de leche en polvo', NULL, 'L', NULL, 1),
(33, 'Próxima atención niña/o', NULL, 'PX', NULL, 1),
(34, 'Próxima atención embarazada o puérpera', NULL, 'PX', NULL, 1),
(35, 'Próxima Vacuna', 'PXV', 'PXA', NULL, 1),
(36, 'Próxima atención salud sexual y reproductiva', 'PXC', 'PXA', NULL, 1),
(37, 'Tarjeta', NULL, 'SO', NULL, 0),
(38, 'TS', NULL, 'SO', NULL, 1),
(39, 'Ficha 9944 recibida de la Escuela', 'FRE', 'FICHAS 9944', NULL, 1),
(40, 'Ficha 9944 enviada al Eq. Mun.', 'FEE', 'FICHAS 9944', NULL, 1),
(41, 'Ficha 9944 informada por Equipo Profesional de la Municipalidad', 'FIE', 'FICHAS 9944', NULL, 1),
(42, 'Ficha 9944 devolución a la Escuela', 'FDE', 'FICHAS 9944', NULL, 1),
(43, 'educación', NULL, 'EDUCACIóN', NULL, 1),
(44, 'Derivacion Alto Riesgo', NULL, 'DRV', NULL, 1),
(45, 'vivienda', NULL, 'SO', NULL, 1),
(46, 'viv', NULL, 'SO', NULL, 0),
(47, 'ww', NULL, 'SO', NULL, 0),
(48, 'mudanza', NULL, 'SO', NULL, 1),
(49, 'QA ESTOS', NULL, 'CDAD', NULL, 0),
(50, 'QA Prueba', NULL, 'QALOL', NULL, 0),
(51, 'QA LOPPP', NULL, 'QALOL', NULL, 0),
(52, 'QA PRUEBA CODIGO', 'AAAA', 'QALOL', NULL, 0),
(54, 'qa probando Mod', 'QAPRUEBA', 'QALOL', NULL, 0),
(53, 'qa prueba unif', 'BBBB', 'QALOL', NULL, 1),
(55, 'as', 'as', 'AS', NULL, 1),
(56, 'Atención especial del desarrollo', 'AED', 'CDAD', NULL, 1),
(57, 'Pedido de Uder/Senaf', 'Uder', 'FICHAS 9944', NULL, 1),
(58, 'Legajo 19', 'leg', 'FICHAS 9944', NULL, 1),
(59, 'fecha efectiva de parto', 'FEP', 'FEP', NULL, 1),
(60, 'CAMPAÑA TRIPLE VIRAL Y SALK 2022', 'CTS22', 'CTS2022', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

CREATE TABLE `movimiento` (
  `id_movimiento` int(20) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `id_persona` int(20) DEFAULT NULL,
  `motivo_1` int(10) DEFAULT NULL,
  `motivo_2` int(10) DEFAULT NULL,
  `motivo_3` int(10) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `id_resp` int(10) DEFAULT NULL,
  `id_centro` int(11) DEFAULT NULL,
  `id_otrainstitucion` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_copy`
--

CREATE TABLE `movimiento_copy` (
  `id_movimiento` int(20) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `id_persona` int(20) DEFAULT NULL,
  `motivo_1` int(10) DEFAULT NULL,
  `motivo_2` int(10) DEFAULT NULL,
  `motivo_3` int(10) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `id_resp` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_escuelas`
--

CREATE TABLE `nivel_escuelas` (
  `ID_Nivel` int(11) NOT NULL,
  `Nivel` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nivel_escuelas`
--

INSERT INTO `nivel_escuelas` (`ID_Nivel`, `Nivel`) VALUES
(2, 'Primario'),
(3, 'Secundario'),
(4, 'Terciario'),
(1, 'Inicial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otras_instituciones`
--

CREATE TABLE `otras_instituciones` (
  `ID_OtraInstitucion` int(11) NOT NULL,
  `Nombre` varchar(700) DEFAULT NULL,
  `Telefono` varchar(200) DEFAULT NULL,
  `Mail` varchar(500) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(20) UNSIGNED NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `documento` varchar(30) DEFAULT NULL,
  `nro_legajo` varchar(150) DEFAULT NULL,
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
  `familia` int(10) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `cambio_domicilio` varchar(999) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `ID_Escuela` int(11) DEFAULT NULL,
  `Trabajo` varchar(300) DEFAULT NULL,
  `meses` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `queries`
--

CREATE TABLE `queries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `db` varchar(50) NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `flags` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report_options`
--

CREATE TABLE `report_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(10) NOT NULL DEFAULT '',
  `ord` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(10) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(50) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsable`
--

CREATE TABLE `responsable` (
  `id_resp` int(10) UNSIGNED NOT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `accountid` int(10) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_eliminarmotivos`
--

CREATE TABLE `solicitudes_eliminarmotivos` (
  `ID` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Motivo` varchar(300) DEFAULT NULL,
  `Cod_Categoria` varchar(300) DEFAULT NULL,
  `Num_Motivo` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Motivo` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_modificarcategorias`
--

CREATE TABLE `solicitudes_modificarcategorias` (
  `ID` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Codigo` varchar(70) DEFAULT NULL,
  `Categoria` varchar(300) DEFAULT NULL,
  `ID_Forma` int(11) DEFAULT NULL,
  `NuevoColor` varchar(300) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_modificarmotivos`
--

CREATE TABLE `solicitudes_modificarmotivos` (
  `ID` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Motivo` varchar(300) DEFAULT NULL,
  `Codigo` varchar(200) DEFAULT NULL,
  `Cod_Categoria` varchar(300) DEFAULT NULL,
  `Num_Motivo` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Motivo` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_unificacion`
--

CREATE TABLE `solicitudes_unificacion` (
  `ID_Solicitud_Unificacion` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `ID_Registro_1` int(11) DEFAULT NULL,
  `ID_Registro_2` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_TipoUnif` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoacciones`
--

CREATE TABLE `tipoacciones` (
  `ID_TipoAccion` int(11) NOT NULL,
  `Tipo` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipoacciones`
--

INSERT INTO `tipoacciones` (`ID_TipoAccion`, `Tipo`) VALUES
(1, 'INSERT'),
(2, 'MODIFY'),
(3, 'DELETE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_unif`
--

CREATE TABLE `tipos_unif` (
  `ID_TipoUnif` int(11) NOT NULL,
  `TipoUnif` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipos_unif`
--

INSERT INTO `tipos_unif` (`ID_TipoUnif`, `TipoUnif`) VALUES
(1, 'MOTIVO'),
(2, 'PERSONAS'),
(3, 'CENTROS SALUD'),
(4, 'ESCUELAS'),
(5, 'BARRIOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuarios`
--

CREATE TABLE `tipo_usuarios` (
  `ID_TipoUsuario` int(11) NOT NULL,
  `TipoUsuario` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_usuarios`
--

INSERT INTO `tipo_usuarios` (`ID_TipoUsuario`, `TipoUsuario`) VALUES
(1, 'Supervisor'),
(2, 'Administrativo'),
(3, 'Profesional');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acciones`
--
ALTER TABLE `acciones`
  ADD PRIMARY KEY (`ID_Accion`),
  ADD KEY `FK_TipoAcciones` (`ID_TipoAccion`);

--
-- Indices de la tabla `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountid`),
  ADD KEY `FK_TipoUsuarios` (`ID_TipoUsuario`);

--
-- Indices de la tabla `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`actionid`);

--
-- Indices de la tabla `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityid`);

--
-- Indices de la tabla `barrios`
--
ALTER TABLE `barrios`
  ADD PRIMARY KEY (`ID_Barrio`);

--
-- Indices de la tabla `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`ID_Bug`),
  ADD KEY `FK_UsuariosBug` (`accountid`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `cat` (`id_categoria`) USING BTREE,
  ADD KEY `cod_cat` (`cod_categoria`) USING BTREE;

--
-- Indices de la tabla `categoria_grupo`
--
ALTER TABLE `categoria_grupo`
  ADD PRIMARY KEY (`id_categoria`,`groupid`);

--
-- Indices de la tabla `centros_salud`
--
ALTER TABLE `centros_salud`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indices de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  ADD PRIMARY KEY (`ID_Escuela`),
  ADD KEY `FK_Nivel` (`ID_Nivel`);

--
-- Indices de la tabla `formas_categorias`
--
ALTER TABLE `formas_categorias`
  ADD PRIMARY KEY (`ID_Forma`);

--
-- Indices de la tabla `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `form_elements`
--
ALTER TABLE `form_elements`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `form_elements` ADD FULLTEXT KEY `flags` (`flags`);

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupid`);

--
-- Indices de la tabla `motivo`
--
ALTER TABLE `motivo`
  ADD PRIMARY KEY (`id_motivo`),
  ADD UNIQUE KEY `mot` (`id_motivo`) USING BTREE,
  ADD KEY `cat` (`cod_categoria`) USING BTREE;

--
-- Indices de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD UNIQUE KEY `mov` (`id_movimiento`) USING BTREE,
  ADD KEY `persona` (`id_persona`) USING BTREE,
  ADD KEY `m1` (`motivo_1`) USING BTREE,
  ADD KEY `m2` (`motivo_2`) USING BTREE,
  ADD KEY `m3` (`motivo_3`) USING BTREE,
  ADD KEY `resp` (`id_resp`) USING BTREE,
  ADD KEY `FK_OtrasInstituciones` (`id_otrainstitucion`);

--
-- Indices de la tabla `movimiento_copy`
--
ALTER TABLE `movimiento_copy`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD UNIQUE KEY `mov` (`id_movimiento`) USING BTREE,
  ADD KEY `persona` (`id_persona`) USING BTREE,
  ADD KEY `m1` (`motivo_1`) USING BTREE,
  ADD KEY `m2` (`motivo_2`) USING BTREE,
  ADD KEY `m3` (`motivo_3`) USING BTREE,
  ADD KEY `resp` (`id_resp`) USING BTREE;

--
-- Indices de la tabla `nivel_escuelas`
--
ALTER TABLE `nivel_escuelas`
  ADD PRIMARY KEY (`ID_Nivel`);

--
-- Indices de la tabla `otras_instituciones`
--
ALTER TABLE `otras_instituciones`
  ADD PRIMARY KEY (`ID_OtraInstitucion`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `persona` (`id_persona`) USING BTREE,
  ADD KEY `apellido` (`apellido`) USING BTREE,
  ADD KEY `nombre` (`nombre`) USING BTREE,
  ADD KEY `FK_Barrio` (`ID_Barrio`);

--
-- Indices de la tabla `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `queries` ADD FULLTEXT KEY `query` (`query`);

--
-- Indices de la tabla `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `reports` ADD FULLTEXT KEY `flags` (`flags`);

--
-- Indices de la tabla `report_options`
--
ALTER TABLE `report_options`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`id_resp`),
  ADD UNIQUE KEY `resp` (`id_resp`) USING BTREE;

--
-- Indices de la tabla `solicitudes_eliminarmotivos`
--
ALTER TABLE `solicitudes_eliminarmotivos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Usuarios` (`ID_Usuario`),
  ADD KEY `FK_Motivos` (`ID_Motivo`);

--
-- Indices de la tabla `solicitudes_modificarcategorias`
--
ALTER TABLE `solicitudes_modificarcategorias`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `solicitudes_modificarmotivos`
--
ALTER TABLE `solicitudes_modificarmotivos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Usuarios` (`ID_Usuario`),
  ADD KEY `FK_Motivos` (`ID_Motivo`);

--
-- Indices de la tabla `solicitudes_unificacion`
--
ALTER TABLE `solicitudes_unificacion`
  ADD PRIMARY KEY (`ID_Solicitud_Unificacion`),
  ADD KEY `FK_Registro_1` (`ID_Registro_1`),
  ADD KEY `FK_Registro_2` (`ID_Registro_2`),
  ADD KEY `FK_Usuarios` (`ID_Usuario`),
  ADD KEY `FK_TiposUnif` (`ID_TipoUnif`);

--
-- Indices de la tabla `tipoacciones`
--
ALTER TABLE `tipoacciones`
  ADD PRIMARY KEY (`ID_TipoAccion`);

--
-- Indices de la tabla `tipos_unif`
--
ALTER TABLE `tipos_unif`
  ADD PRIMARY KEY (`ID_TipoUnif`);

--
-- Indices de la tabla `tipo_usuarios`
--
ALTER TABLE `tipo_usuarios`
  ADD PRIMARY KEY (`ID_TipoUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acciones`
--
ALTER TABLE `acciones`
  MODIFY `ID_Accion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2652;

--
-- AUTO_INCREMENT de la tabla `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `actions`
--
ALTER TABLE `actions`
  MODIFY `actionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `activity`
--
ALTER TABLE `activity`
  MODIFY `activityid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `barrios`
--
ALTER TABLE `barrios`
  MODIFY `ID_Barrio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `bugs`
--
ALTER TABLE `bugs`
  MODIFY `ID_Bug` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `centros_salud`
--
ALTER TABLE `centros_salud`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  MODIFY `ID_Escuela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `formas_categorias`
--
ALTER TABLE `formas_categorias`
  MODIFY `ID_Forma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `form_elements`
--
ALTER TABLE `form_elements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `motivo`
--
ALTER TABLE `motivo`
  MODIFY `id_motivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  MODIFY `id_movimiento` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1029;

--
-- AUTO_INCREMENT de la tabla `movimiento_copy`
--
ALTER TABLE `movimiento_copy`
  MODIFY `id_movimiento` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9638;

--
-- AUTO_INCREMENT de la tabla `nivel_escuelas`
--
ALTER TABLE `nivel_escuelas`
  MODIFY `ID_Nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `otras_instituciones`
--
ALTER TABLE `otras_instituciones`
  MODIFY `ID_OtraInstitucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=925;

--
-- AUTO_INCREMENT de la tabla `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `report_options`
--
ALTER TABLE `report_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `responsable`
--
ALTER TABLE `responsable`
  MODIFY `id_resp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `solicitudes_eliminarmotivos`
--
ALTER TABLE `solicitudes_eliminarmotivos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes_modificarcategorias`
--
ALTER TABLE `solicitudes_modificarcategorias`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `solicitudes_modificarmotivos`
--
ALTER TABLE `solicitudes_modificarmotivos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `solicitudes_unificacion`
--
ALTER TABLE `solicitudes_unificacion`
  MODIFY `ID_Solicitud_Unificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tipoacciones`
--
ALTER TABLE `tipoacciones`
  MODIFY `ID_TipoAccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_unif`
--
ALTER TABLE `tipos_unif`
  MODIFY `ID_TipoUnif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_usuarios`
--
ALTER TABLE `tipo_usuarios`
  MODIFY `ID_TipoUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
