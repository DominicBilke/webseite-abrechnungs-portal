# Billing Portal - Installation Guide

This guide will help you install and configure the Billing Portal for both abrechnung-portal.de and billing-pages.com.

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher (or MariaDB 10.2 or higher)
- Apache web server with mod_rewrite enabled
- Composer (PHP package manager)
- Git (optional, for version control)

## Installation Steps

### 1. Download the Project

```bash
# Clone the repository (if using Git)
git clone https://github.com/your-username/billing-pages.git
cd billing-pages

# Or download and extract the ZIP file
# Then navigate to the billing-pages directory
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# If you don't have Composer installed, download it from:
# https://getcomposer.org/download/
```

### 3. Configure Environment

```bash
# Copy the environment configuration file
cp config/env.example config/.env

# Edit the configuration file with your settings
nano config/.env
```

**Required Configuration:**

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=billing_portal
DB_USER=your_database_user
DB_PASS=your_database_password

# Application Configuration
APP_NAME="Billing Portal"
APP_URL=http://your-domain.com
APP_ENV=production
APP_DEBUG=false

# Session Configuration
SESSION_SECRET=your-very-long-random-secret-key-here
SESSION_LIFETIME=3600

# Email Configuration (for notifications)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# Default Language
DEFAULT_LOCALE=de
```

### 4. Set Up Database

```bash
# Create the database
mysql -u root -p
CREATE DATABASE billing_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'billing_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON billing_portal.* TO 'billing_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import the database schema
mysql -u billing_user -p billing_portal < database/schema.sql
```

### 5. Configure Web Server

#### Apache Configuration

1. **Set Document Root:**
   Configure your web server to point to the `public` directory.

2. **Enable mod_rewrite:**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Virtual Host Configuration:**
   ```apache
   <VirtualHost *:80>
       ServerName abrechnung-portal.de
       ServerAlias www.abrechnung-portal.de
       DocumentRoot /path/to/billing-pages/public
       
       <Directory /path/to/billing-pages/public>
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/billing-portal_error.log
       CustomLog ${APACHE_LOG_DIR}/billing-portal_access.log combined
   </VirtualHost>
   
   <VirtualHost *:80>
       ServerName billing-pages.com
       ServerAlias www.billing-pages.com
       DocumentRoot /path/to/billing-pages/public
       
       <Directory /path/to/billing-pages/public>
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/billing-pages_error.log
       CustomLog ${APACHE_LOG_DIR}/billing-pages_access.log combined
   </VirtualHost>
   ```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name abrechnung-portal.de www.abrechnung-portal.de;
    root /path/to/billing-pages/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

server {
    listen 80;
    server_name billing-pages.com www.billing-pages.com;
    root /path/to/billing-pages/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6. Set File Permissions

```bash
# Set proper permissions
sudo chown -R www-data:www-data /path/to/billing-pages
sudo chmod -R 755 /path/to/billing-pages
sudo chmod -R 777 /path/to/billing-pages/storage  # If you have a storage directory
```

### 7. Create SSL Certificates (Recommended)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL certificates
sudo certbot --apache -d abrechnung-portal.de -d www.abrechnung-portal.de
sudo certbot --apache -d billing-pages.com -d www.billing-pages.com
```

### 8. Test the Installation

1. **Access the application:**
   - German version: https://abrechnung-portal.de
   - English version: https://billing-pages.com

2. **Default login credentials:**
   - Username: `admin`
   - Password: `password` (change this immediately!)

3. **Change default password:**
   - Log in as admin
   - Go to Settings → Profile
   - Change the password

## Post-Installation Configuration

### 1. Configure Email Settings

Update the email configuration in `config/.env` to enable email notifications:

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### 2. Set Up Backup

Create a backup script:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/billing-portal"
DB_NAME="billing_portal"
DB_USER="billing_user"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# File backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /path/to/billing-pages

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

Make it executable and add to cron:
```bash
chmod +x backup.sh
crontab -e
# Add: 0 2 * * * /path/to/backup.sh
```

### 3. Configure Logging

