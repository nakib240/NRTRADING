# 🌐 Production Deployment Guide - NR TRADING

## Deployment Options

### Option 1: Shared Hosting (Recommended for Small Business)

#### Best for:
- Small to medium traffic
- Budget-friendly
- Easy management
- No server administration needed

#### Recommended Providers:
- **Bangladesh**: WebHosterBD, ExonHost, Hosting Bangladesh
- **International**: Bluehost, HostGator, SiteGround, Namecheap

---

## 📦 Step-by-Step Deployment (cPanel Hosting)

### Phase 1: Pre-Deployment Preparation

1. **Prepare Files**
   ```
   - Zip the entire NRTRADING folder
   - Or prepare for FTP upload
   - Ensure all files are included
   ```

2. **Database Export**
   ```
   - Already provided: database/nrtrading.sql
   - Contains all tables and seed data
   ```

### Phase 2: Server Setup

#### A. Upload Files

**Via cPanel File Manager:**
1. Login to cPanel
2. Navigate to File Manager
3. Go to `public_html` directory
4. Upload NRTRADING.zip
5. Extract the zip file
6. Result: `public_html/nrtrading/` contains all files

**Via FTP (FileZilla):**
1. Connect to your server via FTP
2. Navigate to `public_html` or `www`
3. Create folder: `nrtrading`
4. Upload all files to `public_html/nrtrading/`

#### B. Create MySQL Database

1. **In cPanel → MySQL Databases**
   - Database Name: `username_nrtrading` (cPanel adds prefix)
   - Create Database

2. **Create MySQL User**
   - Username: `username_nrtrade` (cPanel adds prefix)
   - Password: Generate strong password (save it!)
   - Create User

3. **Add User to Database**
   - Select user: `username_nrtrade`
   - Select database: `username_nrtrading`
   - Grant ALL PRIVILEGES
   - Make Changes

4. **Import Database**
   - Go to phpMyAdmin (cPanel)
   - Select database: `username_nrtrading`
   - Click "Import" tab
   - Choose file: `nrtrading.sql`
   - Click "Go"
   - Wait for success message

#### C. Configure Application

Edit `config/config.php`:

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');  // Usually localhost
define('DB_NAME', 'username_nrtrading');  // Your database name
define('DB_USER', 'username_nrtrade');    // Your database user
define('DB_PASS', 'your_strong_password'); // Your database password

// Site Configuration
define('SITE_NAME', 'NR TRADING');
define('SITE_URL', 'https://yourdomain.com/nrtrading');  // Update this!
define('ADMIN_URL', 'https://yourdomain.com/nrtrading/admin'); // Update this!
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/nrtrading');

// Currency
define('CURRENCY_SYMBOL', '৳');
define('CURRENCY_CODE', 'BDT');

// Other settings remain the same...
```

**Important**: Replace `yourdomain.com` with your actual domain!

#### D. Set File Permissions

In cPanel File Manager or via FTP:
```
uploads/products/  → 755 (writable)
config/            → 644 (readable)
```

**Check permissions:**
- Right-click folder → Change Permissions
- Set to 755 for `uploads/products/`

### Phase 3: Security Configuration

#### 1. Change Admin Password

Login to admin panel:
- URL: `https://yourdomain.com/nrtrading/admin`
- Email: `admin@nrtrading.com`
- Password: `admin123`

**Immediately update:**
- Go to Settings or directly in database
- Change admin password
- Use strong password (16+ characters, mixed case, numbers, symbols)

#### 2. Secure Database Password

```php
// In config.php
define('DB_PASS', 'Use_Strong_Password_Here_!@#$%^&*');
```

#### 3. Enable HTTPS (SSL)

In cPanel:
1. Go to SSL/TLS Status
2. Install Let's Encrypt SSL Certificate (Free)
3. Or use AutoSSL

Update `config.php`:
```php
define('SITE_URL', 'https://yourdomain.com/nrtrading');
define('ADMIN_URL', 'https://yourdomain.com/nrtrading/admin');
```

#### 4. Restrict Admin Access (Optional)

Create `.htaccess` in `admin/` folder:
```apache
# Restrict admin panel by IP (optional)
# Uncomment and add your IP address
# Order Deny,Allow
# Deny from all
# Allow from 123.456.789.0
```

#### 5. Hide Sensitive Files

Update `.htaccess` in root:
```apache
# Prevent directory listing
Options -Indexes

# Protect config files
<FilesMatch "^(config\.php|database\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Protect SQL files
<FilesMatch "\.sql$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Phase 4: Testing

#### Test Customer Side:
1. **Homepage**: `https://yourdomain.com/nrtrading`
   - [ ] Hero section loads
   - [ ] Categories display
   - [ ] Featured products show
   - [ ] Latest products show

2. **Shop Page**
   - [ ] Products load
   - [ ] Search works
   - [ ] Filters work
   - [ ] Pagination works

3. **Product Details**
   - [ ] Product info displays
   - [ ] Images load
   - [ ] Add to cart works
   - [ ] Related products show

4. **Cart System**
   - [ ] Items added to cart
   - [ ] Cart counter updates
   - [ ] Quantity can be changed
   - [ ] Items can be removed

5. **Checkout**
   - [ ] Form loads
   - [ ] Order can be placed
   - [ ] Success message shows
   - [ ] Order appears in database

6. **Order Tracking**
   - [ ] Can search by order number
   - [ ] Status timeline displays

