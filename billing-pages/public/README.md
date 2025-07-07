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

## 🚀 Quick Start

### Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd billing-pages
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Set up the database**
   ```bash
   # Import the database schema
   mysql -u your_username -p your_database < database/schema.sql
   ```

4. **Configure the application**
   ```bash
   # Copy and edit the configuration
   cp config/config.example.php config/config.php
   # Edit config.php with your database credentials
   ```

5. **Set up the web server**
   ```bash
   # For development, you can use PHP's built-in server
   php -S localhost:8000 -t public
   ```

6. **Access the application**
   - Open your browser and go to `http://localhost:8000`
   - Login with the default credentials (check the database for user setup)

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

## 🔧 Configuration

### Database Configuration

Edit `config/config.php` to set your database credentials:

```php
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'billing_system');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Environment Variables

Create a `.env` file in the root directory:

```env
APP_DEBUG=true
APP_ENV=development
SESSION_SECRET=your-secret-key
DEFAULT_LOCALE=de
```

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