Enable logging by setting in `config/.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### 4. Set Up Monitoring

Consider setting up monitoring tools like:
- Uptime monitoring (UptimeRobot, Pingdom)
- Error tracking (Sentry)
- Performance monitoring (New Relic)

## Troubleshooting

### Common Issues

1. **500 Internal Server Error:**
   - Check file permissions
   - Verify .htaccess is working
   - Check PHP error logs

2. **Database Connection Error:**
   - Verify database credentials in .env
   - Check if MySQL service is running
   - Ensure database exists

3. **URL Rewriting Not Working:**
   - Enable mod_rewrite: `sudo a2enmod rewrite`
   - Restart Apache: `sudo systemctl restart apache2`
   - Check .htaccess file exists

4. **Permission Denied:**
   - Set correct ownership: `sudo chown -R www-data:www-data /path/to/billing-pages`
   - Set correct permissions: `sudo chmod -R 755 /path/to/billing-pages`

### Log Files

Check these log files for errors:
- Apache error log: `/var/log/apache2/error.log`
- PHP error log: `/var/log/php8.0-fpm.log`
- Application logs: Check your web server's error log

## Security Considerations

1. **Change default passwords immediately**
2. **Use strong, unique passwords**
3. **Keep software updated**
4. **Enable SSL/HTTPS**
5. **Regular backups**
6. **Monitor access logs**
7. **Use firewall rules**
8. **Regular security audits**

## Support

For support and questions:
- Check the documentation in the `docs/` directory
- Review the README.md file
- Create an issue on the GitHub repository

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Quick Setup

### 1. Database Setup

1. **Create the database:**
   ```sql
   CREATE DATABASE billing_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import the schema:**
   ```bash
   mysql -u your_username -p billing_system < database/schema.sql
   ```

3. **Add demo users:**
   ```bash
   mysql -u your_username -p billing_system < database/demo_users.sql
   ```

### 2. Configuration

1. **Copy configuration file:**
   ```bash
   cp config/config.example.php config/config.php
   ```

2. **Edit `config/config.php` with your database credentials:**
   ```php
   define('DB_HOST', 'localhost:3306');
   define('DB_NAME', 'billing_system');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

### 3. Demo Users

After running the demo_users.sql script, you'll have these users:

**Demo User:**
- Username: `demo`
- Password: `demo123`
- Role: User

**Admin User:**
- Username: `admin`
- Password: `password`
- Role: Admin

### 4. Web Server Setup

#### Option A: PHP Built-in Server (Development)
```bash
cd billing-pages
php -S localhost:8000 -t public
```

#### Option B: Apache/Nginx (Production)
1. Point your web server to the `public/` directory
2. Ensure `.htaccess` is enabled for Apache
3. Set proper file permissions

### 5. Access the Application

Open your browser and go to:
- **Development:** `http://localhost:8000`
- **Production:** `http://your-domain.com`

## Troubleshooting

### Login Issues

If you're having trouble logging in:

1. **Check database connection:**
   - Verify database credentials in `config/config.php`
   - Ensure MySQL service is running

2. **Verify demo users exist:**
   ```sql
   SELECT username, email, status FROM users;
   ```

3. **Reset demo user password:**
   ```sql
   UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'demo';
   UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';
   ```

### Localization Issues

If localization is not working:

1. **Check locale files exist:**
   - `locales/de.php` (German)
   - `locales/en.php` (English)

2. **Verify file permissions:**
   - Ensure web server can read locale files

3. **Check configuration:**
   - Verify `DEFAULT_LOCALE` is set in config

### Common Errors

1. **"Call to undefined method"**
   - Run `composer install` to install dependencies

2. **"Database connection failed"**
   - Check database credentials and MySQL service

3. **"Template not found"**
   - Ensure all template files are in the correct directories

## File Structure

```
billing-pages/
├── assets/                 # Static assets
├── config/                 # Configuration files
├── database/              # Database schema and demo data
│   ├── schema.sql         # Main database schema
│   └── demo_users.sql     # Demo users and sample data
├── locales/               # Localization files
├── public/                # Web root
├── src/                   # Application source
└── templates/             # View templates
```

## Security Notes

1. **Change default passwords** in production
2. **Use HTTPS** in production
3. **Set proper file permissions**
4. **Regular database backups**
5. **Keep dependencies updated**

## Support

For issues and questions:
1. Check this installation guide
2. Review the README.md file
3. Check the troubleshooting section
4. Create an issue in the repository

---

**Billing Pages Portal** - Professional billing and time tracking solution 