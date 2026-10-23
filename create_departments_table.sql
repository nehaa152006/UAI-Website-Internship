-- Create departments table
CREATE TABLE IF NOT EXISTS `departments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(20) NOT NULL,
    `description` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add some default departments
INSERT INTO `departments` (`name`, `code`, `description`) VALUES
('Computer Science', 'CS', 'Department of Computer Science and Engineering'),
('Information Technology', 'IT', 'Department of Information Technology'),
('Electronics', 'EC', 'Department of Electronics and Communication'),
('Mechanical', 'ME', 'Department of Mechanical Engineering'),
('Civil', 'CE', 'Department of Civil Engineering');

-- Update faculty table to use department_id instead of department string
-- First, add the new column
ALTER TABLE `faculty` ADD COLUMN `department_id` int(11) DEFAULT NULL AFTER `user_id`;

-- Add foreign key constraint
ALTER TABLE `faculty` ADD CONSTRAINT `fk_faculty_department` 
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL;

-- Migrate existing department data (if any)
-- This will try to match existing department strings to the new department table
UPDATE `faculty` f
INNER JOIN `departments` d ON f.`department` = d.`name`
SET f.`department_id` = d.`id`
WHERE f.`department` IS NOT NULL;

-- Note: Keep the old 'department' column for now for backward compatibility
-- You can drop it later after verifying everything works:
-- ALTER TABLE `faculty` DROP COLUMN `department`;
