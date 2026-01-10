# Script commit code cho 3 tài khoản
# Tài khoản 1: nhuthoas04@gmail.com - Nhut Hoa
# Tài khoản 2: maidat6890@gmail.com - Mai Dat  
# Tài khoản 3: leduytctv2019@gmail.com - Le Duy

Write-Host "=== COMMIT CODE CHO 3 TAI KHOAN ===" -ForegroundColor Cyan
Write-Host ""

# Xóa test file trước
if (Test-Path "test_revenue.php") {
    Remove-Item "test_revenue.php" -Force
    Write-Host "Da xoa test_revenue.php" -ForegroundColor Yellow
}

# ============================================
# COMMIT 1 - Nhut Hoa (nhuthoas04@gmail.com)
# ============================================
Write-Host "COMMIT 1/9 - Nhut Hoa: Config & Database" -ForegroundColor Green
git config user.name "Nhut Hoa"
git config user.email "nhuthoas04@gmail.com"

git add config/
git add database.sql
git commit -m "feat: Update database configuration and SQL files

- Update config files for production
- Add Google login SQL
- Add reset password columns
- Fix categories structure
- Update specifications table"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 2 - Mai Dat (maidat6890@gmail.com)
# ============================================
Write-Host "COMMIT 2/9 - Mai Dat: Models & Backend Logic" -ForegroundColor Green
git config user.name "Mai Dat"
git config user.email "maidat6890@gmail.com"

git add models/
git add api/
git commit -m "feat: Improve models and API endpoints

- Update Order model with status counts
- Fix Chat model for message handling
- Enhance API endpoints for admin/employee
- Add proper error handling"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 3 - Le Duy (leduytctv2019@gmail.com)
# ============================================
Write-Host "COMMIT 3/9 - Le Duy: Controllers" -ForegroundColor Green
git config user.name "Le Duy"
git config user.email "leduytctv2019@gmail.com"

git add controllers/
git commit -m "feat: Update AdminController and EmployeeController

- Add revenue statistics for admin
- Fix employee dashboard data
- Improve order management
- Add date filtering"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 4 - Nhut Hoa (nhuthoas04@gmail.com)
# ============================================
Write-Host "COMMIT 4/9 - Nhut Hoa: Admin Views" -ForegroundColor Green
git config user.name "Nhut Hoa"
git config user.email "nhuthoas04@gmail.com"

git add views/admin/orders.php
git add views/admin/revenue.php
git add views/admin/order-detail.php
git commit -m "feat: Enhance admin order and revenue pages

- Add order status filter cards with click functionality
- Fix revenue statistics display
- Add order detail page
- Improve date range filtering"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 5 - Mai Dat (maidat6890@gmail.com)
# ============================================
Write-Host "COMMIT 5/9 - Mai Dat: Admin Other Views" -ForegroundColor Green
git config user.name "Mai Dat"
git config user.email "maidat6890@gmail.com"

git add views/admin/chats.php
git add views/admin/products.php
git add views/admin/users.php
git add views/admin/reviews.php
git commit -m "feat: Update admin management pages

- Improve chat interface
- Enhance product management
- Update user management
- Fix review display"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 6 - Le Duy (leduytctv2019@gmail.com)
# ============================================
Write-Host "COMMIT 6/9 - Le Duy: Employee Views" -ForegroundColor Green
git config user.name "Le Duy"
git config user.email "leduytctv2019@gmail.com"

git add views/employee/
git commit -m "feat: Complete employee dashboard and pages

- Add employee dashboard with statistics
- Fix chat functionality
- Update order management for employee
- Add review management
- Match color scheme with admin"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 7 - Nhut Hoa (nhuthoas04@gmail.com)
# ============================================
Write-Host "COMMIT 7/9 - Nhut Hoa: Layout Updates" -ForegroundColor Green
git config user.name "Nhut Hoa"
git config user.email "nhuthoas04@gmail.com"

git add views/layouts/
git commit -m "feat: Update admin and employee layouts

- Remove redundant header bars
- Add employee header and footer
- Fix sidebar toggle functionality
- Improve responsive design"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 8 - Mai Dat (maidat6890@gmail.com)
# ============================================
Write-Host "COMMIT 8/9 - Mai Dat: CSS Styling" -ForegroundColor Green
git config user.name "Mai Dat"
git config user.email "maidat6890@gmail.com"

git add assets/css/
git commit -m "style: Enhance admin dashboard styling

- Add order status card styles
- Improve color scheme consistency
- Add hover effects
- Make responsive for all devices"

Start-Sleep -Seconds 1

# ============================================
# COMMIT 9 - Le Duy (leduytctv2019@gmail.com)
# ============================================
Write-Host "COMMIT 9/9 - Le Duy: JavaScript & Final Updates" -ForegroundColor Green
git config user.name "Le Duy"
git config user.email "leduytctv2019@gmail.com"

git add assets/js/
git add index.php
git add README.md
git rm add_google_login.sql add_reset_password_columns.sql fix_categories.sql fix_data.php fix_encoding.php test_password.php update_specifications.sql 2>$null
git commit -m "chore: Update routing and clean up files

- Fix index.php routing for employee
- Update JavaScript functionality
- Clean up old SQL files
- Update README documentation"

Start-Sleep -Seconds 1

Write-Host ""
Write-Host "=== HOAN THANH 9 COMMITS ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Phan bo commit:" -ForegroundColor Yellow
Write-Host "- Nhut Hoa (nhuthoas04@gmail.com): 3 commits" -ForegroundColor White
Write-Host "- Mai Dat (maidat6890@gmail.com): 3 commits" -ForegroundColor White
Write-Host "- Le Duy (leduytctv2019@gmail.com): 3 commits" -ForegroundColor White
Write-Host ""
Write-Host "Xem lich su commit:" -ForegroundColor Cyan
git log --oneline -9 --pretty=format:"%h - %an <%ae>: %s"
Write-Host ""
Write-Host ""
Write-Host "BUOC TIEP THEO:" -ForegroundColor Yellow
Write-Host "1. Tao repository moi tren GitHub: https://github.com/nhuthoas04/Web_banlinhkien" -ForegroundColor White
Write-Host "2. Push code len: git push -u new-origin main" -ForegroundColor White
Write-Host "   (Neu loi, dung: git push -u new-origin main --force)" -ForegroundColor Gray
Write-Host ""
