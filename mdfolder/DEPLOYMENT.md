# 🚀 Deployment Guide - Free Hosting Options

## ⚠️ Important: GitHub Pages Limitation

**GitHub Pages only supports static websites** (HTML, CSS, JavaScript). Your application is a **PHP + MySQL application**, which requires server-side processing. **GitHub Pages will NOT work for this project.**

## ✅ Free Hosting Solutions for PHP + MySQL

Here are the best free hosting options for your PinePix EIMS application:

---

## Option 1: InfinityFree (Recommended for Beginners) ⭐

**Best for:** Complete beginners, all-in-one solution

### Features:
- ✅ Free PHP hosting (PHP 8.0+)
- ✅ Free MySQL database (400 MB)
- ✅ Free subdomain (e.g., `yourname.infinityfreeapp.com`)
- ✅ Free SSL certificate
- ✅ phpMyAdmin access
- ✅ No credit card required
- ✅ Unlimited bandwidth
- ⚠️ Limited to 50,000 hits/day

### Step-by-Step Deployment:

1. **Sign Up:**
   - Go to: https://www.infinityfree.net/
   - Click "Sign Up Free"
   - Verify your email

2. **Create Account:**
   - Login to InfinityFree Control Panel
   - Click "Create Account"

3. **Upload Files:**
   - Use File Manager in control panel, OR
   - Use FTP client (FileZilla):
     - Host: `ftpupload.net`
     - Username: (provided by InfinityFree)
     - Password: (provided by InfinityFree)
     - Port: `21`
   - Upload all project files to `htdocs/` folder

4. **Create MySQL Database:**
   - In Control Panel, go to "MySQL Databases"
   - Click "Create New Database"
   - Note down:
     - Database name
     - Database username
     - Database password
     - Database host (usually `sqlXXX.infinityfree.com`)

5. **Import Database:**
   - Go to "phpMyAdmin" in Control Panel
   - Select your database
   - Click "Import"
   - Upload `database/schema.sql`
   - Click "Go"

6. **Configure Environment:**
   - In File Manager, edit `.env` file:
   ```env
   DB_HOST=sqlXXX.infinityfree.com
   DB_NAME=epiz_XXXXX_yourdb
   DB_USER=epiz_XXXXX_youruser
   DB_PASS=your_password
   BASE_URL=https://yourname.infinityfreeapp.com/
   ```

7. **Set File Permissions:**
   - Set `public/uploads/` folders to `755` or `777`
   - Can be done via File Manager → Right-click → Permissions

8. **Test Your Site:**
   - Visit: `https://yourname.infinityfreeapp.com/`
   - Default admin: `admin@pinepix.com` / `admin123`

---

## Option 2: 000webhost (Alternative)

**Best for:** Simple deployment, good performance

### Features:
- ✅ Free PHP hosting
- ✅ Free MySQL (300 MB)
- ✅ Free subdomain
- ✅ Free SSL
- ⚠️ Limited bandwidth (3 GB/month)
- ⚠️ No cron jobs on free plan

### Steps:
1. Sign up: https://www.000webhost.com/
2. Create website
3. Upload files via File Manager or FTP
4. Create MySQL database in control panel
5. Import schema via phpMyAdmin
6. Configure `.env` file

---

## Option 3: Free MySQL Database Hosting (Separate)

If you want to host PHP separately and use a free MySQL service:

### A. PlanetScale (Free Tier)
- **URL:** https://planetscale.com/
- **Free Tier:** 1 database, 1 GB storage, 1 billion reads/month
- **Note:** Serverless MySQL, requires connection string changes

### B. Railway (Free Tier)
- **URL:** https://railway.app/
- **Free Tier:** $5 credit/month (enough for small projects)
- **MySQL:** Included

### C. Supabase (PostgreSQL, but compatible)
- **URL:** https://supabase.com/
- **Free Tier:** 500 MB database
- **Note:** PostgreSQL, not MySQL (requires migration)

---

## Option 4: Render (Recommended for Production) ⭐

**Best for:** Modern deployment, good performance

### Features:
- ✅ Free PHP hosting
- ✅ Free PostgreSQL (can use MySQL with paid plan)
- ✅ Free SSL
- ✅ Auto-deploy from GitHub
- ✅ Custom domain support
- ⚠️ Free tier spins down after 15 min inactivity

### Step-by-Step:

