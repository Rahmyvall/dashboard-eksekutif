# SQL Verification Queries - RBAC Permission System

Gunakan queries berikut untuk verify permission system di database:

---

## 1️⃣ Verifikasi Total Permissions

```sql
-- Hitung total permissions yang ada
SELECT COUNT(*) as total_permissions FROM permissions;
-- Expected: 249
```

---

## 2️⃣ Verifikasi Total Roles

```sql
-- Hitung total roles
SELECT COUNT(*) as total_roles FROM roles;
-- Expected: 9
```

---

## 3️⃣ Verifikasi Permissions per Role

```sql
-- Lihat detail permissions per role
SELECT 
    r.name as role,
    COUNT(rp.permission_id) as permission_count
FROM roles r
LEFT JOIN role_has_permissions rp ON r.id = rp.role_id
GROUP BY r.id, r.name
ORDER BY permission_count DESC;

-- Expected output:
-- Super Administrator    249
-- Direktur Utama         36
-- HRD Manager            43
-- Manager Departemen     14
-- Karyawan               12
-- Admin Pelayanan        24
-- Admin Operasional      24
-- Finance Staff          19
-- Auditor Internal       35
```

---

## 4️⃣ Verifikasi Permissions Tertentu Sudah Seeded

```sql
-- Cek semua permission untuk modul tertentu
SELECT * FROM permissions WHERE name LIKE 'branches%' ORDER BY name;

SELECT * FROM permissions WHERE name LIKE 'employees%' ORDER BY name;

SELECT * FROM permissions WHERE name LIKE 'invoices%' ORDER BY name;

SELECT * FROM permissions WHERE name LIKE 'performance%' ORDER BY name;
```

---

## 5️⃣ Verifikasi Role-Permission Assignment

```sql
-- Lihat semua permissions untuk Super Admin
SELECT p.name, p.display_name
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE r.name = 'super_admin'
ORDER BY p.name;
```

---

## 6️⃣ Verifikasi User's Roles

```sql
-- Lihat role dari user tertentu
SELECT u.name, u.email, r.name as role
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id 
LEFT JOIN roles r ON r.id = mhr.role_id
WHERE mhr.model_type = 'App\\Models\\User'
ORDER BY u.name;
```

---

## 7️⃣ Verifikasi User's Permissions

```sql
-- Lihat semua permissions dari user tertentu (via roles)
SELECT DISTINCT p.name, p.display_name
FROM users u
INNER JOIN model_has_roles mhr ON u.id = mhr.model_id
INNER JOIN roles r ON r.id = mhr.role_id
INNER JOIN role_has_permissions rp ON r.id = rp.role_id
INNER JOIN permissions p ON p.id = rp.permission_id
WHERE u.email = 'admin@example.com'  -- Ganti dengan email user
ORDER BY p.name;
```

---

## 8️⃣ Verifikasi Permissions by Category

```sql
-- Lihat permissions yang tersedia, grouped by kategori (awal nama)
SELECT 
    SUBSTRING_INDEX(name, '.', 1) as module,
    SUBSTRING_INDEX(name, '.', -1) as action,
    COUNT(*) as count
FROM permissions
GROUP BY SUBSTRING_INDEX(name, '.', 1), SUBSTRING_INDEX(name, '.', -1)
ORDER BY module, action;
```

---

## 9️⃣ Verifikasi Roles Lengkap

```sql
-- Lihat semua roles dengan detail
SELECT 
    id, 
    name,
    display_name,
    description,
    created_at
FROM roles
ORDER BY created_at;
```

---

## 🔟 Verifikasi Audit Logs

```sql
-- Lihat access attempts yang dilog (jika audit middleware berjalan)
SELECT 
    id,
    user_id,
    action,
    resource,
    created_at
FROM audit_logs
ORDER BY created_at DESC
LIMIT 20;
```

---

## 1️⃣1️⃣ Cek Permissions yang Kosong (Tidak Ada di Role Manapun)

```sql
-- Cari permissions yang tidak diberikan ke role manapun
SELECT p.name
FROM permissions p
LEFT JOIN role_has_permissions rp ON p.id = rp.permission_id
WHERE rp.permission_id IS NULL
ORDER BY p.name;

-- Expected: EMPTY (semua permissions harus assigned ke minimal satu role)
```

---

## 1️⃣2️⃣ Verifikasi Specific Role Permissions (HRD Manager)

```sql
-- Lihat semua permissions untuk HRD Manager
SELECT p.name, p.display_name, p.category
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE r.name = 'hrd_manager'
ORDER BY p.name;

-- Expected: 43 permissions terkait HR dan Employee
```

---

## 1️⃣3️⃣ Verifikasi Specific Role Permissions (Karyawan)

