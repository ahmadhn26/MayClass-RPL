-- =============================================
-- Fix Missing Login Throttle Columns
-- Run this SQL on your hosting database
-- =============================================

-- Add login throttle columns to users table
ALTER TABLE `users` 
ADD COLUMN `failed_login_attempts` int unsigned NOT NULL DEFAULT '0' AFTER `account_number`,
ADD COLUMN `locked_until` timestamp NULL DEFAULT NULL AFTER `failed_login_attempts`,
ADD COLUMN `last_failed_login_at` timestamp NULL DEFAULT NULL AFTER `locked_until`;
