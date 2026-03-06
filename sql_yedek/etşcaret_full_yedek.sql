-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- Sunucu sürümü:                10.4.32-MariaDB - mariadb.org binary distribution
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- eticaret için veritabanı yapısı dökülüyor
CREATE DATABASE IF NOT EXISTS `eticaret` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci */;
USE `eticaret`;

-- tablo yapısı dökülüyor eticaret.musteriler
CREATE TABLE IF NOT EXISTS `musteriler` (
  `musteri_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `sehir` varchar(100) DEFAULT NULL,
  `ulke` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`musteri_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.musteriler: ~2 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `musteriler` (`musteri_id`, `ad`, `soyad`, `email`, `telefon`, `adres`, `sehir`, `ulke`) VALUES
	(1, 'Ahmet', 'Yılmaz', 'ahmet.yilmaz@example.com', '+905321234567', 'İstiklal Cad. No:10', 'İstanbul', 'Türkiye'),
	(2, 'Ayşe', 'Demir', 'ayse.demir@example.com', '+905321234568', 'Atatürk Bulvarı No:25', 'Ankara', 'Türkiye');

-- tablo yapısı dökülüyor eticaret.siparis_detaylari
CREATE TABLE IF NOT EXISTS `siparis_detaylari` (
  `detay_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siparis_id` int(10) unsigned NOT NULL,
  `bilesen_id` int(10) unsigned NOT NULL,
  `adet` int(10) unsigned NOT NULL,
  `birim_fiyat` decimal(10,2) NOT NULL,
  PRIMARY KEY (`detay_id`),
  KEY `siparis_id` (`siparis_id`),
  KEY `bilesen_id` (`bilesen_id`),
  CONSTRAINT `siparis_detaylari_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`siparis_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `siparis_detaylari_ibfk_2` FOREIGN KEY (`bilesen_id`) REFERENCES `urun_bilesenler` (`bilesen_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.siparis_detaylari: ~4 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `siparis_detaylari` (`detay_id`, `siparis_id`, `bilesen_id`, `adet`, `birim_fiyat`) VALUES
	(1, 1, 1, 10, 0.50),
	(2, 1, 4, 5, 0.40),
	(3, 2, 6, 20, 0.10),
	(4, 2, 9, 2, 0.80);

-- tablo yapısı dökülüyor eticaret.siparisler
CREATE TABLE IF NOT EXISTS `siparisler` (
  `siparis_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `musteri_id` int(10) unsigned NOT NULL,
  `siparis_tarihi` datetime NOT NULL DEFAULT current_timestamp(),
  `durum` varchar(50) DEFAULT 'Hazırlanıyor',
  PRIMARY KEY (`siparis_id`),
  KEY `musteri_id` (`musteri_id`),
  CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.siparisler: ~2 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `siparisler` (`siparis_id`, `musteri_id`, `siparis_tarihi`, `durum`) VALUES
	(1, 1, '2025-11-20 19:31:28', 'Hazırlanıyor'),
	(2, 2, '2025-11-20 19:31:28', 'Tamamlandı');

-- tablo yapısı dökülüyor eticaret.urun_bilesenler
CREATE TABLE IF NOT EXISTS `urun_bilesenler` (
  `bilesen_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` int(10) unsigned NOT NULL,
  `bilesen_tr` varchar(255) NOT NULL,
  `bilesen_en` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bilesen_id`),
  KEY `idx_kategori` (`kategori_id`),
  KEY `idx_bilesen_tr` (`bilesen_tr`),
  CONSTRAINT `fk_bilesen_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `urun_kategoriler` (`kategori_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.urun_bilesenler: ~48 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `urun_bilesenler` (`bilesen_id`, `kategori_id`, `bilesen_tr`, `bilesen_en`) VALUES
	(1, 15, 'NPN Transistör BC547', 'NPN Transistor BC547'),
	(2, 15, 'PNP Transistör BC557', 'PNP Transistor BC557'),
	(3, 15, 'MOSFET IRF540N', 'MOSFET IRF540N'),
	(4, 15, 'MOSFET IRFZ44N', 'MOSFET IRFZ44N'),
	(5, 15, 'Darlington TIP122', 'Darlington TIP122'),
	(6, 15, 'JFET 2N5457', 'JFET 2N5457'),
	(7, 15, 'Güç Transistörü 2N3055', 'Power Transistor 2N3055'),
	(8, 15, 'Transistör BD139', 'Transistor BD139'),
	(9, 15, 'NPN 2N2222', 'NPN 2N2222'),
	(10, 15, 'NPN 2N3904', 'NPN 2N3904'),
	(11, 15, 'PNP 2N2907', 'PNP 2N2907'),
	(12, 15, 'PNP 2N3906', 'PNP 2N3906'),
	(13, 15, 'Power TIP3055', 'Power TIP3055'),
	(14, 15, 'Power MJ2955', 'Power MJ2955'),
	(15, 15, 'Power TIP41C', 'Power TIP41C'),
	(16, 15, 'Power TIP42C', 'Power TIP42C'),
	(17, 15, 'MOSFET IRF3205', 'MOSFET IRF3205'),
	(18, 15, 'MOSFET IRF840', 'MOSFET IRF840'),
	(19, 15, 'MOSFET 2N7000', 'MOSFET 2N7000'),
	(20, 15, 'Darlington TIP127', 'Darlington TIP127'),
	(21, 15, 'Darlington BDW93C', 'Darlington BDW93C'),
	(22, 15, 'Darlington BDW94C', 'Darlington BDW94C'),
	(23, 15, 'IGBT IRG4PC50', 'IGBT IRG4PC50'),
	(24, 15, 'IGBT GT60M303', 'IGBT GT60M303'),
	(25, 15, 'Fototransistör L14G2', 'Phototransistor L14G2'),
	(26, 15, 'UJT 2N2646', 'Unijunction Transistor 2N2646'),
	(27, 15, 'HF Transistör BFR93A', 'High Frequency Transistor BFR93A'),
	(28, 15, 'RF Power 2N3866', 'RF Power Transistor 2N3866'),
	(29, 15, 'RF Power 2N5109', 'RF Power Transistor 2N5109'),
	(30, 15, 'NPN MJE340', 'NPN MJE340'),
	(31, 15, 'PNP MJE350', 'PNP MJE350'),
	(32, 15, 'NPN BD241', 'NPN BD241'),
	(33, 15, 'PNP BD242', 'PNP BD242'),
	(34, 15, 'NPN TIP120', 'NPN TIP120'),
	(35, 15, 'PNP TIP125', 'PNP TIP125'),
	(36, 15, 'NPN S8050', 'NPN S8050'),
	(37, 15, 'PNP S8550', 'PNP S8550'),
	(38, 15, 'NPN KSP2222A', 'NPN KSP2222A'),
	(39, 15, 'MOSFET AO4407', 'MOSFET AO4407'),
	(40, 15, 'MOSFET IRLZ44N', 'MOSFET IRLZ44N'),
	(41, 15, 'MOSFET IRLB8721', 'MOSFET IRLB8721'),
	(42, 15, 'MOSFET FDP7030', 'MOSFET FDP7030'),
	(43, 15, 'JFET J201', 'JFET J201'),
	(44, 15, 'JFET BF245', 'JFET BF245'),
	(45, 15, 'IGBT IRG4BC30', 'IGBT IRG4BC30'),
	(46, 15, 'IGBT HGTG30N60A4', 'IGBT HGTG30N60A4'),
	(47, 15, 'RF Transistör BFR96', 'RF Transistor BFR96'),
	(48, 15, 'RF Transistör 2SC3355', 'RF Transistor 2SC3355');

-- tablo yapısı dökülüyor eticaret.urun_fiyatlar
CREATE TABLE IF NOT EXISTS `urun_fiyatlar` (
  `fiyat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bilesen_id` int(10) unsigned NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `para_birimi` varchar(10) DEFAULT 'TRY',
  PRIMARY KEY (`fiyat_id`),
  KEY `bilesen_id` (`bilesen_id`),
  CONSTRAINT `urun_fiyatlar_ibfk_1` FOREIGN KEY (`bilesen_id`) REFERENCES `urun_bilesenler` (`bilesen_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.urun_fiyatlar: ~9 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `urun_fiyatlar` (`fiyat_id`, `bilesen_id`, `fiyat`, `para_birimi`) VALUES
	(1, 1, 0.50, 'TRY'),
	(2, 2, 0.60, 'TRY'),
	(3, 3, 2.50, 'TRY'),
	(4, 4, 0.40, 'TRY'),
	(5, 5, 0.70, 'TRY'),
	(6, 6, 0.10, 'TRY'),
	(7, 7, 1.20, 'TRY'),
	(8, 8, 0.15, 'TRY'),
	(9, 9, 0.80, 'TRY');

-- tablo yapısı dökülüyor eticaret.urun_kategoriler
CREATE TABLE IF NOT EXISTS `urun_kategoriler` (
  `kategori_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `kategori_tr` varchar(255) NOT NULL,
  `kategori_en` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kategori_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_kategori_tr` (`kategori_tr`),
  CONSTRAINT `fk_kategori_parent` FOREIGN KEY (`parent_id`) REFERENCES `urun_kategoriler` (`kategori_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.urun_kategoriler: ~244 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `urun_kategoriler` (`kategori_id`, `parent_id`, `kategori_tr`, `kategori_en`) VALUES
	(1, NULL, 'Aktif Devre Elemanları', 'Active Components'),
	(2, NULL, 'Pasif Devre Elemanları', 'Passive Components'),
	(3, NULL, 'Mikrodenetleyiciler ve İşlemciler', 'Microcontrollers & Processors'),
	(4, NULL, 'Sensörler', 'Sensors'),
	(5, NULL, 'Konektörler ve Soketler', 'Connectors & Sockets'),
	(6, NULL, 'Devre Kartları ve PCB Malzemeleri', 'PCBs & Materials'),
	(7, NULL, 'Güç Kaynakları ve Bataryalar', 'Power Supplies & Batteries'),
	(8, NULL, 'Kablolar ve Aksesuarlar', 'Cables & Accessories'),
	(9, NULL, 'Test ve Ölçüm Cihazları', 'Test & Measurement Equipment'),
	(10, NULL, 'Modüller', 'Modules'),
	(11, NULL, 'Elektromekanik Parçalar', 'Electromechanical Parts'),
	(12, NULL, 'Optoelektronik', 'Optoelectronics'),
	(13, NULL, 'Soğutma ve Mekanik Parçalar', 'Cooling & Mechanical Parts'),
	(14, NULL, 'Yazılım ve Geliştirme Kitleri', 'Software & Development Kits'),
	(15, 1, 'Transistörler', 'Transistors'),
	(16, 1, 'Diyotlar', 'Diodes'),
	(17, 1, 'Entegre Devreler', 'Integrated Circuits'),
	(18, 1, 'Röleler', 'Relays'),
	(19, 1, 'Optokuplörler', 'Optocouplers'),
	(20, 1, 'IGBT’ler', 'IGBTs'),
	(21, 1, 'LED’ler', 'LEDs'),
	(22, 1, 'Lazer Diyotlar', 'Laser Diodes'),
	(23, 1, 'Fotodiyotlar', 'Photodiodes'),
	(24, 1, 'Sensör Entegreleri', 'Sensor ICs'),
	(25, 2, 'Dirençler', 'Resistors'),
	(26, 2, 'Kapasitörler', 'Capacitors'),
	(27, 2, 'Bobinler', 'Inductors'),
	(28, 2, 'Potansiyometreler', 'Potentiometers'),
	(29, 2, 'Termistörler', 'Thermistors'),
	(30, 2, 'Varistörler', 'Varistors'),
	(31, 2, 'Kristaller / Osilatörler', 'Crystals / Oscillators'),
	(32, 2, 'Ferrit Çekirdekler', 'Ferrite Cores'),
	(33, 2, 'Sigortalar', 'Fuses'),
	(34, 3, 'Mikrodenetleyiciler', 'Microcontrollers'),
	(35, 3, 'Mikroişlemciler', 'Microprocessors'),
	(36, 3, 'Geliştirme Kartları', 'Development Boards'),
	(37, 3, 'FPGA’ler', 'FPGAs'),
	(38, 3, 'DSP’ler', 'DSPs'),
	(39, 3, 'Programlama Adaptörleri', 'Programming Adapters'),
	(40, 4, 'Sıcaklık Sensörleri', 'Temperature Sensors'),
	(41, 4, 'Nem Sensörleri', 'Humidity Sensors'),
	(42, 4, 'Basınç Sensörleri', 'Pressure Sensors'),
	(43, 4, 'Gaz Sensörleri', 'Gas Sensors'),
	(44, 4, 'Hareket Sensörleri', 'Motion Sensors'),
	(45, 4, 'Işık Sensörleri', 'Light Sensors'),
	(46, 4, 'Manyetik Sensörler', 'Magnetic Sensors'),
	(47, 4, 'İvmeölçer / Gyro Sensörleri', 'Accelerometers / Gyros'),
	(48, 4, 'Mesafe Sensörleri', 'Distance Sensors'),
	(49, 5, 'Header Pinler', 'Header Pins'),
	(50, 5, 'Jumper Kablolar', 'Jumper Wires'),
	(51, 5, 'USB Konektörleri', 'USB Connectors'),
	(52, 5, 'RJ45 / Ethernet Soketleri', 'RJ45 / Ethernet Sockets'),
	(53, 5, 'Güç Konektörleri', 'Power Connectors'),
	(54, 5, 'Terminal Bloklar', 'Terminal Blocks'),
	(55, 5, 'IC Soketleri', 'IC Sockets'),
	(56, 5, 'BNC / SMA Konektörleri', 'BNC / SMA Connectors'),
	(57, 6, 'Hazır PCB Kartları', 'Ready PCBs'),
	(58, 6, 'Breadboardlar', 'Breadboards'),
	(59, 6, 'Proto Kartlar', 'Proto Boards'),
	(60, 6, 'PCB Üretim Malzemeleri', 'PCB Materials'),
	(61, 6, 'PCB Klemensleri', 'PCB Terminals'),
	(62, 6, 'PCB Konektörleri', 'PCB Connectors'),
	(63, 6, 'PCB Delme / Kesme Malzemeleri', 'PCB Tools'),
	(64, 7, 'Adaptörler', 'Adapters'),
	(65, 7, 'SMPS Güç Kaynakları', 'SMPS Power Supplies'),
	(66, 7, 'Bataryalar', 'Batteries'),
	(67, 7, 'Powerbank Modülleri', 'Powerbank Modules'),
	(68, 7, 'Şarj Devreleri', 'Charging Circuits'),
	(69, 7, 'Solar Paneller', 'Solar Panels'),
	(70, 7, 'DC-DC Dönüştürücüler', 'DC-DC Converters'),
	(71, 7, 'AC-DC Dönüştürücüler', 'AC-DC Converters'),
	(72, 8, 'Jumper Kablolar', 'Jumper Wires'),
	(73, 8, 'Koaksiyel Kablolar', 'Coaxial Cables'),
	(74, 8, 'USB Kabloları', 'USB Cables'),
	(75, 8, 'Ethernet Kabloları', 'Ethernet Cables'),
	(76, 8, 'Güç Kabloları', 'Power Cables'),
	(77, 8, 'Lehim Telleri', 'Solder Wires'),
	(78, 8, 'Isı Büzüşmeli Makaronlar', 'Heat Shrink Tubes'),
	(79, 8, 'Kablo Bağları', 'Cable Ties'),
	(80, 9, 'Multimetreler', 'Multimeters'),
	(81, 9, 'Osiloskoplar', 'Oscilloscopes'),
	(82, 9, 'Sinyal Jeneratörleri', 'Signal Generators'),
	(83, 9, 'Güç Ölçerler', 'Power Meters'),
	(84, 9, 'LCR Metreler', 'LCR Meters'),
	(85, 9, 'Logic Analyzer', 'Logic Analyzers'),
	(86, 9, 'Termal Kameralar', 'Thermal Cameras'),
	(87, 9, 'Test Prob ve Aksesuarları', 'Test Probes & Accessories'),
	(88, 10, 'Wi-Fi Modülleri', 'Wi-Fi Modules'),
	(89, 10, 'Bluetooth Modülleri', 'Bluetooth Modules'),
	(90, 10, 'GSM / 4G Modülleri', 'GSM / 4G Modules'),
	(91, 10, 'GPS Modülleri', 'GPS Modules'),
	(92, 10, 'RF Modülleri', 'RF Modules'),
	(93, 10, 'NFC Modülleri', 'NFC Modules'),
	(94, 10, 'Kamera Modülleri', 'Camera Modules'),
	(95, 10, 'Ses Modülleri', 'Audio Modules'),
	(96, 11, 'Röleler', 'Relays'),
	(97, 11, 'Switchler', 'Switches'),
	(98, 11, 'Butonlar', 'Buttons'),
	(99, 11, 'Motorlar', 'Motors'),
	(100, 11, 'Solenoidler', 'Solenoids'),
	(101, 11, 'Joystickler', 'Joysticks'),
	(102, 11, 'Klavye / Tuş Takımları', 'Keypads'),
	(103, 12, 'LED’ler', 'LEDs'),
	(104, 12, 'Yüksek Güç LED’ler', 'High Power LEDs'),
	(105, 12, 'RGB LED’ler', 'RGB LEDs'),
	(106, 12, 'Lazer Diyotlar', 'Laser Diodes'),
	(107, 12, 'Fotodiyotlar', 'Photodiodes'),
	(108, 12, 'Fototransistörler', 'Phototransistors'),
	(109, 12, 'Optokuplörler', 'Optocouplers'),
	(110, 12, 'IR LED ve Alıcılar', 'IR LEDs & Receivers'),
	(111, 13, 'Heatsink (Soğutucu)', 'Heatsinks'),
	(112, 13, 'Fanlar', 'Fans'),
	(113, 13, 'Termal Macunlar', 'Thermal Pastes'),
	(114, 13, 'Kasa ve Muhafazalar', 'Cases & Enclosures'),
	(115, 13, 'Montaj Vidaları', 'Mounting Screws'),
	(116, 13, 'PCB Tutucular', 'PCB Holders'),
	(117, 13, 'Radyatörler', 'Radiators'),
	(118, 14, 'Arduino Kitleri', 'Arduino Kits'),
	(119, 14, 'Raspberry Pi Kitleri', 'Raspberry Pi Kits'),
	(120, 14, 'STM32 Geliştirme Kitleri', 'STM32 Development Kits'),
	(121, 14, 'FPGA Geliştirme Kitleri', 'FPGA Development Kits'),
	(122, 14, 'Robotik Kitler', 'Robotics Kits'),
	(123, 14, 'Eğitim Setleri', 'Educational Sets'),
	(124, 14, 'Sensör Kitleri', 'Sensor Kits'),
	(125, 15, 'NPN Transistörler', 'NPN Transistors'),
	(126, 15, 'PNP Transistörler', 'PNP Transistors'),
	(127, 15, 'MOSFET Transistörler', 'MOSFET Transistors'),
	(128, 15, 'Darlington Transistörler', 'Darlington Transistors'),
	(129, 15, 'JFET Transistörler', 'JFET Transistors'),
	(130, 15, 'IGBT Transistörler', 'IGBT Transistors'),
	(131, 16, 'Doğrultucu Diyotlar', 'Rectifier Diodes'),
	(132, 16, 'Zener Diyotlar', 'Zener Diodes'),
	(133, 16, 'Schottky Diyotlar', 'Schottky Diodes'),
	(134, 16, 'LED Diyotlar', 'LED Diodes'),
	(135, 16, 'Fotodiyotlar', 'Photodiodes'),
	(136, 17, 'Op-Amp Entegreleri', 'Operational Amplifiers'),
	(137, 17, 'Timer Entegreleri', 'Timer ICs'),
	(138, 17, 'Regülatör Entegreleri', 'Voltage Regulators'),
	(139, 17, 'Mikrodenetleyici Entegreleri', 'Microcontroller ICs'),
	(140, 17, 'EEPROM / Hafıza Entegreleri', 'EEPROM / Memory ICs'),
	(141, 25, 'Sabit Dirençler', 'Fixed Resistors'),
	(142, 25, 'Değişken Dirençler', 'Variable Resistors'),
	(143, 25, 'Potansiyometreler', 'Potentiometers'),
	(144, 25, 'Termistörler', 'Thermistors'),
	(145, 25, 'Fotoresistörler', 'Photoresistors'),
	(146, 26, 'Seramik Kapasitörler', 'Ceramic Capacitors'),
	(147, 26, 'Elektrolitik Kapasitörler', 'Electrolytic Capacitors'),
	(148, 26, 'Tantal Kapasitörler', 'Tantalum Capacitors'),
	(149, 26, 'Film Kapasitörler', 'Film Capacitors'),
	(150, 26, 'Süper Kapasitörler', 'Super Capacitors'),
	(151, 27, 'Hava Çekirdekli Bobinler', 'Air Core Inductors'),
	(152, 27, 'Ferrit Çekirdekli Bobinler', 'Ferrite Core Inductors'),
	(153, 27, 'Toroid Bobinler', 'Toroidal Inductors'),
	(154, 27, 'SMD Bobinler', 'SMD Inductors'),
	(155, 34, 'PIC Mikrodenetleyiciler', 'PIC Microcontrollers'),
	(156, 34, 'AVR Mikrodenetleyiciler', 'AVR Microcontrollers'),
	(157, 34, 'ARM Mikrodenetleyiciler', 'ARM Microcontrollers'),
	(158, 34, 'STM32 Mikrodenetleyiciler', 'STM32 Microcontrollers'),
	(159, 40, 'Analog Sıcaklık Sensörleri', 'Analog Temperature Sensors'),
	(160, 40, 'Dijital Sıcaklık Sensörleri', 'Digital Temperature Sensors'),
	(161, 48, 'IR Mesafe Sensörleri', 'IR Distance Sensors'),
	(162, 48, 'Ultrasonik Mesafe Sensörleri', 'Ultrasonic Distance Sensors'),
	(163, 46, 'Hall Effect Sensörleri', 'Hall Effect Sensors'),
	(164, 51, 'USB Type-A', 'USB Type-A'),
	(165, 51, 'USB Type-C', 'USB Type-C'),
	(166, 52, 'RJ45 Cat5', 'RJ45 Cat5'),
	(167, 52, 'RJ45 Cat6', 'RJ45 Cat6'),
	(168, 56, 'SMA Konnektörler', 'SMA Connectors'),
	(169, 57, 'Tek Katmanlı PCB', 'Single Layer PCB'),
	(170, 57, 'Çift Katmanlı PCB', 'Double Layer PCB'),
	(171, 57, 'Çok Katmanlı PCB', 'Multilayer PCB'),
	(172, 60, 'FR4 PCB Malzemeleri', 'FR4 PCB Materials'),
	(173, 60, 'Alüminyum PCB Malzemeleri', 'Aluminum PCB Materials'),
	(174, 66, 'Li-ion Bataryalar', 'Li-ion Batteries'),
	(175, 66, 'NiMH Bataryalar', 'NiMH Batteries'),
	(176, 66, 'Alkalin Bataryalar', 'Alkaline Batteries'),
	(177, 70, 'Step-Down DC-DC', 'Step-Down DC-DC'),
	(178, 70, 'Step-Up DC-DC', 'Step-Up DC-DC'),
	(179, 74, 'USB 2.0 Kabloları', 'USB 2.0 Cables'),
	(180, 74, 'USB 3.0 Kabloları', 'USB 3.0 Cables'),
	(181, 75, 'Cat5 Ethernet Kabloları', 'Cat5 Ethernet Cables'),
	(182, 75, 'Cat6 Ethernet Kabloları', 'Cat6 Ethernet Cables'),
	(183, 73, 'Koaksiyel RG58', 'Coaxial RG58'),
	(184, 80, 'Dijital Multimetreler', 'Digital Multimeters'),
	(185, 80, 'Analog Multimetreler', 'Analog Multimeters'),
	(186, 81, 'Masaüstü Osiloskoplar', 'Desktop Oscilloscopes'),
	(187, 81, 'Taşınabilir Osiloskoplar', 'Portable Oscilloscopes'),
	(188, 82, 'Fonksiyon Jeneratörleri', 'Function Generators'),
	(189, 88, 'ESP8266 Modülleri', 'ESP8266 Modules'),
	(190, 88, 'ESP32 Modülleri', 'ESP32 Modules'),
	(191, 89, 'HC-05 Bluetooth', 'HC-05 Bluetooth'),
	(192, 89, 'HC-06 Bluetooth', 'HC-06 Bluetooth'),
	(193, 90, 'SIM800 GSM Modülleri', 'SIM800 GSM Modules'),
	(194, 90, 'SIM900 GSM Modülleri', 'SIM900 GSM Modules'),
	(195, 18, 'Mini Röleler', 'Mini Relays'),
	(196, 18, 'Güç Röleleri', 'Power Relays'),
	(197, 18, 'Endüstriyel Röleler', 'Industrial Relays'),
	(198, 97, 'Toggle Switchler', 'Toggle Switches'),
	(199, 97, 'Push Button Switchler', 'Push Button Switches'),
	(200, 98, 'Tact Switchler', 'Tact Switches'),
	(201, 98, 'Mini Butonlar', 'Mini Buttons'),
	(202, 99, 'DC Motorlar', 'DC Motors'),
	(203, 99, 'Step Motorlar', 'Stepper Motors'),
	(204, 99, 'Servo Motorlar', 'Servo Motors'),
	(205, 101, 'Joystick Modülleri', 'Joystick Modules'),
	(206, 102, 'Membran Klavyeler', 'Membrane Keypads'),
	(207, 21, '5mm LED’ler', '5mm LEDs'),
	(208, 21, 'SMD LED’ler', 'SMD LEDs'),
	(209, 104, 'High Power LED’ler', 'High Power LEDs'),
	(210, 105, 'RGB SMD LED’ler', 'RGB SMD LEDs'),
	(211, 110, 'IR LED Vericiler', 'IR LED Emitters'),
	(212, 110, 'IR LED Alıcılar', 'IR LED Receivers'),
	(213, 21, 'UV LED’ler', 'UV LEDs'),
	(214, 23, 'IR Fotodiyotlar', 'IR Photodiodes'),
	(215, 108, 'IR Fototransistörler', 'IR Phototransistors'),
	(216, 19, 'Yüksek Hızlı Optokuplörler', 'High Speed Optocouplers'),
	(217, 111, 'Alüminyum Heatsinkler', 'Aluminum Heatsinks'),
	(218, 111, 'Bakır Heatsinkler', 'Copper Heatsinks'),
	(219, 111, 'CPU Heatsinkler', 'CPU Heatsinks'),
	(220, 111, 'GPU Heatsinkler', 'GPU Heatsinks'),
	(221, 112, '12V Fanlar', '12V Fans'),
	(222, 112, 'USB Fanlar', 'USB Fans'),
	(223, 112, 'Mini Fanlar', 'Mini Fans'),
	(224, 112, 'Endüstriyel Fanlar', 'Industrial Fans'),
	(225, 114, 'Masaüstü Kasa', 'Desktop Cases'),
	(226, 114, 'Endüstriyel Kasa', 'Industrial Enclosures'),
	(227, 114, 'Taşınabilir Kasa', 'Portable Cases'),
	(228, 114, 'Rackmount Kasa', 'Rackmount Cases'),
	(229, 118, 'Arduino Starter Kit', 'Arduino Starter Kit'),
	(230, 118, 'Arduino Advanced Kit', 'Arduino Advanced Kit'),
	(231, 118, 'Arduino Sensör Kitleri', 'Arduino Sensor Kits'),
	(232, 118, 'Arduino Robotik Kitleri', 'Arduino Robotics Kits'),
	(233, 119, 'Raspberry Pi 4 Kit', 'Raspberry Pi 4 Kit'),
	(234, 119, 'Raspberry Pi Pico Kit', 'Raspberry Pi Pico Kit'),
	(235, 119, 'Raspberry Pi Kamera Kitleri', 'Raspberry Pi Camera Kits'),
	(236, 119, 'Raspberry Pi IoT Kitleri', 'Raspberry Pi IoT Kits'),
	(237, 120, 'STM32F4 Discovery Kit', 'STM32F4 Discovery Kit'),
	(238, 120, 'STM32 Sensör Kitleri', 'STM32 Sensor Kits'),
	(239, 121, 'FPGA Spartan Kit', 'FPGA Spartan Kit'),
	(240, 121, 'FPGA Eğitim Kitleri', 'FPGA Education Kits'),
	(241, 122, 'Robotik Eğitim Kitleri', 'Robotics Education Kits'),
	(242, 122, 'Robotik Mekanik Kitler', 'Robotics Mechanical Kits'),
	(243, 124, 'Sensör Eğitim Kitleri', 'Sensor Education Kits'),
	(244, 124, 'Sensör Deney Setleri', 'Sensor Experiment Kits');

-- tablo yapısı dökülüyor eticaret.urun_markalar
CREATE TABLE IF NOT EXISTS `urun_markalar` (
  `marka_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `marka_ad` varchar(255) NOT NULL,
  PRIMARY KEY (`marka_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.urun_markalar: ~7 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `urun_markalar` (`marka_id`, `marka_ad`) VALUES
	(1, 'ON Semiconductor'),
	(2, 'STMicroelectronics'),
	(3, 'Texas Instruments'),
	(4, 'Infineon'),
	(5, 'NXP'),
	(6, 'Toshiba'),
	(7, 'Analog Devices');

-- tablo yapısı dökülüyor eticaret.urun_stok
CREATE TABLE IF NOT EXISTS `urun_stok` (
  `stok_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bilesen_id` int(10) unsigned NOT NULL,
  `stok_miktar` int(10) unsigned NOT NULL,
  `depo` varchar(255) DEFAULT 'Merkez',
  PRIMARY KEY (`stok_id`),
  KEY `bilesen_id` (`bilesen_id`),
  CONSTRAINT `urun_stok_ibfk_1` FOREIGN KEY (`bilesen_id`) REFERENCES `urun_bilesenler` (`bilesen_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- eticaret.urun_stok: ~9 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT INTO `urun_stok` (`stok_id`, `bilesen_id`, `stok_miktar`, `depo`) VALUES
	(1, 1, 500, 'Merkez'),
	(2, 2, 400, 'Merkez'),
	(3, 3, 200, 'Merkez'),
	(4, 4, 600, 'Merkez'),
	(5, 5, 350, 'Merkez'),
	(6, 6, 1000, 'Merkez'),
	(7, 7, 150, 'Merkez'),
	(8, 8, 800, 'Merkez'),
	(9, 9, 250, 'Merkez');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
