🍍 Pineapple Entrepreneur Information Management System (PinePix)
Product Requirements Document (Full Version)

Tech Stack (Updated – NO Frameworks)

Backend: PHP 8+ (vanilla PHP, procedural or simple MVC)

Frontend: HTML5, CSS3, JavaScript (vanilla)

Database: MySQL 8

UI Libraries:

Bootstrap 5

DataTables

SweetAlert2

Select2

Sonner Toast

ApexCharts

Font Awesome Icons

Mapping: Leaflet.js

Location Autocomplete: Google Maps Places API

Authentication: Custom login / register / forgot password (PHP + MySQL)

1. System Overview

The Pineapple Entrepreneur Information Management System is a web-based system designed to:

✔ Manage entrepreneur biodata, farm info, shop info
✔ Allow admins/vendors to publish announcements
✔ Provide a public landing page for guests
✔ Display maps (farm, shop)
✔ Provide an FAQ AI Chatbot using the Gemini API
✔ Support social media linking
✔ Provide a full admin backend

2. User Roles
1. Guest

View public landing page

View announcements

Access chatbot (limited mode)

2. Entrepreneur

Register & login

Manage their biodata

Manage their farms (with maps)

Manage their shop info

View announcements

Use chatbot (unlimited)

3. Admin

Full CRUD on entrepreneurs

Manage announcements

Manage chatbot knowledge base

Manage system settings (social media, logo, map API key)

3. Core Features
3.1 Authentication Module (Custom)
Features:

Register (email verification optional)

Login

Forgot password (email token reset)

Profile page (update password, info)

UI Requirements:

Clean minimal login form

Bootstrap card centered

SweetAlert2 for messages

3.2 Entrepreneur Information Management
Fields:

Biodata:

Full name

Phone

Email

Gender

IC/Passport

Address

Profile image

Business category (Select2)

Farm Information:

Farm name

Farm size

Farm address

Leaflet map pinpoint (lat/lng)

Google autocomplete search input

Farm images

Shop Information:

Shop name

Shop address

Leaflet map pinpoint

Operation hours

Contact number

UI Requirements:

Use DataTables for entrepreneur listing

Clean 2-column Bootstrap forms

Map modal pop-up for selecting location

SweetAlert2 on submission

Sonner Toast for quick notifications

3.3 Announcement Module

Admin and vendors can publish:

Pineapple prices

Promotions

Roadshows

News

UI:

DataTables list

ApexCharts (optional price trend chart)

Public view card layout

3.4 Social Media Links

Entrepreneurs can add:

Facebook

Instagram

TikTok

Website

Shopee/Lazada links

UI:

Font Awesome icons

Clickable buttons in entrepreneur profile

3.5 Public Landing Page

Accessible without login.

Includes:

Hero header (pineapple farm aesthetic)

Latest announcements

Map showing all entrepreneur farms (Leaflet cluster map)

Social media links

CTA buttons (Register, Login)

3.6 Admin Module

Admin capabilities:

Manage users

Manage announcements

Manage FAQ chatbot datasets

Manage settings

3.7 AI Chatbot (Gemini API)
Features:

FAQ mode (uses stored Q&A)

AI mode (Gemini API)

Chat log history

Role-based access (guest limited)

UI:

Chatbox with bubbles

Floating chatbot widget

Typing indicator

Toggle FAQ vs AI mode

4. Database Schema (MySQL)

Below is the complete SQL schema.

4.1 Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','entrepreneur') DEFAULT 'entrepreneur',
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    gender VARCHAR(20),
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

4.2 Farms Table
CREATE TABLE farms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    farm_name VARCHAR(255),
    farm_size VARCHAR(100),
    address TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    images TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

4.3 Shops Table
CREATE TABLE shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    shop_name VARCHAR(255),
    address TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    operation_hours VARCHAR(255),
    contact VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

4.4 Announcements Table
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    type ENUM('price','promotion','roadshow','other'),
    description TEXT,
    image VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

4.5 Social Links Table
CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    tiktok VARCHAR(255),
    website VARCHAR(255),
    shopee VARCHAR(255),
    lazada VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

4.6 Chatbot Knowledge Base
CREATE TABLE faq_knowledge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT,
    answer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

4.7 Chat Logs
CREATE TABLE chat_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    message TEXT,
    response TEXT,
    mode ENUM('faq','ai'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

4.8 System Settings
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_maps_api_key VARCHAR(255),
    leaflet_default_lat DECIMAL(10,7),
    leaflet_default_lng DECIMAL(10,7),
    site_logo VARCHAR(255)
);

5. Frontend UI Design Blueprint
General Design Principles

✔ Consistency (bootstrap spacing tokens, button styles)
✔ Accessibility (contrast, font sizes)
✔ Minimalism (clean forms, icons)
✔ Mobile-first responsive design

5.1 Navigation Layout
Header:
- Logo left
- Right menu: Login / Profile / Logout

Sidebar (logged in):
- Dashboard
- Entrepreneurs (Admin only)
- My Biodata
- My Farm
- My Shop
- Announcements
- Social Links
- AI Chatbot
- Settings (Admin)

Entrepreneur List (Admin)
DataTable:
Columns: Name | Phone | Email | Farms | Shops | Actions
Buttons: Add, Edit, Delete

Farm Form
Farm Name | Farm Size
Address (Google autocomplete)
[ Select location on map (Leaflet modal) ]
Map: Pinpoint + Save Lat/Lng

Announcements
Card layout (public)
Title
Type badge
Description
Image
Date

Chatbot
Chat bubble interface
Input box + Send
Mode switch:
 [FAQ]  [AI]

6. User Stories
Authentication

As a user, I can register an account.

As a user, I can login using email & password.

As a user, I can reset my password if forgotten.

Entrepreneurs

As an entrepreneur, I can update my biodata.

As an entrepreneur, I can add/edit/delete my farm details.

As an entrepreneur, I can mark farm location on a map.

As an entrepreneur, I can update shop info.

As an entrepreneur, I can add social media links.

Announcements

As admin/vendor, I can publish announcements.

As guest, I can view announcements.

Chatbot

As a user, I can chat with the AI chatbot.

As admin, I can manage FAQ database.

Admin

As admin, I can manage all entrepreneurs.

As admin, I can manage system settings.

7. API Requirements
Chatbot Request
POST /api/chat
Payload:
{
  "message": "...",
  "mode": "faq" | "ai"
}

Google Places Autocomplete

JS script included in farm/shop forms.


