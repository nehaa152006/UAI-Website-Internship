# Design Document: AI-Integrated College Academic Portal

## Overview

The AI-Integrated College Academic Portal is a web-based educational management system built on CodeIgniter framework with phpMyAdmin database backend. The system provides role-based access control for three user types (Admin, Faculty, Student) and integrates Google's Gemini API for AI-powered content generation and document-based chat functionality.

The architecture follows a modular design with clear separation between authentication, content management, AI services, and user interfaces. The system emphasizes security through role-based permissions and provides intelligent content access based on semester progression.

## Architecture

### High-Level Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Browser   │    │   Mobile App    │    │  API Clients    │
└─────────┬───────┘    └─────────┬───────┘    └─────────┬───────┘
          │                      │                      │
          └──────────────────────┼──────────────────────┘
                                 │
                    ┌─────────────────┐
                    │  Load Balancer  │
                    └─────────┬───────┘
                              │
                 ┌─────────────────────┐
                 │  CodeIgniter App    │
                 │  ┌───────────────┐  │
                 │  │ Controllers   │  │
                 │  │ Models        │  │
                 │  │ Views         │  │
                 │  │ Libraries     │  │
                 │  └───────────────┘  │
                 └─────────┬───────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
┌───────▼───────┐ ┌────────▼────────┐ ┌───────▼───────┐
│ phpMyAdmin    │ │  Gemini API     │ │ File Storage  │
│ Database      │ │  Services       │ │ System        │
└───────────────┘ └─────────────────┘ └───────────────┘
```

### System Components

1. **Authentication Layer**: Role-based login and session management
2. **Content Management Layer**: File upload, storage, and retrieval
3. **AI Integration Layer**: Gemini API services for content generation and document chat
4. **Access Control Layer**: Permission enforcement and semester-based restrictions
5. **Database Layer**: phpMyAdmin for data persistence
6. **User Interface Layer**: Role-specific dashboards and interactions

## Components and Interfaces

### Authentication Module

**Components:**
- `Auth_Controller`: Handles login/logout for all user roles
- `Session_Manager`: Manages user sessions and role persistence
- `Permission_Guard`: Enforces role-based access controls

**Interfaces:**
```php
interface AuthenticationInterface {
    public function login(string $username, string $password, string $role): bool;
    public function logout(): void;
    public function validateSession(): bool;
    public function hasPermission(string $resource, string $action): bool;
}
```

### User Management Module

**Components:**
- `Admin_Controller`: Admin-specific operations
- `User_Model`: Database operations for user accounts
- `Role_Manager`: Faculty role and subject assignment management

**Interfaces:**
```php
interface UserManagementInterface {
    public function createFaculty(array $facultyData): int;
    public function assignSubject(int $facultyId, int $subjectId): bool;
    public function removeFaculty(int $facultyId): bool;
    public function getFacultySubjects(int $facultyId): array;
}
```

### Content Management Module

**Components:**
- `Resource_Controller`: File upload and management
- `File_Handler`: File validation and storage
- `Content_Model`: Database operations for resources

**Interfaces:**
```php
interface ContentManagementInterface {
    public function uploadResource(array $fileData, int $subjectId): bool;
    public function validateFileType(string $filename): bool;
    public function getResourcesBySubject(int $subjectId): array;
    public function deleteResource(int $resourceId): bool;
}
```

### AI Services Module

**Components:**
- `Gemini_Service`: API integration with Google Gemini
- `Document_Chat_Engine`: Direct document content chat implementation
- `OCR_Processor`: Image analysis for question paper formats
- `Content_Generator`: AI-powered assignment and quiz creation

**Interfaces:**
```php
interface AIServicesInterface {
    public function generateAssignment(array $topics, array $parameters): string;
    public function generateQuiz(array $content, int $questionCount): array;
    public function analyzeQuestionPaperFormat(string $imagePath): array;
    public function chatWithDocument(string $query, string $documentContent): string;
}
```

### Student Access Module

**Components:**
- `Student_Controller`: Student-specific operations
- `Semester_Manager`: Semester progression and access control
- `Resource_Access`: Content retrieval with permission checks

**Interfaces:**
```php
interface StudentAccessInterface {
    public function getCurrentSemesterResources(int $studentId): array;
    public function getPastSemesterResources(int $studentId): array;
    public function checkFutureSemesterAccess(int $studentId, int $semesterId): bool;
    public function updateSemesterProgression(int $studentId, int $newSemester): bool;
}
```

## Data Models

### User Management Schema

```sql
-- Users table for all system users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Faculty-specific information
CREATE TABLE faculty (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    employee_id VARCHAR(20) UNIQUE,
    department VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Student-specific information
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    student_id VARCHAR(20) UNIQUE,
    current_semester INT NOT NULL,
    enrollment_year YEAR,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Academic Structure Schema

```sql
-- Subjects/Courses
CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_code VARCHAR(20) UNIQUE NOT NULL,
    subject_name VARCHAR(200) NOT NULL,
    semester INT NOT NULL,
    credits INT DEFAULT 3,
    is_active BOOLEAN DEFAULT TRUE
);

-- Faculty-Subject assignments
CREATE TABLE faculty_subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    subject_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assignment (faculty_id, subject_id)
);
```

### Content Management Schema

```sql
-- Resource storage
CREATE TABLE resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    faculty_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    file_type ENUM('pdf', 'ppt', 'excel', 'csv', 'epub', 'weblink') NOT NULL,
    file_path VARCHAR(500),
    file_size BIGINT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Assignments
CREATE TABLE assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    faculty_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    total_marks INT NOT NULL,
    due_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_ai_generated BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Question papers
CREATE TABLE question_papers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    faculty_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    total_marks INT NOT NULL,
    format_template_id INT,
    generated_content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Question paper format templates
CREATE TABLE paper_formats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    template_name VARCHAR(100) NOT NULL,
    format_structure JSON,
    sample_image_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
```

### AI Integration Schema

```sql
-- Chat sessions for document interaction
CREATE TABLE chat_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    resource_id INT NOT NULL,
    session_token VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
);

-- Chat messages
CREATE TABLE chat_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    message_type ENUM('user', 'assistant') NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property Reflection

After analyzing all acceptance criteria, several properties can be consolidated to eliminate redundancy:

- Properties 5.2 and 5.3 both test RAG content restriction and can be combined into a single comprehensive property
- Properties 2.3 and 2.5 both test content storage outcomes and can be combined
- Properties 6.3 and 6.5 both test authentication security and can be combined

### Core System Properties

**Property 1: Faculty account creation persistence**
*For any* admin user and valid faculty data, creating a faculty account should result in the account being stored in the database with correct role permissions assigned
**Validates: Requirements 1.2**

**Property 2: Subject assignment access restriction**
*For any* faculty user and subject assignment, the faculty user should only be able to access resources and perform operations on subjects they are assigned to
**Validates: Requirements 1.3**

**Property 3: Faculty account removal cleanup**
*For any* faculty account, when an admin removes it, the account should be deactivated and all associated permissions should be revoked
**Validates: Requirements 1.4**

**Property 4: Admin-only privilege modification**
*For any* attempt to modify faculty roles or permissions, only admin users should be able to successfully perform these operations
**Validates: Requirements 1.5**

**Property 5: File type validation**
*For any* file upload attempt, the system should validate the file format against supported types (PDF, PPT, Excel, CSV, ePub, web links) and reject unsupported formats
**Validates: Requirements 2.1**

**Property 6: Upload permission verification**
*For any* faculty user attempting to upload content, the system should verify the user has subject assignment permissions for the target subject before allowing upload
**Validates: Requirements 2.2**

**Property 7: Content storage and availability**
*For any* successfully uploaded resource content, the system should store it with proper metadata, access controls, and make it available for document chat and student access
**Validates: Requirements 2.3, 2.5**

**Property 8: Faculty content visibility restriction**
*For any* faculty user managing resources, the system should display only content for subjects matching their subject assignments
**Validates: Requirements 2.4**

**Property 9: AI assignment generation**
*For any* faculty request for AI-generated assignments, the system should use Gemini API to create assessments based on uploaded resource content
**Validates: Requirements 3.2**

**Property 10: AI quiz generation**
*For any* faculty request for quiz generation, the system should use Gemini API to generate questions from specified topics and materials
**Validates: Requirements 3.3**

**Property 11: OCR format analysis**
*For any* uploaded question paper format image, the OCR service should extract structural elements including layout, spacing, and formatting patterns
**Validates: Requirements 3.4, 8.1**

**Property 12: Question paper format compliance**
*For any* question paper generation request, the system should create papers following the analyzed format with specified parameters (total marks, question distribution, faculty attribution)
**Validates: Requirements 3.5, 8.3, 8.4, 8.5**

**Property 13: Format template persistence**
*For any* completed format analysis, the system should store the recognized template for reuse in future question paper generation
**Validates: Requirements 8.2**

**Property 14: Current semester access**
*For any* student user accessing current semester content, the system should provide full access to all active course resource content
**Validates: Requirements 4.2**

**Property 15: Past semester access**
*For any* student user requesting past semester materials, the system should grant access to all previous resource content, quizzes, and question papers
**Validates: Requirements 4.3**

**Property 16: Future semester access denial**
*For any* student user attempting to access future semester content, the system should deny access and maintain content restrictions
**Validates: Requirements 4.4**

**Property 17: Semester progression updates**
*For any* semester progression event, the system should automatically update student user access permissions to reflect the new current semester
**Validates: Requirements 4.5**

**Property 18: Document chat content restriction**
*For any* student query through the chat interface, the system should process queries using only the selected document's content as context and ensure answers derive exclusively from that document
**Validates: Requirements 5.2, 5.3**

**Property 19: Document scope limitation**
*For any* student query about content not present in the selected document, the system should indicate the information is not available in the current document rather than generating external information
**Validates: Requirements 5.4**

**Property 20: Chat session history**
*For any* chat interaction, the system should maintain conversation history for the current session
**Validates: Requirements 5.5**

**Property 21: Role-based dashboard redirection**
*For any* successful authentication, the system should redirect users to role-specific dashboards with appropriate feature access
**Validates: Requirements 6.2**

**Property 22: Session security and permission enforcement**
*For any* established user session, the system should maintain session security and enforce role-based permissions throughout the session using secure authentication methods
**Validates: Requirements 6.3, 6.5**

**Property 23: Unauthorized access handling**
*For any* unauthorized access attempt, the system should deny access and log the security event
**Validates: Requirements 6.4**

**Property 24: phpMyAdmin database usage**
*For any* data operation, the system should use phpMyAdmin database for all data storage
**Validates: Requirements 7.1**

**Property 25: CodeIgniter database abstraction**
*For any* database operation, the system should use CodeIgniter database abstraction layer for secure data access
**Validates: Requirements 7.3**

**Property 26: CodeIgniter file handling**
*For any* file upload operation, the system should store files using CodeIgniter file handling capabilities
**Validates: Requirements 7.4**

**Property 27: CodeIgniter API service integration**
*For any* API integration requirement, the system should implement Gemini API calls through CodeIgniter service layers
**Validates: Requirements 7.5**

## Error Handling

### Authentication Errors
- **Invalid Credentials**: Return appropriate error messages without revealing whether username or password is incorrect
- **Session Timeout**: Redirect to login with session expired message
- **Insufficient Permissions**: Display access denied message and log security event
- **Account Deactivated**: Prevent login and display account status message

### File Upload Errors
- **Unsupported File Type**: Reject upload with clear error message about supported formats
- **File Size Limits**: Enforce maximum file size and display appropriate error
- **Storage Failures**: Handle disk space issues and provide user feedback
- **Permission Errors**: Verify faculty subject assignments before upload

### AI Service Errors
- **Gemini API Failures**: Implement retry logic and fallback error messages
- **OCR Processing Errors**: Handle image analysis failures gracefully
- **Content Generation Timeouts**: Provide user feedback for long-running operations
- **Invalid AI Responses**: Validate generated content before presenting to users

### Database Errors
- **Connection Failures**: Implement connection pooling and retry mechanisms
- **Query Errors**: Log database errors and provide user-friendly messages
- **Data Integrity Violations**: Handle constraint violations with appropriate feedback
- **Transaction Rollbacks**: Ensure data consistency during complex operations

### Access Control Errors
- **Semester Restriction Violations**: Block future semester access with clear messaging
- **Subject Assignment Violations**: Prevent faculty from accessing unassigned subjects
- **Role Permission Violations**: Enforce admin-only operations strictly
- **Resource Access Violations**: Validate student access to resources based on semester

## Testing Strategy

### Dual Testing Approach

The system will implement both unit testing and property-based testing to ensure comprehensive coverage:

**Unit Testing:**
- Specific examples demonstrating correct behavior
- Integration points between components  
- Edge cases and error conditions
- Authentication flows and permission checks
- File upload and validation scenarios
- Database operations and data integrity

**Property-Based Testing:**
- Universal properties that should hold across all inputs
- Role-based access control verification
- Content generation and AI integration
- Semester progression and access restrictions
- RAG system behavior with various content types

### Property-Based Testing Implementation

**Framework Selection:** PHPUnit with Eris library for property-based testing in PHP

**Configuration Requirements:**
- Each property-based test must run a minimum of 100 iterations
- Tests must be tagged with comments referencing design document properties
- Tag format: `**Feature: college-academic-portal, Property {number}: {property_text}**`
- Each correctness property must be implemented by a single property-based test

**Test Categories:**

1. **Access Control Properties** (Properties 2, 4, 8, 14, 15, 16, 22, 23)
   - Generate random user roles and permissions
   - Verify access restrictions are enforced correctly
   - Test semester-based content access

2. **Content Management Properties** (Properties 5, 6, 7, 9, 10, 18, 19)
   - Generate various file types and content
   - Test upload validation and storage
   - Verify AI-generated content quality

3. **Authentication Properties** (Properties 1, 3, 21, 22)
   - Generate user credentials and session data
   - Test login flows and session management
   - Verify role-based redirections

4. **AI Integration Properties** (Properties 11, 12, 13, 27)
   - Generate various input formats and parameters
   - Test OCR analysis and content generation
   - Verify API integration through CodeIgniter services

5. **Database Properties** (Properties 24, 25, 26)
   - Test database operations and abstraction layer usage
   - Verify phpMyAdmin compatibility and CodeIgniter integration
   - Test file storage mechanisms

### Unit Testing Focus Areas

- **Authentication Module**: Login/logout flows, session management, password validation
- **File Upload Module**: File type validation, storage paths, metadata extraction
- **AI Service Integration**: API call formatting, response parsing, error handling
- **Database Models**: CRUD operations, relationship management, data validation
- **Access Control**: Permission checking, role verification, semester restrictions

### Integration Testing

- **End-to-End User Flows**: Complete user journeys from login to content access
- **AI Service Integration**: Full workflow from content upload to AI generation
- **Database Transactions**: Multi-table operations and data consistency
- **File Storage Integration**: Upload, storage, and retrieval workflows
- **Document Chat Integration**: Document content processing, query handling, and response generation

### Performance Testing Considerations

- **Database Query Optimization**: Test query performance with large datasets
- **File Upload Performance**: Test with various file sizes and concurrent uploads
- **AI API Response Times**: Monitor Gemini API call latencies
- **Document Chat Performance**: Test chat response times with large documents
- **Concurrent User Load**: Test system behavior under multiple simultaneous users