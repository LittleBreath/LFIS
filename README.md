# LFIS - Lost and Found Items System

A full-stack web application for managing lost and found items in schools, colleges, and offices.

## 🎯 Features

### For Users
- **Report Lost Items** - File detailed reports with photos and descriptions
- **Report Found Items** - Help others by reporting found items
- **Browse Found Items** - Search through found items with advanced filters
- **Contact Finders** - Direct messaging with item finders
- **Track Reports** - Monitor your submitted reports

### For Admins
- **Dashboard** - Real-time statistics and analytics
- **Manage Reports** - Review, approve, and reject submissions
- **Match Items** - Verify automatic matches between lost/found items
- **User Management** - Monitor user accounts and activity
- **Settings** - Configure system parameters and email notifications

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Framework:** Bootstrap 5.3.3
- **Charts:** Chart.js 3.9.1
- **Icons:** Bootstrap Icons 1.11.3

## 📋 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, or built-in PHP server)

### Setup Steps

1. **Clone/Download the repository**
   ```bash
   cd LFIS
   ```

2. **Create the uploads directory**
   ```bash
   mkdir uploads/
   chmod 755 uploads/
   ```

3. **Create MySQL Database**
   - Open phpMyAdmin or MySQL client
   - Import the database schema:
   ```bash
   mysql -u root -p < admin/database.sql
   ```

4. **Configure Database Connection**
   - Edit `admin/config.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'lfis_db');
   ```

5. **Configure Site URL**
   - Update `SITE_URL` in `admin/config.php`:
   ```php
   define('SITE_URL', 'http://your-domain.com/LFIS/');
   ```

6. **Set proper file permissions**
   ```bash
   chmod 755 uploads/
   chmod 644 *.php
   chmod 644 admin/*.php
   ```

### Running the Application

**Using PHP Built-in Server:**
```bash
cd d:\LFIS
php -S localhost:8000
```
Then open: `http://localhost:8000`

**Using Apache/Nginx:**
- Configure virtual host to point to `LFIS` directory
- Access via configured domain

## 🔐 Admin Access

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

**Access Admin Panel:** `http://your-site/admin/login.php`

⚠️ **Important:** Change the default password immediately after first login!

## 📁 Project Structure

```
LFIS/
├── admin/
│   ├── config.php           # Database configuration
│   ├── functions.php        # Database helper functions
│   ├── login.php            # Admin login page
│   ├── dashboard.php        # Main dashboard
│   ├── manage-reports.php   # Report management
│   ├── manage-matches.php   # Match management
│   ├── manage-users.php     # User management
│   ├── settings.php         # System settings
│   ├── logout.php           # Logout handler
│   └── database.sql         # Database schema
├── uploads/                 # User uploaded files (create manually)
├── index.php                # Home page
├── report-lost.php          # Report lost form
├── report-found.php         # Report found form
├── browse-found.php         # Browse found items
├── process_lost_report.php  # Lost report processor
├── process_found_report.php # Found report processor
├── process_contact.php      # Contact message processor
└── api.php                  # API endpoints
```

## 🔌 API Endpoints

The system provides JSON API endpoints at `api.php`:

### Available Actions:
- `api.php?action=stats` - Get dashboard statistics
- `api.php?action=recent_reports` - Get recent reports
- `api.php?action=lost_reports` - Get all lost reports
- `api.php?action=found_reports` - Get all found reports
- `api.php?action=matches` - Get all matches
- `api.php?action=search&q=keyword` - Search reports

## 📋 Database Tables

### lost_reports
Stores lost item reports with item details, reporter info, and status.

### found_reports
Stores found item reports with photos, condition, and storage location.

### matches
Tracks automatic and manual matches between lost and found items.

### users
Stores user account information (for future user authentication).

### contact_messages
Stores messages between users and item finders.

### admin_logs
Audit trail of admin actions and system events.

### settings
System configuration settings.

## 🔄 Form Submission Flow

1. **User submits form** → Frontend validation → Form data sent to processor
2. **Processor validates** → Saves to database → Returns JSON response
3. **Frontend handles response** → Shows success/error message
4. **Admin reviews** → Approves/rejects report
5. **System creates matches** → Notifies relevant parties

## 🚀 Features in Development

- [ ] Email notifications
- [ ] User authentication system
- [ ] Advanced search and filters
- [ ] Match confidence scoring
- [ ] User ratings and reviews
- [ ] Mobile app
- [ ] Multi-language support

## 🐛 Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify credentials in `admin/config.php`
- Ensure database exists: `CREATE DATABASE lfis_db;`

### Upload Issues
- Create `uploads/` directory with write permissions: `chmod 755 uploads/`
- Check `MAX_UPLOAD_SIZE` in `admin/config.php`
- Verify PHP `upload_max_filesize` and `post_max_size`

### Admin Login Failed
- Clear browser cookies
- Check MySQL `users` table
- Verify password hash format

### Forms Not Submitting
- Check browser console for JavaScript errors
- Verify processor files exist in root directory
- Check server error logs

## 📞 Support

For issues or questions:
1. Check the troubleshooting section
2. Review error logs in `admin_logs` table
3. Check browser developer console (F12)

## 📄 License

© 2026 BintDjango. All Rights Reserved.

## 👥 Contributors

- Development Team: BintDjango

---

**Version:** 1.0  
**Last Updated:** June 1, 2026
