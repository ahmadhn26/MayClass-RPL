
CREATE DATABASE IF NOT EXISTS mayclass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mayclass_db;

-- --------------------------------------------------------
-- Tabel inti aplikasi yang mengikuti struktur migrasi Laravel
-- --------------------------------------------------------

-- --------------------------------------------------------
-- TABLE: users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('visitor', 'student', 'tutor', 'admin') NOT NULL DEFAULT 'visitor',
  `student_id` varchar(20) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `gender` enum('male', 'female', 'other') DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_student_id_unique` (`student_id`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: tutor_profiles
-- --------------------------------------------------------
CREATE TABLE `tutor_profiles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `specializations` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `experience_years` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `students_taught` int UNSIGNED NOT NULL DEFAULT 0,
  `hours_taught` int UNSIGNED NOT NULL DEFAULT 0,
  `rating` decimal(3,2) DEFAULT NULL,
  `certifications` json DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tutor_profiles_slug_unique` (`slug`),
  KEY `tutor_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `tutor_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: packages
-- --------------------------------------------------------
CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `grade_range` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `card_price_label` varchar(255) NOT NULL,
  `detail_title` varchar(255) NOT NULL,
  `detail_price_label` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `summary` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: package_features
-- --------------------------------------------------------
CREATE TABLE `package_features` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `package_features_package_id_foreign` (`package_id`),
  CONSTRAINT `package_features_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `cardholder_name` varchar(255) DEFAULT NULL,
  `card_number_last_four` varchar(255) DEFAULT NULL,
  `payment_proof_path` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_package_id_foreign` (`package_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: enrollments
-- --------------------------------------------------------
CREATE TABLE `enrollments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `starts_at` date NOT NULL,
  `ends_at` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enrollments_user_package_order_unique` (`user_id`,`package_id`,`order_id`),
  KEY `enrollments_package_id_foreign` (`package_id`),
  KEY `enrollments_order_id_foreign` (`order_id`),
  CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `enrollments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: materials
-- --------------------------------------------------------
CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  `resource_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_slug_unique` (`slug`),
  KEY `materials_package_id_foreign` (`package_id`),
  CONSTRAINT `materials_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: material_objectives
-- --------------------------------------------------------
CREATE TABLE `material_objectives` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `material_objectives_material_id_foreign` (`material_id`),
  CONSTRAINT `material_objectives_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: material_chapters
-- --------------------------------------------------------
CREATE TABLE `material_chapters` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `material_chapters_material_id_foreign` (`material_id`),
  CONSTRAINT `material_chapters_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: quizzes
-- --------------------------------------------------------
CREATE TABLE `quizzes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `class_level` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  `duration_label` varchar(255) NOT NULL,
  `question_count` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quizzes_slug_unique` (`slug`),
  KEY `quizzes_package_id_foreign` (`package_id`),
  CONSTRAINT `quizzes_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: quiz_levels
-- --------------------------------------------------------
CREATE TABLE `quiz_levels` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quiz_levels_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `quiz_levels_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: quiz_takeaways
-- --------------------------------------------------------
CREATE TABLE `quiz_takeaways` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quiz_takeaways_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `quiz_takeaways_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: schedule_templates
CREATE TABLE `schedule_templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `class_level` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `day_of_week` tinyint UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `duration_minutes` smallint UNSIGNED NOT NULL DEFAULT 90,
  `student_count` smallint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `schedule_templates_user_id_day_of_week_index` (`user_id`,`day_of_week`),
  KEY `schedule_templates_package_id_foreign` (`package_id`),
  CONSTRAINT `schedule_templates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedule_templates_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: schedule_sessions
-- --------------------------------------------------------
CREATE TABLE `schedule_sessions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `schedule_template_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `class_level` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `student_count` smallint UNSIGNED DEFAULT NULL,
  `mentor_name` varchar(255) NOT NULL,
  `start_at` datetime NOT NULL,
  `duration_minutes` smallint UNSIGNED NOT NULL DEFAULT 90,
  `is_highlight` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'scheduled',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `schedule_sessions_user_id_foreign` (`user_id`),
  KEY `schedule_sessions_package_id_foreign` (`package_id`),
  KEY `schedule_sessions_schedule_template_id_foreign` (`schedule_template_id`),
  KEY `schedule_sessions_status_index` (`status`),
  KEY `schedule_sessions_start_at_is_highlight_index` (`start_at`,`is_highlight`),
  CONSTRAINT `schedule_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `schedule_sessions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `schedule_sessions_schedule_template_id_foreign` FOREIGN KEY (`schedule_template_id`) REFERENCES `schedule_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabel bawaan Laravel untuk autentikasi dan notifikasi
-- --------------------------------------------------------
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLE: password_resets
-- --------------------------------------------------------
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- INSERT DATA AWAL (ADMIN DEFAULT)
-- --------------------------------------------------------
INSERT INTO `users` (`name`, `username`, `email`, `password`, `role`)
VALUES ('Admin MayClass', 'admin', 'admin@mayclass.com', '$2y$10$kU9mXLgNoycC6s5yGswB0OfmZ7Nfq1qAhdgLoM9JykOYoP7jN2qZy', 'admin');

-- Password di atas = "admin123" (bcrypt)

-- --------------------------------------------------------
-- TABLE: sessions
-- --------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
