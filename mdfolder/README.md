# 🍍 PinePix - Pineapple Entrepreneur Information Management System

A comprehensive web-based system for managing pineapple entrepreneurs, farms, shops, and announcements.

## Features

- ✅ Custom Authentication (Login, Register, Forgot Password)
- ✅ Entrepreneur Biodata Management
- ✅ Farm Management with Leaflet Maps & Google Autocomplete
- ✅ Shop Information Management
- ✅ Social Media Links Integration
- ✅ Announcement Management (Prices, Promotions, Roadshows, News)
- ✅ Public Landing Page with Farm Map
- ✅ Admin Panel with User Management
- ✅ AI Chatbot (Gemini API) with FAQ Knowledge Base
- ✅ Responsive, Modern UI with Bootstrap 5

## Tech Stack

- **Backend:** PHP 8+ (Vanilla PHP, Simple MVC)
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Database:** MySQL 8
- **UI Libraries:**
  - Bootstrap 5
  - DataTables
  - SweetAlert2
  - Select2
  - Sonner Toast
  - ApexCharts
  - Font Awesome
  - Leaflet.js (Maps)
  - Google Maps Places API

## 🚀 Deployment

**⚠️ Important:** GitHub Pages only supports static websites. This PHP + MySQL application requires server-side hosting.

### Quick Deployment Options:

1. **InfinityFree** (Easiest) - Free PHP + MySQL hosting
   - See: [`QUICK_DEPLOY.md`](QUICK_DEPLOY.md)
   
2. **Render** (Recommended) - Modern platform with auto-deploy
   - See: [`DEPLOYMENT.md`](DEPLOYMENT.md)

3. **Other Options:** 000webhost, Railway, Heroku
   - Full guide: [`DEPLOYMENT.md`](DEPLOYMENT.md)

### Free MySQL Hosting:
- **PlanetScale** - Free tier: 1 DB, 1 GB
- **Railway** - $5 credit/month
- **Aiven** - Free MySQL trial

📚 **Full Deployment Guide:** See [`DEPLOYMENT.md`](DEPLOYMENT.md) for detailed instructions.

## Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server with mod_rewrite enabled
- Composer (optional, not required for this project)

### Setup Steps

1. **Clone or download the project**
   ```bash
   cd pinepix-eims
   ```

2. **Configure Database**
   - Create a MySQL database named `pinepix`
   - Import the schema file:
   ```bash
   mysql -u root -p pinepix < database/schema.sql
   ```

3. **Configure Database Connection**
   - Edit `config/database.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'pinepix');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   ```

4. **Set Base URL**
   - Edit `config/database.php`
   - Update BASE_URL to match your local setup:
   ```php
   define('BASE_URL', 'http://localhost/pinepix-eims/');
   ```

5. **Create Upload Directories**
   ```bash
   mkdir -p public/uploads/profiles
   mkdir -p public/uploads/farms
   mkdir -p public/uploads/announcements
   chmod -R 755 public/uploads
   ```

6. **Configure Web Server**
   
   For Apache, ensure `.htaccess` is enabled and mod_rewrite is active.
   
   For Nginx, add rewrite rules:
   ```nginx
   location / {
       try_files $uri $uri/ /public/index.php?$query_string;
   }
   ```

7. **Set Up API Keys** (Optional but Recommended)
   - Log in as admin (default: admin@pinepix.com / admin123)
   - Go to Admin > Settings
   - Enter your Google Maps API Key (for address autocomplete)
   - Enter your Gemini API Key (for AI chatbot)

## Default Login

After installation, use these credentials:

- **Email:** admin@pinepix.com
- **Password:** admin123

**⚠️ Important:** Change the default admin password immediately after first login!

## Project Structure

```
pinepix-eims/
├── assets/
│   ├── css/
│   │   ├── auth.css
│   │   └── main.css
│   └── js/
│       └── main.js
├── config/
│   ├── autoload.php
│   ├── database.php
│   └── db_connection.php
├── controllers/
├── database/
│   └── schema.sql
├── helpers/
│   ├── Auth.php
│   └── Helper.php
├── models/
├── partials/
├── public/
│   ├── admin/
│   │   ├── entrepreneurs.php
│   │   ├── faq.php
│   │   └── settings.php
│   ├── api/
│   │   └── chat.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   ├── uploads/
│   ├── announcements.php
│   ├── biodata.php
│   ├── chatbot.php
│   ├── dashboard.php
│   ├── farm.php
│   ├── index.php
│   ├── profile.php
│   ├── shop.php
│   └── social-links.php
├── views/
│   ├── auth/
│   ├── partials/
│   ├── public/
│   └── ...
├── .htaccess
├── prd.md
└── README.md
```

## User Roles

1. **Guest**
   - View public landing page
   - View announcements
   - Limited chatbot access (FAQ mode only)

2. **Entrepreneur**
   - All guest features
   - Manage biodata
   - Manage farms and shops
   - Add social media links
   - Create announcements
   - Full chatbot access

3. **Admin**
   - All entrepreneur features
   - Manage all entrepreneurs
   - Manage FAQ knowledge base
   - Configure system settings

## API Endpoints

### Chat API
- **Endpoint:** `/api/chat.php`
- **Method:** POST
- **Payload:**
  ```json
  {
    "message": "Your question",
    "mode": "faq" | "ai"
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "response": "AI response text",
    "mode": "faq"
  }
  ```

## Development Notes

- The system uses a simple MVC pattern
- All database queries use PDO with prepared statements
- File uploads are validated and stored in `public/uploads/`
- Session-based authentication
- CSRF protection recommended for production

## Security Considerations

1. Change default admin password
2. Set proper file permissions on uploads directory
3. Use environment variables for sensitive configuration (recommended)
4. Enable HTTPS in production
5. Implement CSRF tokens for forms
6. Regular database backups

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

This project is open source and available for use.

## Support

For issues or questions, please refer to the PRD document (`prd.md`) for specifications.

---

**Built with ❤️ for Pineapple Entrepreneurs**
