# Hostinger .htaccess Troubleshooting

## Current Issue
The 3 faculty generator pages are still showing 404 errors on Hostinger.

## Try These .htaccess Versions in Order

### VERSION 1: Simple (Currently Applied)
```apache
RewriteEngine On

# Remove index.php from URL if present
RewriteCond %{THE_REQUEST} ^GET.*index\.php [NC]
RewriteRule (.*?)index\.php/*(.*) /$1$2 [R=301,NE,L]

# Handle CodeIgniter routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### VERSION 2: With Query String
```apache
RewriteEngine On
RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L,QSA]
```

### VERSION 3: Without Trailing Slash
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php?/$1 [L]
</IfModule>
```

### VERSION 4: Explicit Index.php
```apache
DirectoryIndex index.php
RewriteEngine on

RewriteCond $1 !^(index\.php|assets|uploads|robots\.txt)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ ./index.php/$1 [L,QSA]
```

### VERSION 5: Most Compatible (Hostinger Recommended)
```apache
<IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On
    RewriteBase /
    
    # Remove trailing slash
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]
    
    # Remove index.php
    RewriteCond %{THE_REQUEST} ^GET.*index\.php [NC]
    RewriteCond %{REQUEST_URI} !/system/.*
    RewriteRule (.*?)index\.php/*(.*) /$1$2 [R=301,NE,L]
    
    # Route everything through index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L,QSA]
</IfModule>

# Prevent directory listing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "\.(md|sql|log|ini|sh|inc|bak|config)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## Config.php Settings to Try

### Option 1: REQUEST_URI (Currently Set)
```php
$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
```

### Option 2: AUTO Detection
```php
$config['index_page'] = '';
$config['uri_protocol'] = 'AUTO';
```

### Option 3: QUERY_STRING
```php
$config['index_page'] = '';
$config['uri_protocol'] = 'QUERY_STRING';
```

### Option 4: PATH_INFO
```php
$config['index_page'] = '';
$config['uri_protocol'] = 'PATH_INFO';
```

### Option 5: Keep index.php (Fallback)
```php
$config['index_page'] = 'index.php';
$config['uri_protocol'] = 'REQUEST_URI';
```

## Testing Procedure

### Step 1: Test Direct Access
Try accessing with index.php explicitly:
```
https://yourdomain.com/index.php/ai_buddy/generate_quiz
```

**If this works:** Routes are fine, it's an .htaccess issue.
**If this fails:** There's a deeper routing or controller issue.

### Step 2: Check Error Logs
Enable logging in `config.php`:
```php
$config['log_threshold'] = 4;
```

Then check `application/logs/` for detailed errors.

### Step 3: Test .htaccess is Working
Create a test file `test.html` in root:
```html
<!DOCTYPE html>
<html>
<body>
<h1>.htaccess is working!</h1>
</body>
</html>
```

Try accessing:
```
https://yourdomain.com/test
```

If it redirects to index.php, .htaccess is working.

### Step 4: Check mod_rewrite
Create `phpinfo.php` in root:
```php
<?php phpinfo(); ?>
```

Access it and search for "mod_rewrite". It should be listed in Loaded Modules.

## Common Hostinger Issues

### Issue 1: .htaccess Not Being Read
**Solution:** Contact Hostinger support to ensure:
- AllowOverride is set to All
- .htaccess files are enabled for your directory

### Issue 2: mod_rewrite Not Enabled
**Solution:** Contact Hostinger support to enable mod_rewrite

### Issue 3: File Permissions
**Solution:** Set correct permissions:
```bash
chmod 644 .htaccess
chmod 644 index.php
chmod 755 application/
```

### Issue 4: Subdirectory Installation
If installed in a subdirectory (e.g., `public_html/myapp/`):

**In .htaccess:**
```apache
RewriteBase /myapp/
```

**In config.php:**
```php
$config['base_url'] = 'https://yourdomain.com/myapp/';
```

## Alternative Solution: Use index.php in URLs

If nothing works, you can keep `index.php` in URLs:

**In config.php:**
```php
$config['index_page'] = 'index.php';
```

**Update sidebar links in `faculty_sidebar.php`:**
```php
<a href="<?php echo base_url('index.php/ai_buddy/generate_quiz'); ?>">
```

This is not ideal but will work if .htaccess issues persist.

## Debugging Commands

### Check if .htaccess exists and is readable:
```bash
ls -la .htaccess
cat .htaccess
```

### Check Apache configuration (if you have SSH access):
```bash
apache2ctl -M | grep rewrite
```

### Test URL rewriting:
```bash
curl -I https://yourdomain.com/ai_buddy/generate_quiz
```

## Contact Hostinger Support

If all else fails, contact Hostinger support with:
1. The .htaccess file content
2. The error you're seeing (404)
3. Ask them to verify:
   - Is mod_rewrite enabled?
   - Is AllowOverride set to All?
   - Are there any server-level restrictions on URL rewriting?

## Quick Fix Checklist

- [ ] Try .htaccess VERSION 1 (currently applied)
- [ ] Test with index.php in URL
- [ ] Check error logs
- [ ] Try .htaccess VERSION 2
- [ ] Try .htaccess VERSION 5 (most compatible)
- [ ] Try config.php Option 2 (AUTO)
- [ ] Check phpinfo for mod_rewrite
- [ ] Verify file permissions
- [ ] Contact Hostinger support
