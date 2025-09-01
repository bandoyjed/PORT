# Portfolio Website - PHP Version

A dynamic portfolio website built with PHP and MySQL, featuring a modern responsive design and admin panel for content management.

## Features

- **Dynamic Content**: All portfolio content is stored in a MySQL database
- **Admin Panel**: Easy-to-use admin interface for managing content
- **Contact Form**: Functional contact form with email notifications
- **Responsive Design**: Modern, mobile-friendly design
- **Security**: CSRF protection and input validation
- **SEO Friendly**: Clean URLs and meta tags

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP, WAMP, or similar local development environment

## Installation

### 1. Database Setup

1. Create a MySQL database named `portfolio_db`
2. Import the database structure by running the SQL commands in `database.sql`
3. Update database credentials in `config.php` if needed

### 2. File Setup

1. Upload all files to your web server directory
2. Ensure the web server has write permissions for the `activity.log` file
3. Update the site configuration in `config.php`

### 3. Configuration

Edit `config.php` to update:
- Database connection details
- Site information (name, email, etc.)
- Admin password (in `admin.php`)

## File Structure

```
portfolio/
├── index.php              # Main portfolio page
├── admin.php              # Admin panel
├── config.php             # Database and site configuration
├── functions.php          # Helper functions
├── process_contact.php    # Contact form handler
├── database.sql           # Database structure and sample data
├── styles.css             # CSS styles
├── script.js              # JavaScript functionality
└── README.md              # This file
```

## Database Tables

- **personal_info**: Personal information and contact details
- **skills**: Skills organized by categories
- **education**: Educational background
- **experience**: Work experience
- **projects**: Portfolio projects
- **applications**: Tools and applications used
- **contact_messages**: Contact form submissions

## Usage

### Frontend
- Visit `index.php` to view the portfolio
- All content is dynamically loaded from the database
- Contact form submissions are stored and can be viewed in admin panel

### Admin Panel
- Access admin panel at `admin.php`
- Default password: `admin123` (change this!)
- Manage all portfolio content:
  - Personal information
  - Skills
  - Projects
  - View contact messages

## Customization

### Adding Content
1. Use the admin panel to add/edit content
2. Or directly insert data into the database tables
3. Update the CSS in `styles.css` for design changes

### Styling
- Modify `styles.css` to change the appearance
- The design is responsive and uses modern CSS Grid/Flexbox
- Color scheme can be easily changed by updating CSS variables

### Functionality
- Add new features by extending `functions.php`
- Modify JavaScript in `script.js` for interactive features
- Add new admin features in `admin.php`

## Security Features

- CSRF token protection for forms
- Input validation and sanitization
- Prepared statements for database queries
- Session-based admin authentication

## Email Configuration

The contact form sends email notifications. To enable this:

1. Configure your server's mail settings
2. Or integrate with services like SendGrid, Mailgun, etc.
3. Update the `sendEmailNotification()` function in `functions.php`

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Errors**
   - Ensure web server has write permissions
   - Check file permissions for `activity.log`

3. **Contact Form Not Working**
   - Check email configuration
   - Verify CSRF token is being generated
   - Check browser console for JavaScript errors

### Debug Mode

To enable debug mode, add this to `config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## License

This project is open source and available under the MIT License.

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the code comments
3. Ensure all requirements are met

## Changelog

### Version 1.0
- Initial release
- Dynamic content management
- Admin panel
- Contact form functionality
- Responsive design
