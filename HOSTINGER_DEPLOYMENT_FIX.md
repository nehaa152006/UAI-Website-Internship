# Hostinger Deployment - 404 Error Fix

## Problem
Faculty generator routes (quiz, question paper, assignment) returning 404 errors on Hostinger shared hosting.

## Root Cause
Hostinger's Apache configuration may handle URL rewriting differently than local development environments. The issue is typically related to:
1. `.htaccess` rewrite rules not being processed correctly
2. `mod_rewrite` not enabled or configured properly
3. CodeIgniter's `uri_protocol` setting incompatible with server

## Solution Applied

### 1. Updated `application/config/config.php`
Changed the following settings for Hostinger compatibility:

```php
// Remove index.php from URLs
$config['index_page'] = '';

// Use REQUEST_URI protocol (works with most Apache setups)
$config['uri_protocol'] = 'REQUEST_URI';
```

### 2. Verified `.htaccess` Configuration
The `.htaccess` file in the root directory has been configured with:
- `RewriteBase /` for proper base path
- Query string append flag `[QSA]` to preserve parameters
- Proper conditions to skip existing files/directories

## Testing Steps

### Step 1: Test with index.php in URL
First, verify the routes work when explicitly including `index.php`:
- `https://yourdomain.com/index.php/ai_buddy/generate_quiz`
- `https://yourdomain.com/index.php/ai_buddy/generate_question_paper`
- `https://yourdomain.com/index.php/ai_buddy/generate_assignment`

**If this works:** The routes are fine, it's just the `.htaccess` rewrite issue.
**If this doesn't work:** There's a deeper routing or controller issue.

### Step 2: Verify mod_rewrite is Enabled
Contact Hostinger support or check your hosting panel to ensure:
- `mod_rewrite` is enabled
- `.htaccess` files are allowed (AllowOverride is set)

### Step 3: Try Alternative URI Protocols
If routes still don't work, try these alternatives in `application/config/config.php`:

**Option A: AUTO detection**
```php
$config['uri_protocol'] = 'AUTO';
```

**Option B: QUERY_STRING**
```php
$config['uri_protocol'] = 'QUERY_STRING';
```

**Option C: PATH_INFO**
```php
$config['uri_protocol'] = 'PATH_INFO';
```

### Step 4: Alternative .htaccess Rules
If the current `.htaccess` doesn't work, try this alternative version:

```apache
RewriteEngine On
RewriteBase /

# Remove trailing slashes
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)/$ /$1 [L,R=301]

# Handle CodeIgniter routing - Alternative 1
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

Or this even simpler version:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L]
</IfModule>
```

## Hostinger-Specific Considerations

### File Permissions
Ensure proper permissions on Hostinger:
```bash
chmod 644 .htaccess
chmod 644 index.php
chmod 755 application/
chmod 755 system/
```

### Base URL Configuration
Update `application/config/config.php` with your actual domain:
```php
$config['base_url'] = 'https://yourdomain.com/';
```

### PHP Version
Ensure you're using PHP 7.4 or higher in Hostinger's control panel.

### Error Logging
Enable error logging to see detailed errors:
```php
// In application/config/config.php
$config['log_threshold'] = 4; // Log all messages
```

Then check `application/logs/` for error details.

## Quick Verification Checklist

- [ ] `$config['index_page']` is set to empty string `''`
- [ ] `$config['base_url']` is set to your actual domain
- [ ] `.htaccess` file exists in root directory
- [ ] `.htaccess` has proper permissions (644)
- [ ] `mod_rewrite` is enabled on server
- [ ] Routes are defined in `application/config/routes.php`
- [ ] Controllers exist in `application/controllers/`
- [ ] Test with `index.php` in URL first

## Common Hostinger Issues

### Issue 1: Subdirectory Installation
If your app is in a subdirectory (e.g., `public_html/myapp/`):
```php
// In config.php
$config['base_url'] = 'https://yourdomain.com/myapp/';
```

```apache
# In .htaccess
RewriteBase /myapp/
```

### Issue 2: Case Sensitivity
Hostinger servers are case-sensitive. Ensure:
- Controller names match exactly (e.g., `AI_buddy.php` not `ai_buddy.php`)
- Route definitions match controller names

### Issue 3: AllowOverride
If `.htaccess` is being ignored, you may need to contact Hostinger support to enable `AllowOverride All` for your directory.

## Support Resources

- Hostinger Knowledge Base: https://support.hostinger.com
- CodeIgniter URL Documentation: https://codeigniter.com/userguide3/general/urls.html
- Contact Hostinger support if mod_rewrite issues persist

## Current Status

✅ Configuration files updated for Hostinger compatibility
✅ `.htaccess` configured with proper rewrite rules
✅ Routes verified in `routes.php`
⏳ Awaiting deployment testing

## Next Steps

1. Upload the updated files to Hostinger
2. Test routes with `index.php` in URL first
3. If working, test without `index.php`
4. If still not working, try alternative configurations above
5. Check error logs for specific error messages
