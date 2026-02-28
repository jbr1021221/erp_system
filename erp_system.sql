-- Adminer 4.8.1 MySQL 8.0.45-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('asset','liability','income','expense','equity') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_account_code_unique` (`account_code`),
  KEY `accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-setting_font_size',	's:5:\"large\";',	1770988026),
('laravel-cache-setting_theme_primary_color',	's:11:\"196 227 213\";',	1770988026),
('laravel-cache-setting_theme_secondary_color',	's:11:\"148 196 245\";',	1770988026),
('laravel-cache-spatie.permission.cache',	'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:52:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"student-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"student-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"student-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"student-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"student-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"payment-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"payment-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"payment-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"payment-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"payment-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:10:\"class-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"class-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"class-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"class-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"user-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:9:\"user-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"user-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"expense-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"expense-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:12:\"expense-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:14:\"expense-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:15:\"expense-approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"expense-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:12:\"account-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"account-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"account-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"account-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:11:\"report-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:13:\"report-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:17:\"permission-assign\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"dashboard-admin\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:20:\"dashboard-accountant\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:17:\"dashboard-teacher\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:17:\"dashboard-student\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"dashboard-parent\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:12:\"setting-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:12:\"setting-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:8:\"fee-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:20:\"fee-structure-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:18:\"fee-structure-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"fee-structure-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:13:\"report-income\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"teacher-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:14:\"teacher-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:12:\"teacher-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:14:\"teacher-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:12:\"teacher-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:6:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:7:\"Teacher\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:6:\"Parent\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:7:\"Student\";s:1:\"c\";s:3:\"web\";}}}',	1771061646);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '30',
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_teacher_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classes_code_unique` (`code`),
  KEY `classes_class_teacher_id_foreign` (`class_teacher_id`),
  CONSTRAINT `classes_class_teacher_id_foreign` FOREIGN KEY (`class_teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `classes` (`id`, `name`, `code`, `capacity`, `academic_year`, `class_teacher_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'Class 1',	'CLASS-1',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(2,	'Class 2',	'CLASS-2',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(3,	'Class 3',	'CLASS-3',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(4,	'Class 4',	'CLASS-4',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(5,	'Class 5',	'CLASS-5',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(6,	'Class 6',	'CLASS-6',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(7,	'Class 7',	'CLASS-7',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(8,	'Class 8',	'CLASS-8',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(9,	'Class 9',	'CLASS-9',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(10,	'Class 10',	'CLASS-10',	40,	'2026',	4,	NULL,	1,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25');

DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_code_unique` (`code`),
  KEY `expense_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `expense_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expense_categories` (`id`, `name`, `code`, `description`, `parent_id`, `created_at`, `updated_at`) VALUES
(1,	'Utilities',	'UTI',	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(2,	'Electricity',	'UTI-ELE',	NULL,	1,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(3,	'Water',	'UTI-WAT',	NULL,	1,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(4,	'Internet',	'UTI-INT',	NULL,	1,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(5,	'Maintenance',	'MAI',	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(6,	'Repairs',	'MAI-REP',	NULL,	5,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(7,	'Cleaning',	'MAI-CLE',	NULL,	5,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(8,	'Salaries',	'SAL',	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(9,	'Teaching Staff',	'SAL-TEA',	NULL,	8,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(10,	'Non-Teaching Staff',	'SAL-NON',	NULL,	8,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(11,	'Supplies',	'SUP',	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(12,	'Stationery',	'SUP-STA',	NULL,	11,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(13,	'Teaching Aids',	'SUP-TEA',	NULL,	11,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(14,	'Events',	'EVE',	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(15,	'Sports Day',	'EVE-SPO',	NULL,	14,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(16,	'Annual Function',	'EVE-ANN',	NULL,	14,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26');

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','cheque','card') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenses_voucher_number_unique` (`voucher_number`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_vendor_id_foreign` (`vendor_id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expenses` (`id`, `voucher_number`, `expense_date`, `category_id`, `vendor_id`, `amount`, `payment_method`, `payment_reference`, `description`, `receipt_file`, `status`, `created_by`, `approved_by`, `approved_at`, `approval_remarks`, `created_at`, `updated_at`) VALUES
(1,	'EXP-68332',	'2026-01-24',	10,	3,	2395.00,	'card',	NULL,	'Demo expense description',	NULL,	'paid',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(2,	'EXP-27584',	'2026-01-23',	15,	4,	1841.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'paid',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(3,	'EXP-44799',	'2025-12-15',	9,	1,	1484.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'pending',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(4,	'EXP-62600',	'2025-12-22',	3,	4,	4097.00,	'card',	NULL,	'Demo expense description',	NULL,	'pending',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(5,	'EXP-88237',	'2025-12-13',	10,	4,	1916.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'pending',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(6,	'EXP-25012',	'2026-02-05',	12,	1,	1120.00,	'card',	NULL,	'Demo expense description',	NULL,	'pending',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(7,	'EXP-10774',	'2026-01-20',	7,	1,	2424.00,	'cheque',	NULL,	'Demo expense description',	NULL,	'paid',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(8,	'EXP-80182',	'2026-01-01',	2,	3,	4907.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(9,	'EXP-34014',	'2026-01-26',	6,	2,	1934.00,	'cheque',	NULL,	'Demo expense description',	NULL,	'paid',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(10,	'EXP-88030',	'2026-01-26',	6,	2,	2563.00,	'card',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(11,	'EXP-28084',	'2026-01-30',	10,	3,	786.00,	'card',	NULL,	'Demo expense description',	NULL,	'pending',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(12,	'EXP-40567',	'2025-12-08',	6,	3,	4275.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'paid',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(13,	'EXP-63980',	'2025-12-30',	10,	3,	1117.00,	'cash',	NULL,	'Demo expense description',	NULL,	'pending',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(14,	'EXP-54029',	'2026-01-11',	6,	1,	892.00,	'cash',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(15,	'EXP-82335',	'2026-01-23',	7,	2,	2278.00,	'bank_transfer',	NULL,	'Demo expense description',	NULL,	'pending',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(16,	'EXP-59587',	'2026-01-22',	9,	3,	4294.00,	'cash',	NULL,	'Demo expense description',	NULL,	'pending',	3,	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(17,	'EXP-87672',	'2025-12-27',	2,	1,	797.00,	'cheque',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(18,	'EXP-10575',	'2026-01-03',	2,	3,	3848.00,	'cheque',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(19,	'EXP-94379',	'2026-02-05',	3,	4,	4656.00,	'card',	NULL,	'Demo expense description',	NULL,	'pending',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(20,	'EXP-84958',	'2026-01-06',	7,	1,	1436.00,	'cash',	NULL,	'Demo expense description',	NULL,	'paid',	3,	1,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26');

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `fee_structures`;
CREATE TABLE `fee_structures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `fee_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Discount percentage (0-100)',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Fixed discount amount',
  `allow_partial_payment` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow partial payment for this fee',
  `minimum_partial_amount` decimal(10,2) DEFAULT NULL COMMENT 'Minimum amount for partial payment',
  `frequency` enum('one_time','monthly','quarterly','half_yearly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_structures_class_id_foreign` (`class_id`),
  CONSTRAINT `fee_structures_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fee_structures` (`id`, `class_id`, `fee_type`, `amount`, `discount_percentage`, `discount_amount`, `allow_partial_payment`, `minimum_partial_amount`, `frequency`, `academic_year`, `is_mandatory`, `created_at`, `updated_at`) VALUES
(1,	1,	'Tution fee',	15000.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-06 07:03:05',	'2026-02-06 07:03:05'),
(2,	1,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(3,	2,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(4,	3,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(5,	4,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(6,	5,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(7,	6,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(8,	7,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(9,	8,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(10,	9,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(11,	10,	'Admission Fee',	5000.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:19:35',	'2026-02-07 02:19:35'),
(12,	1,	'Tuition Fee',	1100.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(13,	1,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(14,	1,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(15,	1,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(16,	1,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(17,	2,	'Tuition Fee',	1200.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(18,	2,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(19,	2,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(20,	2,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(21,	2,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(22,	3,	'Tuition Fee',	1300.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(23,	3,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(24,	3,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(25,	3,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(26,	3,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(27,	4,	'Tuition Fee',	1400.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(28,	4,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(29,	4,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(30,	4,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(31,	4,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(32,	5,	'Tuition Fee',	1500.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(33,	5,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(34,	5,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(35,	5,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(36,	5,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(37,	6,	'Tuition Fee',	1600.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(38,	6,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(39,	6,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(40,	6,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(41,	6,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(42,	7,	'Tuition Fee',	1700.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(43,	7,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(44,	7,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(45,	7,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(46,	7,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(47,	8,	'Tuition Fee',	1800.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(48,	8,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(49,	8,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(50,	8,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(51,	8,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(52,	9,	'Tuition Fee',	1900.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(53,	9,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(54,	9,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(55,	9,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(56,	9,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(57,	10,	'Tuition Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'monthly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(58,	10,	'Exam Fee',	500.00,	0.00,	0.00,	0,	NULL,	'quarterly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(59,	10,	'Sports & Cultural Fee',	300.00,	0.00,	0.00,	0,	NULL,	'half_yearly',	'2026',	0,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(60,	10,	'Session Fee',	2000.00,	0.00,	0.00,	0,	NULL,	'yearly',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01'),
(61,	10,	'ID Card & Diary',	500.00,	0.00,	0.00,	0,	NULL,	'one_time',	'2026',	1,	'2026-02-07 02:27:01',	'2026-02-07 02:27:01');

DROP TABLE IF EXISTS `general_settings`;
CREATE TABLE `general_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `general_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `general_settings` (`id`, `key`, `value`, `display_name`, `group`, `created_at`, `updated_at`) VALUES
(1,	'student_id_prefix',	'STD-',	'Student ID Prefix',	'identifier',	'2026-02-06 06:41:25',	'2026-02-13 06:07:06'),
(2,	'class_id_prefix',	'CLS-',	'Class ID Prefix',	'identifier',	'2026-02-06 06:41:25',	'2026-02-13 06:07:06'),
(3,	'receipt_id_prefix',	'REC-',	'Receipt ID Prefix',	'receipt',	'2026-02-06 06:41:25',	'2026-02-13 06:07:06'),
(4,	'receipt_name',	'Official Payment Receipt',	'Receipt Title',	'receipt',	'2026-02-06 06:41:25',	'2026-02-13 06:07:06'),
(5,	'role_permission_style',	'text',	'Role/Permission Style',	'style',	'2026-02-06 06:41:25',	'2026-02-13 06:07:06'),
(6,	'font_size',	'large',	'System Font Size',	'appearance',	'2026-02-07 01:21:03',	'2026-02-13 06:07:06'),
(7,	'admission_form_logo',	'/images/logo.png',	'Admission Form Logo',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(8,	'admission_form_banner',	'/images/banner.jpg',	'Admission Form Banner',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(9,	'admission_form_institution_name',	'ERP Institution',	'Institution Name',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(10,	'admission_form_institution_address',	'Address Line 1, City, Country',	'Institution Address',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(11,	'admission_form_phone',	'+880 1234-567890',	'Phone Number',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(12,	'admission_form_email',	'info@institution.edu',	'Email Address',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(13,	'admission_form_website',	'www.institution.edu',	'Website',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(14,	'admission_form_title',	'STUDENT ADMISSION FORM',	'Form Title',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(15,	'admission_form_academic_year',	'2024-2025',	'Academic Year',	'admission_form',	'2026-02-07 01:33:58',	'2026-02-13 06:07:06'),
(16,	'theme_primary_color',	'196 227 213',	'Theme Primary Color',	'appearance',	'2026-02-13 03:16:22',	'2026-02-13 06:07:06'),
(17,	'theme_secondary_color',	'148 196 245',	'Theme Secondary Color',	'appearance',	'2026-02-13 03:34:19',	'2026-02-13 06:07:06');

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'0001_01_01_000000_create_users_table',	1),
(2,	'0001_01_01_000001_create_cache_table',	1),
(3,	'0001_01_01_000002_create_jobs_table',	1),
(4,	'2026_02_06_094547_create_classes_table',	1),
(5,	'2026_02_06_094547_create_fee_structures_table',	1),
(6,	'2026_02_06_094547_create_sections_table',	1),
(7,	'2026_02_06_094547_create_students_table',	1),
(8,	'2026_02_06_094547_create_subjects_table',	1),
(9,	'2026_02_06_094547_create_vendors_table',	1),
(10,	'2026_02_06_094548_create_accounts_table',	1),
(11,	'2026_02_06_094548_create_expense_categories_table',	1),
(12,	'2026_02_06_094548_create_expenses_table',	1),
(13,	'2026_02_06_094548_create_payments_table',	1),
(14,	'2026_02_06_094548_create_transactions_table',	1),
(15,	'2026_02_06_095732_create_permission_tables',	1),
(16,	'2026_02_06_120431_create_general_settings_table',	1),
(17,	'2026_02_06_123118_add_fee_structure_id_to_payments_table',	2),
(18,	'2026_02_06_123747_add_billing_period_to_payments_table',	3),
(19,	'2026_02_06_135725_create_teachers_table',	4),
(20,	'2026_02_07_071518_add_discount_and_partial_payment_to_fee_structures_table',	5),
(21,	'2026_02_07_075609_create_student_fee_assignments_table',	6),
(22,	'2026_02_07_085725_remove_unique_constraint_from_receipt_number',	7);

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1,	'App\\Models\\User',	2),
(3,	'App\\Models\\User',	3),
(4,	'App\\Models\\User',	4);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','online','cheque','card') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `received_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fee_structure_id` bigint unsigned DEFAULT NULL,
  `billing_month` tinyint unsigned DEFAULT NULL,
  `billing_year` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_student_id_foreign` (`student_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  KEY `payments_fee_structure_id_foreign` (`fee_structure_id`),
  CONSTRAINT `payments_fee_structure_id_foreign` FOREIGN KEY (`fee_structure_id`) REFERENCES `fee_structures` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payments` (`id`, `receipt_number`, `student_id`, `amount`, `payment_date`, `payment_method`, `transaction_reference`, `fee_type`, `remarks`, `status`, `received_by`, `created_at`, `updated_at`, `fee_structure_id`, `billing_month`, `billing_year`) VALUES
(1,	'REC-96950',	1,	3719.00,	'2026-01-03',	'bank_transfer',	'TXN392924',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(2,	'REC-33918',	1,	2260.00,	'2025-12-26',	'cash',	'TXN664464',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(3,	'REC-38592',	2,	4665.00,	'2025-12-13',	'online',	'TXN534969',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(4,	'REC-51482',	2,	1309.00,	'2026-01-10',	'online',	'TXN300778',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(5,	'REC-25248',	3,	4060.00,	'2025-12-13',	'cheque',	'TXN484036',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(6,	'REC-19513',	3,	2351.00,	'2025-12-30',	'online',	'TXN804491',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(7,	'REC-92510',	3,	3889.00,	'2025-12-11',	'cash',	'TXN445681',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(8,	'REC-36838',	4,	1044.00,	'2026-01-03',	'cheque',	'TXN220363',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(9,	'REC-16983',	5,	1843.00,	'2025-11-29',	'bank_transfer',	'TXN235963',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(10,	'REC-73969',	6,	1572.00,	'2025-11-14',	'cheque',	'TXN837640',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(11,	'REC-36263',	7,	1506.00,	'2025-12-04',	'cash',	'TXN706175',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(12,	'REC-64219',	8,	2858.00,	'2026-01-27',	'cash',	'TXN794549',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(13,	'REC-36935',	8,	1661.00,	'2026-01-24',	'online',	'TXN182999',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(14,	'REC-40111',	9,	1701.00,	'2025-11-13',	'cheque',	'TXN370679',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(15,	'REC-83772',	9,	2454.00,	'2025-12-09',	'online',	'TXN618963',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(16,	'REC-84906',	10,	3570.00,	'2026-01-30',	'online',	'TXN488767',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(17,	'REC-38918',	10,	1404.00,	'2026-01-24',	'online',	'TXN306988',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(18,	'REC-68603',	11,	1883.00,	'2025-12-03',	'cheque',	'TXN948145',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(19,	'REC-20150',	11,	3420.00,	'2025-12-24',	'cheque',	'TXN592529',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(20,	'REC-69685',	12,	3752.00,	'2025-11-09',	'cash',	'TXN949095',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(21,	'REC-24889',	13,	4432.00,	'2026-01-16',	'bank_transfer',	'TXN464862',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(22,	'REC-44281',	13,	4607.00,	'2025-11-18',	'bank_transfer',	'TXN517468',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(23,	'REC-54816',	13,	4405.00,	'2026-01-03',	'online',	'TXN344093',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(24,	'REC-41225',	14,	1822.00,	'2025-12-31',	'cheque',	'TXN178985',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(25,	'REC-27295',	14,	4986.00,	'2026-02-05',	'cheque',	'TXN384120',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(26,	'REC-80772',	15,	2197.00,	'2025-12-14',	'bank_transfer',	'TXN269511',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(27,	'REC-62646',	15,	3577.00,	'2025-12-09',	'cheque',	'TXN550485',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(28,	'REC-51277',	16,	4838.00,	'2025-12-18',	'online',	'TXN672421',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(29,	'REC-89333',	16,	2578.00,	'2025-11-30',	'cheque',	'TXN617592',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(30,	'REC-11424',	17,	4970.00,	'2025-12-06',	'online',	'TXN531866',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(31,	'REC-42929',	17,	2268.00,	'2025-11-29',	'bank_transfer',	'TXN664930',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(32,	'REC-58829',	17,	4051.00,	'2026-01-10',	'cash',	'TXN145584',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(33,	'REC-41299',	18,	1882.00,	'2026-02-05',	'online',	'TXN414299',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(34,	'REC-49505',	18,	1021.00,	'2025-12-16',	'online',	'TXN891658',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(35,	'REC-20104',	18,	3684.00,	'2026-01-16',	'online',	'TXN234549',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(36,	'REC-81424',	19,	4039.00,	'2025-12-02',	'online',	'TXN650186',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(37,	'REC-10386',	20,	4857.00,	'2026-01-26',	'bank_transfer',	'TXN805569',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(38,	'REC-54243',	20,	1664.00,	'2026-02-05',	'cash',	'TXN202890',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(39,	'REC-97864',	20,	2491.00,	'2025-11-29',	'bank_transfer',	'TXN413624',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(40,	'REC-11237',	21,	3717.00,	'2025-11-22',	'online',	'TXN540885',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(41,	'REC-45625',	21,	2896.00,	'2026-02-04',	'cheque',	'TXN735934',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(42,	'REC-36809',	21,	1741.00,	'2025-11-26',	'cash',	'TXN343087',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(43,	'REC-75178',	22,	2288.00,	'2025-12-16',	'cash',	'TXN832267',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(44,	'REC-44718',	23,	2807.00,	'2025-11-30',	'cash',	'TXN412360',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(45,	'REC-17001',	23,	1192.00,	'2026-01-04',	'cash',	'TXN768737',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(46,	'REC-10311',	23,	2102.00,	'2026-01-26',	'cash',	'TXN914424',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(47,	'REC-69258',	24,	2901.00,	'2025-11-28',	'online',	'TXN842322',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(48,	'REC-48634',	24,	4700.00,	'2025-12-16',	'cash',	'TXN718122',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(49,	'REC-78744',	25,	4732.00,	'2025-12-03',	'cheque',	'TXN192018',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(50,	'REC-43286',	26,	4722.00,	'2025-12-19',	'online',	'TXN777610',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(51,	'REC-85195',	26,	4254.00,	'2025-11-23',	'cash',	'TXN559373',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(52,	'REC-86413',	27,	1322.00,	'2025-12-09',	'cheque',	'TXN940014',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(53,	'REC-20546',	27,	4313.00,	'2025-11-29',	'online',	'TXN446134',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(54,	'REC-68569',	28,	2070.00,	'2025-12-05',	'cheque',	'TXN908855',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(55,	'REC-20136',	28,	2245.00,	'2026-01-18',	'online',	'TXN230230',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(56,	'REC-66183',	29,	3559.00,	'2025-12-11',	'online',	'TXN465775',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(57,	'REC-56859',	30,	4502.00,	'2026-01-18',	'cheque',	'TXN230390',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(58,	'REC-48540',	30,	3893.00,	'2025-11-16',	'bank_transfer',	'TXN635009',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(59,	'REC-77383',	30,	4482.00,	'2025-11-11',	'cash',	'TXN884849',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(60,	'REC-48147',	31,	1374.00,	'2026-01-28',	'bank_transfer',	'TXN144830',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(61,	'REC-57960',	31,	1816.00,	'2026-01-06',	'online',	'TXN405183',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(62,	'REC-62370',	32,	3419.00,	'2025-12-16',	'online',	'TXN332396',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(63,	'REC-38951',	32,	4051.00,	'2025-11-12',	'bank_transfer',	'TXN113782',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(64,	'REC-10495',	32,	3107.00,	'2025-11-16',	'cheque',	'TXN796487',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(65,	'REC-49747',	33,	1533.00,	'2026-01-10',	'cheque',	'TXN484073',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(66,	'REC-13469',	33,	1622.00,	'2026-02-05',	'cash',	'TXN999259',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(67,	'REC-96625',	34,	1221.00,	'2025-12-31',	'cheque',	'TXN876878',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(68,	'REC-44059',	34,	4798.00,	'2026-01-19',	'bank_transfer',	'TXN304622',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(69,	'REC-10826',	34,	4703.00,	'2026-01-19',	'cash',	'TXN529825',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(70,	'REC-29430',	35,	2390.00,	'2025-12-31',	'bank_transfer',	'TXN518110',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(71,	'REC-78893',	35,	3088.00,	'2026-01-29',	'bank_transfer',	'TXN404123',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(72,	'REC-74332',	35,	1304.00,	'2025-11-20',	'online',	'TXN421096',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(73,	'REC-70816',	36,	4856.00,	'2026-01-27',	'cash',	'TXN321344',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(74,	'REC-60988',	36,	2508.00,	'2025-12-28',	'bank_transfer',	'TXN722729',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(75,	'REC-16150',	36,	2522.00,	'2025-12-01',	'online',	'TXN712635',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(76,	'REC-49553',	37,	4564.00,	'2025-12-18',	'cash',	'TXN247703',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(77,	'REC-33657',	38,	2983.00,	'2025-12-26',	'online',	'TXN255034',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(78,	'REC-81095',	38,	1703.00,	'2026-01-03',	'bank_transfer',	'TXN926803',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(79,	'REC-43357',	39,	1958.00,	'2025-12-28',	'cheque',	'TXN589746',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(80,	'REC-23865',	40,	1216.00,	'2025-11-15',	'cash',	'TXN581647',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(81,	'REC-90836',	40,	1849.00,	'2026-01-30',	'bank_transfer',	'TXN996212',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(82,	'REC-44702',	40,	1867.00,	'2025-12-10',	'bank_transfer',	'TXN968617',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(83,	'REC-37459',	41,	3978.00,	'2026-01-03',	'online',	'TXN636917',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(84,	'REC-61773',	41,	3993.00,	'2025-11-12',	'cash',	'TXN359895',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(85,	'REC-59830',	41,	2649.00,	'2025-11-20',	'bank_transfer',	'TXN966451',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(86,	'REC-61244',	42,	2182.00,	'2025-12-23',	'bank_transfer',	'TXN595640',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(87,	'REC-66559',	43,	4760.00,	'2025-12-06',	'cheque',	'TXN171831',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(88,	'REC-61821',	44,	1521.00,	'2026-01-07',	'bank_transfer',	'TXN905274',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(89,	'REC-83412',	44,	2947.00,	'2025-11-29',	'cheque',	'TXN121052',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(90,	'REC-48525',	44,	2203.00,	'2025-12-04',	'cheque',	'TXN453571',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(91,	'REC-96834',	45,	1189.00,	'2026-01-11',	'cheque',	'TXN652254',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(92,	'REC-50402',	46,	3762.00,	'2025-11-23',	'cheque',	'TXN922263',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(93,	'REC-21889',	47,	1115.00,	'2026-01-18',	'cash',	'TXN747503',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(94,	'REC-91776',	47,	3922.00,	'2025-11-10',	'online',	'TXN110549',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(95,	'REC-13718',	48,	2755.00,	'2026-01-29',	'cash',	'TXN620581',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(96,	'REC-84177',	49,	4263.00,	'2026-01-30',	'cash',	'TXN293194',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(97,	'REC-61050',	50,	3766.00,	'2025-12-14',	'cash',	'TXN711909',	'Tuition Fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(98,	'REC-81779',	50,	1982.00,	'2025-11-13',	'online',	'TXN686876',	'exam_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(99,	'REC-53561',	50,	2766.00,	'2026-01-27',	'cash',	'TXN145323',	'admission_fee',	'Tuition Fee Payment',	'completed',	3,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL,	NULL,	NULL),
(100,	'REC-6985F155A7F34',	18,	15000.00,	'2026-02-06',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-06 07:49:09',	'2026-02-06 07:49:09',	1,	6,	'2026'),
(101,	'ADM-20260207-6986F68EC6794',	51,	1000.00,	'2026-02-07',	'cash',	NULL,	'Admission Fee',	NULL,	'completed',	2,	'2026-02-07 02:23:42',	'2026-02-07 02:23:42',	2,	NULL,	'2026'),
(102,	'REC-20260207-6986FD845EBAB',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:53:24',	'2026-02-07 02:53:24',	1,	1,	'2026'),
(104,	'REC-20260207-6986FE4042E76',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:56:32',	'2026-02-07 02:56:32',	1,	2,	'2026'),
(106,	'REC-20260207-6986FE938F3D7',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:57:55',	'2026-02-07 02:57:55',	1,	2,	'2026'),
(107,	'REC-20260207-6986FE938F3D7',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:57:55',	'2026-02-07 02:57:55',	1,	3,	'2026'),
(108,	'REC-20260207-6986FE938F3D7',	51,	500.00,	'2026-02-07',	'cash',	NULL,	'Exam Fee',	NULL,	'completed',	2,	'2026-02-07 02:57:55',	'2026-02-07 02:57:55',	13,	3,	'2026'),
(109,	'REC-20260207-6986FE938F3D7',	51,	500.00,	'2026-02-07',	'cash',	NULL,	'Exam Fee',	NULL,	'completed',	2,	'2026-02-07 02:57:55',	'2026-02-07 02:57:55',	13,	6,	'2026'),
(110,	'REC-20260207-6986FE938F3D7',	51,	2000.00,	'2026-02-07',	'cash',	NULL,	'Session Fee',	NULL,	'completed',	2,	'2026-02-07 02:57:55',	'2026-02-07 02:57:55',	15,	NULL,	'2026'),
(111,	'REC-20260207-6986FEA927534',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:58:17',	'2026-02-07 02:58:17',	1,	4,	'2026'),
(112,	'REC-20260207-6986FEA927534',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 02:58:17',	'2026-02-07 02:58:17',	1,	5,	'2026'),
(113,	'REC-20260207-6986FEA927534',	51,	500.00,	'2026-02-07',	'cash',	NULL,	'Exam Fee',	NULL,	'completed',	2,	'2026-02-07 02:58:17',	'2026-02-07 02:58:17',	13,	9,	'2026'),
(114,	'REC-20260207-698700B4B3CDF',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 03:07:00',	'2026-02-07 03:07:00',	1,	6,	'2026'),
(115,	'REC-20260207-698728DFF240B',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 05:58:23',	'2026-02-07 05:58:23',	1,	7,	'2026'),
(116,	'REC-20260207-698728DFF240B',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 05:58:24',	'2026-02-07 05:58:24',	1,	8,	'2026'),
(117,	'REC-20260207-698728DFF240B',	51,	500.00,	'2026-02-07',	'cash',	NULL,	'Exam Fee',	NULL,	'completed',	2,	'2026-02-07 05:58:24',	'2026-02-07 05:58:24',	13,	12,	'2026'),
(118,	'REC-20260207-69876828B5077',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	1,	9,	'2026'),
(119,	'REC-20260207-69876828B5077',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	1,	10,	'2026'),
(120,	'REC-20260207-69876828B5077',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	1,	11,	'2026'),
(121,	'REC-20260207-69876828B5077',	51,	13500.00,	'2026-02-07',	'cash',	NULL,	'Tution fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	1,	12,	'2026'),
(122,	'REC-20260207-69876828B5077',	51,	300.00,	'2026-02-07',	'cash',	NULL,	'Sports & Cultural Fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	14,	6,	'2026'),
(123,	'REC-20260207-69876828B5077',	51,	300.00,	'2026-02-07',	'cash',	NULL,	'Sports & Cultural Fee',	NULL,	'completed',	2,	'2026-02-07 10:28:24',	'2026-02-07 10:28:24',	14,	12,	'2026');

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1,	'student-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(2,	'student-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(3,	'student-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(4,	'student-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(5,	'student-view',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(6,	'payment-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(7,	'payment-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(8,	'payment-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(9,	'payment-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(10,	'payment-view',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(11,	'class-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(12,	'class-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(13,	'class-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(14,	'class-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(15,	'user-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(16,	'user-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(17,	'user-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(18,	'user-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(19,	'expense-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(20,	'expense-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(21,	'expense-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(22,	'expense-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(23,	'expense-approve',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(24,	'expense-view',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(25,	'account-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(26,	'account-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(27,	'account-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(28,	'account-view',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(29,	'report-view',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(30,	'report-export',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(31,	'role-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(32,	'role-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(33,	'role-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(34,	'role-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(35,	'permission-assign',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(36,	'dashboard-admin',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(37,	'dashboard-accountant',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(38,	'dashboard-teacher',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(39,	'dashboard-student',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(40,	'dashboard-parent',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(41,	'setting-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(42,	'setting-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(43,	'fee-list',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(44,	'fee-structure-create',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(45,	'fee-structure-edit',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(46,	'fee-structure-delete',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(47,	'report-income',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(48,	'teacher-list',	'web',	'2026-02-06 07:59:57',	'2026-02-06 07:59:57'),
(49,	'teacher-create',	'web',	'2026-02-06 07:59:57',	'2026-02-06 07:59:57'),
(50,	'teacher-edit',	'web',	'2026-02-06 07:59:57',	'2026-02-06 07:59:57'),
(51,	'teacher-delete',	'web',	'2026-02-06 07:59:57',	'2026-02-06 07:59:57'),
(52,	'teacher-view',	'web',	'2026-02-06 07:59:57',	'2026-02-06 07:59:57');

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,	1),
(2,	1),
(3,	1),
(4,	1),
(5,	1),
(6,	1),
(7,	1),
(8,	1),
(9,	1),
(10,	1),
(11,	1),
(12,	1),
(13,	1),
(14,	1),
(15,	1),
(16,	1),
(17,	1),
(18,	1),
(19,	1),
(20,	1),
(21,	1),
(22,	1),
(23,	1),
(24,	1),
(25,	1),
(26,	1),
(27,	1),
(28,	1),
(29,	1),
(30,	1),
(31,	1),
(32,	1),
(33,	1),
(34,	1),
(35,	1),
(36,	1),
(37,	1),
(38,	1),
(39,	1),
(40,	1),
(41,	1),
(42,	1),
(43,	1),
(44,	1),
(45,	1),
(46,	1),
(47,	1),
(48,	1),
(49,	1),
(50,	1),
(51,	1),
(52,	1),
(1,	2),
(2,	2),
(3,	2),
(5,	2),
(6,	2),
(7,	2),
(10,	2),
(11,	2),
(12,	2),
(13,	2),
(15,	2),
(16,	2),
(17,	2),
(19,	2),
(24,	2),
(29,	2),
(30,	2),
(36,	2),
(41,	2),
(42,	2),
(43,	2),
(44,	2),
(45,	2),
(46,	2),
(48,	2),
(49,	2),
(50,	2),
(52,	2),
(1,	3),
(5,	3),
(6,	3),
(7,	3),
(8,	3),
(10,	3),
(19,	3),
(20,	3),
(21,	3),
(23,	3),
(25,	3),
(26,	3),
(27,	3),
(28,	3),
(29,	3),
(30,	3),
(37,	3),
(1,	4),
(5,	4),
(11,	4),
(38,	4),
(6,	5),
(10,	5),
(39,	5),
(5,	6),
(6,	6),
(7,	6),
(10,	6),
(40,	6);

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1,	'Super Admin',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(2,	'Admin',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(3,	'Accountant',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(4,	'Teacher',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(5,	'Student',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(6,	'Parent',	'web',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25');

DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_class_id_foreign` (`class_id`),
  CONSTRAINT `sections_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sections` (`id`, `class_id`, `name`, `capacity`, `created_at`, `updated_at`) VALUES
(1,	1,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(2,	1,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(3,	2,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(4,	2,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(5,	3,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(6,	3,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(7,	4,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(8,	4,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(9,	5,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(10,	5,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(11,	6,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(12,	6,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(13,	7,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(14,	7,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(15,	8,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(16,	8,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(17,	9,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(18,	9,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(19,	10,	'A',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25'),
(20,	10,	'B',	40,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GMzBqLbqlyKBIaqoWHXcq0VOd8ODxjMH0EOkgEi4',	2,	'127.0.0.1',	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNW5kTWpHQ1VBU1VLdnQyOFdCeENUdnlDZDJZR0NiRzJUTHVOQlg3SyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcGF5bWVudHMvY3JlYXRlP3N0dWRlbnRfaWQ9NTEiO3M6NToicm91dGUiO3M6MTU6InBheW1lbnRzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',	1770656276),
('MZfZtrIC913Kywo5nWsdDoVjvp72vruo1qA8wudi',	2,	'127.0.0.1',	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ0lhb25ZOFhkbFZveXNSTmtLRFd0a3lKWTl1M1VEcmM3SmhkbTNmZiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',	1770656041),
('tCOr24Po39oGV76KFt8lsQJhVFOlsP5Ht5EfX2WT',	2,	'127.0.0.1',	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',	'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNGpTd01Ra2dTTUJ1cHI3SkI3cjBLd3lwdjhQWHhPY2Fjekxqdld0OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zdHVkZW50cy81MSI7czo1OiJyb3V0ZSI7czoxMzoic3R1ZGVudHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',	1770984430);

DROP TABLE IF EXISTS `student_fee_assignments`;
CREATE TABLE `student_fee_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `fee_structure_id` bigint unsigned NOT NULL,
  `discount_type` enum('none','percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_permanent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Permanent discount or one-time for admission',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_fee_assignments_student_id_fee_structure_id_unique` (`student_id`,`fee_structure_id`),
  KEY `student_fee_assignments_fee_structure_id_foreign` (`fee_structure_id`),
  CONSTRAINT `student_fee_assignments_fee_structure_id_foreign` FOREIGN KEY (`fee_structure_id`) REFERENCES `fee_structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_fee_assignments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `student_fee_assignments` (`id`, `student_id`, `fee_structure_id`, `discount_type`, `discount_value`, `is_permanent`, `notes`, `created_at`, `updated_at`) VALUES
(3,	51,	1,	'percentage',	10.00,	0,	NULL,	'2026-02-07 02:20:20',	'2026-02-07 02:20:20'),
(4,	51,	2,	'fixed',	1000.00,	0,	NULL,	'2026-02-07 02:20:20',	'2026-02-07 02:20:20');

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `section_id` bigint unsigned DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `status` enum('active','inactive','graduated','transferred','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_id_unique` (`student_id`),
  KEY `students_class_id_foreign` (`class_id`),
  KEY `students_section_id_foreign` (`section_id`),
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `email`, `phone`, `address`, `photo`, `guardian_name`, `guardian_phone`, `guardian_email`, `guardian_relation`, `class_id`, `section_id`, `enrollment_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'ST-2026-0001',	'Student',	'Name 1',	'2019-02-06',	'female',	'student1@school.com',	'01394487148',	'123 Fake Street, City',	NULL,	'Parent of Student 1',	'01329031380',	'parent1@school.com',	NULL,	6,	12,	'2025-08-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(2,	'ST-2026-0002',	'Student',	'Name 2',	'2010-02-06',	'female',	'student2@school.com',	'01338579593',	'123 Fake Street, City',	NULL,	'Parent of Student 2',	'01965897695',	'parent2@school.com',	NULL,	9,	18,	'2025-01-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(3,	'ST-2026-0003',	'Student',	'Name 3',	'2013-02-06',	'female',	'student3@school.com',	'01343793614',	'123 Fake Street, City',	NULL,	'Parent of Student 3',	'01657590379',	'parent3@school.com',	NULL,	7,	14,	'2025-12-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(4,	'ST-2026-0004',	'Student',	'Name 4',	'2012-02-06',	'female',	'student4@school.com',	'01404741268',	'123 Fake Street, City',	NULL,	'Parent of Student 4',	'01415709749',	'parent4@school.com',	NULL,	5,	10,	'2026-01-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(5,	'ST-2026-0005',	'Student',	'Name 5',	'2014-02-06',	'male',	'student5@school.com',	'01344095587',	'123 Fake Street, City',	NULL,	'Parent of Student 5',	'01585808940',	'parent5@school.com',	NULL,	9,	18,	'2025-10-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(6,	'ST-2026-0006',	'Student',	'Name 6',	'2010-02-06',	'female',	'student6@school.com',	'01909634516',	'123 Fake Street, City',	NULL,	'Parent of Student 6',	'01611957440',	'parent6@school.com',	NULL,	7,	14,	'2025-06-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(7,	'ST-2026-0007',	'Student',	'Name 7',	'2016-02-06',	'female',	'student7@school.com',	'01745564175',	'123 Fake Street, City',	NULL,	'Parent of Student 7',	'01595113967',	'parent7@school.com',	NULL,	6,	11,	'2024-09-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(8,	'ST-2026-0008',	'Student',	'Name 8',	'2010-02-06',	'male',	'student8@school.com',	'01608276969',	'123 Fake Street, City',	NULL,	'Parent of Student 8',	'01899648097',	'parent8@school.com',	NULL,	9,	17,	'2025-10-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(9,	'ST-2026-0009',	'Student',	'Name 9',	'2018-02-06',	'male',	'student9@school.com',	'01477645960',	'123 Fake Street, City',	NULL,	'Parent of Student 9',	'01373409153',	'parent9@school.com',	NULL,	5,	9,	'2024-06-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(10,	'ST-2026-0010',	'Student',	'Name 10',	'2018-02-06',	'female',	'student10@school.com',	'01342786249',	'123 Fake Street, City',	NULL,	'Parent of Student 10',	'01742039809',	'parent10@school.com',	NULL,	2,	3,	'2025-08-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(11,	'ST-2026-0011',	'Student',	'Name 11',	'2019-02-06',	'female',	'student11@school.com',	'01956754969',	'123 Fake Street, City',	NULL,	'Parent of Student 11',	'01494417389',	'parent11@school.com',	NULL,	6,	12,	'2025-02-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(12,	'ST-2026-0012',	'Student',	'Name 12',	'2019-02-06',	'male',	'student12@school.com',	'01968785422',	'123 Fake Street, City',	NULL,	'Parent of Student 12',	'01626157625',	'parent12@school.com',	NULL,	2,	4,	'2024-07-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(13,	'ST-2026-0013',	'Student',	'Name 13',	'2019-02-06',	'female',	'student13@school.com',	'01175431571',	'123 Fake Street, City',	NULL,	'Parent of Student 13',	'01393596771',	'parent13@school.com',	NULL,	6,	12,	'2025-08-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(14,	'ST-2026-0014',	'Student',	'Name 14',	'2014-02-06',	'female',	'student14@school.com',	'01623028829',	'123 Fake Street, City',	NULL,	'Parent of Student 14',	'01223425317',	'parent14@school.com',	NULL,	6,	11,	'2025-11-06',	'active',	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(15,	'ST-2026-0015',	'Student',	'Name 15',	'2011-02-06',	'male',	'student15@school.com',	'01129075652',	'123 Fake Street, City',	NULL,	'Parent of Student 15',	'01635740491',	'parent15@school.com',	NULL,	3,	5,	'2025-08-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(16,	'ST-2026-0016',	'Student',	'Name 16',	'2014-02-06',	'female',	'student16@school.com',	'01419967038',	'123 Fake Street, City',	NULL,	'Parent of Student 16',	'01547049404',	'parent16@school.com',	NULL,	9,	17,	'2025-01-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(17,	'ST-2026-0017',	'Student',	'Name 17',	'2020-02-06',	'male',	'student17@school.com',	'01913646701',	'123 Fake Street, City',	NULL,	'Parent of Student 17',	'01757155313',	'parent17@school.com',	NULL,	10,	20,	'2024-09-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(18,	'ST-2026-0018',	'Student',	'Name 18',	'2010-02-06',	'male',	'student18@school.com',	'01539025583',	'123 Fake Street, City',	NULL,	'Parent of Student 18',	'01403269081',	'parent18@school.com',	NULL,	1,	2,	'2025-07-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(19,	'ST-2026-0019',	'Student',	'Name 19',	'2019-02-06',	'female',	'student19@school.com',	'01841216090',	'123 Fake Street, City',	NULL,	'Parent of Student 19',	'01282101470',	'parent19@school.com',	NULL,	4,	7,	'2024-11-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(20,	'ST-2026-0020',	'Student',	'Name 20',	'2016-02-06',	'female',	'student20@school.com',	'01433660113',	'123 Fake Street, City',	NULL,	'Parent of Student 20',	'01397396235',	'parent20@school.com',	NULL,	2,	4,	'2025-11-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(21,	'ST-2026-0021',	'Student',	'Name 21',	'2014-02-06',	'female',	'student21@school.com',	'01718415156',	'123 Fake Street, City',	NULL,	'Parent of Student 21',	'01979847559',	'parent21@school.com',	NULL,	9,	17,	'2024-06-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(22,	'ST-2026-0022',	'Student',	'Name 22',	'2016-02-06',	'male',	'student22@school.com',	'01523177167',	'123 Fake Street, City',	NULL,	'Parent of Student 22',	'01450361621',	'parent22@school.com',	NULL,	3,	5,	'2025-06-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(23,	'ST-2026-0023',	'Student',	'Name 23',	'2010-02-06',	'female',	'student23@school.com',	'01161329886',	'123 Fake Street, City',	NULL,	'Parent of Student 23',	'01100738557',	'parent23@school.com',	NULL,	10,	19,	'2024-07-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(24,	'ST-2026-0024',	'Student',	'Name 24',	'2013-02-06',	'male',	'student24@school.com',	'01653564882',	'123 Fake Street, City',	NULL,	'Parent of Student 24',	'01663292243',	'parent24@school.com',	NULL,	3,	6,	'2024-07-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(25,	'ST-2026-0025',	'Student',	'Name 25',	'2015-02-06',	'female',	'student25@school.com',	'01875390804',	'123 Fake Street, City',	NULL,	'Parent of Student 25',	'01651905442',	'parent25@school.com',	NULL,	6,	11,	'2025-07-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(26,	'ST-2026-0026',	'Student',	'Name 26',	'2018-02-06',	'male',	'student26@school.com',	'01600614267',	'123 Fake Street, City',	NULL,	'Parent of Student 26',	'01478524523',	'parent26@school.com',	NULL,	1,	2,	'2025-10-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(27,	'ST-2026-0027',	'Student',	'Name 27',	'2020-02-06',	'male',	'student27@school.com',	'01153680465',	'123 Fake Street, City',	NULL,	'Parent of Student 27',	'01945216832',	'parent27@school.com',	NULL,	8,	16,	'2025-10-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(28,	'ST-2026-0028',	'Student',	'Name 28',	'2010-02-06',	'male',	'student28@school.com',	'01942797796',	'123 Fake Street, City',	NULL,	'Parent of Student 28',	'01810166729',	'parent28@school.com',	NULL,	4,	8,	'2026-01-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(29,	'ST-2026-0029',	'Student',	'Name 29',	'2015-02-06',	'female',	'student29@school.com',	'01374613707',	'123 Fake Street, City',	NULL,	'Parent of Student 29',	'01117424512',	'parent29@school.com',	NULL,	6,	11,	'2025-11-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(30,	'ST-2026-0030',	'Student',	'Name 30',	'2013-02-06',	'female',	'student30@school.com',	'01124497830',	'123 Fake Street, City',	NULL,	'Parent of Student 30',	'01278489530',	'parent30@school.com',	NULL,	1,	1,	'2025-04-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(31,	'ST-2026-0031',	'Student',	'Name 31',	'2013-02-06',	'female',	'student31@school.com',	'01469032219',	'123 Fake Street, City',	NULL,	'Parent of Student 31',	'01383266873',	'parent31@school.com',	NULL,	4,	8,	'2026-01-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(32,	'ST-2026-0032',	'Student',	'Name 32',	'2020-02-06',	'male',	'student32@school.com',	'01455169176',	'123 Fake Street, City',	NULL,	'Parent of Student 32',	'01894775262',	'parent32@school.com',	NULL,	4,	7,	'2025-02-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(33,	'ST-2026-0033',	'Student',	'Name 33',	'2016-02-06',	'male',	'student33@school.com',	'01610456964',	'123 Fake Street, City',	NULL,	'Parent of Student 33',	'01905989751',	'parent33@school.com',	NULL,	4,	7,	'2025-08-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(34,	'ST-2026-0034',	'Student',	'Name 34',	'2015-02-06',	'female',	'student34@school.com',	'01603058883',	'123 Fake Street, City',	NULL,	'Parent of Student 34',	'01238764047',	'parent34@school.com',	NULL,	9,	18,	'2024-10-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(35,	'ST-2026-0035',	'Student',	'Name 35',	'2019-02-06',	'female',	'student35@school.com',	'01883141509',	'123 Fake Street, City',	NULL,	'Parent of Student 35',	'01701542900',	'parent35@school.com',	NULL,	6,	11,	'2024-03-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(36,	'ST-2026-0036',	'Student',	'Name 36',	'2013-02-06',	'male',	'student36@school.com',	'01907265403',	'123 Fake Street, City',	NULL,	'Parent of Student 36',	'01785345126',	'parent36@school.com',	NULL,	1,	1,	'2025-08-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(37,	'ST-2026-0037',	'Student',	'Name 37',	'2020-02-06',	'female',	'student37@school.com',	'01139938565',	'123 Fake Street, City',	NULL,	'Parent of Student 37',	'01922819272',	'parent37@school.com',	NULL,	8,	16,	'2025-10-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(38,	'ST-2026-0038',	'Student',	'Name 38',	'2011-02-06',	'female',	'student38@school.com',	'01412254281',	'123 Fake Street, City',	NULL,	'Parent of Student 38',	'01676846024',	'parent38@school.com',	NULL,	9,	18,	'2025-05-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(39,	'ST-2026-0039',	'Student',	'Name 39',	'2020-02-06',	'male',	'student39@school.com',	'01535170741',	'123 Fake Street, City',	NULL,	'Parent of Student 39',	'01929586260',	'parent39@school.com',	NULL,	9,	17,	'2026-01-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(40,	'ST-2026-0040',	'Student',	'Name 40',	'2020-02-06',	'female',	'student40@school.com',	'01675516210',	'123 Fake Street, City',	NULL,	'Parent of Student 40',	'01958691561',	'parent40@school.com',	NULL,	7,	14,	'2025-06-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(41,	'ST-2026-0041',	'Student',	'Name 41',	'2016-02-06',	'male',	'student41@school.com',	'01605568079',	'123 Fake Street, City',	NULL,	'Parent of Student 41',	'01901736241',	'parent41@school.com',	NULL,	5,	9,	'2025-12-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(42,	'ST-2026-0042',	'Student',	'Name 42',	'2017-02-06',	'female',	'student42@school.com',	'01374116897',	'123 Fake Street, City',	NULL,	'Parent of Student 42',	'01111994020',	'parent42@school.com',	NULL,	8,	16,	'2024-09-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(43,	'ST-2026-0043',	'Student',	'Name 43',	'2015-02-06',	'female',	'student43@school.com',	'01332841882',	'123 Fake Street, City',	NULL,	'Parent of Student 43',	'01691604630',	'parent43@school.com',	NULL,	8,	15,	'2025-11-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(44,	'ST-2026-0044',	'Student',	'Name 44',	'2014-02-06',	'female',	'student44@school.com',	'01365646788',	'123 Fake Street, City',	NULL,	'Parent of Student 44',	'01574865970',	'parent44@school.com',	NULL,	3,	5,	'2024-08-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(45,	'ST-2026-0045',	'Student',	'Name 45',	'2015-02-06',	'female',	'student45@school.com',	'01619991619',	'123 Fake Street, City',	NULL,	'Parent of Student 45',	'01794029774',	'parent45@school.com',	NULL,	9,	17,	'2024-04-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(46,	'ST-2026-0046',	'Student',	'Name 46',	'2015-02-06',	'male',	'student46@school.com',	'01240316907',	'123 Fake Street, City',	NULL,	'Parent of Student 46',	'01326177371',	'parent46@school.com',	NULL,	2,	3,	'2025-11-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(47,	'ST-2026-0047',	'Student',	'Name 47',	'2013-02-06',	'male',	'student47@school.com',	'01307698422',	'123 Fake Street, City',	NULL,	'Parent of Student 47',	'01386266623',	'parent47@school.com',	NULL,	1,	2,	'2025-09-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(48,	'ST-2026-0048',	'Student',	'Name 48',	'2012-02-06',	'male',	'student48@school.com',	'01447146453',	'123 Fake Street, City',	NULL,	'Parent of Student 48',	'01457206322',	'parent48@school.com',	NULL,	1,	1,	'2024-06-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(49,	'ST-2026-0049',	'Student',	'Name 49',	'2019-02-06',	'female',	'student49@school.com',	'01363476308',	'123 Fake Street, City',	NULL,	'Parent of Student 49',	'01869585756',	'parent49@school.com',	NULL,	5,	10,	'2025-10-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(50,	'ST-2026-0050',	'Student',	'Name 50',	'2013-02-06',	'male',	'student50@school.com',	'01520171306',	'123 Fake Street, City',	NULL,	'Parent of Student 50',	'01130910576',	'parent50@school.com',	NULL,	5,	9,	'2025-05-06',	'active',	'2026-02-06 06:41:26',	'2026-02-06 06:41:26',	NULL),
(51,	'STU202600051',	'aSLAM',	'AYIN',	'2026-02-04',	'male',	'admin@madrasa.com',	'017178855663',	NULL,	NULL,	'ayesha',	'01761127260',	NULL,	'mother',	1,	1,	'2026-02-07',	'active',	'2026-02-07 02:09:29',	'2026-02-07 02:09:29',	NULL);

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_date` date NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_phone_unique` (`phone`),
  UNIQUE KEY `teachers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `teachers` (`id`, `name`, `email`, `phone`, `designation`, `join_date`, `salary`, `address`, `photo`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'Teacher 1',	'teacher1@school.com',	'01317051459',	'Head of Department',	'2022-07-06',	68724.00,	'Teachers Colony, Road 1',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(2,	'Teacher 2',	'teacher2@school.com',	'01851116913',	'Lecturer',	'2025-02-06',	68170.00,	'Teachers Colony, Road 2',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(3,	'Teacher 3',	'teacher3@school.com',	'01153156032',	'Assistant Teacher',	'2023-07-06',	61449.00,	'Teachers Colony, Road 3',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(4,	'Teacher 4',	'teacher4@school.com',	'01109150634',	'Junior Teacher',	'2025-05-06',	69167.00,	'Teachers Colony, Road 4',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(5,	'Teacher 5',	'teacher5@school.com',	'01500025751',	'Assistant Teacher',	'2024-09-06',	36350.00,	'Teachers Colony, Road 5',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(6,	'Teacher 6',	'teacher6@school.com',	'01889569755',	'Junior Teacher',	'2023-11-06',	59527.00,	'Teachers Colony, Road 6',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(7,	'Teacher 7',	'teacher7@school.com',	'01709786198',	'Assistant Teacher',	'2025-02-06',	61867.00,	'Teachers Colony, Road 7',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(8,	'Teacher 8',	'teacher8@school.com',	'01193164530',	'Junior Teacher',	'2023-02-06',	26067.00,	'Teachers Colony, Road 8',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(9,	'Teacher 9',	'teacher9@school.com',	'01354197421',	'Senior Teacher',	'2024-03-06',	63338.00,	'Teachers Colony, Road 9',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36'),
(10,	'Teacher 10',	'teacher10@school.com',	'01432438609',	'Junior Teacher',	'2025-06-06',	49813.00,	'Teachers Colony, Road 10',	NULL,	1,	'2026-02-06 08:02:36',	'2026-02-06 08:02:36');

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_number_unique` (`transaction_number`),
  KEY `transactions_account_id_foreign` (`account_id`),
  KEY `transactions_created_by_foreign` (`created_by`),
  CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `photo`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Test User',	'test@example.com',	'2026-02-06 06:27:45',	'$2y$12$xuw8uy0gWqNpDY8wPIPcjur290lSfMWRMFkymP4RwgFavCEr5RsEe',	NULL,	NULL,	'active',	NULL,	'UmJOQEFqtU',	'2026-02-06 06:27:45',	'2026-02-06 06:27:45',	NULL),
(2,	'Super Admin',	'admin@erp.com',	NULL,	'$2y$12$ohkwfjV3GlTfNguFZ8z66uLi/e2bvaybriJIgBINWkgtc8A3nJSdS',	NULL,	NULL,	'active',	NULL,	NULL,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(3,	'John Accountant',	'accountant@erp.com',	NULL,	'$2y$12$4k.gtdQ9aEZJS309szqsM.ASSbjKcLjOBW2Z7Idlmi/k.rggPu/2S',	NULL,	NULL,	'active',	NULL,	NULL,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL),
(4,	'Sarah Teacher',	'teacher@erp.com',	NULL,	'$2y$12$csSYHvStNN8Pe4T8whYkqusB7aYqy6nVWLySpGehAplK9E3TDbZbS',	NULL,	NULL,	'active',	NULL,	NULL,	'2026-02-06 06:41:25',	'2026-02-06 06:41:25',	NULL);

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vendors` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `bank_name`, `account_number`, `tax_number`, `created_at`, `updated_at`) VALUES
(1,	'Office Depot',	'Manager',	'0123456789',	'officedepot@vendor.com',	'Vendor Address',	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(2,	'Power Company',	'Manager',	'0123456789',	'powercompany@vendor.com',	'Vendor Address',	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(3,	'Net Provider',	'Manager',	'0123456789',	'netprovider@vendor.com',	'Vendor Address',	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26'),
(4,	'Cleaning Services Inc',	'Manager',	'0123456789',	'cleaningservicesinc@vendor.com',	'Vendor Address',	NULL,	NULL,	NULL,	'2026-02-06 06:41:26',	'2026-02-06 06:41:26');

-- 2026-02-13 13:53:12
