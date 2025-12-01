<div align="center">

# 🍍 PinePix

### Pineapple Entrepreneur Information Management System

![PinePix Logo](assets/images/logoblack.png)

**Connecting pineapple entrepreneurs, farms, and businesses in one unified platform**

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0-purple.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-Open%20Source-green.svg)](LICENSE)

[Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation) • [Deployment](#-deployment) • [Support](#-support)

---

</div>

## 📋 Table of Contents

- [About](#-about)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Screenshots](#-screenshots)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [User Roles](#-user-roles)
- [API Documentation](#-api-documentation)
- [Deployment](#-deployment)
- [Project Structure](#-project-structure)
- [Security](#-security)
- [Contributing](#-contributing)
- [Support](#-support)
- [License](#-license)

---

## 🎯 About

**PinePix** is a comprehensive web-based information management system designed specifically for pineapple entrepreneurs. It provides a unified platform to manage entrepreneur profiles, farm locations, shop information, announcements, and includes an AI-powered chatbot for customer support.

### Key Highlights

- 🌾 **Farm Management** - Track and manage farm locations with interactive maps
- 🏪 **Shop Management** - Manage shop information and operating hours
- 📢 **Announcements** - Publish prices, promotions, roadshows, and news
- 🤖 **AI Chatbot** - Powered by Google Gemini API for intelligent customer support
- 🗺️ **Interactive Maps** - Leaflet.js integration for farm and shop location visualization
- 👥 **Multi-User System** - Support for guests, entrepreneurs, and administrators

---

## ✨ Features

### 🔐 Authentication & Security
- ✅ Custom authentication system (Login, Register, Forgot Password)
- ✅ Email verification support
- ✅ Password reset via email token
- ✅ Session-based security
- ✅ Role-based access control

### 👤 Entrepreneur Management
- ✅ Complete biodata management (name, contact, address, profile image)
- ✅ Business category selection
- ✅ Social media links integration (Facebook, Instagram, TikTok, Website, Shopee, Lazada)
- ✅ Profile customization

### 🌾 Farm Management
- ✅ Farm information tracking (name, size, address)
- ✅ Interactive map integration with Leaflet.js
- ✅ Google Maps Places API for address autocomplete
- ✅ Multiple farm images support
- ✅ GPS coordinates (latitude/longitude) storage

### 🏪 Shop Management
- ✅ Shop details management
- ✅ Operating hours configuration
- ✅ Contact information
- ✅ Map location integration
- ✅ Multiple shop support per entrepreneur

### 📢 Announcements System
- ✅ Multiple announcement types (Prices, Promotions, Roadshows, News)
- ✅ Rich content with images
- ✅ Public and authenticated views
- ✅ Admin and vendor publishing capabilities
- ✅ Price trend visualization with ApexCharts

### 🤖 AI Chatbot
- ✅ Google Gemini API integration
- ✅ FAQ mode with knowledge base
- ✅ AI mode for advanced queries
- ✅ Chat history logging
- ✅ Role-based access (limited for guests, unlimited for registered users)

### 🎨 User Interface
- ✅ Modern, responsive design with Bootstrap 5
- ✅ DataTables for efficient data management
- ✅ SweetAlert2 for beautiful alerts
- ✅ Sonner Toast for notifications
- ✅ Select2 for enhanced dropdowns
- ✅ Dark mode support
- ✅ Mobile-first responsive design

### 🗺️ Public Features
- ✅ Public landing page with hero section
- ✅ Interactive map showing all farms and shops
- ✅ Latest announcements display
- ✅ Statistics dashboard
- ✅ Social media integration

### ⚙️ Admin Panel
- ✅ Complete entrepreneur management (CRUD operations)
- ✅ FAQ knowledge base management
- ✅ System settings configuration
- ✅ Google Maps API key management
- ✅ Gemini API key configuration
- ✅ Site logo customization

---

## 🛠️ Tech Stack

### Backend
- **PHP 8.0+** - Vanilla PHP (no frameworks)
- **MySQL 8.0** - Relational database
- **PDO** - Database abstraction layer

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with custom themes
- **JavaScript (Vanilla)** - No framework dependencies
- **Bootstrap 5** - Responsive UI framework

### UI Libraries & Tools
- **DataTables** - Advanced table functionality
- **SweetAlert2** - Beautiful alert dialogs
- **Select2** - Enhanced select dropdowns
- **Sonner Toast** - Toast notifications
- **ApexCharts** - Data visualization
- **Font Awesome** - Icon library
- **Leaflet.js** - Interactive maps
- **Google Maps Places API** - Address autocomplete

### APIs & Services
- **Google Gemini API** - AI chatbot functionality
- **Google Maps Places API** - Location autocomplete
- **SMTP (Gmail)** - Email functionality

---

## 📸 Screenshots

> 📝 *Screenshots coming soon!*

### Dashboard Preview
- Modern, clean interface
- Responsive design
- Intuitive navigation

### Map Integration
- Interactive farm locations
- Shop location markers
- Cluster visualization

### Chatbot Interface
- Clean chat UI
- FAQ and AI modes
- Chat history

---

## 🚀 Installation

### Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.0 or higher** - [Download PHP](https://www.php.net/downloads.php)
- **MySQL 8.0 or higher** - [Download MySQL](https://dev.mysql.com/downloads/)
- **Apache/Nginx** - Web server with `mod_rewrite` enabled
- **Composer** (optional) - For dependency management

### Quick Start (5 Minutes)

#### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/pinepix.git
cd pinepix
```

#### Step 2: Database Setup

1. Create a new MySQL database:
```sql
CREATE DATABASE pinepix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the database schema:
```bash
mysql -u root -p pinepix < database/schema.sql
```

Or via phpMyAdmin:
- Select the `pinepix` database
- Click "Import"
- Choose `database/schema.sql`
- Click "Go"

#### Step 3: Environment Configuration

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Edit `.env` file with your configuration:
```env
# Database Configuration
DB_HOST=localhost
DB_NAME=pinepix
DB_USER=root
DB_PASS=your_password
DB_CHARSET=utf8mb4

# Application Configuration
BASE_URL=http://localhost:3000/
APP_NAME=PinePix
APP_VERSION=1.0.0

# Email Configuration (Optional)
MAIL_FROM_EMAIL=your-email@gmail.com
MAIL_SMTP_HOST=smtp.gmail.com
MAIL_SMTP_PORT=587
MAIL_SMTP_USER=your-email@gmail.com
MAIL_SMTP_PASS=your-app-password

# API Keys (Optional - can also be set via Admin Panel)
GEMINI_API_KEY=your-gemini-api-key
GOOGLE_MAPS_API_KEY=your-google-maps-api-key
```

> 📚 **Detailed Setup:** See [`mdfolder/ENV_SETUP.md`](mdfolder/ENV_SETUP.md) for complete environment configuration guide.

#### Step 4: File Permissions

**Windows (PowerShell):**
```powershell
New-Item -ItemType Directory -Force -Path "public\uploads\profiles", "public\uploads\farms", "public\uploads\shops", "public\uploads\announcements"
```

**Linux/Mac:**
```bash
mkdir -p public/uploads/{profiles,farms,shops,announcements}
chmod -R 755 public/uploads
```

#### Step 5: Start the Server

**Using PHP Built-in Server:**
```bash
# Windows
php -S localhost:3000 -t public router.php

# Linux/Mac
php -S localhost:3000 -t public router.php
```

**Using Laragon/XAMPP/WAMP:**
- Place project in `htdocs` or `www` directory
- Access via: `http://localhost/pinepix/`

#### Step 6: Access the Application

1. Open your browser: `http://localhost:3000/`
2. Default admin credentials:
   - **Email:** `admin@pinepix.com`
   - **Password:** `admin123`

> ⚠️ **IMPORTANT:** Change the default admin password immediately after first login!

#### Step 7: Configure API Keys (Optional)

1. Log in as admin
2. Navigate to: **Admin Panel → Settings**
3. Add your API keys:
   - **Google Maps API Key** - For address autocomplete
   - **Gemini API Key** - For AI chatbot functionality

> 📚 **Need help?** See [`mdfolder/INSTALLATION.md`](mdfolder/INSTALLATION.md) for detailed installation instructions.

---

## ⚙️ Configuration

### Database Configuration

Edit `config/database.php` or use `.env` file:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'pinepix');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

### Application Settings

```php
define('BASE_URL', 'http://localhost:3000/');
define('APP_NAME', 'PinePix');
define('APP_VERSION', '1.0.0');
```

### File Upload Settings

```php
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
```

### Session Configuration

```php
define('SESSION_LIFETIME', 86400); // 24 hours
```

> 📚 **Full Configuration Guide:** See [`mdfolder/ENV_SETUP.md`](mdfolder/ENV_SETUP.md)

---

## 👥 User Roles

### 👤 Guest
- View public landing page
- Browse announcements
- Access chatbot (FAQ mode only)
- View farm and shop locations on map

### 🌾 Entrepreneur
- All guest features
- Register and manage account
- Update biodata and profile
- Manage farms (add, edit, delete)
- Manage shops (add, edit, delete)
- Add social media links
- Create and publish announcements
- Full chatbot access (FAQ + AI mode)
- View personal dashboard

### 👨‍💼 Admin
- All entrepreneur features
- Manage all entrepreneurs (CRUD operations)
- Manage FAQ knowledge base
- Configure system settings
- Manage API keys (Google Maps, Gemini)
- Customize site logo
- View system statistics

---

## 📡 API Documentation

### Chat API

**Endpoint:** `POST /api/chat.php`

**Authentication:** Required (except for FAQ mode)

**Request Body:**
```json
{
  "message": "What is the current pineapple price?",
  "mode": "faq" | "ai"
}
```

**Response:**
```json
{
  "success": true,
  "response": "The current pineapple price is RM 4.62 per piece...",
  "mode": "faq"
}
```

**Error Response:**
```json
{
  "success": false,
  "error": "Error message here"
}
```

### Price Fetch API

**Endpoint:** `GET /api/fetch-price.php`

**Response:**
```json
{
  "success": true,
  "price": 4.62,
  "unit": "per piece",
  "week": 48,
  "year": 2025,
  "update_date": "30 November 2025",
  "source": "ManaMurah.com"
}
```

### Chat History API

**Endpoint:** `GET /api/chat-history.php`

**Authentication:** Required

**Response:**
```json
{
  "success": true,
  "history": [
    {
      "id": 1,
      "message": "User message",
      "response": "Bot response",
      "mode": "faq",
      "created_at": "2025-01-15 10:30:00"
    }
  ]
}
```

---

## 🌐 Deployment

### ⚠️ Important Note

**GitHub Pages does NOT support PHP applications.** This project requires server-side hosting with PHP and MySQL support.

### Recommended Hosting Options

#### 1. InfinityFree (Easiest - Free) ⭐
- ✅ Free PHP hosting (PHP 8.0+)
- ✅ Free MySQL database (400 MB)
- ✅ Free SSL certificate
- ✅ No credit card required
- 📚 [Full Guide](mdfolder/DEPLOYMENT.md#option-1-infinityfree-recommended-for-beginners-)

#### 2. Render (Recommended for Production) ⭐
- ✅ Free PHP hosting
- ✅ Auto-deploy from GitHub
- ✅ Free SSL
- ✅ Custom domain support
- 📚 [Full Guide](mdfolder/DEPLOYMENT.md#option-4-render-recommended-for-production-)

#### 3. Railway
- ✅ Modern platform
- ✅ $5 credit/month (free tier)
- ✅ MySQL included
- 📚 [Full Guide](mdfolder/DEPLOYMENT.md#option-3-free-mysql-database-hosting-separate)

#### 4. 000webhost
- ✅ Free PHP + MySQL
- ✅ Simple deployment
- 📚 [Full Guide](mdfolder/DEPLOYMENT.md#option-2-000webhost-alternative)

> 📚 **Complete Deployment Guide:** See [`mdfolder/DEPLOYMENT.md`](mdfolder/DEPLOYMENT.md) for detailed instructions on all hosting options.

### Pre-Deployment Checklist

- [ ] Update `.env` with production values
- [ ] Change default admin password
- [ ] Configure production database
- [ ] Set `BASE_URL` to production domain
- [ ] Enable HTTPS/SSL
- [ ] Set proper file permissions
- [ ] Configure SMTP for email
- [ ] Add API keys (Google Maps, Gemini)
- [ ] Disable error reporting in production
- [ ] Set up database backups

---

## 📁 Project Structure

```
pinepix/
├── assets/                 # Static assets
│   ├── css/                # Stylesheets
│   │   ├── auth.css
│   │   ├── custom.css
│   │   ├── dark-mode.css
│   │   └── main.css
│   ├── images/             # Images and logos
│   │   ├── logoblack.png
│   │   ├── logowhite.png
│   │   └── hero.png
│   └── js/                 # JavaScript files
│       └── main.js
├── cache/                  # Cache directory
│   └── pineapple_price.json
├── config/                 # Configuration files
│   ├── autoload.php
│   ├── database.php
│   └── db_connection.php
├── cron/                   # Cron jobs
│   └── update-prices.php
├── database/               # Database files
│   ├── schema.sql
│   └── migration_add_multiple_images.sql
├── favicon/                # Favicon files
├── helpers/                # Helper classes
│   ├── Auth.php
│   ├── Env.php
│   ├── Helper.php
│   ├── Mail.php
│   └── PriceScraper.php
├── mdfolder/               # Documentation
│   ├── DEPLOYMENT.md
│   ├── EMAIL_SETUP.md
│   ├── ENV_SETUP.md
│   ├── INSTALLATION.md
│   ├── prd.md
│   └── README.md
├── public/                 # Public directory (web root)
│   ├── admin/              # Admin pages
│   │   ├── entrepreneurs.php
│   │   ├── faq.php
│   │   └── settings.php
│   ├── api/                # API endpoints
│   │   ├── chat.php
│   │   ├── chat-history.php
│   │   ├── contact.php
│   │   └── fetch-price.php
│   ├── auth/               # Authentication pages
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   ├── uploads/            # Upload directories
│   │   ├── profiles/
│   │   ├── farms/
│   │   ├── shops/
│   │   └── announcements/
│   ├── announcements.php
│   ├── biodata.php
│   ├── chatbot.php
│   ├── contact.php
│   ├── dashboard.php
│   ├── farm.php
│   ├── index.php           # Landing page
│   ├── profile.php
│   ├── shop.php
│   └── social-links.php
├── views/                  # View templates
│   ├── auth/
│   ├── partials/
│   │   ├── footer.php
│   │   ├── header.php
│   │   └── sidebar.php
│   └── public/
│       └── index.php
├── .env                    # Environment variables (not in git)
├── .env.example            # Environment template
├── .gitignore
├── composer.json
├── router.php              # Router for PHP built-in server
├── start-server.bat        # Windows start script
└── start-server.sh         # Linux/Mac start script
```

---

## 🔒 Security

### Security Best Practices

1. **Change Default Credentials**
   - Immediately change default admin password
   - Use strong, unique passwords

2. **Environment Variables**
   - Never commit `.env` file to version control
   - Use `.env.example` as template
   - Keep API keys secure

3. **File Permissions**
   - Set upload directories to `755` (folders) and `644` (files)
   - Restrict access to sensitive files

4. **Database Security**
   - Use prepared statements (PDO) - ✅ Already implemented
   - Use strong database passwords
   - Limit database user privileges

5. **Production Checklist**
   - Enable HTTPS/SSL
   - Disable error reporting in production
   - Implement CSRF protection (recommended)
   - Regular security updates
   - Database backups

6. **API Security**
   - Validate all user inputs
   - Sanitize file uploads
   - Rate limiting (recommended for production)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes**
   - Follow existing code style
   - Add comments for complex logic
   - Test your changes thoroughly
4. **Commit your changes**
   ```bash
   git commit -m 'Add some amazing feature'
   ```
5. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
6. **Open a Pull Request**

### Development Guidelines

- Follow PSR coding standards
- Write clear commit messages
- Update documentation for new features
- Test on multiple browsers
- Ensure mobile responsiveness

---

## 📞 Support

### Documentation

- 📚 [Product Requirements Document](mdfolder/prd.md)
- 📚 [Installation Guide](mdfolder/INSTALLATION.md)
- 📚 [Environment Setup](mdfolder/ENV_SETUP.md)
- 📚 [Deployment Guide](mdfolder/DEPLOYMENT.md)
- 📚 [Email Setup](mdfolder/EMAIL_SETUP.md)

### Common Issues

**Database Connection Error**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists

**404 Page Not Found**
- Verify `.htaccess` is present
- Enable `mod_rewrite` in Apache
- Check `BASE_URL` configuration

**Images Not Uploading**
- Check `public/uploads/` directories exist
- Verify file permissions (755 or 777)
- Check PHP `upload_max_filesize` in `php.ini`

**Chatbot Not Working**
- Verify Gemini API key is set
- Check browser console for errors
- Ensure API key has correct permissions

### Getting Help

- 📖 Check the documentation in `mdfolder/`
- 🐛 Open an issue on GitHub
- 💬 Contact the development team

---

## 📄 License

This project is open source and available for use.

---

<div align="center">

### 🌟 Star this repository if you find it helpful!

**Built with ❤️ for Pineapple Entrepreneurs**

[⬆ Back to Top](#-pinepix)

</div>

