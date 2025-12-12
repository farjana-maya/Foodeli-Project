# Restaurant Owner Application Approval System - TODO

## Current Issues
- Form redirects to signin after submission (user already logged in)
- Rejection only deletes restaurant, not user account
- Dashboard is incomplete with limited functionality
- No notification system for approval/rejection

## Tasks

### 1. Fix Form Submission Redirect
- [x] Change redirect from '/signin' to '/restaurant-owner-dashboard' in RestaurantOwnerForm.vue

### 2. Enhance Rejection Logic
- [x] Modify rejectApplication in RestaurantApplicationController.php to delete user account on rejection
- [x] Add proper error handling and response messages

### 3. Add Notification System
- [ ] Create notification model and migration (if needed)
- [ ] Add notification endpoints in backend
- [ ] Add notification methods in adminService.js
- [ ] Display notifications in admin and owner dashboards

### 4. Enhance Restaurant Dashboard
- [x] Add menu management functionality
- [x] Add order management functionality
- [x] Add analytics/stats functionality
- [x] Add real-time updates for application status changes

### 5. Add Real-time Updates
- [ ] Implement polling or websockets for status updates
- [ ] Auto-refresh dashboard when application is approved

### 6. Testing
- [ ] Test approval flow (admin approves -> owner gets dashboard access)
- [ ] Test rejection flow (admin rejects -> user account deleted)
- [ ] Test dashboard functionality
- [ ] Test notifications
