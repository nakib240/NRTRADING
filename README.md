# NR TRADING - Full-Stack eCommerce Website

A professional PHP & MySQL eCommerce platform for wholesale spice trading business with complete customer shopping experience and admin management panel.

## 🚀 Features

### Customer Features
- **Product Browsing**: Shop page with search, category filters, price sorting
- **Product Details**: Image gallery, stock availability, quantity selector
- **Shopping Cart**: Session-based cart with real-time updates
- **Checkout**: Complete order placement with billing/shipping info
- **Order Tracking**: Track order status with timeline view
- **User Account**: Profile management and order history
- **Authentication**: Secure login/registration system

### Admin Panel Features
- **Dashboard**: Statistics overview with sales, orders, customers metrics
- **Category Management**: CRUD operations for product categories
- **Product Management**: Add/edit/delete products with image upload
- **Order Management**: View orders, update status, add tracking notes
- **Customer Management**: View registered customers and order history
- **Reports**: Daily/monthly sales reports, low stock alerts
- **Settings**: Editable site configuration (business info, social links, banner text)

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP (for local development)

## 🔧 Installation Instructions

### Local Development (XAMPP)

1. **Install XAMPP**
   - Download from [https://www.apachefriends.org](https://www.apachefriends.org)
   - Install and start Apache and MySQL services

2. **Copy Project Files**
   ```bash
   # Copy the NRTRADING folder to your XAMPP htdocs directory
   C:\xampp\htdocs\NRTRADING\
   ```

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create new database named: `nrtrading`
   - Click on "Import" tab
   - Choose file: `NRTRADING/database/nrtrading.sql`
   - Click "Go" to import

4. **Configure Database Connection**
   - Open `config/config.php`
   - Verify database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'nrtrading');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

5. **Set File Permissions**
   - Create uploads directory if not exists:
     ```
     NRTRADING/uploads/products/
     ```
   - Ensure write permissions on uploads folder

6. **Access the Website**
   - Customer Site: `http://localhost/NRTRADING`
   - Admin Panel: `http://localhost/NRTRADING/admin`

### Production Deployment (Shared Hosting)

1. **Upload Files**
   - Upload all files via FTP/cPanel File Manager
   - Recommended path: `public_html/nrtrading/`

2. **Create Database**
   - Create MySQL database via cPanel
   - Import `database/nrtrading.sql` via phpMyAdmin

3. **Update Configuration**
   - Edit `config/config.php`:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'your_database_name');
     define('DB_USER', 'your_database_user');
     define('DB_PASS', 'your_database_password');
     
     define('SITE_URL', 'https://yourdomain.com/nrtrading');
     define('ADMIN_URL', 'https://yourdomain.com/nrtrading/admin');
     ```

4. **Set Permissions**
   - Set `uploads/products/` to 755 or 777 (writable)

5. **Security Recommendations**
   - Change default admin password immediately
   - Use HTTPS (SSL certificate)
   - Update `DB_PASS` to strong password
   - Consider restricting admin panel by IP

## 🔐 Default Credentials

### Admin Panel
- **URL**: `http://localhost/NRTRADING/admin`
- **Email**: `admin@nrtrading.com`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change the admin password immediately after first login!

### Test Customer Account
You can register a new customer account or use the registration form at:
`http://localhost/NRTRADING/login.php`

## 📁 Project Structure

