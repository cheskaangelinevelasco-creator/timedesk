-- Migration: add selected_freebies column to payment table
ALTER TABLE `payment` ADD COLUMN `selected_freebies` TEXT DEFAULT NULL;

-- Make sure existing rows are set to NULL explicitly (no-op but clear intent)
UPDATE `payment` SET `selected_freebies` = NULL WHERE `selected_freebies` IS NULL;
