-- Migration: Add Multiple Images Support
-- Run this to update announcements and shops tables for multiple images

-- 1. Add images column to announcements (keep image for backward compatibility, migrate data)
ALTER TABLE announcements ADD COLUMN images TEXT NULL AFTER image;

-- Migrate existing single image to images array
UPDATE announcements 
SET images = CONCAT('["', image, '"]') 
WHERE image IS NOT NULL AND image != '' AND (images IS NULL OR images = '');

-- 2. Add images column to shops table
ALTER TABLE shops ADD COLUMN images TEXT NULL AFTER contact;

-- Note: After migration, you can optionally drop the old 'image' column from announcements:
-- ALTER TABLE announcements DROP COLUMN image;

