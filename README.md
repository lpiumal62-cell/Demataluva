# Bright Future Academy - School Management Website

A modern, responsive school management website built with PHP, MySQL, and modern web technologies. Features a dark/light theme, animations, and a comprehensive admin panel.

## 🚀 Features

### Frontend Pages
- **Home Page**: Hero slider, animated statistics, quick links, testimonials
- **About Us**: Mission & vision, school history timeline, teacher profiles, awards
- **Classes**: Class listings with teacher assignments, curriculum highlights
- **Teachers**: Faculty profiles with contact information and qualifications
- **Results**: Student test scores with search and filtering capabilities
- **Gallery**: Photo gallery with filtering by events and categories
- **Events**: School events and news with newsletter subscription
- **Contact**: Contact form, school information, FAQ section

### Admin Panel
- **Dashboard**: Statistics overview, recent activities, quick actions
- **Student Management**: Add, edit, delete student records
- **Teacher Management**: Manage faculty information
- **Class Management**: Organize classes and assignments
- **Test Scores**: Input and manage student test results
- **Gallery Management**: Upload and organize photos
- **Event Management**: Create and manage school events
- **Message Center**: Handle contact form submissions

### Design Features
- **Dark/Light Theme**: Toggle between themes with persistent preference
- **Responsive Design**: Mobile, tablet, and desktop friendly
- **Animations**: AOS (Animate On Scroll) library integration
- **Modern UI**: TailwindCSS with custom styling
- **Interactive Elements**: Swiper.js sliders, modals, hover effects

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, TailwindCSS, JavaScript, AOS, Swiper.js
- **Backend**: PHP 8+
- **Database**: MySQL
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Inter)

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## 🔧 Installation

### 1. Clone/Download the Project
```bash
git clone [repository-url]
# or download and extract the ZIP file
```

### 2. Database Setup
1. Create a MySQL database named `bright_future_academy`
2. Import the database schema:
```bash
mysql -u your_username -p bright_future_academy < database/schema.sql
```

### 3. Configuration
1. Update database credentials in `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bright_future_academy');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 4. File Permissions
Ensure the web server has read/write permissions to the project directory.

### 5. Web Server Setup
- **Apache**: Enable mod_rewrite and point document root to project folder
- **Nginx**: Configure server block to serve PHP files

### 6. Access the Website
- Frontend: `http://your-domain/`
- Admin Panel: `http://your-domain/admin/login.php`

## 🔐 Default Admin Credentials

- **Username**: admin
- **Password**: admin123

**⚠️ Important**: Change these credentials immediately after installation!

## 📁 Project Structure

```
Demataluva/
├── admin/                 # Admin panel files
│   ├── login.php         # Admin login
│   ├── dashboard.php     # Admin dashboard
│   ├── students.php      # Student management
│   └── logout.php        # Logout functionality
├── assets/               # Static assets
│   ├── css/
│   │   └── style.css    # Custom styles
│   └── js/
│       └── main.js      # JavaScript functions
├── config/               # Configuration files
│   └── database.php     # Database connection
├── database/             # Database files
│   └── schema.sql       # Database schema
├── includes/             # PHP includes
│   └── functions.php    # Helper functions
├── index.php            # Home page
├── about.php            # About page
├── classes.php          # Classes page
├── teachers.php         # Teachers page
├── results.php          # Results page
├── gallery.php          # Gallery page
├── events.php           # Events page
└── contact.php          # Contact page
```

## 🎨 Customization

### Theme Colors
Edit CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #1fb6ff;
    --secondary-color: #ffd700;
    --dark-bg: #0d1117;
    /* ... other variables */
}
```

### Adding New Pages
1. Create new PHP file in root directory
2. Include navigation and footer from existing pages
3. Add page link to navigation menu
4. Update admin sidebar if needed

### Database Modifications
1. Update `database/schema.sql` for new tables/fields
2. Modify `includes/functions.php` for new functionality
3. Update admin pages to handle new data

## 🔧 Features Implementation

### Dark/Light Theme
- Theme preference stored in localStorage
- CSS classes toggle between themes
- Smooth transitions between theme changes

### Animations
- AOS library for scroll animations
- Custom CSS animations for hover effects
- Counter animations for statistics

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Flexible grid layouts

### Form Handling
- Server-side validation
- CSRF protection (can be added)
- Email validation
- Sanitized input handling

## 🚀 Deployment

### Production Checklist
- [ ] Change default admin credentials
- [ ] Update database credentials
- [ ] Enable HTTPS
- [ ] Configure proper file permissions
- [ ] Set up regular database backups
- [ ] Configure error logging
- [ ] Test all functionality

### Performance Optimization
- Enable PHP OPcache
- Use CDN for static assets
- Optimize images
- Enable gzip compression
- Configure browser caching

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Admin Login Not Working**
   - Check if admin user exists in database
   - Verify password hashing
   - Check session configuration

3. **Images Not Loading**
   - Check file permissions
   - Verify image paths
   - Ensure web server can serve static files

4. **CSS/JS Not Loading**
   - Check file paths
   - Verify web server configuration
   - Clear browser cache

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For support and questions:
- Create an issue in the repository
- Contact: info@brightfutureacademy.com

## 🔄 Updates

### Version 1.0.0
- Initial release
- Complete frontend and admin functionality
- Dark/light theme support
- Responsive design
- Database integration

---

**Bright Future Academy** - Empowering minds and building futures through quality education.
