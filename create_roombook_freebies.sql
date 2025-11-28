-- Migration: add selected_freebies column to roombook
ALTER TABLE `roombook` 
  ADD COLUMN `selected_freebies` TEXT DEFAULT NULL;

-- Backfill existing rows (optional) -- leave NULL if already present
UPDATE `roombook` SET `selected_freebies` = NULL WHERE `selected_freebies` IS NULL;
