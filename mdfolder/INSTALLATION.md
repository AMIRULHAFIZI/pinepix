# PinePix Installation Guide

## Quick Start (5 minutes)

### Step 1: Database Setup

1. Open phpMyAdmin or MySQL command line
2. Create a new database:
   ```sql
   CREATE DATABASE pinepix;
   ```
3. Import the schema:
   ```sql
   USE pinepix;
   SOURCE database/schema.sql;
   ```
   Or via command line:
   ```bash
   mysql -u root -p pinepix < database/schema.sql
   ```

### Step 2: Configuration

1. Edit `config/database.php`
2. Update your database credentials (already set for Laragon default):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'pinepix');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Change if different
   ```

3. Verify BASE_URL matches your setup:
   ```php
   define('BASE_URL', 'http://localhost/pinepix-eims/');
   ```

### Step 3: File Permissions

Ensure upload directories exist and are writable:
```bash
# Windows (PowerShell)
New-Item -ItemType Directory -Force -Path "public\uploads\profiles", "public\uploads\farms", "public\uploads\announcements"

# Linux/Mac
mkdir -p public/uploads/{profiles,farms,announcements}
chmod -R 755 public/uploads
```

### Step 4: Access the System

1. Open browser: `http://localhost/pinepix-eims/`
2. Default admin login:
   - Email: `admin@pinepix.com`
   - Password: `admin123`
3. **IMPORTANT:** Change admin password immediately!

### Step 5: Configure API Keys (Optional)

1. Login as admin
2. Go to: Admin > Settings
3. Add:
   - **Google Maps API Key** (for address autocomplete)
   - **Gemini API Key** (for AI chatbot)

## Troubleshooting

### Database Connection Error
- Check database credentials in `config/database.php`
- Ensure MySQL is running
- Verify database `pinepix` exists

### Page Not Found (404)
- Check `.htaccess` is present
- Enable mod_rewrite in Apache
- Verify BASE_URL is correct

### Images Not Uploading
- Check `public/uploads/` directories exist
- Verify file permissions (755 or 777)
- Check PHP upload limits in `php.ini`

### Chatbot Not Working
- Verify Gemini API key is set in Admin Settings
- Check browser console for errors
- Ensure API key has correct permissions

## Laragon Specific Notes

Laragon users:
- Database password is usually `admin` (already configured)
- PHP version should be 8.0+
- Apache should have mod_rewrite enabled
- Access via: `http://localhost/pinepix-eims/`

## Production Deployment

Before deploying to production:

1. Change default admin password
2. Update database credentials
3. Set BASE_URL to production domain
4. Enable HTTPS
5. Configure proper file permissions
6. Set up regular database backups
7. Review security settings
8. Disable error reporting in production

## Next Steps

After installation:
1. Create your first entrepreneur account
2. Add farm locations
3. Create announcements
4. Configure FAQ knowledge base
5. Customize settings

---

Need help? Check the main README.md or refer to prd.md for specifications.
