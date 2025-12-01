# Environment Variables Setup Guide

This project uses `.env` files to store sensitive configuration data securely.

## Quick Setup

1. **Copy the example file:**
   ```bash
   cp .env.example .env
   ```

2. **Edit `.env` file** and fill in your actual values:
   ```bash
   # Use your preferred text editor
   notepad .env        # Windows
   nano .env           # Linux/Mac
   ```

3. **Update sensitive values:**
   - Database credentials
   - Email/SMTP settings
   - API keys (optional)

## Environment Variables

### Database Configuration
- `DB_HOST` - Database host (default: localhost)
- `DB_NAME` - Database name (default: pinepix)
- `DB_USER` - Database username
- `DB_PASS` - Database password
- `DB_CHARSET` - Database charset (default: utf8mb4)

### Application Configuration
- `BASE_URL` - Your application URL (e.g., http://localhost:3000/)
- `APP_NAME` - Application name (default: PinePix)
- `APP_VERSION` - Application version

### Email Configuration
- `MAIL_FROM_EMAIL` - Email address for sending emails
- `MAIL_REPLY_TO` - Reply-to email address

### SMTP Configuration (Gmail)
- `MAIL_SMTP_HOST` - SMTP server (default: smtp.gmail.com)
- `MAIL_SMTP_PORT` - SMTP port (default: 587)
- `MAIL_SMTP_USER` - SMTP username (your Gmail)
- `MAIL_SMTP_PASS` - SMTP password (Gmail App Password)

**Important:** For Gmail, use an App Password, not your regular password!
See `EMAIL_SETUP.md` for detailed instructions.

### API Keys (Optional)
- `GEMINI_API_KEY` - Google Gemini API key for chatbot
- `GOOGLE_MAPS_API_KEY` - Google Maps API key for address autocomplete

These can also be set via Admin Panel > Settings.

### Other Settings
- `TIMEZONE` - PHP timezone (default: Asia/Kuala_Lumpur)
- `SESSION_LIFETIME` - Session lifetime in seconds (default: 86400 = 24 hours)
- `MAX_FILE_SIZE` - Maximum file upload size in bytes (default: 5242880 = 5MB)
- `ALLOWED_IMAGE_TYPES` - Comma-separated list of allowed image MIME types

## Security Notes

✅ **DO:**
- Keep `.env` file private
- Add `.env` to `.gitignore` (already done)
- Use strong passwords
- Use App Passwords for Gmail
- Review `.env` file regularly

❌ **DON'T:**
- Commit `.env` to Git
- Share `.env` file publicly
- Use production credentials in development
- Store API keys in code

## File Structure

```
pinepix/
├── .env              # Your actual configuration (NOT in Git)
├── .env.example      # Template file (safe to commit)
├── config/
│   └── database.php  # Loads from .env
└── helpers/
    └── Env.php       # Environment variable loader
```

## Verification

After setting up `.env`, verify it's working:

1. Check that `.env` exists:
   ```bash
   ls -la .env
   ```

2. Test configuration loading:
   ```bash
   php -r "require 'config/autoload.php'; echo 'DB_HOST: ' . DB_HOST;"
   ```

3. Check `.gitignore` includes `.env`:
   ```bash
   grep .env .gitignore
   ```

## Troubleshooting

### Configuration not loading?
- Ensure `.env` file exists in project root
- Check file permissions (should be readable)
- Verify no syntax errors in `.env` file
- Check PHP error logs

### Values not updating?
- Clear any PHP opcode cache (if using)
- Restart web server
- Verify `.env` file was saved correctly

### Git still tracking .env?
- Remove from Git: `git rm --cached .env`
- Verify `.gitignore` includes `.env`
- Commit the removal

## Production Deployment

For production:

1. Create `.env` on production server
2. Set production values (different from development)
3. Ensure file permissions: `chmod 600 .env`
4. Never commit production `.env` to Git
5. Use environment-specific values for each deployment

