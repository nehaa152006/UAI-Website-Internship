# 🚀 Deployment Checklist

## Pre-Deployment

### Database
- [ ] Create new database
- [ ] Import `database_schema.sql`
- [ ] Run `clean_database_for_client.sql`
- [ ] Verify all tables created successfully
- [ ] Test database connection

### Configuration
- [ ] Update `application/config/database.php` with production credentials
- [ ] Add Gemini API keys in `application/libraries/AI_service.php`
- [ ] Set `ENVIRONMENT` to 'production' in `index.php`
- [ ] Configure base URL in `application/config/config.php`

### Dependencies
- [ ] Run `composer install --no-dev`
- [ ] Verify vendor folder exists
- [ ] Check PDF parser library installed

### File Permissions
- [ ] `application/cache/` - 755
- [ ] `application/logs/` - 755
- [ ] `uploads/` - 755
- [ ] `uploads/resources/` - 755
- [ ] `uploads/assignments/` - 755
- [ ] `uploads/question_papers/` - 755
- [ ] `uploads/temp/` - 755

### Security
- [ ] Change default admin password
- [ ] Remove or secure `database_schema.sql` from web root
- [ ] Remove or secure `clean_database_for_client.sql` from web root
- [ ] Verify `.htaccess` files in place
- [ ] Check `uploads/.htaccess` prevents PHP execution
- [ ] Disable directory listing

## Post-Deployment

### Testing
- [ ] Test admin login
- [ ] Create test faculty account
- [ ] Create test student account
- [ ] Add test subject
- [ ] Upload test resource
- [ ] Generate test quiz
- [ ] Test HawkAI chat
- [ ] Test mindmap generation
- [ ] Test flashcard generation
- [ ] Test file downloads

### Performance
- [ ] Enable PHP OPcache
- [ ] Set appropriate PHP memory limits
- [ ] Configure session handling
- [ ] Test with multiple concurrent users

### Monitoring
- [ ] Set up error logging
- [ ] Configure log rotation
- [ ] Monitor API usage
- [ ] Set up backup schedule

### Documentation
- [ ] Provide `CLIENT_SETUP_GUIDE.md` to client
- [ ] Share default admin credentials securely
- [ ] Document any custom configurations
- [ ] Provide training materials

## Client Handover

### Credentials to Provide
- [ ] Database credentials
- [ ] Admin login (username: admin, password: admin123)
- [ ] Gemini API keys (if provided by you)
- [ ] Server access details

### Files to Provide
- [ ] Complete application code
- [ ] `CLIENT_SETUP_GUIDE.md`
- [ ] `PROJECT_DOCUMENTATION.md`
- [ ] `QUICK_START_GUIDE.md`
- [ ] Sample CSV files for bulk import

### Training
- [ ] Admin dashboard walkthrough
- [ ] Faculty portal demonstration
- [ ] Student portal demonstration
- [ ] AI features overview
- [ ] Troubleshooting guide

## Final Checks

- [ ] All sample/test data removed
- [ ] Production database clean
- [ ] API keys configured
- [ ] Error pages working
- [ ] SSL certificate installed (if applicable)
- [ ] Backup system in place
- [ ] Client has admin access
- [ ] Client knows how to change admin password
- [ ] Support contact information provided

## Notes

**Important Reminders:**
1. Client MUST change admin password immediately
2. Client needs their own Gemini API keys for AI features
3. Regular backups are essential
4. Monitor API usage to avoid rate limits
5. Keep CodeIgniter and dependencies updated

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@example.com`

⚠️ **Security Warning:** These credentials should be changed immediately after first login!

---

## Deployment Complete! ✅

Date: _______________
Deployed By: _______________
Client: _______________
Notes: _______________