1. **Push to GitHub:**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/yourusername/pinepix-eims.git
   git push -u origin main
   ```

2. **Create Render Account:**
   - Go to: https://render.com/
   - Sign up with GitHub

3. **Create Web Service:**
   - Click "New" → "Web Service"
   - Connect your GitHub repository
   - Settings:
     - **Name:** pinepix-eims
     - **Environment:** PHP
     - **Build Command:** (leave empty)
     - **Start Command:** `php -S 0.0.0.0:8000 -t public`
     - **Root Directory:** `public`

4. **Create PostgreSQL Database:**
   - Click "New" → "PostgreSQL"
   - Name: `pinepix-db`
   - Note connection string

5. **Configure Environment Variables:**
   - In Web Service → Environment
   - Add all variables from `.env`:
     ```
     DB_HOST=your-postgres-host
     DB_NAME=your-db-name
     DB_USER=your-user
     DB_PASS=your-password
     BASE_URL=https://your-app.onrender.com/
     ```

6. **Migrate Database:**
   - Convert MySQL schema to PostgreSQL (or use MySQL on paid plan)
   - Import via Render's database dashboard

---

## Option 5: Heroku (Legacy, but still works)

**Note:** Heroku removed free tier, but has low-cost options ($5/month)

### Steps:
1. Install Heroku CLI
2. Create `Procfile`:
   ```
   web: vendor/bin/heroku-php-apache2 public/
   ```
3. Deploy:
   ```bash
   heroku create your-app-name
   heroku addons:create cleardb:ignite  # Free MySQL
   git push heroku main
   ```

---

## 📋 Pre-Deployment Checklist

Before deploying, ensure:

- [ ] `.env` file is configured with production values
- [ ] `.env` is in `.gitignore` (won't be committed)
- [ ] `.env.example` is committed (template for others)
- [ ] Database schema is exported (`database/schema.sql`)
- [ ] All upload directories exist (`public/uploads/profiles`, etc.)
- [ ] File permissions are set correctly (755 for folders, 644 for files)
- [ ] `BASE_URL` in `.env` matches your production domain
- [ ] SMTP credentials are updated for production
- [ ] API keys (Google Maps, Gemini) are set
- [ ] Test locally before deploying

---

## 🔧 Common Deployment Issues & Solutions

### Issue 1: Database Connection Failed
**Solution:**
- Verify database host, username, password in `.env`
- Check if database host allows external connections
- Some hosts require IP whitelisting

### Issue 2: 500 Internal Server Error
**Solution:**
- Check PHP error logs in hosting control panel
- Verify file permissions (755 for folders)
- Ensure `.htaccess` is uploaded
- Check PHP version (needs PHP 8.0+)

### Issue 3: Images Not Uploading
**Solution:**
- Set `public/uploads/` folders to `777` (writable)
- Check PHP `upload_max_filesize` in hosting settings
- Verify `MAX_FILE_SIZE` in `.env`

### Issue 4: Routes Not Working (404 errors)
**Solution:**
- Ensure `.htaccess` is in `public/` folder
- Verify `mod_rewrite` is enabled on Apache
- Check `BASE_URL` in `.env` matches your domain

### Issue 5: Session Not Working
**Solution:**
- Check `session.save_path` is writable
- Verify `SESSION_LIFETIME` in `.env`
- Some hosts require specific session configuration

---

## 🔐 Security Checklist for Production

- [ ] Change default admin password
- [ ] Use strong database passwords
- [ ] Enable HTTPS/SSL (most free hosts provide this)
- [ ] Set secure session configuration
- [ ] Review file upload restrictions
- [ ] Disable PHP error display in production
- [ ] Use environment variables for all secrets
- [ ] Regularly update dependencies
- [ ] Backup database regularly

---

## 📊 Comparison Table

| Hosting | PHP | MySQL | SSL | Custom Domain | Ease | Rating |
|---------|-----|-------|-----|---------------|------|--------|
| InfinityFree | ✅ | ✅ (400MB) | ✅ | ❌ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| 000webhost | ✅ | ✅ (300MB) | ✅ | ❌ | ⭐⭐⭐⭐ | ⭐⭐⭐ |
| Render | ✅ | ✅ (PostgreSQL) | ✅ | ✅ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Railway | ✅ | ✅ | ✅ | ✅ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Heroku | ✅ | ✅ (paid) | ✅ | ✅ | ⭐⭐⭐ | ⭐⭐⭐ |

---

## 🎯 Recommended Approach

**For Beginners:** Use **InfinityFree**
- Easiest setup
- All-in-one solution
- Good documentation

**For Production:** Use **Render** or **Railway**
- Better performance
- Auto-deploy from GitHub
- More reliable
- Custom domains

---

## 📚 Additional Resources

- InfinityFree Documentation: https://forum.infinityfree.com/
- Render Documentation: https://render.com/docs
- Railway Documentation: https://docs.railway.app/
- PHP Deployment Best Practices: https://www.php.net/manual/en/features.deployment.php

---

## 🆘 Need Help?

If you encounter issues during deployment:
1. Check hosting provider's documentation
2. Review PHP error logs
3. Test database connection separately
4. Verify all environment variables are set correctly