```sql
-- Lihat permissions untuk Karyawan (most restricted)
SELECT p.name, p.display_name
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE r.name = 'karyawan'
ORDER BY p.name;

-- Expected: 12 permissions (mostly view_own, create_own)
```

---

## 1️⃣4️⃣ Verifikasi Master Data Permissions

```sql
-- Lihat semua master data permissions
SELECT 
    r.name as role,
    GROUP_CONCAT(p.name) as permissions
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE p.name LIKE 'branches%' 
   OR p.name LIKE 'departments%'
   OR p.name LIKE 'positions%'
   OR p.name LIKE 'employees%'
GROUP BY r.id, r.name
ORDER BY r.name;
```

---

## 1️⃣5️⃣ Verifikasi Service Orders Permissions

```sql
-- Lihat service order permissions distribution
SELECT 
    r.name as role,
    COUNT(p.id) as count
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE p.name LIKE 'service_orders%'
GROUP BY r.id, r.name
ORDER BY count DESC;
```

---

## 1️⃣6️⃣ Verifikasi Finance Permissions

```sql
-- Lihat finance permissions untuk semua roles
SELECT 
    r.name as role,
    GROUP_CONCAT(p.name) as permissions
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE p.name LIKE 'expenses%'
   OR p.name LIKE 'invoices%'
   OR p.name LIKE 'payments%'
GROUP BY r.id, r.name
ORDER BY r.name;
```

---

## 1️⃣7️⃣ Verifikasi Performance/KPI Permissions

```sql
-- Lihat performance permissions
SELECT 
    r.name as role,
    COUNT(p.id) as count
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE p.name LIKE 'performance%'
   OR p.name LIKE 'employee_performance%'
   OR p.name LIKE 'employee_targets%'
GROUP BY r.id, r.name
ORDER BY count DESC;
```

---

## 1️⃣8️⃣ Verifikasi System Admin Permissions

```sql
-- Lihat system permissions (users, roles, permissions, audit)
SELECT 
    r.name as role,
    COUNT(p.id) as count
FROM permissions p
INNER JOIN role_has_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON r.id = rp.role_id
WHERE p.name LIKE 'users%'
   OR p.name LIKE 'roles%'
   OR p.name LIKE 'permissions%'
   OR p.name LIKE 'audit%'
   OR p.name LIKE 'system%'
GROUP BY r.id, r.name
ORDER BY count DESC;
```

---

## 🔍 Hasil Verifikasi - Checklist

Jalankan semua queries di atas dan pastikan:

- [ ] Query #1: Total permissions = 249
- [ ] Query #2: Total roles = 9
- [ ] Query #3: Semua roles punya permissions sesuai tabel di atas
- [ ] Query #4: Permission untuk branches, employees, invoices ada
- [ ] Query #5: Super Admin punya 249 permissions
- [ ] Query #6: Semua users punya role yang assigned
- [ ] Query #7: User dapat melihat permissions berdasarkan rolenya
- [ ] Query #8: Permissions terdistribusi ke berbagai modules
- [ ] Query #9: Semua 9 roles terlihat dalam database
- [ ] Query #10: Audit logs mulai terbentuk saat ada akses (jika aktif)
- [ ] Query #11: KOSONG (tidak ada orphan permissions)
- [ ] Query #12-18: Specific role permissions sesuai dokumentasi

---

## 💾 Run All Verification at Once

Untuk PostgreSQL (Save as file `verify_permissions.sql`):

```sql
\echo '========================================='
\echo 'PERMISSION SYSTEM VERIFICATION'
\echo '========================================='

\echo '1. Total Permissions:'
SELECT COUNT(*) as total_permissions FROM permissions;

\echo '2. Total Roles:'
SELECT COUNT(*) as total_roles FROM roles;

\echo '3. Permissions per Role:'
SELECT 
    r.name as role,
    COUNT(rp.permission_id) as permission_count
FROM roles r
LEFT JOIN role_has_permissions rp ON r.id = rp.role_id
GROUP BY r.id, r.name
ORDER BY permission_count DESC;

\echo '4. Permissions by Category:'
SELECT 
    SUBSTRING(name, 1, POSITION('.' IN name) - 1) as module,
    SUBSTRING(name, POSITION('.' IN name) + 1) as action,
    COUNT(*) as count
FROM permissions
GROUP BY SUBSTRING(name, 1, POSITION('.' IN name) - 1), 
         SUBSTRING(name, POSITION('.' IN name) + 1)
ORDER BY module, action;

\echo '========================================='
\echo 'Verification Complete'
\echo '========================================='
```

**Run dengan:**
```bash
psql -U postgres -d dashboard_eksekutif -f verify_permissions.sql
```

---

**Last Updated:** 2026-08-14
