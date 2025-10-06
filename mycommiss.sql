-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 06, 2025 at 01:32 PM
-- Server version: 10.5.29-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mycommiss`
--
CREATE DATABASE IF NOT EXISTS `mycommiss` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mycommiss`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'admin', '1234', 'Administrator', '2025-10-04 22:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_description`) VALUES
(1, 'NOTEBOOK (โน้ตบุ๊ค)', ''),
(2, 'KEYBOARD (คีย์บอร์ด)', ''),
(3, 'Gaming Chair (เก้าอี้เกมมิ่ง)', ''),
(4, 'MONITOR (จอมอนิเตอร์)', ''),
(5, 'Game controller/Joystick ', ''),
(6, 'COOLING SYSTEM (ชุดระบายความร้อน)', ''),
(7, 'COMPUTER SET (คอมพิวเตอร์ยกเซ็ด)', ''),
(8, 'MOUSE (เมาส์)', ''),
(9, 'Gaming Headset (หูฟังเกมมิ่ง)', ''),
(10, 'SPEAKER (ลำโพง)', '');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `payment_method` enum('COD','BANK_TRANSFER','QR','CASH') DEFAULT 'COD',
  `payment_status` enum('รอดำเนินการ','ชำระเงินแล้ว','ยกเลิก') DEFAULT 'รอดำเนินการ',
  `order_status` enum('รอดำเนินการ','กำลังจัดเตรียม','จัดส่งแล้ว','สำเร็จ','ยกเลิก') DEFAULT 'รอดำเนินการ',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipped_date` datetime DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `p_id` int(11) NOT NULL,
  `p_name` varchar(255) NOT NULL,
  `p_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `p_stock` int(11) NOT NULL DEFAULT 0,
  `p_description` text DEFAULT NULL,
  `p_image` varchar(255) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`p_id`, `p_name`, `p_price`, `p_stock`, `p_description`, `p_image`, `cat_id`, `created_at`) VALUES
