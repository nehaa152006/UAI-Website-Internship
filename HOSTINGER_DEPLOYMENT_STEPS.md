# Hostinger Deployment - Quick Steps

## Pre-Deployment Checklist

- [ ] Database is cleaned and ready (admin account created)
- [ ] All files are committed and ready to upload
- [ ] You have Hostinger FTP/File Manager access
- [ ] You have Hostinger database access (phpMyAdmin)

## Step-by-Step Deployment

### 1. Upload Files to Hostinger

**Via File Manager:**
1. Login to Hostinger control panel
2. Go to File Manager
3. Navigate to `public_html` (or your domain's root folder)
4. Upload all project files EXCEPT:
   - `.git/` folder
   - `application/logs/*` (create empty folder instead)
   - `application/cache/*` (create empty folder instead)
   - `uploads/resources/*` (keep folder structure, remove test files)

**Via FTP:**
1. Use FileZilla or similar FTP client
2. Connect to your Hostinger FTP
3. Upload all files to `public_html/`

### 2. Create Database on Hostinger

1. Go to Hostinger control panel → Databases
2. Create new MySQL database
3. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost`)

### 3. Import Database

1. Go to phpMyAdmin in Hostinger
2. Select your database
3. Click "Import" tab
4. Upload `clean_database_for_client.sql`
5. Click "Go" to import
6. Verify all tables are created (16 tables)

### 4. Configure Database Connection

Edit `application/config/database.php` on server:

```php
$db['default'] = array(
    'hostname' => 'localhost',  // Usually localhost on Hostinger
    'username' => 'your_db_username',  // From step 2
    'password' => 'your_db_password',  // From step 2
    'database' => 'your_db_name',      // From step 2
    'dbdriver' => 'mysqli',
    // ... rest stays the same
);
```

### 5. Update Base URL

Edit `application/config/config.php` on server:

```php
$config['base_url'] = 'https://yourdomain.com/';
```

Replace `yourdomain.com` with your actual domain.

### 6. Set File Permissions

Using File Manager or FTP, set these permissions:

```
.htaccess                           → 644
index.php                           → 644
application/                        → 755
application/config/                 → 755
application/config/database.php     → 644
application/config/config.php       → 644
application/logs/                   → 777 (writable)
application/cache/                  → 777 (writable)
uploads/                            → 755
uploads/resources/                  → 777 (writable)
uploads/assignments/                → 777 (writable)
uploads/question_papers/            → 777 (writable)
uploads/temp/                       → 777 (writable)
system/                             → 755
```

### 7. Verify PHP Version

1. Go to Hostinger control panel
2. Find PHP Configuration or PHP Version
3. Ensure PHP 7.4 or higher is selected
4. Enable these extensions if not already:
   - mysqli
   - mbstring
   - curl
   - json
   - zip

### 8. Test the Deployment

**Test 1: Homepage**
- Visit `https://yourdomain.com/`
- Should show login page
- Title should be "AI Powered Academic Hub"

**Test 2: Login**
- Username: `admin`
- Password: `admin123`
- Should login successfully

**Test 3: Test Routes with index.php**
- Visit `https://yourdomain.com/index.php/ai_buddy/generate_quiz`
- Should load (even if access denied due to role)
- If this works, routes are configured correctly

**Test 4: Test Clean URLs**
- Visit `https://yourdomain.com/ai_buddy/generate_quiz`
- Should load without 404 error
- If this works, `.htaccess` is working correctly

**Test 5: Faculty Features**
1. Create a faculty account from admin dashboard
2. Login as faculty
3. Test all three generator pages:
   - Quiz Generator
   - Question Paper Generator
   - Assignment Generator
4. All should load without 404 errors

### 9. Configure Gemini API Keys

Edit `application/libraries/AI_service.php` on server:

```php
private $api_keys = [
    'YOUR_GEMINI_API_KEY_1',
    'YOUR_GEMINI_API_KEY_2',
    'YOUR_GEMINI_API_KEY_3'
];
```

Replace with client's actual Gemini API keys.

### 10. Security Hardening

**Change Admin Password:**
1. Login as admin
2. Go to profile/settings
3. Change password from `admin123` to something secure

**Update Encryption Key:**
Edit `application/config/config.php`:
```php
$config['encryption_key'] = 'generate-a-random-32-character-key-here';
```

**Enable HTTPS (if available):**
Uncomment in `.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

And update `config.php`:
```php
$config['cookie_secure'] = TRUE;
```

## Troubleshooting

### If 404 Errors Occur

1. **Check .htaccess is uploaded**
   - File might be hidden, enable "Show hidden files"
   - Verify it's in the root directory with `index.php`

2. **Test with index.php in URL**
   - If `index.php/ai_buddy/generate_quiz` works
   - But `/ai_buddy/generate_quiz` doesn't
   - Then it's an `.htaccess` issue

3. **Try alternative .htaccess**
   - Rename `.htaccess.alternative` to `.htaccess`
   - Test again

4. **Try different URI protocol**
   - Edit `config.php`
   - Change `$config['uri_protocol']` to `'AUTO'`
   - Test again

5. **Contact Hostinger Support**
   - Ask: "Is mod_rewrite enabled?"
   - Ask: "Is AllowOverride set to All?"
   - Provide them with the error details

### If Database Connection Fails

1. Verify database credentials in `database.php`
2. Check if database user has all privileges
3. Try `127.0.0.1` instead of `localhost` for hostname
4. Check Hostinger's database documentation

### If Pages Load but Features Don't Work

1. Check `application/logs/` for errors
2. Verify file permissions (especially writable folders)
3. Check PHP error logs in Hostinger control panel
4. Verify Gemini API keys are configured

## Post-Deployment

- [ ] Admin can login
- [ ] Admin can create faculty accounts
- [ ] Admin can create student accounts
- [ ] Faculty can login
- [ ] Faculty can access all generator pages
- [ ] Faculty can upload resources
- [ ] Students can login
- [ ] Students can view resources
- [ ] HawkAI chat works
- [ ] All generator features work
- [ ] No 404 errors on any page

## Client Handover

Provide client with:
1. ✅ Admin credentials (username: admin, password: admin123)
2. ✅ Database credentials
3. ✅ Instructions to change admin password
4. ✅ Instructions to add Gemini API keys
5. ✅ CLIENT_SETUP_GUIDE.md
6. ✅ QUICK_START_GUIDE.md
7. ✅ This deployment guide

## Support Files

- `CLIENT_SETUP_GUIDE.md` - Complete setup instructions for client
- `HOSTINGER_FIX_SUMMARY.md` - 404 error troubleshooting
- `HOSTINGER_DEPLOYMENT_FIX.md` - Detailed technical guide
- `DEPLOYMENT_CHECKLIST.md` - General deployment checklist
- `.htaccess.alternative` - Alternative configuration to try

## Success!

If all tests pass, your deployment is complete! 🎉

The client can now:
- Login and manage users
- Add their own data (faculty, students, subjects)
- Upload resources
- Use all AI-powered features
- Generate quizzes, question papers, and assignments
