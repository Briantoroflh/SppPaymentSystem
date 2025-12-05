# Role-Based Menu Access Control Implementation

## Overview
Implementasi role-based access control menggunakan Spatie Permissions package dengan struktur terstruktur dan clean.

## Components

### 1. Database Structure
- **menu_role** pivot table: Menghubungkan menus dengan roles
- Composite unique key untuk mencegah duplicate entries

### 2. Models

#### Menu Model
```php
public function roles()
{
    return $this->belongsToMany(\Spatie\Permission\Models\Role::class, 'menu_role', 'menu_id', 'role_id');
}
```
- Relasi many-to-many dengan Spatie Role model

### 3. Helper Classes

#### MenuAccessHelper
Located at: `app/Helper/MenuAccessHelper.php`

**Methods:**
- `getAccessibleMenus()` - Get menus berdasarkan user roles
- `canAccessMenu($menuId)` - Check apakah user bisa akses menu tertentu
- `getGroupedAccessibleMenus()` - Get menus grouped by section

**Logic:**
- Super Admin dapat mengakses semua menus
- Role lain hanya dapat mengakses menus yang di-assign ke role mereka

### 4. Middleware

#### CheckMenuAccess
Located at: `app/Http/Middleware/CheckMenuAccess.php`

**Purpose:** Melindungi routes berdasarkan menu access

**Logic:**
1. Jika user belum authenticated, redirect ke login
2. Jika Super Admin, allow akses
3. Jika menu tidak ada di database, allow akses (non-menu routes)
4. Jika user tidak memiliki role yang authorized, abort dengan 403

### 5. Seeders

#### RoleSeeder
Membuat 3 roles:
- Super Admin
- School Admin
- Student

#### MenuRoleSeeder
Assign menus ke roles:
- **Super Admin**: All menus
- **School Admin**: Dashboard, School, Major, Region, Student, Teacher
- **Student**: Dashboard

## Usage

### Assigning Roles to Users
```php
$user = User::find(1);
$user->assignRole('Super Admin');
// atau
$user->assignRole(['School Admin', 'Teacher']);
```

### Checking User Roles
```php
if ($user->hasRole('Super Admin')) {
    // Allow access
}

if ($user->hasAnyRole(['Super Admin', 'School Admin'])) {
    // Allow access
}
```

### In Views
```blade
@if(Auth::user()->hasRole('Super Admin'))
    <!-- Show content only for Super Admin -->
@endif
```

### Using MenuAccessHelper
```php
use App\Helper\MenuAccessHelper;

// Get menus for current user
$menus = MenuAccessHelper::getAccessibleMenus();

// Group by section
$groupedMenus = MenuAccessHelper::getGroupedAccessibleMenus();

// Check if can access specific menu
$canAccess = MenuAccessHelper::canAccessMenu($menuId);
```

### Protecting Routes with Middleware
```php
Route::middleware('check.menu.access')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/school', [SchoolController::class, 'index'])->name('school.index');
});
```

## Sidebar Integration
Sidebar otomatis menampilkan menus berdasarkan user roles:

```blade
@php
use App\Helper\MenuAccessHelper;
$menus = MenuAccessHelper::getGroupedAccessibleMenus();
@endphp
```

## Adding New Menu Roles

Untuk menambah menu ke role baru:

```php
// Di MenuRoleSeeder atau controller
$role = Role::where('name', 'School Admin')->first();
$menu = Menu::where('title', 'New Menu')->first();

$menu->roles()->attach($role);
```

Atau menggunakan eloquent:
```php
$menu->roles()->sync([$roleId1, $roleId2]);
```

## Super Admin Privilege
Super Admin secara otomatis dapat mengakses:
- Semua menus
- Semua routes yang protected dengan `check.menu.access` middleware
- Tidak perlu di-assign menus secara individual

## Best Practices
1. Selalu gunakan `check.menu.access` middleware pada authenticated routes
2. Definisikan menu assignments dalam MenuRoleSeeder
3. Gunakan `MenuAccessHelper` untuk query menus di sidebar/views
4. Untuk granular control, tambahkan permissions ke roles
5. Selalu test access dengan user dari role yang berbeda

## Troubleshooting
- Jika menu tidak muncul, check `menu_role` table untuk relasi
- Jika middleware abort 403, pastikan menu di-assign ke role user
- Gunakan `$user->getRoleNames()` untuk debug user roles
