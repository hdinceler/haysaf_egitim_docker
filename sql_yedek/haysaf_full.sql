-- --------------------------------------------------------
-- Veritabanı: haysaf
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `haysaf` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci */;
USE `haysaf`;

-- --------------------------------------------------------
-- Kategoriler (hiyerarşik, sınırsız alt kategori desteği)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `urun_kategoriler`;
CREATE TABLE `urun_kategoriler` (
  `kategori_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` INT UNSIGNED NULL,
  `kategori_tr` VARCHAR(255) NOT NULL,
  `kategori_en` VARCHAR(255),
  PRIMARY KEY (`kategori_id`),
  KEY `idx_parent` (`parent_id`),
  CONSTRAINT `fk_kategori_parent`
    FOREIGN KEY (`parent_id`) REFERENCES `urun_kategoriler` (`kategori_id`)
    ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Bileşenler
-- --------------------------------------------------------
DROP TABLE IF EXISTS `urun_bilesenler`;
CREATE TABLE `urun_bilesenler` (
  `bilesen_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategori_id` INT UNSIGNED NOT NULL,
  `bilesen_tr` VARCHAR(255) NOT NULL,
  `bilesen_en` VARCHAR(255),
  PRIMARY KEY (`bilesen_id`),
  KEY `idx_kategori` (`kategori_id`),
  CONSTRAINT `fk_bilesen_kategori`
    FOREIGN KEY (`kategori_id`) REFERENCES `urun_kategoriler` (`kategori_id`)
    ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Marka Tablosu
-- --------------------------------------------------------
DROP TABLE IF EXISTS `urun_markalar`;
CREATE TABLE `urun_markalar` (
  `marka_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `marka_ad` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`marka_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Fiyat Tablosu
-- --------------------------------------------------------
DROP TABLE IF EXISTS `urun_fiyatlar`;
CREATE TABLE `urun_fiyatlar` (
  `fiyat_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bilesen_id` INT UNSIGNED NOT NULL,
  `fiyat` DECIMAL(10,2) NOT NULL,
  `para_birimi` VARCHAR(10) DEFAULT 'TRY',
  PRIMARY KEY (`fiyat_id`),
  FOREIGN KEY (`bilesen_id`) REFERENCES `urun_bilesenler` (`bilesen_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Stok Tablosu
-- --------------------------------------------------------
DROP TABLE IF EXISTS `urun_stok`;
CREATE TABLE `urun_stok` (
  `stok_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bilesen_id` INT UNSIGNED NOT NULL,
  `stok_miktar` INT UNSIGNED NOT NULL,
  `depo` VARCHAR(255) DEFAULT 'Merkez',
  PRIMARY KEY (`stok_id`),
  FOREIGN KEY (`bilesen_id`) REFERENCES `urun_bilesenler` (`bilesen_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Örnek Veri: Markalar
-- --------------------------------------------------------
INSERT INTO `urun_markalar` (`marka_ad`) VALUES
('ON Semiconductor'),
('STMicroelectronics'),
('Texas Instruments'),
('Infineon'),
('NXP'),
('Toshiba'),
('Analog Devices');

-- --------------------------------------------------------
-- Örnek Veri: Kategoriler
-- --------------------------------------------------------
INSERT INTO `urun_kategoriler` (`kategori_tr`, `kategori_en`, `parent_id`) VALUES
('Aktif Devre Elemanları', 'Active Components', NULL),
('Pasif Devre Elemanları', 'Passive Components', NULL);

-- Alt kategoriler
INSERT INTO `urun_kategoriler` (`kategori_tr`, `kategori_en`, `parent_id`) VALUES
('Transistörler', 'Transistors', 1),
('Diyotlar', 'Diodes', 1),
('Dirençler', 'Resistors', 2),
('Kapasitörler', 'Capacitors', 2);

-- Alt-alt kategoriler
INSERT INTO `urun_kategoriler` (`kategori_tr`, `kategori_en`, `parent_id`) VALUES
('NPN Transistörler', 'NPN Transistors', 3),
('PNP Transistörler', 'PNP Transistors', 3),
('MOSFET Transistörler', 'MOSFET Transistors', 3),
('Zener Diyotlar', 'Zener Diodes', 4),
('Schottky Diyotlar', 'Schottky Diodes', 4),
('Sabit Dirençler', 'Fixed Resistors', 5),
('Değişken Dirençler', 'Variable Resistors', 5),
('Seramik Kapasitörler', 'Ceramic Capacitors', 6),
('Elektrolitik Kapasitörler', 'Electrolytic Capacitors', 6);

-- --------------------------------------------------------
-- Örnek Veri: Bileşenler
-- --------------------------------------------------------
INSERT INTO `urun_bilesenler` (`kategori_id`, `bilesen_tr`, `bilesen_en`) VALUES
(7, 'BC547 NPN Transistör', 'BC547 NPN Transistor'),
(8, 'BC557 PNP Transistör', 'BC557 PNP Transistor'),
(9, 'IRF540 MOSFET', 'IRF540 MOSFET'),
(10, '1N4733 Zener Diyot 5.1V', '1N4733 Zener Diode 5.1V'),
(11, '1N5819 Schottky Diyot', '1N5819 Schottky Diode'),
(12, '1kΩ Sabit Direnç', '1kΩ Fixed Resistor'),
(13, '10kΩ Potansiyometre', '10kΩ Variable Resistor'),
(14, '100nF Seramik Kapasitör', '100nF Ceramic Capacitor'),
(15, '100µF 25V Elektrolitik Kapasitör', '100µF 25V Electrolytic Capacitor');

-- --------------------------------------------------------
-- Örnek Veri: Fiyatlar
-- --------------------------------------------------------
INSERT INTO `urun_fiyatlar` (`bilesen_id`, `fiyat`, `para_birimi`) VALUES
(1, 0.50, 'TRY'),
(2, 0.60, 'TRY'),
(3, 2.50, 'TRY'),
(4, 0.40, 'TRY'),
(5, 0.70, 'TRY'),
(6, 0.10, 'TRY'),
(7, 1.20, 'TRY'),
(8, 0.15, 'TRY'),
(9, 0.80, 'TRY');

-- --------------------------------------------------------
-- Örnek Veri: Stok
-- --------------------------------------------------------
INSERT INTO `urun_stok` (`bilesen_id`, `stok_miktar`, `depo`) VALUES
(1, 500, 'Merkez'),
(2, 400, 'Merkez'),
(3, 200, 'Merkez'),
(4, 600, 'Merkez'),
(5, 350, 'Merkez'),
(6, 1000, 'Merkez'),
(7, 150, 'Merkez'),
(8, 800, 'Merkez'),
(9, 250, 'Merkez');
