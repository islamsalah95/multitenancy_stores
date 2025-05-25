# Domain Setup Guide for Tency Multi-Tenancy

## 🎯 **The Problem**
You're seeing XAMPP info instead of your stores because the domains aren't properly configured to point to your Laravel application.

## 🔧 **Solution: Proper Domain Setup**

### **Step 1: Update Hosts File**

**Windows**: Edit `C:\Windows\System32\drivers\etc\hosts` as Administrator
**Mac/Linux**: Edit `/etc/hosts` with sudo

Add these lines:
```
127.0.0.1 localhost
127.0.0.1 adam-haynes.localhost
127.0.0.1 irene-parrish.localhost
```

### **Step 2: Stop XAMPP Apache**
1. Open XAMPP Control Panel
2. Click "Stop" for Apache
3. Keep MySQL running

### **Step 3: Start Laravel Server**
Open Command Prompt in your project directory:
```bash
cd C:\xampp8.2\htdocs\tency
php artisan serve --host=0.0.0.0 --port=8000
```

### **Step 4: Test the Setup**

1. **Main App**: `http://localhost:8000`
   - Should show store platform
   - Should list existing stores

2. **Tenant Stores**:
   - Adam Haynes: `http://adam-haynes.localhost:8000`
   - Irene Parrish: `http://irene-parrish.localhost:8000`

### **Step 5: Verify Tenant Database Isolation**

1. **Login to main app**: `http://localhost:8000/login`
2. **Go to dashboard**: `http://localhost:8000/dashboard`
3. **Add categories and products**
4. **Visit tenant domain**: `http://irene-parrish.localhost:8000`
5. **Verify**: Products appear in tenant database, not main database

## 🔍 **Debug Commands**

### **Check Domain Resolution**
```bash
ping adam-haynes.localhost
# Should return 127.0.0.1
```

### **Check Tenant Detection**
Visit these debug URLs:
- Landlord: `http://localhost:8000/debug`
- Tenant: `http://adam-haynes.localhost:8000/debug`

### **Check Database Isolation**
```bash
cd C:\xampp8.2\htdocs\tency
php artisan tinker

# Check main database
App\Models\Store::all();

# Switch to tenant and check tenant database
$store = App\Models\Store::find(2);
$store->makeCurrent();
App\Models\Category::all();
App\Models\Product::all();
```

## 🚨 **Troubleshooting**

### **Still Seeing XAMPP Info?**
1. Ensure XAMPP Apache is stopped
2. Clear DNS cache: `ipconfig /flushdns` (Windows)
3. Try different browser or incognito mode
4. Check hosts file is saved correctly

### **Domain Not Resolving?**
1. Run Command Prompt as Administrator
2. Edit hosts file with Administrator privileges
3. Save and restart browser

### **Database Issues?**
```bash
# Run tenant migrations
php artisan tenant:migrate

# Check if tenant databases exist
php artisan tinker
App\Models\Store::all()->each(function($store) {
    echo "Store: {$store->name}, Database: {$store->getDatabaseName()}\n";
});
```

## ✅ **Expected Results**

After proper setup:

**Main Domain** (`localhost:8000`):
- ✅ Shows Laravel application
- ✅ Store management works
- ✅ Can add categories/products

**Tenant Domains** (`store-name.localhost:8000`):
- ✅ Shows public store
- ✅ Store-specific branding
- ✅ Only shows that store's products
- ✅ Separate database per store

**Database Isolation**:
- ✅ Each store has own database
- ✅ Categories/products go to tenant DB
- ✅ No cross-tenant data access

## 🎉 **Success Indicators**

1. **No XAMPP info page** on any domain
2. **Different content** on each tenant domain
3. **Products appear** in tenant stores after adding them
4. **Database isolation** confirmed via debug commands

Follow these steps carefully and your multi-tenant system will work perfectly with proper database isolation!
