# College Academic Portal

An AI-Integrated College Academic Portal built with CodeIgniter framework and phpMyAdmin database backend.

## Features

- **Role-based Authentication**: Separate interfaces for Admin, Faculty, and Student roles
- **Content Management**: Faculty can upload and manage educational resources
- **AI Integration**: Gemini API for assignment generation and document chat
- **Semester-based Access**: Students can access current and past semester materials
- **Document Chat**: AI-powered chat interface for interacting with study materials
- **Question Paper Generation**: OCR-based format recognition and AI content generation

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- phpMyAdmin (for database management)
- CodeIgniter 3.x framework

## Installation

1. **Clone/Download the project files**
   ```bash
   # Place all files in your web server document root
   ```

2. **Download CodeIgniter Framework**
   - Download CodeIgniter 3.x from https://codeigniter.com/
   - Extract the `system` folder to the project root
   - The project structure should look like:
     ```
     /
     ├── application/
     ├── system/          (CodeIgniter system files)
     ├── index.php
     ├── .htaccess
     └── database_schema.sql
     ```

3. **Database Setup**
   ```sql
   # Import the database schema
   mysql -u root -p < database_schema.sql
   
   # Or use phpMyAdmin to import database_schema.sql
   ```

4. **Configuration**
   - Update `application/config/database.php` with your database credentials
   - Update `application/config/config.php` with your base URL
   - Ensure `uploads/` directory exists and is writable

5. **Web Server Configuration**
   - Ensure mod_rewrite is enabled for Apache
   - Point document root to the project directory
   - Ensure .htaccess file is being processed

## Default Login Credentials

- **Admin**: username: `admin`, password: `admin123`
- Additional users need to be created through the admin interface

## Project Structure

```
application/
├── config/          # Configuration files
├── controllers/     # MVC Controllers
├── core/           # Extended core classes
├── models/         # Database models
└── views/          # View templates
    ├── auth/       # Authentication views
    ├── admin/      # Admin interface views
    ├── faculty/    # Faculty interface views
    ├── student/    # Student interface views
    └── templates/  # Common templates
```

## Key Components

### Authentication System
- Role-based login with separate interfaces
- Session management with security controls
- Permission enforcement throughout the application

### User Management (Admin)
- Create and manage faculty accounts
- Assign subjects to faculty members
- Monitor system usage and statistics

### Content Management (Faculty)
- Upload educational resources (PDF, PPT, Excel, CSV, ePub, web links)
- Create manual assignments
- AI-powered quiz and assignment generation
- Question paper format recognition and generation

### Student Interface
- Semester-based resource access
- Document chat with AI assistance
- Progress tracking and material review

## Database Schema

The application uses a normalized database schema with the following main tables:
- `users` - All system users with role-based access
- `faculty` - Faculty-specific information
- `students` - Student-specific information and semester tracking
- `subjects` - Course/subject definitions
- `faculty_subjects` - Faculty-to-subject assignments
- `resources` - Educational materials and files
- `assignments` - Assignment definitions
- `chat_sessions` - Document chat sessions
- `chat_messages` - Chat conversation history

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention through CodeIgniter's query builder
- XSS protection through input filtering
- CSRF protection (configurable)
- Role-based access control
- Session security and timeout handling
- File upload validation and restrictions

## AI Integration (Future Implementation)

The system is designed to integrate with:
- **Gemini API** for content generation and chat responses
- **OCR Services** for question paper format analysis
- **RAG System** for document-based chat functionality

## Development Notes

This implementation provides the core framework and structure. Future tasks will add:
- Complete CRUD operations for all entities
- File upload and storage mechanisms
- AI service integrations
- Advanced user interfaces
- Property-based testing
- Comprehensive error handling

## Requirements Compliance

This implementation addresses the following requirements:
- **7.1**: phpMyAdmin-compatible MySQL database structure
- **7.2**: CodeIgniter framework MVC architecture
- **7.3**: CodeIgniter database abstraction layer usage
- **6.1**: Separate login interfaces for different roles
- **6.2**: Role-based dashboard redirection
- **1.1**: Admin dashboard with management capabilities
- **4.1**: Student dashboard with semester cards interface

## License

This project is developed for educational purposes as part of the College Academic Portal specification.