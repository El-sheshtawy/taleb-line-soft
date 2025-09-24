# Test Users and Data

## Test Users Created

### 1. Admin User
- **Username:** `admin`
- **Password:** `123456`
- **Role:** `admin`
- **Access:** Full system administration

### 2. School User
- **Username:** `school_test`
- **Password:** `123456`
- **Role:** `school`
- **Access:** School management functions



### 3. Teacher User
- **Username:** `teacher_test`
- **Password:** `123456`
- **Role:** `teacher`
- **Access:** Full teacher functions (view + actions)

### 4. Viewer User (مراقب)
- **Username:** `viewer_test`
- **Password:** `123456`
- **Role:** `مراقب`
- **Access:** View-only (no actions allowed)

### 5. Supervisor User (مشرف)
- **Username:** `supervisor_test`
- **Password:** `123456`
- **Role:** `مشرف`
- **Access:** View-only + actions on teacher routes only

## Test School Resources

### School: مدرسة الاختبار الابتدائية
- **2 Classes:** الفصل الأول أ, الفصل الأول ب
- **5 Students:** أحمد محمد علي, فاطمة أحمد سالم, محمد سعد الدين, نور الهدى محمد, عبدالله أحمد
- **5 Teachers:** Including the test users and additional teachers
- **Follow-up System:** نظام المتابعة التجريبي (غ, ت, م)

## Testing Instructions

1. **Login as Admin:** Use admin panel to create/manage special users
2. **Login as مراقب:** Verify you can view but not perform actions
3. **Login as مشرف:** Verify you can only perform actions on teacher routes
4. **Login as Teacher:** Verify full functionality works
5. **Login as School:** Verify school management works

## Database Status
- All migrations applied successfully
- Test data seeded successfully
- New user roles (مراقب, مشرف) working correctly