(1, 'ASUS VIVOBOOK GO 14 M1404FA-EB562WA - COOL SILVER', '15990.00', 50, 'Asus Vivobook Go 14 M1404FA-EB562WA โน้ตบุ๊กบางเบาเพียง 17.9 มม. และ 1.38 กก. มาพร้อมจอ FHD\r\nIPS 14 นิ้ว ถนอมสายตา ทนทานตามมาตรฐาน US MIL-STD-810H และบานพับ 180° ใช้งานสะดวก\r\nมี AI ตัดเสียงรบกวน, NumberPad บนทัชแพด พร้อม Windows 11 Home, Office Home 2024 และ Microsoft 365 Basic\r\n\r\n• ซีพียู : AMD Ryzen 5 7520U\r\n• แรม : 16GB LPDDR5 on board\r\n• เอสเอสดี : 512GB PCIe 3/NVMe M.2 SSD\r\n• จอแสดงผล : 14.0&quot; FHD (1920 x 1080) 16:9 aspect ratio, 45% NTSC color gamut\r\n• กราฟิก : AMD Radeon Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home 2024 / Microsoft 365 Basic', '1759726233_1.jpg', 1, '2025-10-06 04:28:08'),
(2, 'HP 15-FD0600TU – SILVER', '13990.00', 18, 'HP Laptop 15 โน้ตบุ๊กดีไซน์บางเบา พร้อมตอบโจทย์การใช้งานในชีวิตประจำวัน ไม่ว่าจะทำงาน เรียน\r\nหรือใช้งานด้านบันเทิง มาพร้อมหน้าจอขนาดใหญ่ ใช้งานได้อย่างต่อเนื่องและลื่นไหล\r\nเหมาะสำหรับผู้ที่มองหาโน้ตบุ๊กที่คุ้มค่าและครบครันในเครื่องเดียว\r\n• ซีพียู : Intel Core i3-1315U\r\n• แรม : 8GB DDR4\r\n• เอสเอสดี : 512GB PCIe/NVMe M.2 SSD\r\n• จอแสดงผล : 15.6&quot; FHD (1920x1080) IPS\r\n• กราฟิก : Intel UHD Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home &amp; Student 2024 / Microsoft 365 Basic', '1759726362_2.jpg', 1, '2025-10-06 04:41:33'),
(3, 'ACER ASPIRE7 A715-59G-550T - TITANIUM BLACK', '23900.00', 6, 'Acer Aspire 7 คือโน้ตบุ๊กที่รวมความแรงและความหรูไว้ในเครื่องเดียว ดีไซน์เรียบเท่เหมาะกับทุกโอกาส มอบประสบการณ์ใช้งานลื่นไหลทั้งทำงานและเล่นเกม พกพาสะดวก ใช้งานได้มั่นใจในทุกสถานการณ์\r\n\r\n• ซีพียู : Intel Core i5-13420H\r\n• แรม : 16GB DDR4\r\n• เอสเอสดี : 512GB PCIe 4/NVMe M.2 SSD\r\n• จอแสดงผล : 15.6\" FHD (1920x1080) IPS 144Hz\r\n• กราฟิก : Nvidia GeForce RTX3050 6GB GDDR6\r\n• ซอฟต์แวร์ : Windows 11 Home\r\n', '1759726356_3.jpg', 1, '2025-10-06 04:48:35'),
(4, 'ACER ASPIRE LITE 15 AL15-52P-586H – SILVER', '15990.00', 15, 'Acer Aspire Lite แล็ปท็อปบางเบา ดีไซน์หลากหลาย ตอบโจทย์ทุกไลฟ์สไตล์ รวมความสวยงามและประสิทธิภาพ มอบประสบการณ์ใช้งานที่ครบครันทั้งด้านดีไซน์และการประมวลผล\r\n\r\n• ซีพียู : Intel Core 5 120U\r\n• แรม : 16GB DDR5\r\n• เอสเอสดี : 512GB PCIe/NVMe M.2 SSD\r\n• จอแสดงผล : 15.6\" FHD (1920x1080) IPS\r\n• กราฟิก : Intel Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home & Student 2024\r\n', '1759726597_4.jpg', 1, '2025-10-06 04:56:37'),
(5, 'ASUS VIVOBOOK 16 M1607KA-MB556WA - QUIET BLUE', '23000.00', 8, 'ASUS Vivobook 16 M1607KA-MB556WA มาพร้อมซีพียู AMD Ryzen AI 5 330 แรม 16GB DDR5 และ SSD ความจุ 512GB ให้ประสิทธิภาพที่รวดเร็ว หน้าจอ 16 นิ้ว WUXGA IPS รองรับการทำงานและความบันเทิงได้อย่างลงตัว กราฟิก AMD Radeon พร้อม Windows 11 Home และ Office Home 2024 ที่ใช้งานได้ทันที\r\n\r\n• ซีพียู : AMD Ryzen AI 5 330\r\n• แรม : 16GB DDR5 on board\r\n• เอสเอสดี : 512GB PCIe 4/NVMe M.2 SSD\r\n• จอแสดงผล : 16.0\" WUXGA (1920 x 1200) IPS-level Panel 60Hz refresh rate Anti-glare\r\n• กราฟิก : AMD Radeon Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home 2024 / Microsoft 365 Basic\r\n', '1759726835_5.jpg', 1, '2025-10-06 05:00:35'),
(6, 'DELL 15 DC15250I5161 - PLATINUM SILVER', '20900.00', 10, 'Dell 15 DC15250I5161 เหมาะกับงานเอกสารและใช้งานทั่วไป มาพร้อม Intel Core i5-1334U, RAM 16GB และ SSD 512GB ทำงานรวดเร็ว จอ 15.6” FHD 120Hz ภาพลื่นตา ใช้งานได้ครบทั้งทำงานและเรียน ราคาคุ้มค่า เหมาะกับคนที่ต้องการโน้ตบุ๊คประสิทธิภาพสมดุล\r\n\r\n• ซีพียู : Intel Core i5-1334U\r\n• แรม : 16GB DDR4 2666 MT/s\r\n• เอสเอสดี : 512GB PCIe/NVMe M.2 SSD\r\n• จอแสดงผล : 15.6\" FHD (1920 x 1080) WVA 120Hz 250 nits Anti-Glare LED Backlit\r\n• กราฟิก : Intel UHD Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home 2024 / Microsoft 365 Basic\r\n', '1759726932_6.jpg', 1, '2025-10-06 05:02:12'),
(7, 'ASUS VIVOBOOK S16 D3607KA-OLED777WA - MATTE GRAY', '32900.00', 3, 'ASUS Vivobook S16 D3607KA มาพร้อม AMD Ryzen AI 7 350, RAM 32GB และ SSD 1TB จอ OLED 16” FHD สีสวย DCI-P3 95% ตอบสนองไว 0.2ms เหมาะทั้งงานกราฟิกและทำงานทั่วไป น้ำหนักเบา ดีไซน์พรีเมียม คุ้มค่ากับสายทำงานและความบันเทิง\r\n\r\n• ซีพียู : AMD Ryzen AI 7 350\r\n• แรม : 32GB (16GB x 2) DDR5\r\n• เอสเอสดี : 1TB PCIe 4/NVMe M.2 SSD\r\n• จอแสดงผล : 16\" FHD (1920 x 1200) OLED 0.2ms 60Hz 300nits DCI-P3 95%\r\n• กราฟิก : AMD Radeon Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home 2024 / Microsoft 365 Basic\r\n', '1759726997_7.jpg', 1, '2025-10-06 05:03:17'),
(8, 'ACER X BUTTERBEAR ASPIRE LITE 15 LIMITED EDITION AL15-42P-R6N3', '19900.00', 7, 'Acer จับมือ Butterbear เปิดตัวโน้ตบุ๊คดีไซน์สุดคิวต์ Aspire Lite 15 Limited Edition ลายหมี Butterbear น่ารักสดใส เหมาะกับสายครีเอทีฟหรือคนรักของน่ารัก ใช้งานได้ทั้งทำงานและเรียนในสไตล์ที่เป็นตัวเอง\r\n\r\n• ซีพียู : AMD Ryzen 5 7430U\r\n• แรม : 32GB DDR4\r\n• เอสเอสดี : 512GB PCIe/NVMe M.2 SSD\r\n• จอแสดงผล : 15.6\" FHD (1920x1080)\r\n• กราฟิก : AMD Radeon Graphics (Integrated)\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home & Student 2024 / Microsoft 365 Basic\r\n', '1759727064_8.jpg', 1, '2025-10-06 05:04:24'),
(9, 'MSI VECTOR A16 HX A8WHG-020TH - COSMOS GRAY', '62900.00', 5, 'MSI Vector A16 HX แล็ปท็อปสายประสิทธิภาพที่ออกแบบมาสำหรับเกม งาน AI และ STEM ดีไซน์ทรงพลัง พกพาได้ ให้ประสบการณ์ที่รวดเร็ว ลื่นไหล และมั่นคง เหมาะกับผู้ใช้ที่ต้องการเครื่องแรงครบเครื่องในทุกสถานการณ์\r\n\r\n• ซีพียู : AMD Ryzen 9 8940HX\r\n• แรม : 16GB DDR5\r\n• เอสเอสดี : 512GB PCIe 4/NVMe M.2 SSD\r\n• จอแสดงผล : 16\" QHD+ (2560x1600) IPS 240Hz\r\n• กราฟิก : Nvidia GeForce RTX5070Ti 12GB GDDR7\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home & Student 2024\r\n', '1759727154_9.jpg', 1, '2025-10-06 05:05:54'),
(10, 'MSI RAIDER 18 HX AI A2XWJG-869TH - CORE BLACK', '165000.00', 4, 'MSI Raider 18 HX AI คือสุดยอดโน้ตบุ๊กเกมมิ่งระดับเดสก์ท็อปที่ผสานเทคโนโลยี AI ล้ำสมัยและจอแสดงผล Mini LED 4K 120 Hz เข้าไว้ด้วยกัน ด้วยดีไซน์ที่แข็งแกร่งและสมดุล มอบทั้งความแรงและความสวยงามให้พร้อมสำหรับทุกการสร้างสรรค์และเกมแรกของคุณ\r\n\r\n• ซีพียู : Intel Core Ultra 9 285HX\r\n• แรม : 64GB DDR5\r\n• เอสเอสดี : 2TB PCIe 5/NVMe M.2 SSD\r\n• จอแสดงผล : 18\" UHD+ (3840x2400) IPS 120Hz\r\n• กราฟิก : Nvidia GeForce RTX5090 24GB GDDR7\r\n• ซอฟต์แวร์ : Windows 11 Home / Office Home & Student 2024\r\n', '1759727248_10.jpg', 1, '2025-10-06 05:07:28'),
(11, 'STEELSERIES APEX PRO MINI OMNIPOINT SWITCH RGB EN – BLACK', '11490.00', 10, 'SteelSeries Apex Pro Mini เป็นคีย์บอร์ดเกมมิ่งไซซ์เล็กแบบ 60% การเชื่อมต่อไร้สายแบบ Dual Mode ทั้ง 2.4GHz Wireless สำหรับการเล่นเกมที่ต้องการความเสถียรและความหน่วงต่ำ และ Bluetooth สำหรับการใช้งานทั่วไปหรือเชื่อมต่อกับอุปกรณ์พกพาที่มาพร้อมกับสวิตช์แม่เหล็ก OmniPoint 2.0 Switch ซึ่งเป็นเทคโนโลยีสุดล้ำจาก SteelSeries ที่สามารถปรับความลึกของการกดได้ระหว่าง 0.1 – 4.0 มม. ตอบโจทย์ทั้งเกมเมอร์และสายพิมพ์ที่ต้องการความแม่นยำและตอบสนองไว\r\n\r\n• สวิตช์ : OmniPoint 2.0 Switch (Magnetic)\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ\r\n• เลย์เอาต์ : ANSI\r\n• ขนาดคีย์บอร์ด : 60% (Compact)\r\n• การเชื่อมต่อ : สาย USB-C เป็น USB-A แบบถอดออกได้, ไร้สาย 2.4GHz, บลูทูธ\r\n', '1759727672_11.jpg', 2, '2025-10-06 05:14:32'),
(12, 'SIGNO KB-712 (RUBBER DOME) (ILLUMINATED)', '250.00', 50, '• Switch : Rubber Dome\r\n• Lighting : 3 Mode LED\r\n• Keycap Font : English/Thai\r\n• Connectivity : USB\r\n', '1759727729_12.jpg', 2, '2025-10-06 05:15:29'),
(13, 'AJAZZ AK680 V2 - RED SWITCH RAINBOW LED EN/TH PURPLE', '590.00', 20, 'Ajazz AK680 V2 คีย์บอร์ดขนาด 65% ที่กะทัดรัดและใช้งานสะดวก มาพร้อมแสงไฟ Rainbow LED เพิ่มความสวยงามและบรรยากาศการใช้งาน รองรับเลย์เอาต์ ANSI มาตรฐาน ใช้งานผ่านการเชื่อมต่อแบบมีสายเพื่อความเสถียรสูงสุด มาพร้อมสาย USB-C to USB-A ที่แข็งแรงทนทาน เหมาะทั้งสำหรับการทำงานและการเล่นเกม ให้คุณสัมผัสการพิมพ์ที่คล่องตัวและประสบการณ์ที่เหนือระดับ\r\n\r\n• สวิตช์ : Red Switch (Linear)\r\n• ขนาด : 65%\r\n• แสงไฟ : Rainbow LED\r\n• คีย์แคป : ภาษาอังกฤษ / ภาษาไทย\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n', '1759727931_13.jpg', 2, '2025-10-06 05:18:51'),
(14, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) ONEX GX3 (BLACK)', '5890.00', 28, 'Gaming Chair', '1759728003_1.jpg', 3, '2025-10-06 05:20:03'),
(15, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) THERMALTAKE CYBERCHAIR E500 (GGC-EG5-BBLFDM-01) (BLACK)', '16900.00', 29, 'Gaming Chair\r\n', '1759728079_2.jpg', 3, '2025-10-06 05:21:19'),
(16, 'AJAZZ AK680 V2 - RED SWITCH RAINBOW LED EN/TH RED', '590.00', 12, 'Ajazz AK680 V2 คีย์บอร์ดขนาด 65% ที่กะทัดรัดและใช้งานสะดวก มาพร้อมแสงไฟ Rainbow LED เพิ่มความสวยงามและบรรยากาศการใช้งาน รองรับเลย์เอาต์ ANSI มาตรฐาน ใช้งานผ่านการเชื่อมต่อแบบมีสายเพื่อความเสถียรสูงสุด มาพร้อมสาย USB-C to USB-A ที่แข็งแรงทนทาน เหมาะทั้งสำหรับการทำงานและการเล่นเกม ให้คุณสัมผัสการพิมพ์ที่คล่องตัวและประสบการณ์ที่เหนือระดับ\r\n\r\n• สวิตช์ : Red Switch (Linear)\r\n• ขนาด : 65%\r\n• แสงไฟ : Rainbow LED\r\n• คีย์แคป : ภาษาอังกฤษ / ภาษาไทย\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n', '1759728127_14.jpg', 2, '2025-10-06 05:22:07'),
(17, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) DUCKY HURRICANE GAMING (DCHU1801) (BLACK)', '8990.00', 21, 'Gaming Chair', '1759728149_3.jpg', 3, '2025-10-06 05:22:29'),
(18, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) ONEX GX3 (BLACK-BLUE) ', '5890.00', 32, 'Gaming Chair', '1759728208_4.jpg', 3, '2025-10-06 05:23:28'),
(19, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) ONEX GX3 (BLACK-RED) ', '5890.00', 30, 'Gaming Chair', '1759728247_5.jpg', 3, '2025-10-06 05:24:07'),
(20, 'ACER AETHON 303 RGB – BLACK', '1990.00', 5, 'Acer Aethon 303 คีย์บอร์ดเกมมิ่งแบบมีสายที่มาพร้อมสวิตช์ Kailh Blue ให้สัมผัสการพิมพ์ที่แม่นยำและชัดเจน ดีไซน์แข็งแรงด้วยแผ่นอลูมิเนียมสีดำ ทนทานกว่า 50 ล้านครั้ง พร้อมไฟ RGB สุดตระการตา มอบประสบการณ์การเล่นเกมที่ดุดันและเต็มไปด้วยสไตล์\r\n\r\n• สวิตช์ : Kailh Blue Switches\r\n• ขนาด : 100% (Full-size)\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ / ภาษาไทย\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย\r\n', '1759728259_15.jpg', 2, '2025-10-06 05:24:19'),
(21, 'MING CGAHAIR (เก้าอี้เกมมิ่ง) COUGAR GAMING ARMOR TITAN (BLACK-ORANGE) ', '10900.00', 25, 'Gaming Chair\r\n', '1759728292_6.jpg', 3, '2025-10-06 05:24:52'),
(22, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) SIGNO E-SPORT BRANCO (GC-207BR) (BLACK-RED)', '5690.00', 29, 'Gaming Chair\r\n', '1759728363_7.jpg', 3, '2025-10-06 05:25:31'),
(23, 'GRAVASTAR MERCURY V75 SPECIAL EDITION - MAGNETIC SWITCH RGB EN LAVENDER PURPLE', '2590.00', 6, 'Gravastar Mercury V75 คีย์บอร์ดเกมมิ่งความเร็วสูง รองรับอัตรารีเฟรช 8,000Hz และสแกนคีย์ 256kHz ลดดีเลย์เหลือเพียง 0.125ms ปรับความลึกของการกดได้ตั้งแต่ 0.1–3.5 มม. รองรับโหมด Rapid Trigger และ LKP เพื่อความแม่นยำและตอบสนองที่เหนือกว่า โครงสร้างอะลูมิเนียมผสมพลาสติก แข็งแรงและเบา รองรับ Hot-Swap เปลี่ยนสวิตช์แม่เหล็กได้โดยไม่ต้องบัดกรี มาพร้อมโฟมซับเสียง 5 ชั้น และไฟ RGB ปรับได้ 16 โหมด แยกโซนอย่างอิสระ\r\n\r\n• สวิตช์ : Magnetic Switch (Linear)\r\n• ขนาด : 75%\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n• การเปลี่ยนสวิตช์ : เปลี่ยนสวิตช์ได้\r\n', '1759728350_16.jpg', 2, '2025-10-06 05:25:50'),
(24, 'GRAVASTAR MERCURY K1 LITE - GRAVASTAR X BSUN LINEAR SWITCH RGB EN CRYSTAL AURORA', '2990.00', 11, 'Gravastar Mercury K1 Lite คีย์บอร์ดที่เบาที่สุดในตระกูล K1 มาพร้อมดีไซน์โครงสร้างโพลีเมอร์แบบออร์แกนิกที่ให้ความแข็งแรงและเบาเป็นพิเศษ แตกต่างจากโลหะแบบเดิม พร้อมดีไซน์ที่เป็นเอกลักษณ์คล้ายโลหะเหลว มีความเสถียรทุกครั้งที่กดแป้น รองรับการเปลี่ยนสวิตช์แบบ hot-swap ทั้ง 3-pin และ 5-pin เหมาะสำหรับผู้ที่ต้องการคีย์บอร์ดที่โดดเด่นทั้งด้านฟังก์ชันและดีไซน์ล้ำสมัย\r\n\r\n• สวิตช์ : GravaStar x BSUN Linear Switch\r\n• ขนาด : 75%\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย / ไร้สาย 2.4GHz / บลูทูธ\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n• การเปลี่ยนสวิตช์ : เปลี่ยนสวิตช์ได้ รองรับสวิตช์ 3 ขา / 5 ขา\r\n', '1759728410_17.jpg', 2, '2025-10-06 05:26:50'),
(25, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) DXRACER MITH TEAM (RZ134/NY) (BLACK-YELLOW) ', '11500.00', 39, 'Gaming Chair\r\n', '1759728421_8.jpg', 3, '2025-10-06 05:27:01'),
(26, 'EGA X JJK K1 WHITE LIMITED EDITION - KTT LINEAR SWITCH RGB EN/TH', '2590.00', 3, 'EGA x JJK K1 White Limited Edition คีย์บอร์ด 98% ดีไซน์พรีเมียม มาพร้อมโครงสร้าง Gasket Mount ที่ช่วยลดแรงกระแทกและให้เสียงพิมพ์นุ่มแน่น หน้าจอ TFT Color Screen แสดงสถานะการเชื่อมต่อ เอฟเฟกต์ไฟ RGB และไฟล์ภาพเคลื่อนไหวได้แบบเรียลไทม์ รองรับการถอดเปลี่ยนสวิตช์ Hot-Swappable 5 Pin ใช้ Keycap PBT พิมพ์ลายทนทาน สัมผัสเยี่ยม เพลต Polycarbonate แข็งแรง ฟังก์ชัน Full Anti-Ghosting และไฟ RGB ปรับแต่งได้ รองรับ Windows และ Mac OS\r\n\r\n• สวิตช์ : KTT Linear Switch\r\n• ขนาด : 98%\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ / ภาษาไทย\r\n• เลย์เอาต์ : ANSI\r\n• จอแสดงผล : จอสีแบบ TFT\r\n• การเชื่อมต่อ : แบบใช้สาย / ไร้สาย 2.4GHz / บลูทูธ\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n• การเปลี่ยนสวิตช์ : เปลี่ยนสวิตช์ได้ รองรับสวิตช์ 3 ขา / 5 ขา\r\n', '1759728463_18.jpg', 2, '2025-10-06 05:27:43'),
(27, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) THERMALTAKE GF500 FLIGHT SIMULATOR COCKPIT - BLACK (GSC-F50-CPASBB-01)', '21990.00', 35, 'Thermaltake GF500 คือค็อกพิทจำลองการบินระดับพรีเมียม วัสดุโครงเหล็ก-อะลูมิเนียมแข็งแรง เบาะไฟเบอร์กลาสปรับเอนได้ พร้อมแผ่นวางจอย ปีกยกคันเร่ง และติดตั้งระบบไฟ RGB เหมาะสำหรับนักบินจำลองผู้ต้องการความสมจริงแบบจัดเต็ม\r\n', '1759728463_9.jpg', 3, '2025-10-06 05:27:43'),
(28, 'GAMING CHAIR (เก้าอี้เกมมิ่ง) CORSAIR TC500 LUXE CF-9010067-WW - FROST ', '16500.00', 42, 'Gaming Chair', '1759728501_10.jpg', 3, '2025-10-06 05:28:21'),
(29, 'GRAVASTAR MERCURY V75 PRO MAGNETIC SWITCH RGB EN - NEON GRAFFITI', '8590.00', 3, 'Gravastar Mercury V75 Pro คีย์บอร์ดเกมมิ่งระดับมือโปร มาพร้อมอัตราการตอบสนอง 8,000Hz และระบบสแกนคีย์ 256kHz ลดค่าหน่วงเหลือเพียง 0.125ms ปรับระดับการกดได้ตั้งแต่ 0.1–3.5 มม. รองรับ Dynamic Rapid Trigger และระบบ LKP เพื่อการตอบสนองที่แม่นยำ ตัวเครื่องแบบกึ่งอะลูมิเนียม แข็งแรงและน้ำหนักเบา รองรับ Hot-Swap สำหรับสวิตช์แม่เหล็ก Hall Effect พร้อมโฟมซับเสียง 5 ชั้น และไฟ RGB 16 โหมดแบบแยกโซน ปรับแต่งได้เต็มรูปแบบ\r\n\r\n• สวิตช์ : Magnetic switch (linear)\r\n• ขนาด : 75%\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ\r\n• เลย์เอาต์ : ANSI\r\n• การเชื่อมต่อ : แบบใช้สาย\r\n• สายเคเบิล : สาย USB-C เป็น USB-A\r\n• การเปลี่ยนสวิตช์ : เปลี่ยนสวิตช์ได้\r\n', '1759728509_19.jpg', 2, '2025-10-06 05:28:29'),
(30, 'STEELSERIES APEX PRO MINI GEN 3 OMNIPOINT 3.0 SWITCH RGB EN – BLACK', '9590.00', 8, 'Apex Pro Mini Gen 3 คือคีย์บอร์ดสายเกมที่เร็วที่สุดในโลก มาพร้อมสวิตช์ OmniPoint 3.0 แบบ Hall Effect ที่ปรับระดับได้ถึง 40 ระดับ ตอบสนองไวสุดขีด ดีไซน์ขนาดเล็กแต่ฟีเจอร์ครบ รองรับ Rapid Trigger, Protection Mode และใช้งานง่ายผ่าน GG Quickset เหมาะสำหรับเกมเมอร์สายแข่งขันตัวจริง\r\n\r\n• สวิตช์ : OmniPoint 3.0 Switch (Magnetic, Hall Effect, Linear)\r\n• แสงไฟ : RGB\r\n• คีย์แคป : ภาษาอังกฤษ\r\n• เลย์เอาต์ : ANSI\r\n• ขนาดคีย์บอร์ด : 60%\r\n• การเชื่อมต่อ : สาย USB-C เป็น USB-A แบบถอดออกได้\r\n', '1759728585_20.jpg', 2, '2025-10-06 05:29:45'),
(31, 'MSI G32C4X - 31.5 INCH VA FHD 250Hz CURVED FREESYNC PREMIUM', '4500.00', 7, '• Color gamut : 114% sRGB, 91% DCI-P3\r\n• Color Support : 1.07 Billion\r\n• Response Time : 1 ms(MPRT)\r\n• Brightness : 300 Nits\r\n• Aspect Ratio : 16:9\r\n', '1759729057_21.jpg', 4, '2025-10-06 05:37:37'),
(32, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) MICROSOFT XBOX CONTROLLER SERIES WLC (BLACK) (MCS-1V8-00014)', '1690.00', 42, 'Support : Xbox Series X / Xbox Series S / Xbox One / Windows 10 / Android / iOS\r\n', '1759729123_1.jpg', 5, '2025-10-06 05:38:43'),
(33, 'CONTROLLER (คอนโทรลเลอร์) LOGITECH GAMING GEAR CONTROLLER F310 CONSOLE STYTE', '2190.00', 52, '● Intuitive rear controls\r\n● Selectable step triggers\r\n● Premium audio\r\n● Extensive customization\r\n', '1759729205_2.jpg', 5, '2025-10-06 05:40:05'),
(34, 'SAMSUNG ODYSSEY G4 LS25BG400EEXXT - 25 INCH IPS FHD 240Hz G-SYNC COMPATIBLE, FREESYNC PREMIUM', '6500.00', 2, '• 25\"\r\n• IPS panel Anti-glare\r\n• 1920 x 1080 240Hz 1ms\r\n• 16.7 million colors\r\n', '1759729224_22.jpg', 4, '2025-10-06 05:40:24'),
(35, 'RACING WHEEL CONTROLLER (คอนโทรลเลอร์พวงมาลัย) LOGITECH GAMING GEAR G29 DRIVING FORCE WHEEL', '8990.00', 29, 'Support : PC / PS3 / PS4\r\n', '1759729241_3.jpg', 5, '2025-10-06 05:40:41'),
(36, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) RAZER WOLVERINE V2 PRO PS (WHITE)', '9990.00', 56, '• Razer™ HyperSpeed Wireless\r\n• Razer™ Mecha-Tactile Action Buttons\r\n• 8-Way Microswitch D-Pad\r\n', '1759729291_4.jpg', 5, '2025-10-06 05:41:31'),
(37, 'MSI MAG 274QRF QD E2 - 27 INCH RAPID IPS 2K 180Hz ADAPTIVE SYNC USB-C', '5500.00', 5, '• 27\"\r\n• Rapid IPS Anti-glare\r\n• 2560 x 1440 180Hz (DP) 144Hz (HDMI) 1ms\r\n• 1.07 billion colors\r\n• 2 x HDMI\r\n', '1759729292_23.jpg', 4, '2025-10-06 05:41:32'),
(38, 'RACING WHEEL ACCESSORIES (อุปกรณ์เสริมพวงมาลัย) MOZA KS STEERING WHEEL (MZ-RS047)', '15900.00', 46, 'For : Moza Racing Wheel Base\r\n', '1759729332_5.jpg', 5, '2025-10-06 05:42:12'),
(39, 'LG ULTRAGEAR 24GS60F-B - 23.8 INCH IPS FHD 180Hz AMD FREESYNC NVIDIA G-SYNC COMPATIBLE', '6500.00', 8, '• 23.8\" IPS panel Anti-glare\r\n• 1920 x 1080 180Hz 1ms\r\n• 16.7 million colors\r\n• 1 x HDMI\r\n', '1759729332_24.jpg', 4, '2025-10-06 05:42:12'),
(40, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) MICROSOFT XBOX WIRELESS - DOOM THE DARK AGE (MCS-EP2-14851)', '2990.00', 57, 'คอนโทรลเลอร์ Xbox รุ่นลิมิเต็ด DOOM: The Dark Ages มาในดีไซน์ชุดเกราะ Slayer สีเขียวด้าน พร้อมหมุด 3D และปุ่ม Sentinel ABXY พร้อมปลดปล่อยพลังโบราณ พร้อมโค้ดสกิน Executioner สำหรับเกม DOOM: The Dark Ages\r\n\r\n• รองรับ: Xbox Series X / Xbox Series S / Xbox One / Windows 10,11 / Android / iOS\r\n\r\n', '1759729373_6.jpg', 5, '2025-10-06 05:42:53'),
(41, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) MICROSOFT XBOX WIRELESS (ELECTRIC VOLT) (QAU-00023)', '1890.00', 52, 'Support : Xbox Series X / Xbox Series S / Xbox One / Windows 10/11 / Android / iOS\r\n', '1759729408_7.jpg', 5, '2025-10-06 05:43:28'),
(42, 'LG ULTRAGEAR 34GP63A-B - 34 INCH VA 2K 160Hz CURVED AMD FREESYNC PREMIUM', '29900.00', 10, '• 34\"\r\n• VA panel\r\n• 3440 x 1440 160Hz (DP) 85Hz (HDMI) 1ms\r\n• 1800R Curved\r\n• 16.7 million colors\r\n• 2 x HDMI\r\n', '1759729413_25.jpg', 4, '2025-10-06 05:43:33'),
(43, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) RAZER WOLVERINE V3 PRO XBOX - WHITE', '6990.00', 57, 'Razer Wolverine V3 Pro ก้าวนำหน้าคู่แข่งไปหนึ่งก้าว หนึ่งช็อต และหนึ่งระดับด้วย Razer Wolverine V3 Pro คอนโทรลเลอร์ไร้สายสำหรับอีสปอร์ตที่สมบูรณ์แบบ ได้รับการรับรองอย่างเป็นทางการจาก Xbox ให้คุณเล่นเหมือนนักแข่งระดับโลกบนคอนโซลและ PC ด้วยความเร็ว การควบคุม และความแม่นยำที่ช่วยให้คุณคว้าชัยชนะ!\r\n\r\n• การเชื่อมต่อ : ใช้สาย ,ไร้สายความเร็วสูง\r\n• แบตเตอร์รี่ : ใช้งานต่อเนื่องสูงสุด 20 ชม.\r\n• ระบบที่ต้องการ : Xbox, PC\r\n', '1759729440_8.jpg', 5, '2025-10-06 05:44:00'),
(44, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) MICROSOFT XBOX WIRELESS - DEEP PINK (MCS-EP2-29913)', '1890.00', 48, 'ยกระดับเกมของคุณด้วย Xbox Wireless Controller ดีไซน์สวย จับถนัดมือ พร้อมแป้นทิศทางไฮบริดและปุ่มแชร์ในตัว รองรับการเชื่อมต่อกับ Xbox Series X|S, Xbox One, พีซี, Android และ iOS ได้อย่างรวดเร็ว\r\n\r\n• รองรับ: Xbox Series X / Xbox Series S / Xbox One / Windows 10,11 / Android / iOS\r\n', '1759729483_9.jpg', 5, '2025-10-06 05:44:43'),
(45, 'WIRELESS CONTROLLER (คอนโทรลเลอร์ไร้สาย) MICROSOFT XBOX - MCS-EP2-29569 HEART BREAKER', '2990.00', 46, 'Xbox Breaker Series Special Edition มาพร้อมลวดลายโดดเด่นทั้ง Ice Breaker, Heart Breaker และ Storm Breaker ที่ช่วยให้คอนโทรลเลอร์ดู “เฉียบ” และมีสไตล์ พร้อมปุ่ม Share ที่ช่วยให้จับภาพหรือคลิปได้ง่าย เชื่อมต่อได้ทั้ง Xbox, PC, มือถือ — เป็นตัวเลือกที่โดนใจทั้งเกมเมอร์และคนชอบของแต่ง เซ็ตให้ดูมีลูกเล่นในเกมสเตชันของคุณ\r\n\r\n• รองรับ: Xbox Series X / Xbox Series S / Xbox One / Windows 10,11 / Android / iOS\r\n\r\n', '1759729519_10.jpg', 5, '2025-10-06 05:45:19'),
(46, 'ASUS PROART PA329CV - 32 INCH IPS 4K 60Hz USB-C', '3900.00', 6, '• Color gamut : 100% sRGB\r\n• Color Support : 1.07 Billion\r\n• Response Time : 5 ms(GTG)\r\n• Brightness : 350 Nits\r\n• Aspect Ratio : 16:9\r\n', '1759729559_26.jpg', 4, '2025-10-06 05:45:59'),
(47, 'LG ULTRAGEAR 27GS60F-B - 27 INCH IPS FHD 180Hz AMD FREESYNC NVIDIA G-SYNC COMPATIBLE', '8500.00', 10, '• 27\"\r\n• IPS panel\r\n• 1920 x 1080 180Hz 1ms\r\n• 16.7 million colors\r\n• 1 x HDMI\r\n• 1 x DP\r\n', '1759729618_27.jpg', 4, '2025-10-06 05:46:58'),
(48, 'LG ULTRAWIDE 29WQ600-W - 29 INCH IPS UWFHD 100Hz USB-C AMD FREESYNC', '7500.00', 20, '• 29\"\r\n• IPS panel\r\n• 2560 x 1080 100Hz 5ms\r\n• 16.7 million colors\r\n• 1 x USB-C (DP Alt Mode)\r\n', '1759729737_28.jpg', 4, '2025-10-06 05:48:57'),
(49, 'ACER NITRO VG270 GBMIPX - 27 INCH IPS FHD 120Hz', '18000.00', 11, '• 27\"\r\n• IPS panel\r\n• 1920 x 1080 120Hz 1ms\r\n• 16.7 million colors\r\n• 1 x HDMI\r\n', '1759729800_29.jpg', 4, '2025-10-06 05:50:00'),
(50, 'GIGABYTE AORUS FO32U2P - 31.5 INCH OLED 4K 240Hz AMD FREESYNC PREMIUM PRO USB-C', '39900.00', 15, '• 31.5\"\r\n• OLED panel Anti-Reflection\r\n• 3840 x 2160 240Hz 0.03ms\r\n• 1.07 billion colors\r\n• 2 x HDMI\r\n', '1759729852_30.jpg', 4, '2025-10-06 05:50:52'),
(51, 'CPU AIR COOLER (พัดลมซีพียู) NOCTUA NH-D15', '4750.00', 89, '• Dimensions Height x Width x Depth (mm) : 165 x 150 x 161\r\n• TDP (W) : 140 w\r\n', '1759729971_1.jpg', 6, '2025-10-06 05:52:51'),
(52, 'CPU AIR COOLER (พัดลมซีพียู) NOCTUA NH-D15 CHROMAX (BLACK)', '5250.00', 208, 'Dimensions Height x Width x Depth (mm) : 160 x 150 x 135\r\n', '1759730014_2.jpg', 6, '2025-10-06 05:53:34'),
(53, 'CPU AIR COOLER (พัดลมซีพียู) DEEPCOOL AG400 PLUS', '990.00', 211, '• Intel LGA1700/1200/1151/1150/1155\r\n• AMD AM5/AM4\r\n', '1759730063_3.jpg', 6, '2025-10-06 05:54:23'),
(54, 'CPU AIR COOLER (พัดลมซีพียู) NOCTUA NH-U9S', '2890.00', 157, '• Dimensions Height x Width x Depth (mm) : 125 x 95 x 95\r\n• TDP (W) : ≈ 140 w\r\n\r\n', '1759730116_4.jpg', 6, '2025-10-06 05:55:16'),
(55, 'COMPUTER SET JIB MARU-2508098 RYZEN 5 7500F / RTX5060 8GB / B650M / 32GB DDR5 / M.2 512GB', '33900.00', 9, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n• บริการซ่อมและตรวจเช็คอาการ ฟรี! ได้ที่เจไอบีกว่า 130 สาขา ใน 70 จังหวัด\r\n', '1759730153_31.jpg', 7, '2025-10-06 05:55:53'),
(56, 'CPU AIR COOLER (พัดลมซีพียู) DEEPCOOL AK620', '2690.00', 211, 'Dimensions Length x Width x Height (mm) : 129 x 138 x 160\r\n', '1759730177_5.jpg', 6, '2025-10-06 05:56:17'),
(57, 'CPU AIR COOLER (พัดลมซีพียู) NOCTUA NH-U12S', '3340.00', 165, '• Dimensions Height x Width x Depth (mm) : 158 x 125 x 95\r\n• TDP (W) : ≈ 140 w\r\n', '1759730211_6.jpg', 6, '2025-10-06 05:56:51'),
(58, 'COMPUTER SET JIB MARU-CM25236 RYZEN 9 9950X3D / RTX5080 16GB / X870E / 96GB DDR5 / M.2 4TB', '175600.00', 15, '• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730234_32.jpg', 7, '2025-10-06 05:57:14'),
(59, 'CPU AIR COOLER (พัดลมซีพียู) NOCTUA NH-U12A', '5250.00', 72, 'Dimensions Height x Width x Depth (mm) : 158 x 125 x 58\r\n', '1759730253_7.jpg', 6, '2025-10-06 05:57:33'),
(60, 'CPU AIR COOLER (พัดลมซีพียู) DEEPCOOL AK500 DIGITAL WHITE', '2490.00', 189, '• 2066/2011-v3/2011/1700/1200/1151/1150/1155\r\n• AM5/AM4\r\n\r\n', '1759730298_8.jpg', 6, '2025-10-06 05:58:18'),
(61, 'COMPUTER SET JIB MARU-CM25133 RYZEN7 9700X / RTX5080 16GB / B850 / 64GB DDR5 / M.2 1TB', '88900.00', 8, '• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n• บริการซ่อมและตรวจเช็คอาการ ฟรี! ได้ที่เจไอบีกว่า 140 สาขา ใน 70 จังหวัด\r\n', '1759730342_33.jpg', 7, '2025-10-06 05:59:02'),
(62, 'CPU LIQUID COOLER (ระบบระบายความร้อนด้วยน้ำ) THERMALRIGHT FROZEN WARFRAM4', '3990.00', 57, 'Frozen Warframe 360 SE ARGB เป็นชุดน้ำปิดที่ออกแบบมาเพื่อการระบายความร้อนที่มีประสิทธิภาพสูง รองรับซ็อกเก็ต Intel และ AMD ล่าสุด พร้อมพัดลม ARGB ที่รองรับ RGB Sync ให้แสงสีที่สวยงาม เสียงพัดลมไม่ดังมาก และมีอัตราการไหลเวียนของอากาศที่ดี เหมาะสำหรับผู้ใช้ที่ต้องการทั้งความเย็นและดีไซน์ที่โดดเด่น\r\n\r\nรองรับซ็อกเก็ต : Intel LGA 1851, 1700, 1200, 115x และ AMD AM5, AM4\r\n', '1759730345_9.jpg', 6, '2025-10-06 05:59:05'),
(63, 'CPU LIQUID COOLER (ระบบระบายความร้อนด้วยน้ำ) ASUS ROG RYUJIN III 360 ARGB EXTREME WHITE EDITION', '15390.00', 66, 'ASUS ROG Ryujin III 360 ARGB Extreme White Edition เป็นระบบระบายความร้อนด้วยน้ำที่มีดีไซน์หรูหรา รองรับ ซ็อกเก็ต Intel และ AMD หลายรุ่น พร้อมจอแสดงผล LCD 3.5 นิ้วแสดงสถานะการทำงาน ระบบ RGB Sync ที่รองรับการควบคุมสีผ่าน Asus Aura Sync\r\n• RGB Sync : Asus Aura Sync\r\n• จอแสดงผล : 3.5\" Full Color LCD\r\n\r\n', '1759730387_10.jpg', 6, '2025-10-06 05:59:47'),
(64, 'COMPUTER SET JIB MARU-CM25221 ULTRA 7 265K / RTX5070TI 16GB / B860M / 64GB DDR5 / M.2 1TB', '76900.00', 5, '• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730400_34.jpg', 7, '2025-10-06 06:00:00'),
(65, 'COMPUTER SET JIB MARU-2510080 RYZEN 7 7800X3D / RTX5060 TI 8GB / B650M / 32GB DDR5 / M.2 1TB', '42900.00', 11, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730470_35.jpg', 7, '2025-10-06 06:01:10'),
(66, 'COMPUTER SET JIB MARU-CM25224 ULTRA 9 285K / RTX5090 32GB / Z890 / 96GB DDR5 / M.2 4TB', '261900.00', 3, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730575_36.jpg', 7, '2025-10-06 06:02:55'),
(67, 'COMPUTER SET JIB MARU-2510077 I7-14700K / RTX5060 TI 8GB / B760M / 32GB DDR5 / M.2 1TB', '43400.00', 4, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730669_37.jpg', 7, '2025-10-06 06:04:29'),
(68, 'X3D / RTX5070 12GB / X870 / 64GB DDR5 / M.2 1TB', '85900.00', 7, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n• ผ่อนสบายๆ 0% นาน 10 เดือน ทุกเซ็ต\r\n', '1759730788_38.jpg', 7, '2025-10-06 06:06:28'),
(69, 'ACER PREDATOR G300 – BLACK', '1190.00', 35, 'เมาส์เกมมิ่งสีดำดีไซน์เท่ ออกแบบมาเพื่อประสิทธิภาพในการเล่นเกม พร้อมรูปทรงที่จับถนัดมือ มอบความแม่นยำและความเร็วในการตอบสนอง เหมาะสำหรับเกมเมอร์ที่ต้องการอุปกรณ์ที่รองรับการเล่นเกมได้อย่างเต็มที่\r\n\r\n• ความละเอียด : สูงสุด 6,200 DPI\r\n• การเชื่อมต่อ : USB2.0\r\n• เซนเซอร์ : Pixart 3327', '1759730848_1.jpg', 8, '2025-10-06 06:07:07'),
(70, 'TX5060 8GB / B760M / 32GB DDR5 / M.2 1TB', '32800.00', 2, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n', '1759730890_39.jpg', 7, '2025-10-06 06:08:10'),
(71, 'ACER PREDATOR G100 – BLACK', '1090.00', 23, 'เมาส์เกมมิ่งดีไซน์เข้มดุดัน สีดำเรียบเท่ ออกแบบมาเพื่อการเล่นเกมโดยเฉพาะ มอบความแม่นยำและการตอบสนองที่รวดเร็ว เหมาะสำหรับเกมเมอร์ที่ต้องการอุปกรณ์ควบคุมที่ไว้วางใจได้ในทุกจังหวะของเกม\r\n\r\n• ความละเอียด : สูงสุด 6,200 DPI\r\n• การเชื่อมต่อ : USB2.0\r\n• เซนเซอร์ : Pixart 3327', '1759730898_2.jpg', 8, '2025-10-06 06:08:18'),
(72, 'RTX5080 16GB / Z790 / 64GB DDR5 / M.2 2TB', '59500.00', 11, '• สามารถปรับเปลี่ยนอุปกรณ์ได้ตามที่ต้องการ\r\n• ทุกเซ็ตที่กำหนด จัดส่งฟรีภายใน 4 ชั่วโมง *เฉพาะกรุงเทพฯ และปริมณฑล\r\n• อุปกรณ์คอมพิวเตอร์เสียภายใน 30 วัน นับจากวันซื้อ เปลี่ยนอุปกรณ์คอมพิวเตอร์ใหม่ให้ทันที ภายใน 24 ชั่วโมง เฉพาะซื้อผ่าน JIB Online เท่านั้น (เงื่อนไขเป็นไปตามที่กำหนด)\r\n', '1759730968_40.jpg', 7, '2025-10-06 06:09:28'),
(73, 'ACER PREDATOR G200 – BLACK', '1190.00', 43, 'เมาส์เกมมิ่งสีดำดีไซน์เข้ม สไตล์ดุดันตามแบบฉบับของ Predator ออกแบบเพื่อรองรับการเล่นเกมอย่างจริงจัง ให้ความแม่นยำสูงและตอบสนองรวดเร็ว เหมาะสำหรับเกมเมอร์ที่ต้องการอุปกรณ์ที่ควบคุมได้มั่นใจในทุกการเคลื่อนไหว\r\n\r\n• ความละเอียด : สูงสุด 6,200 DPI\r\n• การเชื่อมต่อ : USB2.0\r\n• เซนเซอร์ : Pixart 3327\r\n', '1759730971_3.jpg', 8, '2025-10-06 06:09:31'),
(74, 'HEADSET (หูฟัง) LOGITECH PRO X GAMING HEADSET WITH BLUE VOICE', '3690.00', 49, '• Headset Response : 20 Hz - 20000 Hz\r\n• Mic Response : 100 Hz - 10000 Hz\r\n\r\n', '1759730991_1.jpg', 9, '2025-10-06 06:09:51'),
(75, 'HP M10 - BLACK', '190.00', 60, '• เมาส์ออปติคอลแบบมีสาย\r\n• เมาส์ออปติคอล 3 ปุ่ม ความละเอียด 1200 DPI\r\n• เชื่อมต่อผ่าน USB พร้อมสายยาว 1.6 เมตร\r\n• รองรับระบบปฏิบัติการ Windows XP, 7, 11\r\n', '1759731042_4.jpg', 8, '2025-10-06 06:10:42'),
(76, 'HEADSET (หูฟัง) ASUS ROG FUSION II 500', '3590.00', 47, '• Headset Response : 20 Hz - 40000 Hz\r\n• Mic Response : 100 Hz - 10000 Hz\r\n\r\n', '1759731050_2.jpg', 9, '2025-10-06 06:10:50'),
(77, 'ARROW-X YDK-SK-M158 (MINT)', '120.00', 45, '• Up to 1,000 DPI\r\n• USB-A Wired connection\r\n', '1759731089_5.jpg', 8, '2025-10-06 06:11:29'),
(78, 'HEADSET (หูฟัง) ASUS ROG DELTA S CORE', '2690.00', 69, '• Headset Response : 20 Hz - 40000 Hz\r\n• Mic Response : 100 Hz - 10000 Hz\r\n', '1759731091_3.jpg', 9, '2025-10-06 06:11:31'),
(79, 'HP G210 BLACK', '315.00', 34, '• 6 ปุ่ม\r\n• 3200/2400/1600/800 DPI\r\n• ไฟLED เปลี่ยนสีอัตโนมัติ\r\n', '1759731130_6.jpg', 8, '2025-10-06 06:12:10'),
(80, 'HEADSET (หูฟัง) HYPERX CLOUD III (RED)', '2590.00', 68, '• HyperX Signature Comfort and Durability\r\n• Angled 53mm Drivers, Tuned for Impeccable Audio\r\n• Crystal-Clear 10mm microphone, noise-cancelling, with LED mic-mute indicator\r\n• Multiplatform Compatible with 3.5mm, USB-C, and USB-A\r\n', '1759731132_4.jpg', 9, '2025-10-06 06:12:12'),
(81, 'HEADSET (หูฟัง) LOGITECH G G335 (WHITE)', '1590.00', 59, '• Headset Response : 20 Hz - 20000 Hz\r\n• Mic Response : 100 Hz - 10000 Hz\r\n\r\n', '1759731165_5.jpg', 9, '2025-10-06 06:12:45'),
(82, 'JBL FLIP 6 (GREY)', '4150.00', 20, '20 Watt', '1759731172_41.jpg', 10, '2025-10-06 06:12:52'),
(83, 'HP G100PLUS BLACK', '385.00', 23, '• 4 ปุ่ม\r\n• 1600/1200/800 DPI ไฟRGB\r\n', '1759731179_7.jpg', 8, '2025-10-06 06:12:59'),
(84, 'WIRELESS HEADSET (หูฟังไร้สาย) HYPERX CLOUD III (BLACK)', '3890.00', 72, '• Up to 120 Hours\r\n• Ultra-clear microphone\r\n• Angled 53mm Drivers\r\n• Gaming-grade wireless\r\n', '1759731201_6.jpg', 9, '2025-10-06 06:13:21'),
(85, 'HEADSET (หูฟัง) RAZER BLACKSHARK V2 X (GREEN)', '1890.00', 75, '• Headset Response : 12 Hz - 28000 Hz\r\n• Mic Response : 100 Hz - 10000 Hz\r\n', '1759731232_7.jpg', 9, '2025-10-06 06:13:52'),
(86, 'HP M270 BLACK', '235.00', 31, '• เซ็นเซอร์ SPC A704E\r\n• 6 ปุ่ม\r\n• 2400/1600/1200/800 DPI ไฟRGB', '1759731240_8.jpg', 8, '2025-10-06 06:14:00'),
(87, 'CREATIVE STAGE V2 SOUNDBAR-SUBWOOFER (BLACK)', '2150.00', 21, 'Main unit: 2 x 20 W, Subwoofer: 1 x 40W, Total System Power: Up to 80W RMS, Peak Power 160W\r\n\r\n', '1759731263_42.jpg', 10, '2025-10-06 06:14:23'),
(88, 'WIRELESS HEADSET (หูฟังไร้สาย) STEELSERIES ARCTIS NOVA PRO WIRELESS (BLACK) (PC & PLAYSTATION)', '15690.00', 54, '• ระบบตัดเสียงรบกวนแบบแอคทีฟ (ANC) พร้อมตัวเลือกการได้ยินทะลุผ่าน\r\n• ไดรเวอร์แม่เหล็กนีโอไดเมียมคุณภาพสูงแบบความละเอียดสูง\r\n• ไมโครโฟนตัดเสียงรบกวนที่ขับเคลื่อนด้วย AI พร้อมดีไซน์แบบซ่อนเก็บได้\r\n• เวลาเล่นไม่จำกัดด้วยแบตเตอรี่แบบถอดเปลี่ยนได้ 2 ก้อน + ชาร์จเร็ว\r\n• ความถี่ 2.4GHz และ Bluetooth พร้อมกันสำหรับผสมเสียงเกมและมือถือ\r\n• การเชื่อมต่อ USB คู่รองรับพีซี Mac PlayStation Switch VR และอื่นๆ\r\n• ไมโครโฟนตัดเสียงรบกวนที่ขับเคลื่อนด้วย AI พร้อมดีไซน์แบบซ่อนเก็บได้\r\n• เสียงรอบทิศทาง 360 องศาสำหรับพีซี (ซอฟต์แวร์ Sonar) และ PS5 (เสียง 3 มิติ Tempest)\r\n', '1759731265_8.jpg', 9, '2025-10-06 06:14:25'),
(89, 'RAPOO N500-BK WIRED OPTICAL (BLACK)', '204.00', 50, '• เมาส์มีสายขนาดพอดีมือ เหมาะกับการใช้งาน\r\n• สามารถปรับ dpi ได้ 1200/1800/2400/3600\r\n• ความละเอียด 1000dpi\r\n• มีปุ่มบังคับการทำงานหน้าหลัง back/forward\r\n• สามารถใช้งานได้ Windows® และ Mac OS®\r\n• ขนาดเมาส์ 121x76x43 mm\r\n• มีสีดำและขาว บรรจุในกล่องกระดาษ', '1759731277_9.jpg', 8, '2025-10-06 06:14:37'),
(90, 'HEADSET (หูฟัง) BEYERDYNAMIC MMX 100 (GREY)', '1690.00', 85, '• Headset Response : 5 Hz - 30000 Hz\r\n• Mic Response : 5 Hz - 18000 Hz\r\n', '1759731296_9.jpg', 9, '2025-10-06 06:14:56'),
(91, 'RAPOO EV200 (BLACK)', '390.00', 46, '● MODEL NAME : EV200\r\n● CONNECTIVITY : USB CABLE/ USB TYPE 3.0\r\n● COMPATIBILITY : WINDOWS XP / VISTA/7/8/10 / MACOS\r\n● FEATURES : BUTTONS 3 / DPI 1600 /DPI SWITCH /TRACKING TECHNOLOGY OPTICAL', '1759731327_10.jpg', 8, '2025-10-06 06:15:10'),
(92, 'CREATIVE PEBBLE V2 USB TYPE-C (BLACK)', '970.00', 15, '• 8 Watt RMS\r\n• 2.0 Channel\r\n', '1759731320_43.jpg', 10, '2025-10-06 06:15:20'),
(93, 'HEADSET (หูฟัง) NUBWO DRACOS X99 (WHITE)', '4690.00', 108, '• ระบบเชื่อมแบบ USB Plug&Play\r\n• มีไฟ Lighting เพิ่มความสวยงามยิ่งขึ้น\r\n• ระบบเสียงแบบ 7.1 Virtual Surround สมจริง\r\n• แถบคาดศีรษะแบบปรับได้ Adjustable Headband\r\n', '1759731356_10.jpg', 9, '2025-10-06 06:15:56'),
(94, 'SPEAKER (ลำโพงบลูทูธ) JBL CHARGE 5 (RED)', '5990.00', 30, '30 Watt RMS for woofer, 10 Watt RMS for tweeter', '1759731378_44.jpg', 10, '2025-10-06 06:16:18'),
(95, 'SPEAKER (ลำโพงบลูทูธ) JBL PARTYBOX 710', '32990.00', 8, '• Bluetooth 5,1\r\n• 800W RMS party speaker\r\n• Customizable lightshow\r\n• IPX4 splashproof\r\n', '1759731423_45.jpg', 10, '2025-10-06 06:17:03'),
(96, 'CREATIVE MUVO PLAY (BLACK)', '12990.00', 10, '10 Watt', '1759731462_46.jpg', 10, '2025-10-06 06:17:42'),
(97, 'GRAVASTAR MARS PRO (BLACK)', '8910.00', 15, '• Mars Pro เป็นลำโพงไร้สายที่มีระบบลำโพงคู่และตัวกระจายเสียงเบสแบบพาสซีฟเพื่อสร้างเสียงรอบด้านที่ทรงพลัง ระยะเวลาการใช้งานแบตเตอรี่ 15 ชั่วโมง ไฟ RGB 6 สี และเทคโนโลยี Bluetooth 5.0 สามารถจับคู่กับลำโพง Mars Pro ตัวอื่นเพื่อสร้างประสบการณ์เสียงสเตอริโอ\r\n• มีอัลกอริธึมเสียง DSP พิเศษในตัวที่ให้เสียงเบสที่หนักแน่น เสียงกลางที่แม่นยำ และเสียงสูงที่คมชัด\r\n• สร้างขึ้นจากโลหะผสมสังกะสีทรงกลม และการออกแบบจุดวางขาตั้งในรูปแบบสามเหลี่ยมทำให้มั่นใจได้ถึงความมั่นคงและสามารถดูดซับแรงกระแทกได้\r\n• การควบคุมระดับเสียงแบบสัมผัส ปัดขึ้นเพื่อพิ่มเสียงหรือปัดลงเพื่อลดเสียง\r\n', '1759731499_47.jpg', 10, '2025-10-06 06:18:19'),
(99, 'GRAVASTAR MARS PRO (YELLOW)', '12500.00', 11, '• Mars Pro เป็นลำโพงไร้สายที่มีระบบลำโพงคู่และตัวกระจายเสียงเบสแบบพาสซีฟเพื่อสร้างเสียงรอบด้านที่ทรงพลัง ระยะเวลาการใช้งานแบตเตอรี่ 15 ชั่วโมง ', '1759731584_48.jpg', 10, '2025-10-06 06:19:44'),
(100, 'GRAVASTAR SUPERNOVA (BLACK)', '7900.00', 23, '• Rated power 25W\r\n• Max power 30W\r\n• Bluetooth 5.3\r\n• Light 3 Modes 8 Colors\r\n• Playtime around 9 hours at 60% volume\r\n', '1759731655_49.jpg', 10, '2025-10-06 06:20:55'),
(101, 'MARSHALL WOBURN III - BLACK', '26000.00', 15, 'Woburn III การันตีว่าทุกพื้นที่จะเต็มไปด้วยเสียงซิกเนเจอร์ของ Marshall ที่ชัดเจนและทรงพลัง จนบ้านทั้งหลังสะเทือน! ระบบ Dynamic Loudness ในตัวจะปรับสมดุลเสียงให้เหมาะสม ทำให้ดนตรีของคุณฟังไพเราะสมบูรณ์แบบในทุกระดับความดัง Woburn III ยังพร้อมสำหรับเทคโนโลยี Bluetooth เจเนอเรชันถัดไป รองรับฟีเจอร์ใหม่ทันทีที่เปิดตัว\r\n\r\n• การเชื่อมต่อแบบไร้สาย Bluetooth 5.2 พร้อมรองรับ Bluetooth LE Audio\r\n• เสียงสเตริโอที่กว้างขึ้นแบบใหม่\r\n• เชื่อมต่อแล้วเปิดเพลงได้ทันที\r\n• ดีไซน์ไอคอนิกที่โดดเด่นไม่เหมือนใคร\r\n• แนวทางที่ยั่งยืนยิ่งขึ้น\r\n• การเชื่อมต่อและควบคุมที่ง่ายดาย\r\n', '1759731701_50.jpg', 10, '2025-10-06 06:21:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `p_id` (`p_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `product` (`p_id`) ON DELETE SET NULL;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