7. **Customer Account**
   - [ ] Can register
   - [ ] Can login
   - [ ] Order history shows
   - [ ] Can logout

#### Test Admin Side:
1. **Admin Login**: `https://yourdomain.com/nrtrading/admin`
   - [ ] Can login with credentials
   - [ ] Dashboard loads with statistics

2. **Categories**
   - [ ] Can view categories
   - [ ] Can add category
   - [ ] Can edit category
   - [ ] Can toggle status

3. **Products**
   - [ ] Product list loads
   - [ ] Can add product
   - [ ] Image upload works
   - [ ] Can edit product
   - [ ] Can delete product

4. **Orders**
   - [ ] Order list loads
   - [ ] Status filters work
   - [ ] Can view order details
   - [ ] Can update order status
   - [ ] Status history saves

5. **Customers**
   - [ ] Customer list loads
   - [ ] Order count shows

6. **Reports**
   - [ ] Daily sales report loads
   - [ ] Monthly sales report loads
   - [ ] Low stock alerts show

7. **Settings**
   - [ ] Settings page loads
   - [ ] Can update settings
   - [ ] Changes save to database
   - [ ] Changes reflect on frontend

### Phase 5: Post-Deployment

#### 1. Update Content
- [ ] Change admin password
- [ ] Update business information (Settings)
- [ ] Update homepage banner text
- [ ] Add social media links
- [ ] Add real product images
- [ ] Create your product categories
- [ ] Add your products

#### 2. Configure Email (Optional)
For order notifications, you can add:
```php
// In config.php
define('SITE_EMAIL', 'orders@yourdomain.com');
define('ADMIN_EMAIL', 'admin@yourdomain.com');
```

Then add email functionality using PHPMailer or mail() function.

#### 3. Set Up Backups

**Database Backup (cPanel):**
1. phpMyAdmin → Export
2. Or use cPanel Backup tool
3. Schedule: Weekly recommended

**File Backup:**
1. cPanel → Backup Wizard
2. Download full backup
3. Store safely offline

#### 4. Monitor Performance
- Check server error logs regularly
- Monitor database size
- Check disk space usage
- Test site speed (GTmetrix, PageSpeed Insights)

---

## 🎯 Domain Configuration

### If using custom domain:

#### Update DNS Records:
```
Type: A Record
Host: @
Points to: Your hosting IP address

Type: CNAME
Host: www
Points to: yourdomain.com
```

#### Update Site URLs:
In `config/config.php`:
```php
define('SITE_URL', 'https://nrtrading.com');  // No /nrtrading if root
define('ADMIN_URL', 'https://nrtrading.com/admin');
```

---

## ❗ Common Issues & Solutions

### Database Connection Error
**Error**: "Could not connect to database"
**Solution**: 
- Check database name, username, password in config.php
- Verify database user has privileges
- Check MySQL service is running

### Image Upload Not Working
**Error**: Images don't save
**Solution**:
- Check `uploads/products/` has 755 or 777 permissions
- Verify folder exists
- Check PHP upload_max_filesize (20M recommended)

### 500 Internal Server Error
**Solution**:
- Check .htaccess syntax
- Check PHP error logs in cPanel
- Enable error reporting temporarily:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

### Session Not Working
**Solution**:
- Check session.save_path in PHP settings
- Verify cookies are enabled
- Check HTTPS/HTTP consistency

### URLs Not Working (404 Error)
**Solution**:
- Check SITE_URL and ADMIN_URL in config.php
- Verify .htaccess file exists
- Check mod_rewrite is enabled

---

## 📊 Performance Optimization

### 1. Enable PHP Caching
In cPanel → Select PHP Version → Enable OPcache

### 2. Image Optimization
- Compress product images before upload
- Use WebP format when possible
- Max recommended size: 800x800px

### 3. Database Optimization
Regularly run in phpMyAdmin:
```sql
OPTIMIZE TABLE products, orders, order_items, categories, users;
```

### 4. Enable Gzip Compression
Add to `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

---

## 🔐 Security Checklist

- [x] Changed default admin password
- [x] Using strong database password
- [x] SSL certificate installed (HTTPS)
- [x] File permissions set correctly
- [x] Sensitive files protected
- [x] SQL injection prevention (prepared statements)
- [x] XSS protection (htmlspecialchars)
- [x] Password hashing (bcrypt)
- [x] Session security enabled
- [x] Regular backups scheduled
- [x] Error display disabled in production
- [x] Database prefix used (if shared hosting)

---

## 📞 Support Resources

### Hosting Support
- Contact your hosting provider's support
- Usually available 24/7 via ticket/chat

### PHP/MySQL Issues
- Check PHP version (7.4+ required)
- Review error logs in cPanel
- Test database connection in phpMyAdmin

### Application Issues
- Review README.md documentation
- Check CHECKLIST.md for features
- Verify all setup steps completed

---

## 🎉 Deployment Complete!

Your NR TRADING eCommerce website is now live!

**Final Steps:**
1. ✅ Test all functionality
2. ✅ Update content and products
3. ✅ Configure email notifications (optional)
4. ✅ Set up analytics (Google Analytics)
5. ✅ Share site URL with customers
6. ✅ Start selling!

**Live URLs:**
- Customer Site: `https://yourdomain.com/nrtrading`
- Admin Panel: `https://yourdomain.com/nrtrading/admin`

**Remember:**
- Keep regular backups
- Monitor site performance
- Update products regularly
- Respond to orders promptly
- Keep admin password secure

Good luck with your business! 🚀
