# Invoice & Billing Management System - XAMPP Setup Guide

## Overview
This is a complete PHP-based Invoice & Billing Management System designed to run on XAMPP with phpMyAdmin.

## Prerequisites
- XAMPP installed (Download from https://www.apachefriends.org/)
- MySQL/MariaDB service running
- PHP 7.4 or higher
- Modern web browser

## Installation Steps

### 1. Extract Project Files
- Extract the project folder to: `C:\xampp\htdocs\invoice-system` (Windows) or `/Applications/XAMPP/htdocs/invoice-system` (Mac) or `/opt/lampp/htdocs/invoice-system` (Linux)

### 2. Create Database

#### Method A: Using phpMyAdmin (Recommended)
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" or "Create database"
3. Enter database name: `invoice_system`
4. Click "Create"
5. Select the new database
6. Go to the "SQL" tab
7. Copy and paste the entire content of `/sql/schema.sql`
8. Click "Go" to execute

#### Method B: Using MySQL Command Line
\`\`\`bash
mysql -u root -p
CREATE DATABASE invoice_system;
USE invoice_system;
SOURCE /path/to/sql/schema.sql;
\`\`\`

### 3. Verify Database Connection
- Edit `config/db.php` if needed (default settings should work for XAMPP):
  - DB_HOST: `localhost`
  - DB_USER: `root`
  - DB_PASSWORD: `` (empty)
  - DB_NAME: `invoice_system`

### 4. Start XAMPP
- **Windows:** Open XAMPP Control Panel → Start Apache and MySQL
- **Mac/Linux:** Run `sudo /Applications/XAMPP/xamppfiles/bin/xampp start` or use the GUI

### 5. Access the Application
- Open browser and navigate to: `http://localhost/invoice-system`
- You should see the login page

### 6. Create Your Account
- Click "Register here" on the login page
- Fill in your details (Full Name, Email, Password)
- Click "Register"
- You'll be redirected to login
- Log in with your credentials

## File Structure

\`\`\`
invoice-system/
├── config/
│   ├── db.php           # Database connection configuration
│   └── auth.php         # Authentication functions
├── includes/
│   ├── header.php       # Page header template
│   └── footer.php       # Page footer template
├── clients/
│   ├── list.php         # View all clients
│   ├── add.php          # Add new client
│   └── edit.php         # Edit client details
├── invoices/
│   ├── list.php         # View all invoices
│   ├── add.php          # Create new invoice
│   ├── view.php         # View invoice details
│   ├── edit.php         # Edit invoice
│   └── pdf.php          # Download invoice as PDF
├── assets/
│   └── styles.css       # Application stylesheet
├── sql/
│   └── schema.sql       # Database schema
├── index.php            # Application entry point
├── login.php            # Login page
├── register.php         # Registration page
├── dashboard.php        # User dashboard
└── logout.php           # Logout handler
\`\`\`

## Key Features

### User Management
- User registration with email and password
- Secure password hashing using bcrypt
- Session-based authentication
- User data isolation

### Client Management
- Add, edit, and delete clients
- Store client contact information
- View all clients in table format

### Invoice Management
- Create invoices with multiple line items
- Edit invoice details and items
- Track invoice status (Draft, Paid)
- View invoice details
- Calculate totals automatically

### PDF Export
- Download invoices as HTML/PDF
- Print-ready formatting
- Professional invoice template

### Dashboard
- Real-time statistics
- Total invoices count
- Paid/Draft invoices tracking
- Total income overview
- Recent invoices list

## Database Schema

### Users Table
Stores user account information with encrypted passwords

### Clients Table
Stores client details linked to user accounts

### Invoices Table
Main invoice records with status tracking

### Invoice Items Table
Line items for each invoice with quantity and pricing

## Security Features

- **SQL Injection Protection:** Prepared statements for all queries
- **Password Security:** bcrypt hashing for user passwords
- **Input Validation:** Sanitization and validation of all inputs
- **Session Management:** Secure user session handling
- **User Isolation:** Users only see their own data

## Troubleshooting

### Database Connection Error
- Verify MySQL service is running in XAMPP
- Check credentials in `config/db.php`
- Ensure `invoice_system` database exists

### Login Issues
- Verify user was registered successfully
- Check that password is correct
- Clear browser cookies and try again

### File Upload/Create Issues
- Ensure write permissions on project directory
- Check that `assets/` and `sql/` directories exist

### PDF Download Issues
- Browser may prompt for download instead of opening
- This is normal behavior - check your Downloads folder

## Browser Compatibility
- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- IE 11: Limited support (not recommended)

## Support
For issues or questions, review the code comments or contact support through phpMyAdmin database interface to verify data integrity.

## Default Test Credentials
After setup, create your own account through the registration page. No default credentials are provided for security reasons.

## Performance Tips
1. Index frequently queried fields (already done in schema)
2. Regular database backups recommended
3. Clear old sessions periodically
4. Monitor database size for large numbers of invoices

## Customization
- Modify `assets/styles.css` to change colors and styles
- Update business name in header/footer
- Add custom fields to database tables as needed
