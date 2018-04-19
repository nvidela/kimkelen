ALTER TABLE `student` 
ADD COLUMN `personal_data` VARCHAR(255) NULL DEFAULT NULL AFTER `origin_school_id`,
ADD COLUMN `file_data` VARCHAR(255) NULL DEFAULT NULL AFTER `personal_data`;