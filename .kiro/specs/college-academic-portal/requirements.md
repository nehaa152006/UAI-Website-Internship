# Requirements Document

## Introduction

The AI-Integrated College Academic Portal is a comprehensive web-based system that facilitates academic resource management, assessment creation, and student learning through AI-powered features. The system serves three distinct user roles (Admin, Faculty, Student) with role-based access controls and integrates Gemini API for content generation and retrieval-augmented generation (RAG) capabilities.

## Glossary

- **Portal_System**: The AI-Integrated College Academic Portal web application
- **Admin_User**: Administrative user with full system privileges
- **Faculty_User**: Teaching staff with subject-specific content management privileges  
- **Student_User**: Learner with semester-based resource access privileges
- **Gemini_API**: Google's AI service for content generation and analysis
- **RAG_System**: Retrieval-Augmented Generation system for contextual AI responses
- **OCR_Service**: Optical Character Recognition for analyzing uploaded images
- **Resource_Content**: Study materials including PDF, PPT, Excel, CSV, ePub files and web links
- **Assignment_Tool**: Digital assignment creation and management functionality
- **Semester_Card**: Visual interface element representing academic semester access
- **Subject_Assignment**: Faculty-to-subject mapping for access control

## Requirements

### Requirement 1

**User Story:** As an Admin, I want to manage user accounts and roles, so that I can control system access and maintain proper organizational structure.

#### Acceptance Criteria

1. WHEN an Admin_User logs into the Portal_System THEN the Portal_System SHALL display an administrative dashboard with user management capabilities
2. WHEN an Admin_User creates a Faculty_User account THEN the Portal_System SHALL store the account credentials and assign initial role permissions
3. WHEN an Admin_User assigns a Subject_Assignment to a Faculty_User THEN the Portal_System SHALL restrict that Faculty_User access to only assigned subjects
4. WHEN an Admin_User removes a Faculty_User account THEN the Portal_System SHALL deactivate the account and revoke all associated permissions
5. WHERE privilege management is required, ONLY Admin_Users SHALL have authority to modify Faculty_User roles and permissions

### Requirement 2

**User Story:** As a Faculty member, I want to upload and manage educational resources, so that I can provide study materials to students in my assigned subjects.

#### Acceptance Criteria

1. WHEN a Faculty_User uploads Resource_Content THEN the Portal_System SHALL validate the file format against supported types (PDF, PPT, Excel, CSV, ePub, web links)
2. WHEN a Faculty_User attempts to upload content THEN the Portal_System SHALL verify the Faculty_User has Subject_Assignment permissions for the target subject
3. WHEN Resource_Content is successfully uploaded THEN the Portal_System SHALL store the content with proper metadata and access controls
4. WHEN a Faculty_User manages resources THEN the Portal_System SHALL display only content for subjects matching their Subject_Assignment
5. WHEN Resource_Content is stored THEN the Portal_System SHALL make it available for RAG_System indexing and student access

### Requirement 3

**User Story:** As a Faculty member, I want to create assignments manually and generate assessments using AI, so that I can efficiently develop varied evaluation materials.

#### Acceptance Criteria

1. WHEN a Faculty_User creates a manual assignment THEN the Portal_System SHALL provide assignment creation tools comparable to Digicampus functionality
2. WHEN a Faculty_User requests AI-generated assignments THEN the Portal_System SHALL use Gemini_API to create assessments based on uploaded Resource_Content
3. WHEN a Faculty_User requests quiz generation THEN the Portal_System SHALL use Gemini_API to generate questions from specified topics and materials
4. WHEN a Faculty_User uploads a question paper format image THEN the Portal_System SHALL use OCR_Service to analyze the structure and layout
5. WHEN generating question papers THEN the Portal_System SHALL create new papers following the analyzed format with specified total marks, question marks distribution, and Faculty_User attribution

### Requirement 4

