# Billing Pages - Modern Billing Portal

A modern, responsive billing portal that supports both German (abrechnung-portal.de) and English (billing-pages.com) interfaces.

## Features

- 🌍 **Multi-language Support**: German and English interfaces
- 📱 **Responsive Design**: Works on desktop, tablet, and mobile
- 🔐 **Secure Authentication**: Modern login system with session management
- 📊 **Multiple Billing Types**: 
  - Company billing (Firmenabrechnung)
  - Tour billing (Tourenabrechnung)
  - Work billing (Arbeitsabrechnung)
  - Task billing (Aufgabenabrechnung)
  - Money billing (Geldabrechnung)
- 📈 **Reports & Analytics**: Data visualization and PDF generation
- 👥 **User Management**: Role-based access control
- 🗺️ **Mapping Integration**: Geographic data visualization
- 📄 **PDF Generation**: Invoice and report creation

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5
- **Backend**: PHP 8.0+
- **Database**: MySQL/MariaDB
- **PDF Generation**: TCPDF
- **Charts**: Chart.js
- **Maps**: Leaflet.js

## Installation

1. Clone the repository
2. Configure your web server to point to the `public` directory
3. Set up the database using the provided SQL schema
4. Configure environment variables in `.env`
5. Install dependencies: `composer install`

## Structure

```
billing-pages/
├── public/              # Web root directory
├── src/                 # Application source code
├── config/              # Configuration files
├── assets/              # Static assets (CSS, JS, images)
├── templates/           # HTML templates
├── locales/             # Translation files
└── docs/                # Documentation
```

## License

MIT License 