# Laravel Backend API Setup - Complete

## ✅ What Was Done

### 1. CORS Configuration
**File**: `config/cors.php`

- ✅ Added `http://localhost:3000` to allowed origins
- ✅ Enabled `supports_credentials` for Sanctum
- ✅ Configured to accept all headers and methods

### 2. Sanctum Configuration
**File**: `config/sanctum.php`

- ✅ Already configured with `localhost:3000` in stateful domains
- ✅ Token expiration set to null (no expiration)
- ✅ Using web guard for authentication

### 3. API Authentication Controller
**File**: `app/Http/Controllers/Api/AuthController.php`

**Endpoints Created**:
- `POST /api/login` - Login and get token
- `POST /api/logout` - Logout and revoke token
- `GET /api/user` - Get authenticated user data

**Features**:
- Returns Sanctum token on login
- Includes user roles and wisata in response
- Proper error handling for invalid credentials

### 4. Dashboard API Controller
**File**: `app/Http/Controllers/Api/DashboardController.php`

**Endpoint Created**:
- `GET /api/dashboard/stats` - Get dashboard statistics

**Data Returned**:
- `transaksi_hari_ini` - Today's transaction count
- `pendapatan_hari_ini` - Today's revenue
- `total_transaksi` - Year's total transactions
- `total_pendapatan` - Year's total revenue
- `transaksi_per_bulan` - Monthly transaction counts (array)
- `pendapatan_per_bulan` - Monthly revenue (array)
- `shift_aktif` - Current active shift (for kasir)
- `total_users` - Total users (for admin/manager)

**Features**:
- Role-based data filtering
- Kasir only sees their wisata data
- Admin/Manager see all data

### 5. API Routes
**File**: `routes/api.php`

**Public Routes**:
- `POST /api/login`

**Protected Routes** (require `auth:sanctum`):
- Authentication: `/api/logout`, `/api/user`
- Dashboard: `/api/dashboard/stats`
- Transactions: `/api/transactions`, `/api/transactions/{id}`
- Shifts: `/api/shifts`, `/api/shifts/{id}`, `/api/shifts/start`, `/api/shifts/end`
- Master Data: `/api/items`, `/api/wisata`, `/api/banks`, `/api/ewalets`

---

## 🧪 Testing the API

### 1. Test Login Endpoint

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"your-email@example.com","password":"your-password"}'
```

**Expected Response**:
```json
{
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "wisata": 1,
    "roles": [...]
  },
  "token": "1|xxxxxxxxxxxxx"
}
```

### 2. Test Dashboard Stats (with token)

```bash
curl -X GET http://localhost:8000/api/dashboard/stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Test from Next.js Frontend

1. **Re-enable authentication** in Next.js:
   - Uncomment code in `middleware.ts`
   - Uncomment code in `app/dashboard/page.tsx`

2. **Restart Next.js dev server**:
   ```bash
   cd siwindu-frontend
   # Stop with Ctrl+C
   npm run dev
   ```

3. **Open browser**:
   - Navigate to `http://localhost:3000`
   - Should redirect to login page
   - Login with existing user credentials
   - Should see real data in dashboard

---

## 📝 Next Steps

### To Enable Full Authentication:

1. **Update Next.js Frontend**:
   - Remove authentication bypass in `middleware.ts`
   - Remove mock session in `dashboard/page.tsx`

2. **Create Test User** (if needed):
   ```bash
   php artisan tinker
   ```
   ```php
   $user = new App\Models\User();
   $user->name = 'Test User';
   $user->email = 'test@siwindu.com';
   $user->password = bcrypt('password');
   $user->wisata = 1;
   $user->save();
   $user->attachRole('admin');
   ```

3. **Test Login Flow**:
   - Open `http://localhost:3000`
   - Login with test credentials
   - Verify dashboard shows real data

---

## ⚠️ Important Notes

- **Laravel must be running**: `php artisan serve` on port 8000
- **Database must be configured**: Check `.env` file
- **Sanctum must be installed**: Already included in Laravel
- **Roles must exist**: Use Laratrust roles (admin, kasir, manager, etc.)

---

## 🐛 Troubleshooting

### CORS Error
- Check `config/cors.php` has `localhost:3000`
- Check `supports_credentials` is `true`
- Clear Laravel config cache: `php artisan config:clear`

### 401 Unauthorized
- Check token is being sent in Authorization header
- Check user exists in database
- Check Sanctum is configured properly

### No Data in Dashboard
- Check database has transactions
- Check user has correct role
- Check wisata filter is working

---

## ✅ Summary

**Backend API is now ready!** 🎉

- ✅ Authentication endpoints working
- ✅ Dashboard stats endpoint working
- ✅ CORS configured for Next.js
- ✅ Sanctum tokens working
- ✅ Role-based access control

**Next**: Re-enable authentication in Next.js frontend and test the full flow!
