-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- MariaDB Sürümü:               10.4+
-- Dosya:                        haysaf_katalog_full.sql
-- Amaç:                         Hiyerarşik kategori ve bileşen yapısı
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
SET NAMES utf8mb4;
SET TIME_ZONE='+00:00';
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

-- --------------------------------------------------------
-- Veritabanı
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `haysaf` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci */;
USE `haysaf`;

-- --------------------------------------------------------
-- Tablolar
-- --------------------------------------------------------

-- Hiyerarşik kategori tablosu: sınırsız alt kategori desteği
DROP TABLE IF EXISTS `urun_kategoriler`;
CREATE TABLE `urun_kategoriler` (
  `kategori_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` INT UNSIGNED NULL,
  `kategori_tr` VARCHAR(255) NOT NULL,
  `kategori_en` VARCHAR(255) NULL,
  PRIMARY KEY (`kategori_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_kategori_tr` (`kategori_tr`),
  CONSTRAINT `fk_kategori_parent`
    FOREIGN KEY (`parent_id`) REFERENCES `urun_kategoriler` (`kategori_id`)
    ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Bileşenler tablosu: her bileşen bir kategoriye bağlanır (hangi seviyeden olursa olsun)
DROP TABLE IF EXISTS `urun_bilesenler`;
CREATE TABLE `urun_bilesenler` (
  `bilesen_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategori_id` INT UNSIGNED NOT NULL,
  `bilesen_tr` VARCHAR(255) NOT NULL,
  `bilesen_en` VARCHAR(255) NULL,
  PRIMARY KEY (`bilesen_id`),
  KEY `idx_kategori` (`kategori_id`),
  KEY `idx_bilesen_tr` (`bilesen_tr`),
  CONSTRAINT `fk_bilesen_kategori`
    FOREIGN KEY (`kategori_id`) REFERENCES `urun_kategoriler` (`kategori_id`)
    ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- Veri: Ana kategoriler (parent_id = NULL)
-- --------------------------------------------------------
INSERT INTO `urun_kategoriler` (`kategori_tr`, `kategori_en`, `parent_id`) VALUES
('Aktif Devre Elemanları', 'Active Components', NULL),
('Pasif Devre Elemanları', 'Passive Components', NULL),
('Mikrodenetleyiciler ve İşlemciler', 'Microcontrollers & Processors', NULL),
('Sensörler', 'Sensors', NULL),
('Konektörler ve Soketler', 'Connectors & Sockets', NULL),
('Devre Kartları ve PCB Malzemeleri', 'PCBs & Materials', NULL),
('Güç Kaynakları ve Bataryalar', 'Power Supplies & Batteries', NULL),
('Kablolar ve Aksesuarlar', 'Cables & Accessories', NULL),
('Test ve Ölçüm Cihazları', 'Test & Measurement Equipment', NULL),
('Modüller', 'Modules', NULL),
('Elektromekanik Parçalar', 'Electromechanical Parts', NULL),
('Optoelektronik', 'Optoelectronics', NULL),
('Soğutma ve Mekanik Parçalar', 'Cooling & Mechanical Parts', NULL),
('Yazılım ve Geliştirme Kitleri', 'Software & Development Kits', NULL);

-- Ana kategori id değişkenleri
SET @cat_aktif   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Aktif Devre Elemanları' LIMIT 1);
SET @cat_pasif   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Pasif Devre Elemanları' LIMIT 1);
SET @cat_mcu     := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Mikrodenetleyiciler ve İşlemciler' LIMIT 1);
SET @cat_sensor  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Sensörler' LIMIT 1);
SET @cat_conn    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Konektörler ve Soketler' LIMIT 1);
SET @cat_pcb     := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Devre Kartları ve PCB Malzemeleri' LIMIT 1);
SET @cat_power   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Güç Kaynakları ve Bataryalar' LIMIT 1);
SET @cat_cable   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Kablolar ve Aksesuarlar' LIMIT 1);
SET @cat_test    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Test ve Ölçüm Cihazları' LIMIT 1);
SET @cat_module  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Modüller' LIMIT 1);
SET @cat_electro := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Elektromekanik Parçalar' LIMIT 1);
SET @cat_opto    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Optoelektronik' LIMIT 1);
SET @cat_cool    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Soğutma ve Mekanik Parçalar' LIMIT 1);
SET @cat_devkit  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Yazılım ve Geliştirme Kitleri' LIMIT 1);

-- --------------------------------------------------------
-- Veri: 2. Seviye alt kategoriler
-- --------------------------------------------------------

-- Aktif Devre Elemanları
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Transistörler', 'Transistors', @cat_aktif),
('Diyotlar', 'Diodes', @cat_aktif),
('Entegre Devreler', 'Integrated Circuits', @cat_aktif),
('Röleler', 'Relays', @cat_aktif),
('Optokuplörler', 'Optocouplers', @cat_aktif),
('IGBT’ler', 'IGBTs', @cat_aktif),
('LED’ler', 'LEDs', @cat_aktif),
('Lazer Diyotlar', 'Laser Diodes', @cat_aktif),
('Fotodiyotlar', 'Photodiodes', @cat_aktif),
('Sensör Entegreleri', 'Sensor ICs', @cat_aktif);

-- Pasif Devre Elemanları
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Dirençler', 'Resistors', @cat_pasif),
('Kapasitörler', 'Capacitors', @cat_pasif),
('Bobinler', 'Inductors', @cat_pasif),
('Potansiyometreler', 'Potentiometers', @cat_pasif),
('Termistörler', 'Thermistors', @cat_pasif),
('Varistörler', 'Varistors', @cat_pasif),
('Kristaller / Osilatörler', 'Crystals / Oscillators', @cat_pasif),
('Ferrit Çekirdekler', 'Ferrite Cores', @cat_pasif),
('Sigortalar', 'Fuses', @cat_pasif);

-- Mikrodenetleyiciler ve İşlemciler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Mikrodenetleyiciler', 'Microcontrollers', @cat_mcu),
('Mikroişlemciler', 'Microprocessors', @cat_mcu),
('Geliştirme Kartları', 'Development Boards', @cat_mcu),
('FPGA’ler', 'FPGAs', @cat_mcu),
('DSP’ler', 'DSPs', @cat_mcu),
('Programlama Adaptörleri', 'Programming Adapters', @cat_mcu);

-- Sensörler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Sıcaklık Sensörleri', 'Temperature Sensors', @cat_sensor),
('Nem Sensörleri', 'Humidity Sensors', @cat_sensor),
('Basınç Sensörleri', 'Pressure Sensors', @cat_sensor),
('Gaz Sensörleri', 'Gas Sensors', @cat_sensor),
('Hareket Sensörleri', 'Motion Sensors', @cat_sensor),
('Işık Sensörleri', 'Light Sensors', @cat_sensor),
('Manyetik Sensörler', 'Magnetic Sensors', @cat_sensor),
('İvmeölçer / Gyro Sensörleri', 'Accelerometers / Gyros', @cat_sensor),
('Mesafe Sensörleri', 'Distance Sensors', @cat_sensor);

-- Konektörler ve Soketler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Header Pinler', 'Header Pins', @cat_conn),
('Jumper Kablolar', 'Jumper Wires', @cat_conn),
('USB Konektörleri', 'USB Connectors', @cat_conn),
('RJ45 / Ethernet Soketleri', 'RJ45 / Ethernet Sockets', @cat_conn),
('Güç Konektörleri', 'Power Connectors', @cat_conn),
('Terminal Bloklar', 'Terminal Blocks', @cat_conn),
('IC Soketleri', 'IC Sockets', @cat_conn),
('BNC / SMA Konektörleri', 'BNC / SMA Connectors', @cat_conn);

-- Devre Kartları ve PCB Malzemeleri
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Hazır PCB Kartları', 'Ready PCBs', @cat_pcb),
('Breadboardlar', 'Breadboards', @cat_pcb),
('Proto Kartlar', 'Proto Boards', @cat_pcb),
('PCB Üretim Malzemeleri', 'PCB Materials', @cat_pcb),
('PCB Klemensleri', 'PCB Terminals', @cat_pcb),
('PCB Konektörleri', 'PCB Connectors', @cat_pcb),
('PCB Delme / Kesme Malzemeleri', 'PCB Tools', @cat_pcb);

-- Güç Kaynakları ve Bataryalar
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Adaptörler', 'Adapters', @cat_power),
('SMPS Güç Kaynakları', 'SMPS Power Supplies', @cat_power),
('Bataryalar', 'Batteries', @cat_power),
('Powerbank Modülleri', 'Powerbank Modules', @cat_power),
('Şarj Devreleri', 'Charging Circuits', @cat_power),
('Solar Paneller', 'Solar Panels', @cat_power),
('DC-DC Dönüştürücüler', 'DC-DC Converters', @cat_power),
('AC-DC Dönüştürücüler', 'AC-DC Converters', @cat_power);

-- Kablolar ve Aksesuarlar
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Jumper Kablolar', 'Jumper Wires', @cat_cable),
('Koaksiyel Kablolar', 'Coaxial Cables', @cat_cable),
('USB Kabloları', 'USB Cables', @cat_cable),
('Ethernet Kabloları', 'Ethernet Cables', @cat_cable),
('Güç Kabloları', 'Power Cables', @cat_cable),
('Lehim Telleri', 'Solder Wires', @cat_cable),
('Isı Büzüşmeli Makaronlar', 'Heat Shrink Tubes', @cat_cable),
('Kablo Bağları', 'Cable Ties', @cat_cable);

-- Test ve Ölçüm Cihazları
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Multimetreler', 'Multimeters', @cat_test),
('Osiloskoplar', 'Oscilloscopes', @cat_test),
('Sinyal Jeneratörleri', 'Signal Generators', @cat_test),
('Güç Ölçerler', 'Power Meters', @cat_test),
('LCR Metreler', 'LCR Meters', @cat_test),
('Logic Analyzer', 'Logic Analyzers', @cat_test),
('Termal Kameralar', 'Thermal Cameras', @cat_test),
('Test Prob ve Aksesuarları', 'Test Probes & Accessories', @cat_test);

-- Modüller
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Wi-Fi Modülleri', 'Wi-Fi Modules', @cat_module),
('Bluetooth Modülleri', 'Bluetooth Modules', @cat_module),
('GSM / 4G Modülleri', 'GSM / 4G Modules', @cat_module),
('GPS Modülleri', 'GPS Modules', @cat_module),
('RF Modülleri', 'RF Modules', @cat_module),
('NFC Modülleri', 'NFC Modules', @cat_module),
('Kamera Modülleri', 'Camera Modules', @cat_module),
('Ses Modülleri', 'Audio Modules', @cat_module);

-- Elektromekanik Parçalar
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Röleler', 'Relays', @cat_electro),
('Switchler', 'Switches', @cat_electro),
('Butonlar', 'Buttons', @cat_electro),
('Motorlar', 'Motors', @cat_electro),
('Solenoidler', 'Solenoids', @cat_electro),
('Joystickler', 'Joysticks', @cat_electro),
('Klavye / Tuş Takımları', 'Keypads', @cat_electro);

-- Optoelektronik
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('LED’ler', 'LEDs', @cat_opto),
('Yüksek Güç LED’ler', 'High Power LEDs', @cat_opto),
('RGB LED’ler', 'RGB LEDs', @cat_opto),
('Lazer Diyotlar', 'Laser Diodes', @cat_opto),
('Fotodiyotlar', 'Photodiodes', @cat_opto),
('Fototransistörler', 'Phototransistors', @cat_opto),
('Optokuplörler', 'Optocouplers', @cat_opto),
('IR LED ve Alıcılar', 'IR LEDs & Receivers', @cat_opto);

-- Soğutma ve Mekanik Parçalar
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Heatsink (Soğutucu)', 'Heatsinks', @cat_cool),
('Fanlar', 'Fans', @cat_cool),
('Termal Macunlar', 'Thermal Pastes', @cat_cool),
('Kasa ve Muhafazalar', 'Cases & Enclosures', @cat_cool),
('Montaj Vidaları', 'Mounting Screws', @cat_cool),
('PCB Tutucular', 'PCB Holders', @cat_cool),
('Radyatörler', 'Radiators', @cat_cool);

-- Yazılım ve Geliştirme Kitleri
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Arduino Kitleri', 'Arduino Kits', @cat_devkit),
('Raspberry Pi Kitleri', 'Raspberry Pi Kits', @cat_devkit),
('STM32 Geliştirme Kitleri', 'STM32 Development Kits', @cat_devkit),
('FPGA Geliştirme Kitleri', 'FPGA Development Kits', @cat_devkit),
('Robotik Kitler', 'Robotics Kits', @cat_devkit),
('Eğitim Setleri', 'Educational Sets', @cat_devkit),
('Sensör Kitleri', 'Sensor Kits', @cat_devkit);

-- --------------------------------------------------------
-- 2. seviye id değişkenleri (örneklerden büyük bir kısmı)
-- --------------------------------------------------------
SET @sub_trans := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Transistörler' LIMIT 1);
SET @sub_diode := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Diyotlar' LIMIT 1);
SET @sub_ic    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Entegre Devreler' LIMIT 1);

SET @sub_res   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Dirençler' LIMIT 1);
SET @sub_cap   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Kapasitörler' LIMIT 1);
SET @sub_ind   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Bobinler' LIMIT 1);

SET @sub_mcu   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Mikrodenetleyiciler' LIMIT 1);
SET @sub_dist  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Mesafe Sensörleri' LIMIT 1);
SET @sub_temp  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Sıcaklık Sensörleri' LIMIT 1);
SET @sub_mag   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Manyetik Sensörler' LIMIT 1);

SET @sub_usb   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='USB Konektörleri' LIMIT 1);
SET @sub_rj45  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='RJ45 / Ethernet Soketleri' LIMIT 1);
SET @sub_bnc   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='BNC / SMA Konektörleri' LIMIT 1);

