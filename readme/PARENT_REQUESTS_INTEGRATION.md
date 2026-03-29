# Parent Absence Requests - Database Integration

## Summary

Successfully integrated the parent absence requests view with the database. The system now fetches real data from the `absentReasons` table instead of using hardcoded sample data.

## Changes Made

### 1. **ParentController.php** - Added `requests()` Method

- **Location**: `/home/snake/Projects/Iskole/app/Controllers/ParentController.php`
- **Purpose**: Handles the display of absence requests for authenticated parents
- **Key Features**:
  - Authenticates user via session
  - Fetches parent record using `ParentModel`
  - Retrieves absence reasons from database
  - Formats data for view consumption
  - Calculates absence duration automatically

### 2. **StudentAbsenceReasonController.php** - Enhanced & Fixed

- **Location**: `/home/snake/Projects/Iskole/app/Controllers/StudentAbsenceReasonController.php`
- **Changes**:
  - Fixed `submit()` method to properly fetch parent data
  - Fixed `viewAbsencesByUserId()` to return data correctly
  - Added `edit()` method for updating absence requests
  - Added `delete()` method for deleting absence requests
  - Removed debug `var_dump()` statements

### 3. **StudentAbsenceReasonModel.php** - Cleaned Up

- **Location**: `/home/snake/Projects/Iskole/app/Model/StudentAbsenceReasonModel.php`
- **Changes**:
  - Enhanced `submitAbsenceReason()` to auto-set submission date and status
  - Improved `getAbsenceReasonsByParentId()` with proper field selection and ordering
  - Added `ORDER BY` clauses to all fetch methods (most recent first)
  - Updated `updateAbsenceReason()` to only allow editing pending requests
  - Removed all `var_dump()` debug statements
  - Simplified return statements

### 4. **parentRequests.php** - Connected to Database

- **Location**: `/home/snake/Projects/Iskole/app/Views/parent/parentRequests.php`
- **Changes**:
  - Removed hardcoded sample data array
  - Now receives data from controller via `$data['recentRequests']`
  - Updated form action URLs to proper routes
  - Fixed edit modal to use correct field names
  - Updated delete function to use correct endpoint

## Database Schema Requirements

The `absentReasons` table should have the following columns:

```sql
- reasonID (Primary Key)
- parentID (Foreign Key to parents table)
- reason (TEXT)
- fromDate (DATE)
- toDate (DATE)
- submittedDate (DATETIME) - Auto-filled on creation
- status (ENUM or VARCHAR) - Values: 'pending', 'acknowledged', 'not_seen'
- acknowledgedBy (VARCHAR, nullable) - Name of teacher who acknowledged
- acknowledgedDate (DATETIME, nullable) - When it was acknowledged
```

## Routing

The following routes should be configured:

1. **View Requests**: `/parent/requests` → `ParentController::requests()`
2. **Submit Request**: `/studentAbsenceReason/submit` → `StudentAbsenceReasonController::submit()`
3. **Edit Request**: `/studentAbsenceReason/edit` → `StudentAbsenceReasonController::edit()`
4. **Delete Request**: `/studentAbsenceReason/delete` → `StudentAbsenceReasonController::delete()`

## How It Works

### User Flow

1. **View Requests**:

   - Parent navigates to `/parent/requests`
   - System authenticates user and fetches their parent record
   - Displays all absence requests for that parent from database
   - Shows status (pending, acknowledged, not_seen)
   - Allows filtering by status using chips

2. **Submit New Request**:

   - Parent fills out form with dates and reason
   - Form submits to `/studentAbsenceReason/submit`
   - System validates data and saves to database with status='pending'
   - Redirects back to requests page with success message

3. **Edit Request** (only for pending requests):

   - Parent clicks "Edit" button on pending request
   - Modal opens with pre-filled data
   - Parent modifies and submits to `/studentAbsenceReason/edit`
   - System updates only if status is still 'pending'
   - Redirects back with success message

4. **Delete Request** (only for pending requests):
   - Parent clicks "Delete" button
   - Confirmation dialog appears
   - On confirm, submits to `/studentAbsenceReason/delete`
   - System removes record from database
   - Redirects back with success message

### Data Flow

```
Browser → ParentController::requests()
           ↓
       ParentModel::getParentByUserId()
           ↓
       StudentAbsenceReasonModel::getAbsenceReasonsByParentId()
           ↓
       Data Formatting (calculate duration, etc.)
           ↓
       View Rendering (parentRequests.php)
           ↓
       Display to User
```

## Testing Checklist

- [ ] Parent can view their absence requests
- [ ] New requests are saved to database
- [ ] Edit functionality works for pending requests
- [ ] Delete functionality works for pending requests
- [ ] Status filters work correctly
- [ ] Acknowledged requests show teacher name and date
- [ ] Duration is calculated correctly
- [ ] Authentication is enforced (redirects if not logged in)
- [ ] Success/error messages display properly

## Future Enhancements

1. **Teacher Acknowledgment**: Add interface for teachers to acknowledge requests
2. **Notifications**: Email/SMS notifications when status changes
3. **Attachments**: Allow parents to upload medical certificates
4. **Calendar View**: Display absences in calendar format
5. **Bulk Operations**: Allow teachers to acknowledge multiple requests at once
6. **History**: Show archived/old requests separately
7. **Reports**: Generate absence statistics and reports

## Notes

- Only pending requests can be edited or deleted
- Acknowledged and not_seen requests are read-only for parents
- The system automatically sets the submission date to current date/time
- Duration is calculated inclusive of both start and end dates
- All database errors are logged via `error_log()`
