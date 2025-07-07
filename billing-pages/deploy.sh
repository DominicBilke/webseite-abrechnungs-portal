#!/bin/bash

# Billing Portal - Production Deployment Script
# This script automates the deployment process for production

set -e  # Exit on any error

echo "🚀 Starting Billing Portal Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root"
   exit 1
fi

# Check prerequisites
print_status "Checking prerequisites..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
PHP_MAJOR=$(echo $PHP_VERSION | cut -d. -f1)
PHP_MINOR=$(echo $PHP_VERSION | cut -d. -f2)

if [ "$PHP_MAJOR" -lt 8 ]; then
    print_error "PHP 8.0 or higher is required. Current version: $PHP_VERSION"
    exit 1
fi

print_status "PHP version: $PHP_VERSION ✓"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    exit 1
fi

print_status "Composer found ✓"

# Check if MySQL/MariaDB is accessible
if ! command -v mysql &> /dev/null; then
    print_warning "MySQL client not found. Database setup will need to be done manually."
fi

# Install dependencies
print_status "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Set up environment file
if [ ! -f .env ]; then
    print_status "Creating environment file..."
    if [ -f config/env.production.example ]; then
        cp config/env.production.example .env
        print_warning "Please edit .env file with your production settings"
    else
        print_error "Environment example file not found"
        exit 1
    fi
else
    print_status "Environment file already exists"
fi

# Set proper permissions
print_status "Setting file permissions..."
chmod 755 public/
chmod 644 public/.htaccess
chmod 600 .env 2>/dev/null || print_warning "Could not set .env permissions"
chmod 755 config/

# Create necessary directories
print_status "Creating necessary directories..."
mkdir -p logs
mkdir -p uploads
chmod 755 logs uploads

# Database setup reminder
print_status "Database setup required:"
echo "1. Create database: CREATE DATABASE billing_system;"
echo "2. Create user: CREATE USER 'billing_user'@'localhost' IDENTIFIED BY 'secure_password';"
echo "3. Grant privileges: GRANT ALL PRIVILEGES ON billing_system.* TO 'billing_user'@'localhost';"
echo "4. Import schema: mysql -u billing_user -p billing_system < database/schema.sql"

# Web server configuration reminder
print_status "Web server configuration required:"
echo "1. Point document root to: $(pwd)/public"
echo "2. Enable HTTPS/SSL"
echo "3. Configure proper security headers"

# Final checks
print_status "Running final checks..."

# Check if public directory exists
if [ ! -d "public" ]; then
    print_error "Public directory not found"
    exit 1
fi

# Check if index.php exists
if [ ! -f "public/index.php" ]; then
    print_error "public/index.php not found"
    exit 1
fi

# Check if .htaccess exists
if [ ! -f "public/.htaccess" ]; then
    print_warning "public/.htaccess not found - security may be compromised"
fi

print_status "✅ Deployment completed successfully!"
echo ""
print_status "Next steps:"
echo "1. Edit .env file with your production settings"
echo "2. Set up database and import schema"
echo "3. Configure web server"
echo "4. Test the application"
echo "5. Set up regular backups"
echo ""
print_status "For help, see README.md or visit: https://www.dominic-bilke.de/en/imprint" 