```
NRTRADING/
├── admin/                  # Admin panel files
│   ├── includes/          # Admin headers, footer, init
│   ├── index.php          # Dashboard
│   ├── categories.php     # Category management
│   ├── products.php       # Product list
│   ├── product-add.php    # Add product
│   ├── product-edit.php   # Edit product
│   ├── orders.php         # Order management
│   ├── order-details.php  # Order details
│   ├── customers.php      # Customer list
│   ├── reports.php        # Sales reports
│   ├── settings.php       # Site settings
│   ├── login.php          # Admin login
│   └── logout.php         # Admin logout
│
├── api/                   # AJAX endpoints
│   ├── add-to-cart.php   # Add to cart
│   ├── update-cart.php   # Update quantity
│   ├── remove-from-cart.php
│   └── cart-info.php     # Cart count/total
│
├── assets/
│   ├── css/
│   │   └── style.css     # Custom styles
│   └── js/
│       └── script.js     # Frontend JavaScript
│
├── config/
│   ├── config.php        # Site configuration
│   └── database.php      # Database class
│
├── database/
│   └── nrtrading.sql     # Database schema + seed data
│
├── includes/
│   ├── header.php        # Main header
│   ├── footer.php        # Main footer
│   ├── helpers.php       # Utility functions
│   ├── init.php          # App initialization
│   └── session.php       # Session management
│
├── uploads/
│   └── products/         # Product images (writable)
│
├── index.php             # Homepage
├── shop.php              # Product listing
├── product.php           # Product details
├── cart.php              # Shopping cart
├── checkout.php          # Checkout page
├── order-success.php     # Order confirmation
├── tracking.php          # Order tracking
├── account.php           # Customer account
├── contact.php           # Contact page
├── login.php             # Login/Register
├── logout.php            # Logout
└── README.md             # This file
```

## 🎨 Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL with PDO
- **Frontend**: Bootstrap 5.3.2
- **Icons**: Bootstrap Icons 1.11.2
- **JavaScript**: Vanilla JS (no frameworks)

## 🔒 Security Features

- **SQL Injection Protection**: PDO prepared statements
- **Password Security**: bcrypt hashing with `password_hash()`
- **CSRF Protection**: Token validation on forms
- **Input Sanitization**: `htmlspecialchars()` on all outputs
- **Session Security**: HTTP-only cookies, session regeneration
- **File Upload Security**: Type validation, size limits, random naming

## 📝 Database Schema

### Main Tables
- **admins**: Admin users
- **users**: Customer accounts
- **categories**: Product categories
- **products**: Product catalog
- **orders**: Order records
- **order_items**: Order line items
- **order_status_history**: Status tracking
- **settings**: Site configuration

## 🛠️ Common Tasks

### Adding New Admin User
```sql
INSERT INTO admins (name, email, password, role) 
VALUES ('Admin Name', 'admin@example.com', '$2y$10$...hashed_password...', 'admin');
```
Generate password hash: `echo password_hash('your_password', PASSWORD_DEFAULT);`

### Updating Site Settings
- Login to admin panel: `http://localhost/NRTRADING/admin`
- Navigate to "Settings" in sidebar
- Update business info, social links, banner text
- Click "Save Settings"

### Managing Products
1. **Add Product**: Admin → Products → Add New Product
2. **Upload Image**: Max 5MB (JPG, PNG, GIF, WEBP)
3. **Set Stock**: Products with stock < 20 show in low stock report
4. **Featured**: Toggle to display on homepage

### Order Status Flow
1. **Pending**: New order placed by customer
2. **Processing**: Admin confirmed and processing
3. **Shipped**: Order dispatched for delivery
4. **Delivered**: Order received by customer
5. **Cancelled**: Order cancelled (by admin or customer)

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL service is running
- Check database credentials in `config/config.php`
- Ensure database `nrtrading` exists

### Image Upload Not Working
- Check `uploads/products/` folder exists
- Verify write permissions (755 or 777)
- Check PHP `upload_max_filesize` and `post_max_size` settings

### Session/Login Issues
- Verify `session_start()` is called
- Check browser cookies are enabled
- Clear browser cache and cookies

### Blank Page/White Screen
- Enable PHP error reporting:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Check Apache error logs

## 📧 Support

For issues or questions:
- Check database connection settings
- Verify file permissions on uploads folder
- Ensure PHP version compatibility (7.4+)
- Review Apache error logs

## 📄 License

This project is provided as-is for business use by NR TRADING.

## 🎉 Credits

Developed for **NR TRADING** - Professional Spice Trading Business

---

**Version**: 1.0.0  
**Last Updated**: 2024  
**Built with**: PHP, MySQL, Bootstrap 5