**User Story:** As a Student, I want to access educational resources based on my semester progression, so that I can study current materials while reviewing past content without accessing future materials prematurely.

#### Acceptance Criteria

1. WHEN a Student_User logs into the Portal_System THEN the Portal_System SHALL display Semester_Cards representing available academic periods
2. WHEN a Student_User accesses current semester content THEN the Portal_System SHALL provide full access to all active course Resource_Content
3. WHEN a Student_User requests past semester materials THEN the Portal_System SHALL grant access to all previous Resource_Content, quizzes, and question papers
4. WHEN a Student_User attempts to access future semester content THEN the Portal_System SHALL deny access and maintain content restrictions
5. WHEN semester progression occurs THEN the Portal_System SHALL automatically update Student_User access permissions to reflect new current semester

### Requirement 5

**User Story:** As a Student, I want to interact with uploaded resources through an AI chat interface, so that I can get contextual answers and study assistance based on my course materials.

#### Acceptance Criteria

1. WHEN a Student_User initiates a chat session THEN the Portal_System SHALL provide an interface similar to NotebookLM for resource interaction
2. WHEN a Student_User asks questions through the chat interface THEN the RAG_System SHALL process queries using only Faculty_User uploaded Resource_Content as context
3. WHEN the RAG_System generates responses THEN the Portal_System SHALL ensure answers derive exclusively from available course materials
4. WHEN a Student_User queries about unavailable content THEN the RAG_System SHALL indicate insufficient context rather than generating external information
5. WHEN chat interactions occur THEN the Portal_System SHALL maintain conversation history for the current session

### Requirement 6

**User Story:** As a system user, I want secure authentication with role-appropriate interfaces, so that I can access only the features and content relevant to my role.

#### Acceptance Criteria

1. WHEN any user accesses the Portal_System THEN the Portal_System SHALL provide separate login interfaces for Admin_User, Faculty_User, and Student_User roles
2. WHEN authentication succeeds THEN the Portal_System SHALL redirect users to role-specific dashboards with appropriate feature access
3. WHEN a user session is established THEN the Portal_System SHALL maintain session security and enforce role-based permissions throughout the session
4. WHEN unauthorized access is attempted THEN the Portal_System SHALL deny access and log the security event
5. WHEN user credentials are processed THEN the Portal_System SHALL validate against the database using secure authentication methods

### Requirement 7

**User Story:** As a system administrator, I want the system to integrate with phpMyAdmin database and CodeIgniter framework, so that the application follows established technical architecture and database management practices.

#### Acceptance Criteria

1. WHEN the Portal_System processes data operations THEN the Portal_System SHALL use phpMyAdmin-compatible MySQL database for all data storage
2. WHEN the Portal_System executes application logic THEN the Portal_System SHALL follow CodeIgniter framework MVC architecture patterns
3. WHEN database operations occur THEN the Portal_System SHALL use CodeIgniter database abstraction layer for secure data access
4. WHEN the Portal_System handles file uploads THEN the Portal_System SHALL store files using CodeIgniter file handling capabilities
5. WHEN API integrations are required THEN the Portal_System SHALL implement Gemini_API calls through CodeIgniter service layers

### Requirement 8

**User Story:** As a Faculty member, I want AI-powered question paper generation with format recognition, so that I can maintain consistent paper layouts while generating new content efficiently.

#### Acceptance Criteria

1. WHEN a Faculty_User uploads a question paper format image THEN the OCR_Service SHALL extract structural elements including layout, spacing, and formatting patterns
2. WHEN format analysis completes THEN the Portal_System SHALL store the recognized template for reuse in question paper generation
3. WHEN generating a new question paper THEN the Gemini_API SHALL create content that matches the analyzed format structure
4. WHEN specifying paper parameters THEN the Portal_System SHALL accept total marks, individual question marks, and responsible Faculty_User information
5. WHEN the question paper is generated THEN the Portal_System SHALL produce a formatted document maintaining the original layout style with new AI-generated content