SET @sub_pcb_ready := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Hazır PCB Kartları' LIMIT 1);
SET @sub_pcb_mat   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='PCB Üretim Malzemeleri' LIMIT 1);

SET @sub_batt  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Bataryalar' LIMIT 1);
SET @sub_dcdc  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='DC-DC Dönüştürücüler' LIMIT 1);

SET @sub_usb_cables := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='USB Kabloları' LIMIT 1);
SET @sub_eth_cables := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Ethernet Kabloları' LIMIT 1);
SET @sub_coax       := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Koaksiyel Kablolar' LIMIT 1);

SET @sub_multimeter := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Multimetreler' LIMIT 1);
SET @sub_scope      := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Osiloskoplar' LIMIT 1);
SET @sub_siggen     := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Sinyal Jeneratörleri' LIMIT 1);

SET @sub_wifi   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Wi-Fi Modülleri' LIMIT 1);
SET @sub_bt     := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Bluetooth Modülleri' LIMIT 1);
SET @sub_gsm    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='GSM / 4G Modülleri' LIMIT 1);

SET @sub_relays := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Röleler' LIMIT 1);
SET @sub_switch := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Switchler' LIMIT 1);
SET @sub_motor  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Motorlar' LIMIT 1);
SET @sub_buttons:= (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Butonlar' LIMIT 1);
SET @sub_joystick:= (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Joystickler' LIMIT 1);
SET @sub_keypads := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Klavye / Tuş Takımları' LIMIT 1);

SET @sub_leds   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='LED’ler' LIMIT 1);
SET @sub_ledhp  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Yüksek Güç LED’ler' LIMIT 1);
SET @sub_ledrgb := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='RGB LED’ler' LIMIT 1);
SET @sub_irleds := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='IR LED ve Alıcılar' LIMIT 1);
SET @sub_photodiode := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Fotodiyotlar' LIMIT 1);
SET @sub_phototrans := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Fototransistörler' LIMIT 1);
SET @sub_opto   := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Optokuplörler' LIMIT 1);

