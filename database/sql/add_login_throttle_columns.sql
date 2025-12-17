-- =====================================================
-- SQL Script: Add Login Throttle Columns to Users Table
-- For: MayClass Account Lock Feature
-- Run this in phpMyAdmin on your hosting
-- =====================================================

-- Add columns for tracking failed login attempts
ALTER TABLE `users` 
ADD COLUMN `failed_login_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `remember_token`,
ADD COLUMN `locked_until` TIMESTAMP NULL DEFAULT NULL AFTER `failed_login_attempts`,
ADD COLUMN `last_failed_login_at` TIMESTAMP NULL DEFAULT NULL AFTER `locked_until`;

-- Add index for faster lock checking during login
ALTER TABLE `users` ADD INDEX `idx_locked_until` (`locked_until`);
