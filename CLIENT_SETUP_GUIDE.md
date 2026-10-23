# Client Setup Guide - AI Powered Academic Hub

## 🚀 Quick Start

This guide will help you set up the AI Powered Academic Hub system for your institution.

---

## 📋 Prerequisites

Before you begin, ensure you have:
- ✅ Web server (Apache/Nginx)
- ✅ PHP 7.4 or higher
- ✅ MySQL 5.7 or higher
- ✅ Composer (PHP package manager)
- ✅ Google Gemini API key (for AI features)

---

## 🔧 Installation Steps

### Step 1: Database Setup

1. **Create a new database:**
   ```sql
   CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import the database schema:**
   ```bash
   mysql -u your_username -p your_database_name < database_schema.sql
   ```

3. **Clean the database (remove sample data):**
   ```bash
   mysql -u your_username -p your_database_name < clean_database_for_client.sql
   ```

### Step 2: Configure Database Connection

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_database_username',
    'password' => 'your_database_password',
    'database' => 'your_database_name',
    // ... rest of config
);
```

### Step 3: Configure AI Service

Edit `application/libraries/AI_service.php` (around line 20):

Replace the API keys with your own Google Gemini API keys:

```php
$this->api_keys = [
    'YOUR_GEMINI_API_KEY_1',
    'YOUR_GEMINI_API_KEY_2',
    'YOUR_GEMINI_API_KEY_3'
];
```

**How to get Gemini API keys:**
1. Visit: https://makersuite.google.com/app/apikey
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy the key and paste it in the configuration

### Step 4: Install Dependencies

```bash
composer install
```

This will install:
- PDF parser library (for extracting text from PDFs)
- Other required dependencies

### Step 5: Set File Permissions

```bash
chmod -R 755 application/cache
chmod -R 755 application/logs
chmod -R 755 uploads
```

---

## 👤 Default Admin Account

After running `clean_database_for_client.sql`, you'll have:

- **Username:** `admin`
- **Password:** `admin123`
- **Email:** `admin@example.com`

**⚠️ IMPORTANT:** Change this password immediately after first login!

---

## 📚 Adding Your Data

### 1. Create Faculty Accounts

**Option A: Through Admin Dashboard**
1. Login as admin
2. Go to "Create Faculty"
3. Fill in faculty details
4. Faculty will receive login credentials

**Option B: Bulk Import (CSV)**
Use the sample format in `sample_faculty.csv`:
```csv
username,email,password,employee_id,department
john.doe,john@example.com,password123,EMP001,Computer Science
```

### 2. Create Student Accounts

**Option A: Through Admin Dashboard**
1. Login as admin
2. Go to "Create Student"
3. Fill in student details
4. Student will receive login credentials

**Option B: Bulk Import (CSV)**
Use the sample format in `sample_students.csv`:
```csv
username,email,password,student_id,current_semester,enrollment_year
jane.smith,jane@example.com,password123,STU001,1,2024
```

### 3. Add Subjects

1. Login as admin
2. Go to "Manage Subjects"
3. Click "Add Subject"
4. Fill in:
   - Subject Code (e.g., CS101)
   - Subject Name (e.g., Introduction to Programming)
   - Semester (1-8)
   - Credits

### 4. Assign Subjects to Faculty

1. Go to "Faculty Management"
2. Select a faculty member
3. Click "Assign Subjects"
4. Select subjects they will teach

### 5. Enroll Students in Subjects

1. Students are automatically enrolled based on their semester
2. Or manually enroll through admin dashboard

---

## 🎯 Features Overview

### For Admin:
- ✅ User management (Faculty & Students)
- ✅ Subject management
- ✅ Faculty-subject assignments
- ✅ System monitoring

### For Faculty:
- ✅ Upload resources (PDF, DOCX, PPTX)
- ✅ Generate AI quizzes
- ✅ Generate question papers
- ✅ Generate assignments
- ✅ Chat with AI about resources (HawkAI)
- ✅ View assigned subjects

### For Students:
- ✅ Access course resources
- ✅ Take AI-generated quizzes
- ✅ View question papers
- ✅ Submit assignments
- ✅ Chat with AI about subjects (HawkAI)
- ✅ Generate mindmaps from resources
- ✅ Generate flashcards for study

---

## 🔐 Security Recommendations

1. **Change Default Admin Password**
   - Login → Profile → Change Password

2. **Update API Keys**
   - Never commit API keys to version control
   - Use environment variables in production

3. **Set Strong Passwords**
   - Minimum 8 characters
   - Mix of letters, numbers, symbols

4. **Regular Backups**
   - Backup database daily
   - Backup uploaded files weekly

5. **Update .htaccess**
   - Ensure proper access restrictions
   - Block direct access to sensitive files

---

## 📁 Directory Structure

```
project/
├── application/          # Core application code
│   ├── controllers/     # Request handlers
│   ├── models/         # Database models
│   ├── views/          # UI templates
│   ├── libraries/      # Custom libraries (AI_service)
│   └── config/         # Configuration files
├── assets/             # CSS, JS files
├── uploads/            # User uploaded files
│   ├── resources/      # Course resources
│   ├── assignments/    # Assignment submissions
│   └── question_papers/# Generated papers
├── system/             # CodeIgniter framework
└── vendor/             # Composer dependencies
```

---

## 🆘 Troubleshooting

### Issue: "Database connection failed"
**Solution:** Check `application/config/database.php` credentials

### Issue: "AI features not working"
**Solution:** Verify Gemini API keys in `AI_service.php`

### Issue: "PDF upload fails"
**Solution:** 
- Check `uploads/` folder permissions (755)
- Verify PHP `upload_max_filesize` in php.ini

### Issue: "Page not found (404)"
**Solution:** 
- Check `.htaccess` file exists
- Enable `mod_rewrite` in Apache

### Issue: "Blank page after login"
**Solution:**
- Check `application/logs/` for errors
- Verify database tables exist

---

## 📞 Support

For technical support or questions:
- Check `PROJECT_DOCUMENTATION.md` for detailed documentation
- Review `QUICK_START_GUIDE.md` for quick reference
- Check application logs in `application/logs/`

---

## 🎓 Best Practices

1. **Regular Maintenance**
   - Clear old logs monthly
   - Archive old submissions
   - Update dependencies quarterly

2. **User Training**
   - Train faculty on resource upload
   - Train students on system navigation
   - Provide quick reference guides

3. **Data Management**
   - Organize resources by subject/semester
   - Use clear naming conventions
   - Regular data cleanup

4. **Performance**
   - Monitor API usage (rate limits)
   - Optimize large file uploads
   - Cache frequently accessed data

---

## ✅ Post-Setup Checklist

- [ ] Database imported successfully
- [ ] Database cleaned (sample data removed)
- [ ] Database credentials configured
- [ ] Gemini API keys configured
- [ ] Composer dependencies installed
- [ ] File permissions set correctly
- [ ] Admin password changed
- [ ] Test login with admin account
- [ ] Create first faculty account
- [ ] Create first student account
- [ ] Add first subject
- [ ] Upload first resource
- [ ] Test AI features (quiz generation)
- [ ] Test HawkAI chat
- [ ] Backup database

---

## 🚀 You're Ready!

Your AI Powered Academic Hub is now set up and ready to use. Start by:
1. Logging in as admin
2. Creating faculty accounts
3. Adding subjects
4. Assigning subjects to faculty
5. Creating student accounts

**Welcome to the future of education! 🎉**