SET @sub_heatsink := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Heatsink (Soğutucu)' LIMIT 1);
SET @sub_fans     := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Fanlar' LIMIT 1);
SET @sub_cases    := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Kasa ve Muhafazalar' LIMIT 1);

SET @sub_arduino  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Arduino Kitleri' LIMIT 1);
SET @sub_rpi      := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Raspberry Pi Kitleri' LIMIT 1);
SET @sub_stm32dk  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='STM32 Geliştirme Kitleri' LIMIT 1);
SET @sub_fpga_dk  := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='FPGA Geliştirme Kitleri' LIMIT 1);
SET @sub_robotics := (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Robotik Kitler' LIMIT 1);
SET @sub_sensorkit:= (SELECT kategori_id FROM urun_kategoriler WHERE kategori_tr='Sensör Kitleri' LIMIT 1);

-- --------------------------------------------------------
-- Veri: 3. Seviye alt kategoriler
-- --------------------------------------------------------

-- Aktif → Transistörler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('NPN Transistörler', 'NPN Transistors', @sub_trans),
('PNP Transistörler', 'PNP Transistors', @sub_trans),
('MOSFET Transistörler', 'MOSFET Transistors', @sub_trans),
('Darlington Transistörler', 'Darlington Transistors', @sub_trans),
('JFET Transistörler', 'JFET Transistors', @sub_trans),
('IGBT Transistörler', 'IGBT Transistors', @sub_trans);

-- Aktif → Diyotlar
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Doğrultucu Diyotlar', 'Rectifier Diodes', @sub_diode),
('Zener Diyotlar', 'Zener Diodes', @sub_diode),
('Schottky Diyotlar', 'Schottky Diodes', @sub_diode),
('LED Diyotlar', 'LED Diodes', @sub_diode),
('Fotodiyotlar', 'Photodiodes', @sub_diode);

-- Aktif → Entegre Devreler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Op-Amp Entegreleri', 'Operational Amplifiers', @sub_ic),
('Timer Entegreleri', 'Timer ICs', @sub_ic),
('Regülatör Entegreleri', 'Voltage Regulators', @sub_ic),
('Mikrodenetleyici Entegreleri', 'Microcontroller ICs', @sub_ic),
('EEPROM / Hafıza Entegreleri', 'EEPROM / Memory ICs', @sub_ic);

-- Pasif → Dirençler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Sabit Dirençler', 'Fixed Resistors', @sub_res),
('Değişken Dirençler', 'Variable Resistors', @sub_res),
('Potansiyometreler', 'Potentiometers', @sub_res),
('Termistörler', 'Thermistors', @sub_res),
('Fotoresistörler', 'Photoresistors', @sub_res);

-- Pasif → Kapasitörler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Seramik Kapasitörler', 'Ceramic Capacitors', @sub_cap),
('Elektrolitik Kapasitörler', 'Electrolytic Capacitors', @sub_cap),
('Tantal Kapasitörler', 'Tantalum Capacitors', @sub_cap),
('Film Kapasitörler', 'Film Capacitors', @sub_cap),
('Süper Kapasitörler', 'Super Capacitors', @sub_cap);

-- Pasif → Bobinler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Hava Çekirdekli Bobinler', 'Air Core Inductors', @sub_ind),
('Ferrit Çekirdekli Bobinler', 'Ferrite Core Inductors', @sub_ind),
('Toroid Bobinler', 'Toroidal Inductors', @sub_ind),
('SMD Bobinler', 'SMD Inductors', @sub_ind);

-- Mikrodenetleyiciler → Mikrodenetleyiciler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('PIC Mikrodenetleyiciler', 'PIC Microcontrollers', @sub_mcu),
('AVR Mikrodenetleyiciler', 'AVR Microcontrollers', @sub_mcu),
('ARM Mikrodenetleyiciler', 'ARM Microcontrollers', @sub_mcu),
('STM32 Mikrodenetleyiciler', 'STM32 Microcontrollers', @sub_mcu);

-- Sensörler → Sıcaklık / Mesafe / Manyetik
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Analog Sıcaklık Sensörleri', 'Analog Temperature Sensors', @sub_temp),
('Dijital Sıcaklık Sensörleri', 'Digital Temperature Sensors', @sub_temp),
('IR Mesafe Sensörleri', 'IR Distance Sensors', @sub_dist),
('Ultrasonik Mesafe Sensörleri', 'Ultrasonic Distance Sensors', @sub_dist),
('Hall Effect Sensörleri', 'Hall Effect Sensors', @sub_mag);

-- Konektörler → USB / RJ45 / BNC-SMA
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('USB Type-A', 'USB Type-A', @sub_usb),
('USB Type-C', 'USB Type-C', @sub_usb),
('RJ45 Cat5', 'RJ45 Cat5', @sub_rj45),
('RJ45 Cat6', 'RJ45 Cat6', @sub_rj45),
('SMA Konnektörler', 'SMA Connectors', @sub_bnc);

-- PCB → Hazır / Malzemeler
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Tek Katmanlı PCB', 'Single Layer PCB', @sub_pcb_ready),
('Çift Katmanlı PCB', 'Double Layer PCB', @sub_pcb_ready),
('Çok Katmanlı PCB', 'Multilayer PCB', @sub_pcb_ready),
('FR4 PCB Malzemeleri', 'FR4 PCB Materials', @sub_pcb_mat),
('Alüminyum PCB Malzemeleri', 'Aluminum PCB Materials', @sub_pcb_mat);

-- Güç → Bataryalar / DC-DC
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Li-ion Bataryalar', 'Li-ion Batteries', @sub_batt),
('NiMH Bataryalar', 'NiMH Batteries', @sub_batt),
('Alkalin Bataryalar', 'Alkaline Batteries', @sub_batt),
('Step-Down DC-DC', 'Step-Down DC-DC', @sub_dcdc),
('Step-Up DC-DC', 'Step-Up DC-DC', @sub_dcdc);

-- Kablolar → USB / Ethernet / Koaksiyel
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('USB 2.0 Kabloları', 'USB 2.0 Cables', @sub_usb_cables),
('USB 3.0 Kabloları', 'USB 3.0 Cables', @sub_usb_cables),
('Cat5 Ethernet Kabloları', 'Cat5 Ethernet Cables', @sub_eth_cables),
('Cat6 Ethernet Kabloları', 'Cat6 Ethernet Cables', @sub_eth_cables),
('Koaksiyel RG58', 'Coaxial RG58', @sub_coax);

-- Test → Multimetre / Scope / Jeneratör
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Dijital Multimetreler', 'Digital Multimeters', @sub_multimeter),
('Analog Multimetreler', 'Analog Multimeters', @sub_multimeter),
('Masaüstü Osiloskoplar', 'Desktop Oscilloscopes', @sub_scope),
('Taşınabilir Osiloskoplar', 'Portable Oscilloscopes', @sub_scope),
('Fonksiyon Jeneratörleri', 'Function Generators', @sub_siggen);

-- Modüller → Wi-Fi / BT / GSM
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('ESP8266 Modülleri', 'ESP8266 Modules', @sub_wifi),
('ESP32 Modülleri', 'ESP32 Modules', @sub_wifi),
('HC-05 Bluetooth', 'HC-05 Bluetooth', @sub_bt),
('HC-06 Bluetooth', 'HC-06 Bluetooth', @sub_bt),
('SIM800 GSM Modülleri', 'SIM800 GSM Modules', @sub_gsm),
('SIM900 GSM Modülleri', 'SIM900 GSM Modules', @sub_gsm);

-- Elektromekanik → Röle / Switch / Motor / Buton / Joystick / Keypad
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Mini Röleler', 'Mini Relays', @sub_relays),
('Güç Röleleri', 'Power Relays', @sub_relays),
('Endüstriyel Röleler', 'Industrial Relays', @sub_relays),
('Toggle Switchler', 'Toggle Switches', @sub_switch),
('Push Button Switchler', 'Push Button Switches', @sub_switch),
('Tact Switchler', 'Tact Switches', @sub_buttons),
('Mini Butonlar', 'Mini Buttons', @sub_buttons),
('DC Motorlar', 'DC Motors', @sub_motor),
('Step Motorlar', 'Stepper Motors', @sub_motor),
('Servo Motorlar', 'Servo Motors', @sub_motor),
('Joystick Modülleri', 'Joystick Modules', @sub_joystick),
('Membran Klavyeler', 'Membrane Keypads', @sub_keypads);

-- Optoelektronik → LED / High Power / RGB / IR / Fotodiyot / Fototransistör / Optokuplör
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('5mm LED’ler', '5mm LEDs', @sub_leds),
('SMD LED’ler', 'SMD LEDs', @sub_leds),
('High Power LED’ler', 'High Power LEDs', @sub_ledhp),
('RGB SMD LED’ler', 'RGB SMD LEDs', @sub_ledrgb),
('IR LED Vericiler', 'IR LED Emitters', @sub_irleds),
('IR LED Alıcılar', 'IR LED Receivers', @sub_irleds),
('UV LED’ler', 'UV LEDs', @sub_leds),
('IR Fotodiyotlar', 'IR Photodiodes', @sub_photodiode),
('IR Fototransistörler', 'IR Phototransistors', @sub_phototrans),
('Yüksek Hızlı Optokuplörler', 'High Speed Optocouplers', @sub_opto);

-- Soğutma → Heatsink / Fan / Kasa
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Alüminyum Heatsinkler', 'Aluminum Heatsinks', @sub_heatsink),
('Bakır Heatsinkler', 'Copper Heatsinks', @sub_heatsink),
('CPU Heatsinkler', 'CPU Heatsinks', @sub_heatsink),
('GPU Heatsinkler', 'GPU Heatsinks', @sub_heatsink),
('12V Fanlar', '12V Fans', @sub_fans),
('USB Fanlar', 'USB Fans', @sub_fans),
('Mini Fanlar', 'Mini Fans', @sub_fans),
('Endüstriyel Fanlar', 'Industrial Fans', @sub_fans),
('Masaüstü Kasa', 'Desktop Cases', @sub_cases),
('Endüstriyel Kasa', 'Industrial Enclosures', @sub_cases),
('Taşınabilir Kasa', 'Portable Cases', @sub_cases),
('Rackmount Kasa', 'Rackmount Cases', @sub_cases);

-- Yazılım & Geliştirme Kitleri → Arduino / Raspberry / STM32 / FPGA / Robotik / Sensör
INSERT INTO urun_kategoriler (kategori_tr, kategori_en, parent_id) VALUES
('Arduino Starter Kit', 'Arduino Starter Kit', @sub_arduino),
('Arduino Advanced Kit', 'Arduino Advanced Kit', @sub_arduino),
('Arduino Sensör Kitleri', 'Arduino Sensor Kits', @sub_arduino),
('Arduino Robotik Kitleri', 'Arduino Robotics Kits', @sub_arduino),
('Raspberry Pi 4 Kit', 'Raspberry Pi 4 Kit', @sub_rpi),
('Raspberry Pi Pico Kit', 'Raspberry Pi Pico Kit', @sub_rpi),
('Raspberry Pi Kamera Kitleri', 'Raspberry Pi Camera Kits', @sub_rpi),
('Raspberry Pi IoT Kitleri', 'Raspberry Pi IoT Kits', @sub_rpi),
('STM32F4 Discovery Kit', 'STM32F4 Discovery Kit', @sub_stm32dk),
('STM32 Sensör Kitleri', 'STM32 Sensor Kits', @sub_stm32dk),
('FPGA Spartan Kit', 'FPGA Spartan Kit', @sub_fpga_dk),
('FPGA Eğitim Kitleri', 'FPGA Education Kits', @sub_fpga_dk),
('Robotik Eğitim Kitleri', 'Robotics Education Kits', @sub_robotics),
('Robotik Mekanik Kitler', 'Robotics Mechanical Kits', @sub_robotics),
('Sensör Eğitim Kitleri', 'Sensor Education Kits', @sub_sensorkit),
('Sensör Deney Setleri', 'Sensor Experiment Kits', @sub_sensorkit);

-- --------------------------------------------------------
-- Örnek: Transistörler 50 bileşen (isteğe göre genişletilebilir)
-- Kategori bağlamı: "Transistörler" 2. seviye, alt tipler 3. seviye.
-- Burada doğrudan Transistörler kategorisine bağlıyoruz (@sub_trans).
-- --------------------------------------------------------
INSERT INTO `urun_bilesenler` (`kategori_id`, `bilesen_tr`, `bilesen_en`) VALUES
(@sub_trans, 'NPN Transistör BC547', 'NPN Transistor BC547'),
(@sub_trans, 'PNP Transistör BC557', 'PNP Transistor BC557'),
(@sub_trans, 'MOSFET IRF540N', 'MOSFET IRF540N'),
(@sub_trans, 'MOSFET IRFZ44N', 'MOSFET IRFZ44N'),
(@sub_trans, 'Darlington TIP122', 'Darlington TIP122'),
(@sub_trans, 'JFET 2N5457', 'JFET 2N5457'),
(@sub_trans, 'Güç Transistörü 2N3055', 'Power Transistor 2N3055'),
(@sub_trans, 'Transistör BD139', 'Transistor BD139'),
(@sub_trans, 'NPN 2N2222', 'NPN 2N2222'),
(@sub_trans, 'NPN 2N3904', 'NPN 2N3904'),
(@sub_trans, 'PNP 2N2907', 'PNP 2N2907'),
(@sub_trans, 'PNP 2N3906', 'PNP 2N3906'),
(@sub_trans, 'Power TIP3055', 'Power TIP3055'),
(@sub_trans, 'Power MJ2955', 'Power MJ2955'),
(@sub_trans, 'Power TIP41C', 'Power TIP41C'),
(@sub_trans, 'Power TIP42C', 'Power TIP42C'),
(@sub_trans, 'MOSFET IRF3205', 'MOSFET IRF3205'),
(@sub_trans, 'MOSFET IRF840', 'MOSFET IRF840'),
(@sub_trans, 'MOSFET 2N7000', 'MOSFET 2N7000'),
(@sub_trans, 'Darlington TIP127', 'Darlington TIP127'),
(@sub_trans, 'Darlington BDW93C', 'Darlington BDW93C'),
(@sub_trans, 'Darlington BDW94C', 'Darlington BDW94C'),
(@sub_trans, 'IGBT IRG4PC50', 'IGBT IRG4PC50'),
(@sub_trans, 'IGBT GT60M303', 'IGBT GT60M303'),
(@sub_trans, 'Fototransistör L14G2', 'Phototransistor L14G2'),
(@sub_trans, 'UJT 2N2646', 'Unijunction Transistor 2N2646'),
(@sub_trans, 'HF Transistör BFR93A', 'High Frequency Transistor BFR93A'),
(@sub_trans, 'RF Power 2N3866', 'RF Power Transistor 2N3866'),
(@sub_trans, 'RF Power 2N5109', 'RF Power Transistor 2N5109'),
(@sub_trans, 'NPN MJE340', 'NPN MJE340'),
(@sub_trans, 'PNP MJE350', 'PNP MJE350'),
(@sub_trans, 'NPN BD241', 'NPN BD241'),
(@sub_trans, 'PNP BD242', 'PNP BD242'),
(@sub_trans, 'NPN TIP120', 'NPN TIP120'),
(@sub_trans, 'PNP TIP125', 'PNP TIP125'),
(@sub_trans, 'NPN S8050', 'NPN S8050'),
(@sub_trans, 'PNP S8550', 'PNP S8550'),
(@sub_trans, 'NPN KSP2222A', 'NPN KSP2222A'),
(@sub_trans, 'MOSFET AO4407', 'MOSFET AO4407'),
(@sub_trans, 'MOSFET IRLZ44N', 'MOSFET IRLZ44N'),
(@sub_trans, 'MOSFET IRLB8721', 'MOSFET IRLB8721'),
(@sub_trans, 'MOSFET FDP7030', 'MOSFET FDP7030'),
(@sub_trans, 'JFET J201', 'JFET J201'),
(@sub_trans, 'JFET BF245', 'JFET BF245'),
(@sub_trans, 'IGBT IRG4BC30', 'IGBT IRG4BC30'),
(@sub_trans, 'IGBT HGTG30N60A4', 'IGBT HGTG30N60A4'),
(@sub_trans, 'RF Transistör BFR96', 'RF Transistor BFR96'),
(@sub_trans, 'RF Transistör 2SC3355', 'RF Transistor 2SC3355');

-- --------------------------------------------------------
-- Tamamlandı
-- --------------------------------------------------------
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
