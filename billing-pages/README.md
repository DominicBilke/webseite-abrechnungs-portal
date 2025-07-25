# Billing Pages Portal

A modern, comprehensive billing portal built with PHP, featuring company management, work tracking, task management, tour billing, and invoice generation.

## Features

### ✅ Completed Features

- **Authentication System**
  - User login/logout
  - Session management
  - Role-based access control

- **Company Management**
  - Add, edit, view, and delete companies
  - Company overview with statistics
  - Detailed company reports
  - Search functionality

- **Work Management**
  - Track work hours and projects
  - Work entry management (CRUD operations)
  - Work reports and analytics
  - Integration with billing system

- **Money Management**
  - Income and expense tracking
  - Payment method categorization
  - Financial reports and analytics
  - Cash flow monitoring

- **Billing System**
  - Invoice generation from work entries
  - PDF invoice creation
  - Email invoice functionality
  - Invoice status management
  - Billing overview and reports

- **Tour Management**
  - Tour tracking and management
  - Distance and duration calculation
  - Tour billing integration
  - Tour reports and analytics

- **Task Management**
  - Task creation and assignment
  - Priority and status management
  - Task completion tracking
  - Task reports and analytics

- **Dashboard**
  - Comprehensive overview with statistics
  - Recent activities
  - Quick actions
  - Charts and analytics

- **Localization**
  - German and English support
  - Complete translation coverage
  - Language switching

## 🚀 Production Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Composer
- Web server (Apache/Nginx)
- SSL certificate (recommended for production)

### Production Installation

1. **Download and extract the application**
   ```bash
   # Extract to your web server directory
   # Ensure the public/ directory is your web root
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Set up the database**
   ```bash
   # Create database and user
   mysql -u root -p
   CREATE DATABASE billing_system;
   CREATE USER 'billing_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON billing_system.* TO 'billing_user'@'localhost';
   FLUSH PRIVILEGES;
   
   # Import the database schema
   mysql -u billing_user -p billing_system < database/schema.sql
   ```

4. **Configure the application**
   ```bash
   # Copy the environment example
   cp config/env.production.example .env
   
   # Edit .env with your production settings
   nano .env
   ```

5. **Set proper permissions**
   ```bash
   # Set directory permissions
   chmod 755 public/
   chmod 644 public/.htaccess
   chmod 600 .env
   chmod 755 config/
   ```

6. **Configure web server**
   - Point document root to `public/` directory
   - Enable HTTPS/SSL
   - Configure proper security headers

7. **Access the application**
   - Open your browser and go to your domain
   - Create your first user account

## 📁 Project Structure

```
billing-pages/
├── assets/                 # Static assets (CSS, JS, images)
├── config/                 # Configuration files
├── database/              # Database schema and migrations
├── locales/               # Localization files
├── public/                # Public web root
├── src/                   # Application source code
│   ├── Controllers/       # Controller classes
│   └── Core/             # Core framework classes
├── templates/             # View templates
└── vendor/                # Composer dependencies
```

## 🔧 Production Configuration

### Environment Configuration

The application uses environment variables for configuration. Copy the example file and update it:

```bash
cp config/env.production.example .env
```

Key configuration options:

```env
# Application Settings
APP_NAME="Billing Portal"
APP_URL="https://your-domain.com"
APP_ENV="production"
APP_DEBUG=false

# Database Configuration
DB_HOST="localhost:3306"
DB_NAME="billing_system"
DB_USER="your_db_user"
DB_PASS="your_secure_password"

# Security
SESSION_SECRET="your-super-secure-random-session-secret-key"
```

### Security Considerations

- Use strong, unique passwords for database and session
- Enable HTTPS/SSL in production
- Regularly backup your database
- Keep PHP and dependencies updated
- Monitor application logs for security issues

## 📊 Database Schema

The application uses the following main tables:

- `users` - User accounts and authentication
- `companies` - Company/client information
- `work_entries` - Work time tracking
- `money_entries` - Income and expense tracking
- `tours` - Tour management
- `tasks` - Task management
- `invoices` - Invoice generation
- `invoice_items` - Invoice line items

## 🎯 Usage Guide

### 1. Company Management

1. Navigate to **Companies** in the main menu
2. Click **Add** to create a new company
3. Fill in company details (name, address, contact info)
4. Use **Overview** to see all companies
5. Use **Reports** for detailed analytics

### 2. Work Tracking

1. Go to **Work** section
2. Click **Add** to log work hours
3. Enter work details (date, hours, description, rate)
4. View work history in **Overview**
5. Generate reports in **Reports**

### 3. Billing

1. Navigate to **Billing**
2. Click **Create Invoice** to generate a new invoice
3. Select work entries to include
4. Review and generate PDF invoice
5. Send invoice via email

### 4. Task Management

1. Go to **Tasks** section
2. Create new tasks with priorities and due dates
3. Track task progress and completion
4. View task reports and analytics

### 5. Tour Management

1. Navigate to **Tours**
2. Add tour details (date, route, vehicle, driver)
3. Track distance and duration
4. Generate tour-based invoices

## 🔒 Security Features

- Session-based authentication
- Role-based access control
- Input sanitization and validation
- SQL injection prevention
- XSS protection
- CSRF protection
- Secure headers

## 🌐 Localization

The application supports multiple languages:

- **German (de)** - Primary language
- **English (en)** - Secondary language

Language can be changed via the language dropdown in the navigation.

## 📈 Reporting

The application provides comprehensive reporting for:

- Company performance
- Work analytics
- Financial reports
- Task completion rates
- Tour statistics
- Billing summaries

## 🛠️ Development

### Adding New Features

1. Create controller in `src/Controllers/`
2. Add routes in `src/Core/Application.php`
3. Create templates in `templates/`
4. Add localization keys in `locales/`

### Code Style

- Follow PSR-4 autoloading standards
- Use meaningful variable and function names
- Add comments for complex logic
- Follow MVC pattern

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/config.php`
   - Ensure MySQL service is running

2. **Permission Errors**
   - Ensure web server has read/write access to the project directory
   - Check file permissions for upload directories

3. **Routing Issues**
   - Ensure `.htaccess` is properly configured
   - Check that mod_rewrite is enabled

### Debug Mode

Enable debug mode in `config/config.php`:

```php
define('APP_DEBUG', true);
```

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📞 Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the troubleshooting section

---

**Billing Pages Portal** - A comprehensive solution for professional billing and time tracking. 