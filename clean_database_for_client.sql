-- ============================================
-- Clean Database for Client Deployment
-- This script removes all data but keeps the schema
-- Creates a default admin account for initial setup
-- ============================================

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TRUNCATE ALL TABLES (Remove Data, Keep Schema)
-- ============================================

-- User Management Tables
TRUNCATE TABLE `users`;
TRUNCATE TABLE `faculty`;
TRUNCATE TABLE `students`;
TRUNCATE TABLE `student_enrollments`;
TRUNCATE TABLE `faculty_subjects`;

-- Subject Management
TRUNCATE TABLE `subjects`;

-- Resource Management
TRUNCATE TABLE `resources`;

-- AI Generated Content
TRUNCATE TABLE `ai_quizzes`;
TRUNCATE TABLE `ai_question_papers`;
TRUNCATE TABLE `ai_assignments`;
TRUNCATE TABLE `assignments`;

-- Student Submissions
TRUNCATE TABLE `quiz_attempts`;

-- AI Chat/Buddy Tables
TRUNCATE TABLE `ai_chat_sessions`;
TRUNCATE TABLE `ai_chat_messages`;

-- Usage Logs
TRUNCATE TABLE `ai_usage_logs`;

-- Migrations
TRUNCATE TABLE `migrations`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- CREATE DEFAULT ADMIN ACCOUNT
-- ============================================
-- Username: admin
-- Password: admin123
-- Note: Client should change this password immediately after first login

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) 
VALUES (1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW());

-- ============================================
-- COMPLETION MESSAGE
-- ============================================
SELECT 'Database cleaned successfully!' AS Status;
SELECT 'Default Admin Account Created:' AS Info;
SELECT 'Username: admin' AS Username;
SELECT 'Password: admin123' AS Password;
SELECT 'IMPORTANT: Change the admin password after first login!' AS Warning;
