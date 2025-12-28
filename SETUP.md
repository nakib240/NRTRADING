# 🚀 Quick Setup Guide - NR TRADING

## Step 1: Import Database (Required)

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create new database: `nrtrading`
3. Click "Import" tab
4. Choose file: `database/nrtrading.sql`
5. Click "Go"

## Step 2: Verify Configuration

Check `config/config.php` - should have:
```php
define('DB_NAME', 'nrtrading');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## Step 3: Create Uploads Folder

The folder should already exist, but verify:
- `uploads/products/` folder exists
- Has write permissions

## Step 4: Access Website

### Customer Site
URL: `http://localhost/NRTRADING`

Test features:
- Browse products on homepage
- Click "Shop" to see all products
- Add products to cart
- Register/Login as customer
- Place test order
- Track order

### Admin Panel
URL: `http://localhost/NRTRADING/admin`

**Login Credentials:**
- Email: `admin@nrtrading.com`
- Password: `admin123`

**⚠️ Change this password immediately!**

Admin features to test:
- View dashboard statistics
- Add/edit categories
- Add/edit products (upload images)
- View and manage orders
- Update order status
- View customers
- Check reports
- Update site settings

## Step 5: Update Site Settings

1. Login to admin panel
2. Click "Settings" in sidebar
3. Update:
   - Business name and contact info
   - Homepage banner text
   - Social media links
   - Shipping cost
   - Footer text
4. Click "Save Settings"

## Step 6: Add Your Products

1. Admin → Categories → Add category
2. Admin → Products → Add New Product
3. Fill in product details:
   - Name (support English and Bengali: পণ্যের নাম)
   - Category
   - Price
   - Original price (for discount display)
   - Stock quantity
   - Description
   - Upload product image (max 5MB)
   - Mark as featured (to show on homepage)

## 📝 Default Data Included

The database comes with sample data:

**Category:**
- Masala / Whole Spices (মসলা)

**9 Sample Products:**
1. Bay Leaf / তেজপাতা - ৳400/kg
2. Black Cumin / কালোজিরা - ৳900/kg
3. Cardamom / এলাচ - ৳2800/kg
4. Cinnamon / দারুচিনি - ৳750/kg
5. Clove / লবঙ্গ - ৳2600/kg
6. Coriander Seeds / ধনিয়া - ৳300/kg
7. Cumin Seeds / জিরা - ৳1100/kg
8. Dry Red Chilli / শুকনা মরিচ - ৳550/kg
9. Turmeric / হলুদ - ৳350/kg

## 🎯 Next Steps

1. **Change admin password** (Settings or directly in database)
2. **Add your own categories and products**
3. **Update business information** in Settings
4. **Upload your logo** (edit header.php)
5. **Customize colors** in assets/css/style.css
6. **Test order flow** from customer side
7. **Test order management** from admin side

## 🐛 Troubleshooting

### Database connection error?
- Check MySQL is running in XAMPP
- Verify database name is "nrtrading"
- Check username/password in config.php

### Can't upload images?
- Verify `uploads/products/` folder exists
- Check folder permissions (should be writable)

### Blank white page?
- Check PHP error logs in XAMPP
- Enable error display in config.php temporarily

### Login not working?
- Clear browser cookies
- Check session_start() is working
- Verify database has admin record

## 📞 Support

Need help? Check:
1. README.md for full documentation
2. Database structure in database/nrtrading.sql
3. PHP error logs in XAMPP control panel

---

**Ready to go!** 🎉

Your eCommerce website is now set up and ready for customization.
