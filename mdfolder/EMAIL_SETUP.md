# Email Setup Guide for Password Reset

## Gmail SMTP Configuration

To enable email sending for password reset functionality, you need to configure Gmail SMTP.

### Step 1: Enable 2-Step Verification

1. Go to your Google Account: https://myaccount.google.com/
2. Navigate to **Security** → **2-Step Verification**
3. Follow the prompts to enable 2-Step Verification

### Step 2: Generate App Password

1. Go to: https://myaccount.google.com/apppasswords
2. Select **App**: Choose "Mail"
3. Select **Device**: Choose "Other (Custom name)" and type "PinePix EIMS"
4. Click **Generate**
5. Copy the 16-character password (it will look like: `abcd efgh ijkl mnop`)

### Step 3: Configure in `config/database.php`

Open `config/database.php` and update:

```php
define('MAIL_SMTP_HOST', 'smtp.gmail.com');
define('MAIL_SMTP_PORT', 587);
define('MAIL_SMTP_USER', 'pinepixmalaysia@gmail.com'); // Your Gmail
define('MAIL_SMTP_PASS', 'your-16-char-app-password'); // The App Password from Step 2
```

**Important:** 
- Use the **App Password** (16 characters), NOT your regular Gmail password
- Remove spaces from the App Password when pasting

### Step 4: Test Email Sending

1. Go to: `http://localhost:3000/auth/forgot-password.php`
2. Enter a registered email address
3. Check your email inbox (and spam folder)
4. You should receive a password reset email

## Troubleshooting

### Email not received?

1. **Check spam folder** - Gmail might mark it as spam initially
2. **Check error logs** - Look in PHP error log for SMTP errors
3. **Verify App Password** - Make sure you're using the App Password, not regular password
4. **Check firewall** - Port 587 should be open for outbound connections
5. **Test connection** - Try accessing Gmail SMTP from your server

### Common Errors

- **"Authentication failed"** - Wrong App Password or 2-Step Verification not enabled
- **"Connection timeout"** - Firewall blocking port 587
- **"Could not connect"** - Check if `smtp.gmail.com` is accessible

## Alternative: Using Other Email Services

If you prefer not to use Gmail, you can use other SMTP services:

### Outlook/Hotmail
```php
define('MAIL_SMTP_HOST', 'smtp-mail.outlook.com');
define('MAIL_SMTP_PORT', 587);
```

### SendGrid
```php
define('MAIL_SMTP_HOST', 'smtp.sendgrid.net');
define('MAIL_SMTP_PORT', 587);
define('MAIL_SMTP_USER', 'apikey');
define('MAIL_SMTP_PASS', 'your-sendgrid-api-key');
```

### Mailgun
```php
define('MAIL_SMTP_HOST', 'smtp.mailgun.org');
define('MAIL_SMTP_PORT', 587);
```

## Security Notes

- Never commit your App Password to version control
- Consider using environment variables for sensitive data
- App Passwords are safer than regular passwords
- Each App Password can be revoked individually

