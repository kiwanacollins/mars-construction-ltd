-- MySQL dump 10.13  Distrib 9.6.0, for macos14.8 (x86_64)
--
-- Host: localhost    Database: mars_estate
-- ------------------------------------------------------
-- Server version	9.6.0

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
-- Table structure for table `about_story_tabs`
--

DROP TABLE IF EXISTS `about_story_tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about_story_tabs` (
  `id` int NOT NULL DEFAULT '1',
  `badge_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mission_text` text COLLATE utf8mb4_unicode_ci,
  `mission_check1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mission_check2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vission_text` text COLLATE utf8mb4_unicode_ci,
  `vission_check1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vission_check2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal_text` text COLLATE utf8mb4_unicode_ci,
  `goal_check1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal_check2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_story_tabs`
--

LOCK TABLES `about_story_tabs` WRITE;
/*!40000 ALTER TABLE `about_story_tabs` DISABLE KEYS */;
INSERT INTO `about_story_tabs` VALUES (1,'Client Centric Approach','Our mission is to deliver reliable, high-quality construction and property management services that turn our clients\' vision into lasting, functional homes. We combine skilled craftsmanship with honest project management from the first plan to the final handover.','Transparent pricing on every project.','Skilled teams committed to quality workmanship.','To be the most trusted name in home construction and property management, known for building communities that stand the test of time and exceed the expectations of every homeowner we serve.','Sustainable building practices on every site.','Long-term partnerships built on trust.','We aim to make quality construction and downloadable house plans accessible to more families, while growing a property management portfolio that protects and grows our clients\' investments.','On-time delivery, project after project.','Dedicated support from design through construction.');
/*!40000 ALTER TABLE `about_story_tabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'Building Tips'),(4,'Company News'),(2,'Home Design'),(3,'Property Management');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('published','draft','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (1,'Five Questions To Ask Before You Buy A House Plan','five-questions-before-you-buy-a-house-plan','A downloadable plan is only a good fit if it matches your lot, your budget, and your local building codes. Here is what to check first.','Buying a house plan online is convenient, but a plan that looks great on a listing page can turn into a costly surprise once you start pricing out the build. Before you commit to a set of drawings, walk through these five questions.\n\nFirst, does the plan match your lot\'s slope and orientation? A plan drawn for flat ground can require expensive foundation changes on a sloped site. Ask your builder to review the plan against your actual site survey before you buy.\n\nSecond, what foundation type does the plan assume? Slab, crawl space, and basement foundations all carry different costs, and swapping one for another after the fact is rarely simple.\n\nThird, does the plan meet your local building code as drawn, or will it need modifications? Codes vary by district, and a plan designed for one country\'s standards may need adjustments elsewhere.\n\nFourth, what is actually included in the price? Some plans are PDF-only, others include a full CAD file you can hand to an engineer. Know which you are buying.\n\nFifth, can you talk to the builder before you buy? A quick call to confirm buildability on your specific site can save you from an expensive mismatch later.','assets/images/resource/news-1.jpg',NULL,1,'published','2026-06-02','2026-08-29 11:31:53','2026-08-29 11:31:53'),(2,'Why We Build With A Fixed Project Timeline','why-we-build-with-a-fixed-project-timeline','Open-ended construction timelines are one of the biggest sources of client frustration. Here is how we structure ours instead.','One of the most common complaints homeowners have about construction is not knowing when the project will actually finish. We address this by agreeing on a fixed milestone schedule before groundbreaking, not after.\n\nEvery project we take on gets broken into phases: site preparation, foundation, framing, roofing, mechanical and electrical rough-in, finishes, and final walkthrough. Each phase gets a target completion date, and we review progress against that schedule every two weeks with the client.\n\nThis does not mean nothing ever changes. Weather delays and material lead times are real. But instead of an open-ended \"it will be done when it\'s done,\" clients get an updated schedule with a specific new date and a reason for the change.\n\nThe result is fewer surprises and a client who knows, at any point in the build, roughly where things stand.','assets/images/resource/news-2.jpg',NULL,1,'published','2026-05-20','2026-08-29 11:31:53','2026-08-29 11:31:53'),(3,'Open-Plan Living: What Actually Works In A Family Home','open-plan-living-what-actually-works','Open floor plans are popular, but not every open layout works well in practice. Here is what separates a good one from a noisy, chaotic one.','Open-plan living rooms and kitchens are one of the most requested features in the plans we sell, but the term covers a wide range of layouts, and not all of them work equally well.\n\nThe biggest mistake we see is removing every wall between the kitchen, dining, and living areas without giving each zone its own defined space. The result is a single undifferentiated room where cooking smells, TV noise, and dinner conversation all compete for the same air.\n\nA better approach uses subtle dividers, a kitchen island, a change in ceiling height, or a half-wall, to signal where one zone ends and another begins, without fully closing off the sightlines that make open plans feel spacious.\n\nWe also recommend keeping at least one door-closable room away from the main living area, whether that is a study, a playroom, or a guest bedroom, so the household has somewhere quiet to retreat to.','assets/images/resource/news-3.jpg',NULL,1,'published','2026-05-05','2026-08-29 11:31:53','2026-08-29 11:31:53'),(4,'A Simple Maintenance Checklist For Rental Properties','a-simple-maintenance-checklist-for-rental-properties','Most costly repairs start as small issues that go unnoticed. Here is the quarterly checklist our property management team runs on every unit.','Owners who hand their property over to a management company often ask what we actually check on a routine visit. Here is the quarterly checklist our team runs on every managed property.\n\nRoof and gutters: we look for loose tiles, blocked gutters, and any sign of water staining on the ceiling below, since roof leaks are one of the most expensive problems to leave unaddressed.\n\nPlumbing: taps, toilets, and visible pipework get checked for leaks and slow drains. A small leak under a sink can cause structural damage long before a tenant reports it.\n\nElectrical: we test that all outlets and switches work, check the distribution board for any tripped breakers, and confirm outdoor lighting is functioning.\n\nExterior: we walk the perimeter for cracks in the foundation, peeling paint, and any pest activity around the base of the building.\n\nAppliances: for furnished units, we run every major appliance to catch issues before they become a tenant complaint.\n\nCatching small issues on this schedule is almost always cheaper than an emergency repair call six months later.','assets/images/resource/news-4.jpg',NULL,1,'published','2026-04-18','2026-08-29 11:31:53','2026-08-29 11:31:53'),(5,'Choosing A Roof Type For Uganda\'s Climate','choosing-a-roof-type-for-ugandas-climate','Roof pitch and material affect more than looks — they affect how well your home handles heavy rain and heat. Here is what to consider.','Roof choice is one of the decisions homeowners spend the least time on, even though it has a direct effect on how comfortable and durable the finished home will be.\n\nIn Uganda\'s climate, a steeper pitch, generally 6:12 or higher, sheds heavy rain faster and reduces the risk of pooling and leaks during the wet season. A gable roof with a steep pitch is a reliable default for most residential plans.\n\nMaterial matters too. Clay tiles handle heat well and last decades, but add weight that the roof structure needs to be engineered for. Metal roofing sheds rain quickly and is lighter, but can transfer more heat into the roof cavity without proper insulation.\n\nWe generally recommend pairing any roof choice with adequate roof cavity ventilation, which does more to keep upstairs rooms comfortable than most homeowners expect.','assets/images/resource/news-5.jpg',NULL,1,'published','2026-03-30','2026-08-29 11:31:53','2026-08-29 11:31:53'),(6,'Mars Construction Now Offers CAD Files On Every Plan','mars-construction-now-offers-cad-files-on-every-plan','Architects and engineers can now purchase editable CAD files alongside the standard PDF plan set for any listing on our site.','Starting this quarter, every house plan on our site is available as an editable CAD file, in addition to the standard PDF set and printed copies.\n\nThis has been one of our most requested features from clients working with their own architect or structural engineer, who needed to modify a plan\'s dimensions, swap a foundation type, or adjust a layout to fit local code before construction could start.\n\nThe CAD file option appears as a pricing tier on every plan page, alongside the standard PDF download and the five-set printed option. Pricing reflects the extra licensing and file preparation involved, and is clearly listed on each plan before checkout.','assets/images/resource/news-6.jpg',NULL,1,'published','2026-03-10','2026-08-29 11:31:53','2026-08-29 11:31:53');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_logos`
--

DROP TABLE IF EXISTS `client_logos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_logos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_logos`
--

LOCK TABLES `client_logos` WRITE;
/*!40000 ALTER TABLE `client_logos` DISABLE KEYS */;
INSERT INTO `client_logos` VALUES (1,'assets/images/clients/1.png',NULL,0),(2,'assets/images/clients/2.png',NULL,1),(3,'assets/images/clients/3.png',NULL,2),(4,'assets/images/clients/4.png',NULL,3),(5,'assets/images/clients/5.png',NULL,4),(6,'assets/images/clients/1.png',NULL,0),(7,'assets/images/clients/2.png',NULL,1),(8,'assets/images/clients/3.png',NULL,2),(9,'assets/images/clients/4.png',NULL,3),(10,'assets/images/clients/5.png',NULL,4);
/*!40000 ALTER TABLE `client_logos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `construction_faqs`
--

DROP TABLE IF EXISTS `construction_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `construction_faqs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `construction_faqs`
--

LOCK TABLES `construction_faqs` WRITE;
/*!40000 ALTER TABLE `construction_faqs` DISABLE KEYS */;
INSERT INTO `construction_faqs` VALUES (1,'Can you build from a plan I purchase on this site?','Yes. Once you purchase a plan and its file set, our team can quote and manage the full build for you, or work alongside a contractor of your choice.',0),(2,'Do you handle permits and inspections?','Yes, we manage the permitting process and coordinate all required inspections throughout the build.',1),(3,'How long does a typical build take?','Timelines vary by plan size and site conditions, but most single-family builds run between 6 and 10 months from groundbreaking to move-in.',2),(4,'Can I make changes to a plan during construction?','Minor modifications are usually possible. We\'ll review any requested changes against the plan and site conditions before construction begins.',3),(5,'Can you build from a plan I purchase on this site?','Yes. Once you purchase a plan and its file set, our team can quote and manage the full build for you, or work alongside a contractor of your choice.',0),(6,'Do you handle permits and inspections?','Yes, we manage the permitting process and coordinate all required inspections throughout the build.',1),(7,'How long does a typical build take?','Timelines vary by plan size and site conditions, but most single-family builds run between 6 and 10 months from groundbreaking to move-in.',2),(8,'Can I make changes to a plan during construction?','Minor modifications are usually possible. We\'ll review any requested changes against the plan and site conditions before construction begins.',3);
/*!40000 ALTER TABLE `construction_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `construction_handles`
--

DROP TABLE IF EXISTS `construction_handles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `construction_handles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `construction_handles`
--

LOCK TABLES `construction_handles` WRITE;
/*!40000 ALTER TABLE `construction_handles` DISABLE KEYS */;
INSERT INTO `construction_handles` VALUES (1,'Site preparation and foundation work',NULL,0),(2,'Framing and structural construction',NULL,1),(3,'Electrical, plumbing, and HVAC installation',NULL,2),(4,'Interior and exterior finishing',NULL,3),(5,'Permits, inspections, and code compliance',NULL,4),(6,'On-site project management from start to finish',NULL,5),(7,'Site preparation and foundation work',NULL,0),(8,'Framing and structural construction',NULL,1),(9,'Electrical, plumbing, and HVAC installation',NULL,2),(10,'Interior and exterior finishing',NULL,3),(11,'Permits, inspections, and code compliance',NULL,4),(12,'On-site project management from start to finish',NULL,5);
/*!40000 ALTER TABLE `construction_handles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `construction_stats`
--

DROP TABLE IF EXISTS `construction_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `construction_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` int NOT NULL DEFAULT '0',
  `suffix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '%',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `construction_stats`
--

LOCK TABLES `construction_stats` WRITE;
/*!40000 ALTER TABLE `construction_stats` DISABLE KEYS */;
INSERT INTO `construction_stats` VALUES (1,92,'%','On-Time Delivery',0),(2,97,'%','Client Satisfaction',1),(3,88,'%','On-Budget Projects',2),(4,92,'%','On-Time Delivery',0),(5,97,'%','Client Satisfaction',1),(6,88,'%','On-Budget Projects',2);
/*!40000 ALTER TABLE `construction_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_faqs`
--

DROP TABLE IF EXISTS `contact_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_faqs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_faqs`
--

LOCK TABLES `contact_faqs` WRITE;
/*!40000 ALTER TABLE `contact_faqs` DISABLE KEYS */;
INSERT INTO `contact_faqs` VALUES (1,'How do I purchase and download a house plan?','Browse our plan catalog, choose the design that fits your needs, and complete checkout. Once payment is confirmed, you\'ll get access to download the full file set right away.',0),(2,'What factors should I consider when choosing a house plan?','Think about lot size and orientation, local building codes, your budget, family size, and how much of the design you plan to customize before construction begins.',1),(3,'How much should I budget for building a home?','Costs vary by plan size, finishes, and site conditions. Beyond the build itself, budget for permits, site preparation, and a contingency for unexpected conditions once ground breaks.',2),(4,'Can Mars Construction build the plan I purchase?','Yes. Once you purchase a plan and its file set, our construction team can quote and manage the full build for you, or work alongside a contractor of your choice.',3),(5,'Can I customize a plan before construction starts?','Minor modifications to a purchased plan are usually possible. Share the changes you have in mind and we\'ll review them against the design and your site conditions.',4),(6,'What is the difference between a Realtor® & real estate?','A Realtor is a licensed agent who is also a member of the National Association of Realtors and bound by its code of ethics, while \"real estate\" broadly refers to the property and industry itself.',0),(7,'What factors should I consider when buying a home?','Location, budget, lot size, resale value, proximity to schools and work, and the total cost of building or buying — including permits, financing, and closing costs — are all worth weighing before you commit.',1),(8,'How much should I budget for purchasing a home?','Beyond the purchase or build price, plan for closing costs (roughly 2% to 5%), inspections, permits, and a contingency fund for unexpected site conditions.',2),(9,'What is a home appraisal, and why is it important?','An appraisal is an independent estimate of a property\'s market value, usually required by lenders to confirm the loan amount matches what the home is actually worth.',3),(10,'What is a home inspection, and should I get one?','A home inspection is a detailed check of a property\'s condition — structure, systems, and safety. It\'s strongly recommended before buying or before final handover on a new build.',4);
/*!40000 ALTER TABLE `contact_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_menu_items`
--

DROP TABLE IF EXISTS `footer_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `col_group` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_menu_items`
--

LOCK TABLES `footer_menu_items` WRITE;
/*!40000 ALTER TABLE `footer_menu_items` DISABLE KEYS */;
INSERT INTO `footer_menu_items` VALUES (1,'Terms of Use','#',0,'2026-08-28 15:28:16','bottom'),(2,'Privacy Policy','#',1,'2026-08-28 15:28:16','bottom'),(3,'Our Services','services.php',2,'2026-08-28 15:28:16','company'),(4,'Contact','contact.php',3,'2026-08-28 15:28:16','company'),(5,'FAQS','faq.php',4,'2026-08-28 15:28:16','company'),(6,'Kampala','plans.php?category=Modern+Villas',0,'2026-08-29 11:31:53','cities'),(7,'Entebbe','plans.php?category=Villas',1,'2026-08-29 11:31:53','cities'),(8,'Jinja','plans.php?category=Residential',2,'2026-08-29 11:31:53','cities'),(9,'Mbarara','plans.php?category=Country+Homes',3,'2026-08-29 11:31:53','cities'),(10,'Gulu','plans.php?category=Apartments',4,'2026-08-29 11:31:53','cities');
/*!40000 ALTER TABLE `footer_menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_slides`
--

DROP TABLE IF EXISTS `hero_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_slides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subheading` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `button_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `button_style` enum('solid','outline') COLLATE utf8mb4_unicode_ci DEFAULT 'solid',
  `bg_type` enum('image','video') COLLATE utf8mb4_unicode_ci DEFAULT 'image',
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button2_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button2_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_slides`
--

LOCK TABLES `hero_slides` WRITE;
/*!40000 ALTER TABLE `hero_slides` DISABLE KEYS */;
INSERT INTO `hero_slides` VALUES (1,'Let\'s Unlock Dream Home here','real estate','From downloadable house plans to full-service construction, Mars Construction turns your vision into a home you are proud to walk into.',NULL,NULL,'assets/images/main-slider/2.jpg',0,'2026-08-28 15:28:16','solid','image',NULL,NULL,NULL),(2,'Build With A Team You Can Trust','construction','Our in-house crews manage every stage of the build, from site preparation to the final walkthrough, so you deal with one accountable partner.','Our Projects','construction.php','assets/images/main-slider/1.jpg',1,'2026-08-29 11:31:53','solid','image','','Get A Quote','contact.php'),(3,'House Plans Ready To Build','house plans','Browse villas, apartments, and country homes designed for real families, then customize a plan to fit your site and budget.','Browse Plans','plans.php','assets/images/main-slider/2.jpg',2,'2026-08-29 11:31:53','solid','image','','','');
/*!40000 ALTER TABLE `hero_slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_service_cards`
--

DROP TABLE IF EXISTS `home_service_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_service_cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icon_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_service_cards`
--

LOCK TABLES `home_service_cards` WRITE;
/*!40000 ALTER TABLE `home_service_cards` DISABLE KEYS */;
INSERT INTO `home_service_cards` VALUES (1,'flaticon-building','Building Construction','From foundation to final walkthrough, our in-house teams manage every stage of your build.','construction.php',0,'2026-08-28 15:28:16',NULL),(2,'flaticon-interior-design','Interior Designing','Interior finishes and layouts tailored to how you actually live in the space.','construction.php',1,'2026-08-28 15:28:16',NULL),(3,'flaticon-building-1','Property Management','Once your home is built, we stay on to handle upkeep, repairs, and maintenance.','property-management.php',2,'2026-08-28 15:28:16',NULL);
/*!40000 ALTER TABLE `home_service_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `parent_id` int DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,'Home','index.php',NULL,0),(2,'House Plans','plans.php',NULL,1),(3,'Construction','construction.php',NULL,2),(4,'Property Management','property-management.php',NULL,3),(5,'Projects','construction.php',NULL,4),(6,'About Us','about.php',NULL,5),(7,'Blog','blog.php',NULL,6),(8,'Contact','contact.php',NULL,7);
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `property_id` int NOT NULL,
  `plan_title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addon_names` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,54,'Meadowlark Farmhouse','',950.00,1);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('new','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('unpaid','paid','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesapal_tracking_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesapal_merchant_ref` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'kiwana collins','collinzcalson@gmail.com','0709411366',950.00,'new',0,'2026-08-30 10:15:34','unpaid',NULL,NULL,NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subheading` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check1` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check2` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_section_unique` (`page_key`,`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` VALUES (1,'contact','intro','Let\'s Talk About Your Project','Contact Us','Have a question about a plan, a build, or an existing property? Send us a message and our team will get back to you within one business day.',NULL,'2026-08-29 11:31:53','2026-08-29 11:31:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'contact','help','We\'re Here To Help','Got Questions?','From choosing the right plan to scheduling a build, our team is on hand to walk you through every step. Reach out any time.',NULL,'2026-08-29 11:31:53','2026-08-29 11:31:53',NULL,NULL,NULL,NULL,NULL,'Live Chat','contact.php');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_title_backgrounds`
--

DROP TABLE IF EXISTS `page_title_backgrounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_title_backgrounds` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_key` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_title_backgrounds`
--

LOCK TABLES `page_title_backgrounds` WRITE;
/*!40000 ALTER TABLE `page_title_backgrounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_title_backgrounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_addons`
--

DROP TABLE IF EXISTS `plan_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_addons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_type` enum('flat','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'flat',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `plan_addons_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_addons`
--

LOCK TABLES `plan_addons` WRITE;
/*!40000 ALTER TABLE `plan_addons` DISABLE KEYS */;
INSERT INTO `plan_addons` VALUES (1,1,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(2,1,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(3,1,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(4,2,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(5,2,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(6,2,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(7,3,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(8,3,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(9,3,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(10,4,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(11,4,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(12,4,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(13,5,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(14,5,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(15,5,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(16,6,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(17,6,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(18,6,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(19,7,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(20,7,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(21,7,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(22,8,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(23,8,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(24,8,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(25,17,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(26,17,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(27,17,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(28,18,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(29,18,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(30,18,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(31,19,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(32,19,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(33,19,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(34,20,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(35,20,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(36,20,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(37,21,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(38,21,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(39,21,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(40,22,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(41,22,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(42,22,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(43,23,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(44,23,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(45,23,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(46,24,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(47,24,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(48,24,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(49,25,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(50,25,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(51,25,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53'),(52,26,'Material List','Itemized list of materials and estimated quantities for this plan.',120.00,'flat',0,'2026-08-29 11:31:53'),(53,26,'Mirror Reverse','Flip the plan left-to-right to fit your lot orientation.',80.00,'flat',1,'2026-08-29 11:31:53'),(54,26,'Local Code Modifications','Adjust the plan to meet Uganda National Building Standards where needed.',8.00,'percent',2,'2026-08-29 11:31:53');
/*!40000 ALTER TABLE `plan_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_categories`
--

DROP TABLE IF EXISTS `plan_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_categories`
--

LOCK TABLES `plan_categories` WRITE;
/*!40000 ALTER TABLE `plan_categories` DISABLE KEYS */;
INSERT INTO `plan_categories` VALUES (1,'Villas','villas','assets/images/resource/property-2.jpg',1),(2,'Apartments','apartments','assets/images/resource/property-3.jpg',2),(3,'Residential','residential','assets/images/resource/property-4.jpg',3),(4,'Hotels','hotels','assets/images/resource/property-5.jpg',4),(5,'Country Homes','country-homes','assets/images/resource/property-6.jpg',5),(6,'Modern Villas','modern-villas','assets/images/resource/property-7.jpg',6);
/*!40000 ALTER TABLE `plan_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_pricing`
--

DROP TABLE IF EXISTS `plan_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_pricing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `tier_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `plan_pricing_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_pricing`
--

LOCK TABLES `plan_pricing` WRITE;
/*!40000 ALTER TABLE `plan_pricing` DISABLE KEYS */;
INSERT INTO `plan_pricing` VALUES (1,1,'PDF Set',1250.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(2,1,'CAD File',2000.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(3,1,'5 Printed Sets',1562.50,'Five physical copies of the construction drawings, shipped to your site or office.',2),(4,2,'PDF Set',980.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(5,2,'CAD File',1568.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(6,2,'5 Printed Sets',1225.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(7,3,'PDF Set',1580.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(8,3,'CAD File',2528.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(9,3,'5 Printed Sets',1975.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(10,4,'PDF Set',1120.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(11,4,'CAD File',1792.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(12,4,'5 Printed Sets',1400.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(13,5,'PDF Set',875.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(14,5,'CAD File',1400.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(15,5,'5 Printed Sets',1093.75,'Five physical copies of the construction drawings, shipped to your site or office.',2),(16,6,'PDF Set',1750.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(17,6,'CAD File',2800.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(18,6,'5 Printed Sets',2187.50,'Five physical copies of the construction drawings, shipped to your site or office.',2),(19,7,'PDF Set',1040.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(20,7,'CAD File',1664.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(21,7,'5 Printed Sets',1300.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(22,8,'PDF Set',1195.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(23,8,'CAD File',1912.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(24,8,'5 Printed Sets',1493.75,'Five physical copies of the construction drawings, shipped to your site or office.',2),(25,17,'PDF Set',890.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(26,17,'CAD File',1424.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(27,17,'5 Printed Sets',1112.50,'Five physical copies of the construction drawings, shipped to your site or office.',2),(28,18,'PDF Set',1050.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(29,18,'CAD File',1680.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(30,18,'5 Printed Sets',1312.50,'Five physical copies of the construction drawings, shipped to your site or office.',2),(31,19,'PDF Set',420.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(32,19,'CAD File',672.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(33,19,'5 Printed Sets',525.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(34,20,'PDF Set',560.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(35,20,'CAD File',896.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(36,20,'5 Printed Sets',700.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(37,21,'PDF Set',780.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(38,21,'CAD File',1248.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(39,21,'5 Printed Sets',975.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(40,22,'PDF Set',940.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(41,22,'CAD File',1504.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(42,22,'5 Printed Sets',1175.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(43,23,'PDF Set',8200.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(44,23,'CAD File',13120.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(45,23,'5 Printed Sets',10250.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(46,24,'PDF Set',5400.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(47,24,'CAD File',8640.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(48,24,'5 Printed Sets',6750.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(49,25,'PDF Set',1180.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(50,25,'CAD File',1888.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(51,25,'5 Printed Sets',1475.00,'Five physical copies of the construction drawings, shipped to your site or office.',2),(52,26,'PDF Set',1340.00,'Digital PDF plan set delivered by email — print as many copies as you need.',0),(53,26,'CAD File',2144.00,'Editable CAD file for architects and engineers who need to modify the plan.',1),(54,26,'5 Printed Sets',1675.00,'Five physical copies of the construction drawings, shipped to your site or office.',2);
/*!40000 ALTER TABLE `plan_pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_faqs`
--

DROP TABLE IF EXISTS `pm_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pm_faqs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_faqs`
--

LOCK TABLES `pm_faqs` WRITE;
/*!40000 ALTER TABLE `pm_faqs` DISABLE KEYS */;
INSERT INTO `pm_faqs` VALUES (1,'Do I need to have built with you to use property management?','No. While it\'s a natural fit for homes we\'ve built, we also manage properties for owners who built or bought elsewhere.',0),(2,'Can you manage a rental property for me?','Yes, we coordinate with tenants on maintenance requests and keep the property in good condition between move-ins and move-outs.',1),(3,'How quickly do you respond to repair requests?','Routine requests are typically scheduled within a few business days; urgent issues get a same-day response.',2),(4,'Is property management available outside your build service area?','Coverage depends on location — get in touch and we\'ll confirm whether we service your property\'s area.',3),(5,'Do I need to have built with you to use property management?','No. While it\'s a natural fit for homes we\'ve built, we also manage properties for owners who built or bought elsewhere.',0),(6,'Can you manage a rental property for me?','Yes, we coordinate with tenants on maintenance requests and keep the property in good condition between move-ins and move-outs.',1),(7,'How quickly do you respond to repair requests?','Routine requests are typically scheduled within a few business days; urgent issues get a same-day response.',2),(8,'Is property management available outside your build service area?','Coverage depends on location — get in touch and we\'ll confirm whether we service your property\'s area.',3);
/*!40000 ALTER TABLE `pm_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_handles`
--

DROP TABLE IF EXISTS `pm_handles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pm_handles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_handles`
--

LOCK TABLES `pm_handles` WRITE;
/*!40000 ALTER TABLE `pm_handles` DISABLE KEYS */;
INSERT INTO `pm_handles` VALUES (1,'Routine maintenance and seasonal upkeep',NULL,0),(2,'Repair coordination and vendor management',NULL,1),(3,'Property inspections and condition reports',NULL,2),(4,'Tenant coordination for rental properties',NULL,3),(5,'Emergency call-out response',NULL,4),(6,'Warranty follow-up on newly built homes',NULL,5),(7,'Routine maintenance and seasonal upkeep',NULL,0),(8,'Repair coordination and vendor management',NULL,1),(9,'Property inspections and condition reports',NULL,2),(10,'Tenant coordination for rental properties',NULL,3),(11,'Emergency call-out response',NULL,4),(12,'Warranty follow-up on newly built homes',NULL,5);
/*!40000 ALTER TABLE `pm_handles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_stats`
--

DROP TABLE IF EXISTS `pm_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pm_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` int NOT NULL DEFAULT '0',
  `suffix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '%',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_stats`
--

LOCK TABLES `pm_stats` WRITE;
/*!40000 ALTER TABLE `pm_stats` DISABLE KEYS */;
INSERT INTO `pm_stats` VALUES (1,95,'%','Response Rate',0),(2,90,'%','Owner Retention',1),(3,93,'%','Issues Resolved First Visit',2),(4,95,'%','Response Rate',0),(5,90,'%','Owner Retention',1),(6,93,'%','Issues Resolved First Visit',2);
/*!40000 ALTER TABLE `pm_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_files`
--

DROP TABLE IF EXISTS `project_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_files_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_files`
--

LOCK TABLES `project_files` WRITE;
/*!40000 ALTER TABLE `project_files` DISABLE KEYS */;
INSERT INTO `project_files` VALUES (1,1,'uploads/projects/project-1-0-property-1.jpg','property-1.jpg',1,0,'2026-08-29 11:31:53'),(2,1,'uploads/projects/project-1-1-property-2.jpg','property-2.jpg',0,1,'2026-08-29 11:31:53'),(3,2,'uploads/projects/project-2-0-property-3.jpg','property-3.jpg',1,0,'2026-08-29 11:31:53'),(4,2,'uploads/projects/project-2-1-property-4.jpg','property-4.jpg',0,1,'2026-08-29 11:31:53'),(5,3,'uploads/projects/project-3-0-property-5.jpg','property-5.jpg',1,0,'2026-08-29 11:31:53'),(6,3,'uploads/projects/project-3-1-property-6.jpg','property-6.jpg',0,1,'2026-08-29 11:31:53'),(7,4,'uploads/projects/project-4-0-property-7.jpg','property-7.jpg',1,0,'2026-08-29 11:31:53'),(8,4,'uploads/projects/project-4-1-property-8.jpg','property-8.jpg',0,1,'2026-08-29 11:31:53'),(9,5,'uploads/projects/project-5-0-property-9.jpg','property-9.jpg',1,0,'2026-08-29 11:31:53'),(10,5,'uploads/projects/project-5-1-property-1.jpg','property-1.jpg',0,1,'2026-08-29 11:31:53'),(11,6,'uploads/projects/project-6-0-property-2.jpg','property-2.jpg',1,0,'2026-08-29 11:31:53'),(12,6,'uploads/projects/project-6-1-property-3.jpg','property-3.jpg',0,1,'2026-08-29 11:31:53');
/*!40000 ALTER TABLE `project_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `story` longtext COLLATE utf8mb4_unicode_ci,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'The Kironde Family Residence','the-kironde-family-residence','Residential','Muyenga, Kampala','The Kironde Family','2025-11-14','The Kironde family came to us with a steep, view-facing lot in Muyenga and a plan drawn for flat ground. We reworked the foundation into a stepped slab to follow the natural grade, which let us keep the original floor plan while cutting excavation costs by nearly a third.\nConstruction ran fourteen months from groundbreaking to handover, with the family visiting the site every second Saturday for a walkthrough. The finished home keeps the lake view from the main living room and both upstairs bedrooms.',1,'2026-08-29 11:31:53','2026-08-29 11:31:53'),(2,'Birchwood Apartments — Phase One','birchwood-apartments-phase-one','Apartments','Ntinda, Kampala','Birchwood Developments Ltd','2025-08-02','Phase one of the Birchwood development delivered twelve apartment units across three blocks, built from our repeatable Birchwood Type A and Type B plans. Working from a standardized plan let us run three blocks in parallel with the same crews and material orders, shortening the overall build timeline.\nAll twelve units were fully leased within six weeks of handover.',1,'2026-08-29 11:31:53','2026-08-29 11:31:53'),(3,'Highland Boutique Hotel — Entebbe','highland-boutique-hotel-entebbe','Hotels','Entebbe','Highland Hospitality Group','2025-05-20','An 18-key boutique hotel built on a hillside site overlooking Lake Victoria. The build required extensive retaining wall work before the main structure could start, which we sequenced alongside utility trenching to keep the schedule on track.\nThe hotel opened for bookings ten months after groundbreaking, ahead of the client\'s original eighteen-month target.',1,'2026-08-29 11:31:53','2026-08-29 11:31:53'),(4,'Orchard Hill Country Home','orchard-hill-country-home-project','Country Homes','Mukono District','Mr. and Mrs. Ssemwogerere','2025-02-10','Built on a five-acre plot outside Mukono, this country home plan was adapted with a larger wraparound porch and an attached workshop for the client\'s woodworking hobby. Site access was limited to a narrow farm road, so material deliveries were scheduled in smaller batches over a longer period.\nThe client now uses the detached workshop for a small furniture-making business.',0,'2026-08-29 11:31:53','2026-08-29 11:31:53'),(5,'Casa Horizon Build — Kansanga','casa-horizon-build-kansanga','Modern Villas','Kansanga, Kampala','Private Client','2024-12-05','A straightforward build of our Casa Horizon plan on a standard suburban lot, completed in ten months with no major site complications. This project is a good baseline for how long a similar villa build typically takes under normal conditions.',0,'2026-08-29 11:31:53','2026-08-29 11:31:53'),(6,'Stonebridge Farmhouse Renovation','stonebridge-farmhouse-renovation','Country Homes','Wakiso District','The Nakato Family','2024-09-18','Although Stonebridge Farmhouse is normally a new-build plan, this project adapted it into an addition onto an existing 1990s farmhouse. We matched the new board-and-batten exterior to the original structure and tied the new bonus room into the existing roofline.\nThe result reads as a single cohesive home rather than a visible addition.',0,'2026-08-29 11:31:53','2026-08-29 11:31:53');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `plan_number` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bedrooms` int DEFAULT '0',
  `bathrooms` decimal(3,1) DEFAULT '0.0',
  `stories` int DEFAULT '1',
  `garage_bays` int DEFAULT '0',
  `area_sqft` decimal(10,2) DEFAULT '0.00',
  `width_ft` decimal(6,2) DEFAULT NULL,
  `depth_ft` decimal(6,2) DEFAULT NULL,
  `foundation_type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roof_type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roof_pitch` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exterior_material` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `shares` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `features` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array of amenity strings',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES (1,'The Meridian Villa','the-meridian-villa',1250.00,'MV-101',4,3.5,2,2,3200.00,68.00,58.00,'Slab','Flat Roof',NULL,'Stucco & Glass','Modern Villas',NULL,1,4894,221,69,'A sleek four-bedroom villa built around clean lines, expansive glazing, and an open-plan living core that flows straight onto a covered outdoor terrace.','Open-concept living\nFloor-to-ceiling windows\nCovered outdoor terrace\nDouble-height entry foyer','2026-08-28 15:28:32','2026-08-30 11:18:20'),(2,'Casa Horizon','casa-horizon',980.00,'MV-102',3,2.5,1,2,2450.00,62.00,54.00,'Slab','Low-Slope',NULL,'Stucco & Wood Cladding','Modern Villas',NULL,0,6512,387,60,'Single-story modern villa with a linear layout, wide overhangs for passive shading, and a private central courtyard.','Private central courtyard\nPassive solar shading\nOpen kitchen island\nWalk-in pantry','2026-08-28 15:28:32','2026-08-30 11:07:42'),(3,'Villa Solstice','villa-solstice',1580.00,'MV-103',5,4.5,2,3,4100.00,78.00,64.00,'Basement','Flat Roof',NULL,'Concrete & Glass','Modern Villas',NULL,1,6933,413,40,'A statement five-bedroom villa with cantilevered upper volumes, a rooftop terrace, and a three-bay garage for larger households.','Cantilevered second floor\nRooftop terrace\nHome theater room\nThree-bay garage','2026-08-28 15:28:32','2026-08-30 11:07:42'),(4,'The Cascade Villa','the-cascade-villa',1120.00,'MV-104',4,3.0,2,2,3050.00,66.00,56.00,'Slab','Flat Roof',NULL,'Stone & Stucco','Modern Villas',NULL,0,4353,199,30,'Split-level modern villa designed around a stepped facade and a landscaped side courtyard that brings light into every room.','Stepped split-level design\nLandscaped side courtyard\nDouble-height living room\nBuilt-in outdoor kitchen','2026-08-28 15:28:32','2026-08-30 11:07:42'),(5,'Villa Aurea','villa-aurea',875.00,'MV-105',3,2.0,1,2,2100.00,58.00,50.00,'Slab','Low-Slope',NULL,'Stucco','Modern Villas',NULL,0,3273,104,45,'Compact single-story villa plan optimized for narrow lots, with a private rear patio and efficient open living space.','Narrow-lot optimized\nPrivate rear patio\nEfficient open floor plan\nMudroom with storage','2026-08-28 15:28:32','2026-08-30 11:07:42'),(6,'The Monarch Villa','the-monarch-villa',1750.00,'MV-106',5,5.0,3,3,4650.00,82.00,70.00,'Basement','Flat Roof',NULL,'Glass & Concrete','Modern Villas',NULL,1,7894,510,85,'Three-story flagship villa with a private elevator, primary suite retreat, and panoramic glazing across all levels.','Private elevator\nPrimary suite retreat\nPanoramic glazing\nRooftop lounge with kitchenette','2026-08-28 15:28:32','2026-08-30 11:07:42'),(7,'Casa Linea','casa-linea',1040.00,'MV-107',4,3.0,2,2,2800.00,64.00,55.00,'Slab','Low-Slope',NULL,'Wood Cladding & Stucco','Modern Villas',NULL,0,5315,296,16,'A linear modern villa layout emphasizing indoor-outdoor connection, with sliding glass walls opening onto a covered patio.','Sliding glass wall system\nCovered patio living\nKitchen with waterfall island\nDedicated home office','2026-08-28 15:28:32','2026-08-30 11:17:52'),(8,'Villa Verde','villa-verde',1195.00,'MV-108',4,3.5,2,2,3150.00,67.00,57.00,'Slab','Flat Roof',NULL,'Stucco & Stone Accents','Modern Villas',NULL,0,4234,202,31,'Eco-conscious modern villa plan with green roof sections, rainwater harvesting provisions, and generous natural cross-ventilation.','Green roof sections\nRainwater harvesting ready\nCross-ventilation design\nSolar panel ready roof','2026-08-28 15:28:32','2026-08-30 11:07:42'),(17,'Willow Court Villa','willow-court-villa',890.00,'MC-V101',4,3.0,2,2,2650.00,58.00,46.00,'Slab on Grade','Gable','6:12','Stone and render','Villas','',1,4418,234,72,'Willow Court Villa is a family-sized home built around a wide central living space that opens onto a covered veranda. The layout separates the bedrooms from the living areas so the house stays quiet at night and social during the day.\nA detached double garage and a compact home office round out the plan for families who need room to work from home.','[\"Covered veranda\",\"Home office nook\",\"Walk-in pantry\",\"Double garage\",\"Guest ensuite\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(18,'Kerrigan Villa','kerrigan-villa',1050.00,'MC-V102',5,4.0,2,2,3400.00,64.00,52.00,'Raised Slab','Hip','5:12','Brick and timber cladding','Villas','',0,6038,399,63,'A five-bedroom villa designed for multi-generational living, with a self-contained guest wing on the ground floor and four bedrooms upstairs. Wide corridors and a shared family lounge keep the upper floor connected without sacrificing privacy.','[\"Ground-floor guest wing\",\"Upstairs family lounge\",\"Covered outdoor kitchen\",\"Study\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(19,'Birchwood Apartments — Type A','birchwood-apartments-type-a',420.00,'MC-A201',2,2.0,1,1,1150.00,36.00,32.00,'Slab on Grade','Flat','2:12','Render and aluminium cladding','Apartments','',1,6459,425,43,'A two-bedroom apartment unit designed for compact urban lots, built to repeat across a block. Each unit gets its own private balcony and secure parking bay, with a shared entry stair serving up to four units per floor.','[\"Private balcony\",\"Secure parking bay\",\"Open-plan kitchen\",\"Built-in storage\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(20,'Birchwood Apartments — Type B','birchwood-apartments-type-b',560.00,'MC-A202',3,2.5,1,1,1480.00,40.00,36.00,'Slab on Grade','Flat','2:12','Render and aluminium cladding','Apartments','',0,3879,211,33,'The larger companion unit in the Birchwood block, with a third bedroom suited to a home office or nursery. Kitchen and living areas open onto a wraparound balcony that catches the afternoon light.','[\"Wraparound balcony\",\"Third bedroom \\/ office\",\"Ensuite main bedroom\",\"Secure parking bay\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(21,'The Fernbrook Residence','the-fernbrook-residence',780.00,'MC-R301',3,2.0,1,2,1950.00,52.00,40.00,'Slab on Grade','Gable','6:12','Brick veneer','Residential','',1,4299,237,73,'A single-story residential plan built for everyday family life, with bedrooms grouped along a quiet wing away from the open-plan kitchen and living room. A covered patio at the back extends the living space outdoors.','[\"Covered back patio\",\"Open-plan kitchen and living\",\"Laundry with outdoor access\",\"Double garage\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(22,'Meadowridge Residence','meadowridge-residence',940.00,'MC-R302',4,2.5,1,2,2300.00,56.00,44.00,'Slab on Grade','Hip','5:12','Render and stone accents','Residential','',0,5920,402,63,'A four-bedroom single-story home with a central kitchen island that anchors the living area. A separate media room gives the family a second gathering space away from the main living room.','[\"Kitchen island\",\"Separate media room\",\"Walk-in closets\",\"Covered entry portico\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(23,'Highland Boutique Hotel','highland-boutique-hotel',8200.00,'MC-H401',18,18.0,3,6,15600.00,120.00,90.00,'Reinforced Slab','Flat with parapet','2:12','Render, stone base, glass curtain wall','Hotels','',1,6340,428,44,'An 18-key boutique hotel plan built around a central courtyard and reception lobby, with guest rooms arranged over three floors for privacy and views. Ground floor includes a restaurant, bar, and back-of-house kitchen.','[\"18 guest rooms\",\"Ground-floor restaurant and bar\",\"Central courtyard\",\"Rooftop terrace\",\"Back-of-house kitchen and laundry\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(24,'Lakeside Inn','lakeside-inn',5400.00,'MC-H402',12,12.0,2,4,9800.00,100.00,70.00,'Reinforced Slab','Gable','6:12','Timber cladding and stone','Hotels','',0,3761,214,34,'A 12-room inn plan designed for a waterfront or lakeside site, with guest rooms opening onto a shared veranda that faces the water. A small conference room supports retreats and events.','[\"12 guest rooms\",\"Waterfront veranda\",\"Small conference room\",\"On-site staff quarters\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(25,'Orchard Hill Country Home','orchard-hill-country-home',1180.00,'MC-C501',4,3.0,1,2,2750.00,60.00,48.00,'Crawl Space','Gable','8:12','Timber siding and stone','Country Homes','',1,4181,240,74,'A country home plan built for a rural or acreage site, with a wraparound porch, mudroom entry, and a great room anchored by a stone fireplace. Bedrooms sit in a separate wing for quiet away from the main living space.','[\"Wraparound porch\",\"Stone fireplace\",\"Mudroom entry\",\"Detached workshop-ready garage\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(26,'Stonebridge Farmhouse','stonebridge-farmhouse',1340.00,'MC-C502',5,3.5,2,3,3600.00,68.00,50.00,'Crawl Space','Gable','8:12','Board-and-batten siding','Country Homes','',0,5801,405,64,'A modern farmhouse plan with a wide front porch, a working mudroom off the garage, and a bonus room upstairs that adapts to a playroom, gym, or extra guest suite as the family\'s needs change.','[\"Wide front porch\",\"Working mudroom\",\"Bonus room upstairs\",\"Three-bay garage\"]','2026-08-29 11:31:53','2026-08-30 11:07:42'),(27,'Cypress Grove Villa','cypress-grove-villa',1240.00,'MC-V103',5,4.5,2,3,3850.00,74.00,60.00,'Slab','Flat Roof','2:12','Stone & Glass','Villas',NULL,1,6223,431,44,'A refined villa wrapped in warm stone and glass, built around a sunken courtyard that draws light into every living space.','Sunken courtyard\nWalk-in wine cellar\nCovered outdoor kitchen\nSmart home wiring','2026-08-30 09:56:39','2026-08-30 11:18:32'),(28,'Villa Meridiana','villa-meridiana',1095.00,'MC-V104',4,3.5,2,2,3200.00,70.00,56.00,'Slab','Low-Slope','3:12','Stucco & Timber','Villas',NULL,0,3642,217,34,'A Mediterranean-inspired villa with arched openings, a shaded loggia, and a layout tuned for indoor-outdoor living.','Shaded loggia\nArched entry portico\nOpen-plan kitchen island\nPrivate primary terrace','2026-08-30 09:56:39','2026-08-30 11:07:42'),(29,'The Palisade Villa','the-palisade-villa',1620.00,'MC-V105',6,5.5,2,3,4700.00,82.00,66.00,'Basement','Flat Roof','2:12','Concrete & Cedar','Villas',NULL,1,4063,243,75,'A commanding hillside villa with a cantilevered upper floor, full-height glazing, and a walk-out lower level built for entertaining.','Cantilevered upper level\nWalk-out lower level\nInfinity-edge pool deck\nHome theater room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(30,'Casa Serenata','casa-serenata',980.00,'MC-V106',4,3.0,1,2,2900.00,66.00,54.00,'Slab','Hip Roof','4:12','Stucco & Stone Accents','Villas',NULL,0,5683,408,5,'A single-story villa organized around a central atrium garden, keeping every room within reach of natural light and air.','Central atrium garden\nSingle-level living\nCovered breezeway\nOutdoor dining pavilion','2026-08-30 09:56:39','2026-08-30 11:07:42'),(31,'Villa Altura','villa-altura',1385.00,'MC-V107',5,4.0,3,2,3980.00,68.00,58.00,'Slab','Flat Roof','2:12','Glass & Steel','Villas',NULL,0,4603,314,20,'A vertical villa that stacks living spaces around a central stairwell skylight, maximizing a narrow urban-edge lot.','Central stairwell skylight\nRooftop terrace\nDouble-height living room\nElevator-ready shaft','2026-08-30 09:56:39','2026-08-30 11:07:42'),(32,'The Sundial Villa','the-sundial-villa',1150.00,'MC-V108',4,3.5,2,2,3350.00,72.00,58.00,'Slab','Low-Slope','3:12','Brick & Render','Villas',NULL,0,3524,220,35,'A sun-tracking layout with deep overhangs and a pool terrace positioned to stay shaded through peak afternoon heat.','Deep roof overhangs\nShaded pool terrace\nButler\'s pantry\nDual home offices','2026-08-30 09:56:39','2026-08-30 11:07:42'),(33,'Birchwood Apartments â€” Type C','birchwood-apartments-type-c',650.00,'MC-A203',3,2.5,1,1,1620.00,48.00,40.00,'Slab','Flat Roof','1:12','Brick & Glass','Apartments',NULL,1,3944,246,75,'The largest Birchwood floor plan, with a wraparound balcony and a kitchen island sized for entertaining.','Wraparound balcony\nKitchen island\nIn-unit laundry\nSecure parcel room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(34,'Harborview Apartments','harborview-apartments',480.00,'MC-A204',2,2.0,1,1,1080.00,42.00,36.00,'Slab','Flat Roof','1:12','Metal Panel & Glass','Apartments',NULL,0,5564,412,6,'A compact two-bedroom apartment plan built for mid-rise developments, with a private balcony facing the courtyard.','Private balcony\nOpen-plan living\nBuilt-in storage wall\nCourtyard-facing orientation','2026-08-30 09:56:39','2026-08-30 11:07:42'),(35,'Cedar Court Apartments','cedar-court-apartments',395.00,'MC-A205',1,1.0,1,1,720.00,32.00,30.00,'Slab','Flat Roof','1:12','Fiber Cement Siding','Apartments',NULL,0,4485,317,21,'An efficient one-bedroom layout for rental or starter-home developments, with a full kitchen and a dedicated study nook.','Dedicated study nook\nFull kitchen\nWalk-in closet\nEnergy-efficient windows','2026-08-30 09:56:39','2026-08-30 11:07:42'),(36,'The Maple Residences','the-maple-residences',720.00,'MC-A206',3,2.0,1,1,1450.00,46.00,38.00,'Slab','Flat Roof','1:12','Brick & Render','Apartments',NULL,0,3405,223,36,'A family-oriented apartment plan with three bedrooms clustered away from the main living area for privacy.','Split bedroom layout\nOpen kitchen and dining\nExtra storage closet\nBalcony off living room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(37,'Skyline Loft Apartments','skyline-loft-apartments',560.00,'MC-A207',2,2.0,1,1,1180.00,40.00,36.00,'Slab','Flat Roof','1:12','Glass Curtain Wall','Apartments',NULL,1,3826,249,76,'A loft-style two-bedroom plan with floor-to-ceiling glazing and an open mezzanine-feel living space.','Floor-to-ceiling glazing\nOpen mezzanine feel\nExposed ceiling detailing\nBuilt-in home office nook','2026-08-30 09:56:39','2026-08-30 11:07:42'),(38,'Willowbrook Apartments','willowbrook-apartments',505.00,'MC-A208',2,1.5,1,1,990.00,38.00,34.00,'Slab','Flat Roof','1:12','Stucco & Timber Accents','Apartments',NULL,0,5446,415,6,'A garden-style apartment plan with a private patio entrance, suited to low-rise multifamily developments.','Private patio entrance\nGarden-style access\nIn-unit laundry closet\nOpen-plan living and dining','2026-08-30 09:56:39','2026-08-30 11:07:42'),(39,'The Hawthorn Residence','the-hawthorn-residence',850.00,'MC-R303',4,2.5,2,2,2450.00,58.00,46.00,'Crawl Space','Gable Roof','6:12','Vinyl Siding & Stone','Residential',NULL,1,5866,440,47,'A dependable family home layout with a mudroom entry, open kitchen, and a flexible bonus room over the garage.','Mudroom entry\nBonus room over garage\nOpen kitchen and family room\nCovered back patio','2026-08-30 09:56:39','2026-08-30 11:07:42'),(40,'Willowmere Residence','willowmere-residence',795.00,'MC-R304',3,2.0,1,2,1980.00,54.00,44.00,'Slab','Hip Roof','5:12','Brick Veneer','Residential',NULL,0,3287,226,37,'A single-story residence designed for easy aging-in-place living, with wide hallways and a no-step entry.','No-step entry\nWide hallways\nMain-floor primary suite\nAttached two-car garage','2026-08-30 09:56:39','2026-08-30 11:07:42'),(41,'The Brookline Residence','the-brookline-residence',910.00,'MC-R305',4,3.0,2,2,2680.00,60.00,48.00,'Crawl Space','Gable Roof','7:12','Fiber Cement & Stone','Residential',NULL,0,2207,132,52,'A classic two-story family residence with a front porch, formal dining room, and a walk-out basement option.','Front porch\nFormal dining room\nWalk-out basement option\nUpstairs laundry room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(42,'Elmwood Residence','elmwood-residence',760.00,'MC-R306',3,2.5,2,2,2050.00,52.00,42.00,'Slab','Gable Roof','6:12','Vinyl Siding','Residential',NULL,0,5328,418,7,'A cost-efficient family plan with an open great room, split-level bedrooms, and a compact footprint for narrower lots.','Split-level bedrooms\nOpen great room\nCompact footprint\nCovered front entry','2026-08-30 09:56:39','2026-08-30 11:07:42'),(43,'The Ashford Residence','the-ashford-residence',875.00,'MC-R307',4,3.0,2,3,2550.00,62.00,48.00,'Crawl Space','Gable Roof','6:12','Brick & Siding','Residential',NULL,1,5748,443,47,'A generous family residence with a three-car garage, a large kitchen island, and a dedicated home office off the entry.','Three-car garage\nLarge kitchen island\nHome office off entry\nUpstairs bonus loft','2026-08-30 09:56:39','2026-08-30 11:07:42'),(44,'Copperfield Residence','copperfield-residence',705.00,'MC-R308',3,2.0,1,2,1850.00,50.00,40.00,'Slab','Hip Roof','5:12','Stucco & Stone','Residential',NULL,0,3168,229,37,'A single-story residence with a split-bedroom plan, vaulted great room ceiling, and a covered rear porch.','Split-bedroom plan\nVaulted great room ceiling\nCovered rear porch\nWalk-in pantry','2026-08-30 09:56:39','2026-08-30 11:07:42'),(45,'Riverside Grand Hotel','riverside-grand-hotel',9800.00,'MC-H403',24,24.0,4,0,21500.00,140.00,90.00,'Slab','Flat Roof','1:12','Brick & Glass Curtain Wall','Hotels',NULL,1,3589,255,78,'A 24-room riverside hotel plan with a landscaped courtyard pool, ground-floor restaurant, and rooftop event space.','Courtyard pool\nGround-floor restaurant\nRooftop event space\nUnderground guest parking','2026-08-30 09:56:39','2026-08-30 11:07:42'),(46,'The Harborline Hotel','the-harborline-hotel',7200.00,'MC-H404',16,16.0,3,0,14200.00,110.00,80.00,'Slab','Flat Roof','1:12','Stucco & Metal Panel','Hotels',NULL,0,5209,41,8,'A mid-scale hotel plan with harbor-facing rooms, a compact lobby lounge, and an efficient back-of-house layout.','Harbor-facing rooms\nLobby lounge\nEfficient back-of-house\nGround-floor conference room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(47,'Cedarpoint Inn & Suites','cedarpoint-inn-and-suites',6100.00,'MC-H405',14,14.0,2,0,11800.00,100.00,76.00,'Slab','Gable Roof','6:12','Timber & Stone','Hotels',NULL,0,4129,326,23,'A cabin-style inn plan built around a central great room lobby with a stone fireplace, suited to a countryside setting.','Stone fireplace lobby\nCentral great room\nOutdoor fire pit terrace\nOn-site breakfast room','2026-08-30 09:56:39','2026-08-30 11:07:42'),(48,'The Meridian Boutique Hotel','the-meridian-boutique-hotel',8600.00,'MC-H406',20,20.0,4,0,18400.00,125.00,85.00,'Slab','Flat Roof','1:12','Stone & Glass','Hotels',NULL,1,4550,352,63,'A boutique hotel plan with a courtyard garden, spa wing, and a rooftop bar overlooking the surrounding neighborhood.','Spa wing\nRooftop bar\nCourtyard garden\nValet drop-off canopy','2026-08-30 09:56:39','2026-08-30 11:07:42'),(49,'Northgate Suites Hotel','northgate-suites-hotel',5400.00,'MC-H407',12,12.0,2,0,9600.00,90.00,70.00,'Slab','Flat Roof','1:12','Fiber Cement & Glass','Hotels',NULL,0,1970,138,53,'A budget-tier suites hotel plan with kitchenette-equipped rooms, aimed at extended-stay guests.','Kitchenette-equipped rooms\nExtended-stay layout\nOn-site laundry facility\nCovered entry canopy','2026-08-30 09:56:39','2026-08-30 11:07:42'),(50,'The Vineyard Retreat Hotel','the-vineyard-retreat-hotel',8100.00,'MC-H408',18,18.0,3,0,16200.00,120.00,82.00,'Slab','Gable Roof','5:12','Stucco & Timber','Hotels',NULL,0,5092,44,9,'A vineyard-style hotel plan with a tasting-room lobby, outdoor terrace dining, and rooms arranged around a central lawn.','Tasting-room lobby\nOutdoor terrace dining\nCentral lawn courtyard\nOn-site event barn','2026-08-30 09:56:39','2026-08-30 11:25:38'),(51,'Willow Creek Farmhouse','willow-creek-farmhouse',1080.00,'MC-C503',4,3.0,2,2,2950.00,62.00,48.00,'Crawl Space','Gable Roof','8:12','Wood Siding & Stone','Country Homes',NULL,1,5511,450,49,'A wraparound-porch farmhouse with a mudroom built for muddy boots, and a great room anchored by a stone hearth.','Wraparound porch\nMudroom with boot bench\nStone fireplace hearth\nDetached workshop barn','2026-08-30 09:56:39','2026-08-30 11:07:42'),(52,'Prairie Rose Cottage','prairie-rose-cottage',690.00,'MC-C504',3,2.0,1,1,1720.00,48.00,38.00,'Crawl Space','Gable Roof','7:12','Stone & Timber','Country Homes',NULL,0,2931,235,39,'A compact single-story cottage suited to a rural lot, with a screened porch and a simple, efficient floor plan.','Screened porch\nEfficient single-level plan\nWood-burning stove hookup\nGarden shed footprint','2026-08-30 09:56:39','2026-08-30 11:07:42'),(53,'Bellview Country Estate','bellview-country-estate',1450.00,'MC-C505',5,4.0,2,3,3900.00,76.00,58.00,'Crawl Space','Gable Roof','8:12','Brick & Timber','Country Homes',NULL,1,3352,261,79,'A grand country estate plan with a wide front porch, formal entry hall, and a detached three-car carriage garage.','Wide front porch\nFormal entry hall\nDetached carriage garage\nGuest suite over garage','2026-08-30 09:56:39','2026-08-30 11:07:42'),(54,'Meadowlark Farmhouse','meadowlark-farmhouse',950.00,'MC-C506',4,2.5,2,2,2600.00,58.00,46.00,'Crawl Space','Gable Roof','7:12','Board & Batten Siding','Country Homes',NULL,0,4972,47,9,'A modern farmhouse plan with board-and-batten siding, a large country kitchen, and a covered rear porch.','Large country kitchen\nCovered rear porch\nBoard-and-batten siding\nMain-floor mudroom','2026-08-30 09:56:39','2026-08-30 11:07:42'),(55,'The Ridgeline Farmhouse','the-ridgeline-farmhouse',1020.00,'MC-C507',4,3.0,2,2,2800.00,60.00,48.00,'Crawl Space','Gable Roof','8:12','Stone & Wood Siding','Country Homes',NULL,0,3893,333,25,'A hillside farmhouse plan with a walk-out lower level, a wraparound deck, and panoramic-facing living room windows.','Walk-out lower level\nWraparound deck\nPanoramic living room windows\nDetached equipment barn','2026-08-30 09:56:39','2026-08-30 11:07:42'),(56,'Orchard Bend Cottage','orchard-bend-cottage',720.00,'MC-C508',3,2.0,1,1,1850.00,50.00,40.00,'Crawl Space','Gable Roof','7:12','Wood Siding & Stone Accents','Country Homes',NULL,0,2814,238,40,'A cozy single-story cottage plan with a covered front porch and an open kitchen-to-living layout suited to rural acreage.','Covered front porch\nOpen kitchen-to-living layout\nAttached one-car garage\nMudroom entry from garage','2026-08-30 09:56:39','2026-08-30 12:19:29');
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_files`
--

DROP TABLE IF EXISTS `property_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `property_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` enum('image','document') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `is_cover` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_files_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_files`
--

LOCK TABLES `property_files` WRITE;
/*!40000 ALTER TABLE `property_files` DISABLE KEYS */;
INSERT INTO `property_files` VALUES (1,1,'uploads/properties/plan-1-property-1.jpg','property-1.jpg','image',1,0,'2026-08-29 11:31:53'),(2,1,'uploads/properties/plan-1-alt-property-2.jpg','property-2.jpg','image',0,1,'2026-08-29 11:31:53'),(3,2,'uploads/properties/plan-2-property-3.jpg','property-3.jpg','image',1,0,'2026-08-29 11:31:53'),(4,2,'uploads/properties/plan-2-alt-property-4.jpg','property-4.jpg','image',0,1,'2026-08-29 11:31:53'),(5,3,'uploads/properties/plan-3-property-5.jpg','property-5.jpg','image',1,0,'2026-08-29 11:31:53'),(6,3,'uploads/properties/plan-3-alt-property-6.jpg','property-6.jpg','image',0,1,'2026-08-29 11:31:53'),(7,4,'uploads/properties/plan-4-property-7.jpg','property-7.jpg','image',1,0,'2026-08-29 11:31:53'),(8,4,'uploads/properties/plan-4-alt-property-8.jpg','property-8.jpg','image',0,1,'2026-08-29 11:31:53'),(9,5,'uploads/properties/plan-5-property-9.jpg','property-9.jpg','image',1,0,'2026-08-29 11:31:53'),(10,5,'uploads/properties/plan-5-alt-property-1.jpg','property-1.jpg','image',0,1,'2026-08-29 11:31:53'),(11,6,'uploads/properties/plan-6-property-2.jpg','property-2.jpg','image',1,0,'2026-08-29 11:31:53'),(12,6,'uploads/properties/plan-6-alt-property-3.jpg','property-3.jpg','image',0,1,'2026-08-29 11:31:53'),(13,7,'uploads/properties/plan-7-property-4.jpg','property-4.jpg','image',1,0,'2026-08-29 11:31:53'),(14,7,'uploads/properties/plan-7-alt-property-5.jpg','property-5.jpg','image',0,1,'2026-08-29 11:31:53'),(15,8,'uploads/properties/plan-8-property-6.jpg','property-6.jpg','image',1,0,'2026-08-29 11:31:53'),(16,8,'uploads/properties/plan-8-alt-property-7.jpg','property-7.jpg','image',0,1,'2026-08-29 11:31:53'),(17,17,'uploads/properties/plan-17-property-8.jpg','property-8.jpg','image',1,0,'2026-08-29 11:31:53'),(18,17,'uploads/properties/plan-17-alt-property-9.jpg','property-9.jpg','image',0,1,'2026-08-29 11:31:53'),(19,18,'uploads/properties/plan-18-property-1.jpg','property-1.jpg','image',1,0,'2026-08-29 11:31:53'),(20,18,'uploads/properties/plan-18-alt-property-2.jpg','property-2.jpg','image',0,1,'2026-08-29 11:31:53'),(21,19,'uploads/properties/plan-19-property-3.jpg','property-3.jpg','image',1,0,'2026-08-29 11:31:53'),(22,19,'uploads/properties/plan-19-alt-property-4.jpg','property-4.jpg','image',0,1,'2026-08-29 11:31:53'),(23,20,'uploads/properties/plan-20-property-5.jpg','property-5.jpg','image',1,0,'2026-08-29 11:31:53'),(24,20,'uploads/properties/plan-20-alt-property-6.jpg','property-6.jpg','image',0,1,'2026-08-29 11:31:53'),(25,21,'uploads/properties/plan-21-property-7.jpg','property-7.jpg','image',1,0,'2026-08-29 11:31:53'),(26,21,'uploads/properties/plan-21-alt-property-8.jpg','property-8.jpg','image',0,1,'2026-08-29 11:31:53'),(27,22,'uploads/properties/plan-22-property-9.jpg','property-9.jpg','image',1,0,'2026-08-29 11:31:53'),(28,22,'uploads/properties/plan-22-alt-property-1.jpg','property-1.jpg','image',0,1,'2026-08-29 11:31:53'),(29,23,'uploads/properties/plan-23-property-2.jpg','property-2.jpg','image',1,0,'2026-08-29 11:31:53'),(30,23,'uploads/properties/plan-23-alt-property-3.jpg','property-3.jpg','image',0,1,'2026-08-29 11:31:53'),(31,24,'uploads/properties/plan-24-property-4.jpg','property-4.jpg','image',1,0,'2026-08-29 11:31:53'),(32,24,'uploads/properties/plan-24-alt-property-5.jpg','property-5.jpg','image',0,1,'2026-08-29 11:31:53'),(33,25,'uploads/properties/plan-25-property-6.jpg','property-6.jpg','image',1,0,'2026-08-29 11:31:53'),(34,25,'uploads/properties/plan-25-alt-property-7.jpg','property-7.jpg','image',0,1,'2026-08-29 11:31:53'),(35,26,'uploads/properties/plan-26-property-8.jpg','property-8.jpg','image',1,0,'2026-08-29 11:31:53'),(36,26,'uploads/properties/plan-26-alt-property-9.jpg','property-9.jpg','image',0,1,'2026-08-29 11:31:53'),(37,27,'uploads/properties/gen-villa-1.jpg','cypress-grove-villa.jpg','image',1,0,'2026-08-30 09:58:23'),(38,28,'uploads/properties/gen-villa-2.jpg','villa-meridiana.jpg','image',1,0,'2026-08-30 09:58:23'),(39,29,'uploads/properties/gen-villa-3.jpg','the-palisade-villa.jpg','image',1,0,'2026-08-30 09:58:23'),(40,30,'uploads/properties/gen-villa-4.jpg','casa-serenata.jpg','image',1,0,'2026-08-30 09:58:23'),(41,31,'uploads/properties/gen-villa-5.jpg','villa-altura.jpg','image',1,0,'2026-08-30 09:58:23'),(42,32,'uploads/properties/gen-villa-6.jpg','the-sundial-villa.jpg','image',1,0,'2026-08-30 09:58:23'),(43,33,'uploads/properties/gen-apt-1.jpg','birchwood-apartments-type-c.jpg','image',1,0,'2026-08-30 09:58:23'),(44,34,'uploads/properties/gen-apt-2.jpg','harborview-apartments.jpg','image',1,0,'2026-08-30 09:58:23'),(45,35,'uploads/properties/gen-apt-3.jpg','cedar-court-apartments.jpg','image',1,0,'2026-08-30 09:58:23'),(46,36,'uploads/properties/gen-apt-4.jpg','the-maple-residences.jpg','image',1,0,'2026-08-30 09:58:23'),(47,37,'uploads/properties/gen-apt-5.jpg','skyline-loft-apartments.jpg','image',1,0,'2026-08-30 09:58:23'),(48,38,'uploads/properties/gen-apt-6.jpg','willowbrook-apartments.jpg','image',1,0,'2026-08-30 09:58:23'),(49,39,'uploads/properties/gen-res-1.jpg','the-hawthorn-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(50,40,'uploads/properties/gen-res-2.jpg','willowmere-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(51,41,'uploads/properties/gen-res-3.jpg','the-brookline-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(52,42,'uploads/properties/gen-res-4.jpg','elmwood-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(53,43,'uploads/properties/gen-res-5.jpg','the-ashford-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(54,44,'uploads/properties/gen-res-6.jpg','copperfield-residence.jpg','image',1,0,'2026-08-30 09:58:23'),(55,45,'uploads/properties/gen-hotel-2.jpg','riverside-grand-hotel.jpg','image',1,0,'2026-08-30 09:58:23'),(56,46,'uploads/properties/gen-hotel-3.jpg','the-harborline-hotel.jpg','image',1,0,'2026-08-30 09:58:23'),(57,47,'uploads/properties/gen-hotel-4.jpg','cedarpoint-inn-and-suites.jpg','image',1,0,'2026-08-30 09:58:23'),(58,48,'uploads/properties/gen-hotel-5.jpg','the-meridian-boutique-hotel.jpg','image',1,0,'2026-08-30 09:58:23'),(59,49,'uploads/properties/gen-hotel-6.jpg','northgate-suites-hotel.jpg','image',1,0,'2026-08-30 09:58:23'),(60,50,'uploads/properties/gen-hotel-2.jpg','the-vineyard-retreat-hotel.jpg','image',1,0,'2026-08-30 09:58:23'),(61,51,'uploads/properties/gen-country-1.jpg','willow-creek-farmhouse.jpg','image',1,0,'2026-08-30 09:58:23'),(62,52,'uploads/properties/gen-country-2.jpg','prairie-rose-cottage.jpg','image',1,0,'2026-08-30 09:58:23'),(63,53,'uploads/properties/gen-country-3.jpg','bellview-country-estate.jpg','image',1,0,'2026-08-30 09:58:23'),(64,54,'uploads/properties/gen-country-4.jpg','meadowlark-farmhouse.jpg','image',1,0,'2026-08-30 09:58:23'),(65,55,'uploads/properties/gen-country-5.jpg','the-ridgeline-farmhouse.jpg','image',1,0,'2026-08-30 09:58:23'),(66,56,'uploads/properties/gen-country-6.jpg','orchard-bend-cottage.jpg','image',1,0,'2026-08-30 09:58:23'),(67,27,'uploads/properties/gen-villa2-1.jpg','cypress-grove-villa-2.jpg','image',0,1,'2026-08-30 11:37:22'),(68,28,'uploads/properties/gen-villa2-2.jpg','villa-meridiana-2.jpg','image',0,1,'2026-08-30 11:37:22'),(69,29,'uploads/properties/gen-villa2-3.jpg','the-palisade-villa-2.jpg','image',0,1,'2026-08-30 11:37:22'),(70,30,'uploads/properties/gen-villa2-4.jpg','casa-serenata-2.jpg','image',0,1,'2026-08-30 11:37:22'),(71,31,'uploads/properties/gen-villa2-5.jpg','villa-altura-2.jpg','image',0,1,'2026-08-30 11:37:22'),(72,32,'uploads/properties/gen-villa2-6.jpg','the-sundial-villa-2.jpg','image',0,1,'2026-08-30 11:37:22'),(73,33,'uploads/properties/gen-apt2-1.jpg','birchwood-apartments-type-c-2.jpg','image',0,1,'2026-08-30 11:37:22'),(74,34,'uploads/properties/gen-apt2-2.jpg','harborview-apartments-2.jpg','image',0,1,'2026-08-30 11:37:22'),(75,35,'uploads/properties/gen-apt2-3.jpg','cedar-court-apartments-2.jpg','image',0,1,'2026-08-30 11:37:22'),(76,36,'uploads/properties/gen-apt2-4.jpg','the-maple-residences-2.jpg','image',0,1,'2026-08-30 11:37:22'),(77,37,'uploads/properties/gen-apt2-5.jpg','skyline-loft-apartments-2.jpg','image',0,1,'2026-08-30 11:37:22'),(78,38,'uploads/properties/gen-apt2-6.jpg','willowbrook-apartments-2.jpg','image',0,1,'2026-08-30 11:37:22'),(79,39,'uploads/properties/gen-res2-1.jpg','the-hawthorn-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(80,40,'uploads/properties/gen-res2-2.jpg','willowmere-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(81,41,'uploads/properties/gen-res2-3.jpg','the-brookline-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(82,42,'uploads/properties/gen-res2-4.jpg','elmwood-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(83,43,'uploads/properties/gen-res2-5.jpg','the-ashford-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(84,44,'uploads/properties/gen-res2-6.jpg','copperfield-residence-2.jpg','image',0,1,'2026-08-30 11:37:22'),(85,45,'uploads/properties/gen-hotel2-1.jpg','riverside-grand-hotel-2.jpg','image',0,1,'2026-08-30 11:37:22'),(86,46,'uploads/properties/gen-hotel2-2.jpg','the-harborline-hotel-2.jpg','image',0,1,'2026-08-30 11:37:22'),(87,47,'uploads/properties/gen-hotel2-3.jpg','cedarpoint-inn-and-suites-2.jpg','image',0,1,'2026-08-30 11:37:22'),(88,48,'uploads/properties/gen-hotel2-4.jpg','the-meridian-boutique-hotel-2.jpg','image',0,1,'2026-08-30 11:37:22'),(89,49,'uploads/properties/gen-hotel2-5.jpg','northgate-suites-hotel-2.jpg','image',0,1,'2026-08-30 11:37:22'),(90,50,'uploads/properties/gen-hotel2-6.jpg','the-vineyard-retreat-hotel-2.jpg','image',0,1,'2026-08-30 11:37:22'),(91,51,'uploads/properties/gen-country2-1.jpg','willow-creek-farmhouse-2.jpg','image',0,1,'2026-08-30 11:37:22'),(92,52,'uploads/properties/gen-country2-2.jpg','prairie-rose-cottage-2.jpg','image',0,1,'2026-08-30 11:37:22'),(93,53,'uploads/properties/gen-country2-3.jpg','bellview-country-estate-2.jpg','image',0,1,'2026-08-30 11:37:22'),(94,54,'uploads/properties/gen-country2-4.jpg','meadowlark-farmhouse-2.jpg','image',0,1,'2026-08-30 11:37:22'),(95,55,'uploads/properties/gen-country2-5.jpg','the-ridgeline-farmhouse-2.jpg','image',0,1,'2026-08-30 11:37:22'),(96,56,'uploads/properties/gen-country2-6.jpg','orchard-bend-cottage-2.jpg','image',0,1,'2026-08-30 11:37:22');
/*!40000 ALTER TABLE `property_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reviewable_type` enum('plan','project') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reviewable_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reviewable_type` (`reviewable_type`,`reviewable_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,'plan',1,'Grace Namutebi','grace.namutebi@example.com',4,'The PDF set was clear and easy to hand straight to our contractor. Construction went smoothly with almost no clarification needed.','approved','2026-08-29 11:31:53'),(2,'plan',2,'David Okello','david.okello@example.com',5,'We customized the plan slightly for our lot and the team was responsive throughout. Would buy from Mars Construction again.','approved','2026-08-29 11:31:53'),(3,'plan',3,'Patricia Nakigozi','patricia.nakigozi@example.com',5,'Good value for the price. The material list add-on saved us a lot of back-and-forth with suppliers.','approved','2026-08-29 11:31:53'),(4,'plan',4,'Samuel Muwonge','samuel.muwonge@example.com',4,'Plan matched exactly what was advertised. Our engineer had no issues adapting it to local code.','approved','2026-08-29 11:31:53'),(5,'plan',5,'Esther Achieng','esther.achieng@example.com',5,'Delivery was fast and the drawings were detailed enough for our builder to quote accurately.','approved','2026-08-29 11:31:53'),(6,'plan',6,'Brian Tumusiime','brian.tumusiime@example.com',4,'Really happy with how the finished house turned out compared to the plan. Great attention to detail.','approved','2026-08-29 11:31:53'),(7,'project',1,'Josephine Auma','josephine.auma@example.com',5,'Mars Construction kept us updated every step of the way. The finished home exceeded what we expected from the original drawings.','approved','2026-08-29 11:31:53'),(8,'project',2,'Peter Ssekandi','peter.ssekandi@example.com',4,'Professional crew, and they stuck to the schedule they gave us at the start. Would recommend to anyone building in Kampala.','approved','2026-08-29 11:31:53'),(9,'project',3,'Diana Kobusingye','diana.kobusingye@example.com',5,'The team handled a difficult sloped site better than we expected. Very pleased with the result.','approved','2026-08-29 11:31:53');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('footer_address','Plot 14, Kironde Road, Muyenga, Kampala, Uganda'),('footer_col1_title','About Mars Construction'),('footer_col2_heading','Explore'),('footer_copyright','&copy; 2026 Mars Construction. All rights reserved.'),('footer_email','info@marsconstruction.com'),('footer_facebook','https://facebook.com/marsconstruction'),('footer_instagram','https://instagram.com/marsconstruction'),('footer_phone','+256 700 123 456'),('footer_text','Mars Construction designs and builds homes across Uganda, from downloadable house plans to full-service construction and property management. We handle every stage of the build so you deal with one accountable team from groundbreaking to handover.'),('footer_twitter','https://twitter.com/marsconstruction'),('footer_youtube','https://youtube.com/@marsconstruction'),('header_email','info@marsconstruction.com'),('header_phone','+256 700 123 456'),('header_whatsapp_url','https://wa.me/256700123456');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,'Leslie Alexander','Sr. Director','assets/images/resource/team-1.png',NULL,NULL,NULL,NULL,0),(2,'Jenny Wilson','Sr. Manager','assets/images/resource/team-2.png',NULL,NULL,NULL,NULL,1),(3,'Arlene McCoy','Sr. HRM','assets/images/resource/team-3.png',NULL,NULL,NULL,NULL,2),(4,'Theresa Webb','Sr. Marketing','assets/images/resource/team-4.png',NULL,NULL,NULL,NULL,3),(5,'Leslie Alexander','Sr. Director','assets/images/resource/team-1.png',NULL,NULL,NULL,NULL,0),(6,'Jenny Wilson','Sr. Manager','assets/images/resource/team-2.png',NULL,NULL,NULL,NULL,1),(7,'Arlene McCoy','Sr. HRM','assets/images/resource/team-3.png',NULL,NULL,NULL,NULL,2),(8,'Theresa Webb','Sr. Marketing','assets/images/resource/team-4.png',NULL,NULL,NULL,NULL,3);
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int DEFAULT '5',
  `testimonial` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Leslie Alexander','Online Broker',5,'Mars Construction delivered our home exactly on schedule and on budget. Their team communicated clearly at every stage and the finished quality speaks for itself.','assets/images/resource/author-4.png',0),(2,'Robert Fox','Property Owner',5,'From the first consultation to the final walkthrough, the Mars Construction team was professional, transparent, and genuinely invested in getting the details right.','assets/images/resource/author-5.png',1),(3,'Leslie Alexander','Online Broker',5,'Mars Construction delivered our home exactly on schedule and on budget. Their team communicated clearly at every stage and the finished quality speaks for itself.','assets/images/resource/author-4.png',0),(4,'Robert Fox','Property Owner',5,'From the first consultation to the final walkthrough, the Mars Construction team was professional, transparent, and genuinely invested in getting the details right.','assets/images/resource/author-5.png',1);
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@marsconstruction.local','$2y$12$ncy2ZEbyVWy4D4DKASMVv.aSbF7s/KCKV9STg2mwBb2ZsnKH02a1.','admin','2026-08-28 15:29:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mars_estate'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-30 15:25:18
