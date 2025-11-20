<?php /* filepath: /home/snake/Projects/Iskole/app/Views/templates/userDirectory.php */ ?>
<link rel="stylesheet" href="/css/userDirectory/userDirectory.css">

<?php 
    require_once __DIR__ . '/../../Controllers/userDirectoryController.php';
    $userDirectory = new UserDirectoryController();
    $users = $userDirectory->getRecentUsers(5); // Show only 5 items initially
?>

<section class="mp-management" aria-labelledby="user-dir-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="user-dir-title">User Directory</h2>
            <p class="subtitle">Manage all system users</p>
        </div>
        <!-- You can add a primary action button here if you have a route -->
        <!-- <a class="btn btn-primary" href="/admin/add-user">Add New User</a> -->
    </header>

    <div class="card">
        <form id="user-search-form" class="toolbar" role="search" novalidate>
            <div class="search-input">
                <input type="text" id="user-search-input" name="q" placeholder="Search users... (name, type, email)" aria-label="Search users">
            </div>
            <button class="btn btn-ghost" type="reset" id="user-search-clear">Clear</button>
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <div class="table-wrap">
            <table class="table" aria-describedby="user-dir-title">
                <thead>
                    <tr class="table-row">
                        <th class="table-head">Name</th>
                        <th class="table-head">Type</th>
                        <th class="table-head">Email</th>
                        <th class="table-head">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <?php 
                        if (empty($users)) {
                            echo "<tr class='table-row'><td class='table-data' colspan='4'>No users found.</td></tr>";
                        } else {
                            foreach ($users as $user) {
                                $id = (int) ($user['userID'] ?? 0);
                                $name = htmlspecialchars(trim(($user['firstName'] ?? '') . ' ' . ($user['lastName'] ?? '')));
                                $email = htmlspecialchars($user['email'] ?? '');
                                $role = htmlspecialchars($user['role'] ?? '');
                                $stId = !empty($user['studentID']) ? ' (' . htmlspecialchars($user['studentID']) . ')' : '';
                                $displayName = $name . $stId;
                                echo "<tr class='table-row' data-user-id='{$id}'>\n" .
                                     "    <td class='table-data' data-col='name'>{$displayName}</td>\n" .
                                     "    <td class='table-data' data-col='type'>{$role}</td>\n" .
                                     "    <td class='table-data' data-col='email'>{$email}</td>\n" .
                                     "    <td class='table-data'>\n" .
                                     "        <div class='row'>\n" .
                                     "            <button class='btn edit-user-btn' data-user-id='{$id}' type='button'>Edit</button>\n" .
                                     "            <button class='btn btn-red' data-user-id='{$id}' type='button'>Delete</button>\n" .
                                     "        </div>\n" .
                                     "    </td>\n" .
                                     "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="modal" role="dialog" aria-labelledby="edit-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="edit-modal-title">Edit User</h3>
            <button class="modal-close" type="button" aria-label="Close modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-user-form">
                <input type="hidden" id="edit-user-id" name="userID">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-first-name">First Name</label>
                        <input type="text" id="edit-first-name" name="firstName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" id="edit-last-name" name="lastName" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit-email">Email</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-phone">Phone Number</label>
                        <input type="tel" id="edit-phone" name="phone" placeholder="+94 XX XXX XXXX">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-gender">Gender</label>
                        <select id="edit-gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit-dob">Date of Birth</label>
                    <input type="date" id="edit-dob" name="dateOfBirth">
                </div>
                
                <div class="form-group" id="edit-student-id-group" style="display: none;">
                    <label for="edit-student-id">Student ID</label>
                    <input type="text" id="edit-student-id" name="studentID">
                </div>
                
                <div class="form-group">
                    <label for="edit-address-1">Address Line 1</label>
                    <input type="text" id="edit-address-1" name="address_line1" placeholder="Street address">
                </div>
                
                <div class="form-group">
                    <label for="edit-address-2">Address Line 2</label>
                    <input type="text" id="edit-address-2" name="address_line2" placeholder="City">
                </div>
                
                <div class="form-group">
                    <label for="edit-address-3">Address Line 3</label>
                    <input type="text" id="edit-address-3" name="address_line3" placeholder="Province/Postal Code">
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="delete-user-modal" class="modal" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h3 id="delete-modal-title">Delete User</h3>
            <button class="modal-close" type="button" aria-label="Close modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="warning-text">Are you sure you want to delete <strong id="delete-user-name"></strong>?</p>
            <p class="muted-text">This action cannot be undone.</p>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost modal-cancel">Cancel</button>
                <button type="button" class="btn btn-red" id="confirm-delete-btn">Delete User</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
  const form = document.getElementById('user-search-form');
  const input = document.getElementById('user-search-input');
  const clearBtn = document.getElementById('user-search-clear');
  const tableBody = document.getElementById('user-table-body');
  let isSearching = false;

  // Perform server-side search
  function performSearch(query) {
    if(!query.trim()) {
      // Reload page to show initial 5 users
      location.reload();
      return;
    }

    isSearching = true;
    
    fetch(`/api/users?action=search&q=${encodeURIComponent(query)}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(result => {
        console.log('Search Results:', result);
        if(result.success && result.users) {
          tableBody.innerHTML = '';
          
          if(result.users.length === 0) {
            tableBody.innerHTML = '<tr class="table-row"><td class="table-data" colspan="4">No users found matching your search.</td></tr>';
          } else {
            result.users.forEach(user => {
              const displayName = user.firstName + ' ' + user.lastName + 
                (user.studentID ? ' (' + user.studentID + ')' : '');
              const row = document.createElement('tr');
              row.className = 'table-row';
              row.setAttribute('data-user-id', user.userID);
              row.innerHTML = `
                <td class="table-data" data-col="name">${displayName}</td>
                <td class="table-data" data-col="type">${user.role}</td>
                <td class="table-data" data-col="email">${user.email}</td>
                <td class="table-data">
                  <div class="row">
                    <button class="btn edit-user-btn" data-user-id="${user.userID}" type="button">Edit</button>
                    <button class="btn btn-red" data-user-id="${user.userID}" type="button">Delete</button>
                  </div>
                </td>
              `;
              tableBody.appendChild(row);
            });
          }
          
          // Re-attach event listeners
          attachEditDeleteListeners();
        } else {
          throw new Error(result.message || 'Invalid response from server');
        }
        isSearching = false;
      })
      .catch(error => {
        console.error('Error searching users:', error);
        alert('Failed to search users: ' + error.message);
        isSearching = false;
      });
  }

  form.addEventListener('submit', (e)=>{ 
    e.preventDefault(); 
    if(!isSearching) {
      performSearch(input.value);
    }
  });
  
  clearBtn.addEventListener('click', ()=>{ 
    input.value=''; 
    location.reload(); // Reload to show initial 5 users
  });

  // Modal functionality
  const editModal = document.getElementById('edit-user-modal');
  const deleteModal = document.getElementById('delete-user-modal');
  let currentUserId = null;

  // Helper functions for modal
  function openModal(modal) {
    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modal) {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Function to handle edit button click
  function handleEditClick() {
    currentUserId = this.getAttribute('data-user-id');
    
    // Fetch complete user data from server
    fetch(`/api/users?action=get&id=${currentUserId}`)
      .then(response => response.json())
      .then(result => {
        if(!result.success) {
          throw new Error(result.message || 'Failed to fetch user data');
        }
        const userData = result;
        // Populate form with fetched data
        document.getElementById('edit-user-id').value = userData.userID || currentUserId;
        document.getElementById('edit-first-name').value = userData.firstName || '';
        document.getElementById('edit-last-name').value = userData.lastName || '';
        document.getElementById('edit-email').value = userData.email || '';
        document.getElementById('edit-phone').value = userData.phone || '';
        document.getElementById('edit-gender').value = userData.gender || '';
        document.getElementById('edit-dob').value = userData.dateOfBirth || '';
        document.getElementById('edit-student-id').value = userData.studentID || '';
        document.getElementById('edit-address-1').value = userData.address_line1 || '';
        document.getElementById('edit-address-2').value = userData.address_line2 || '';
        document.getElementById('edit-address-3').value = userData.address_line3 || '';
        
        // Show student ID field if user is a student
        const studentIdGroup = document.getElementById('edit-student-id-group');
        studentIdGroup.style.display = userData.studentID ? 'block' : 'none';
        
        openModal(editModal);
      })
      .catch(error => {
        console.error('Error fetching user data:', error);
        // Fallback: use data from table row
        const row = this.closest('.table-row');
        const nameText = row.querySelector('[data-col="name"]').textContent;
        const email = row.querySelector('[data-col="email"]').textContent;
        
        const nameMatch = nameText.match(/^(.+?)\s*(?:\(([^)]+)\))?$/);
        const fullName = nameMatch ? nameMatch[1].trim() : nameText;
        const studentID = nameMatch && nameMatch[2] ? nameMatch[2] : '';
        const nameParts = fullName.split(' ');
        const firstName = nameParts[0] || '';
        const lastName = nameParts.slice(1).join(' ') || '';
        
        document.getElementById('edit-user-id').value = currentUserId;
        document.getElementById('edit-first-name').value = firstName;
        document.getElementById('edit-last-name').value = lastName;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-student-id').value = studentID;
        
        const studentIdGroup = document.getElementById('edit-student-id-group');
        studentIdGroup.style.display = studentID ? 'block' : 'none';
        
        openModal(editModal);
      });
  }

  // Function to handle delete button click
  function handleDeleteClick() {
    currentUserId = this.getAttribute('data-user-id');
    const row = this.closest('.table-row');
    const userName = row.querySelector('[data-col="name"]').textContent;
    
    document.getElementById('delete-user-name').textContent = userName;
    openModal(deleteModal);
  }

  // Function to attach event listeners
  function attachEditDeleteListeners() {
    document.querySelectorAll('.edit-user-btn').forEach(btn => {
      btn.removeEventListener('click', handleEditClick);
      btn.addEventListener('click', handleEditClick);
    });
    
    // Only target delete buttons in table rows, not the confirm button in modal
    document.querySelectorAll('.table-row .btn-red').forEach(btn => {
      btn.removeEventListener('click', handleDeleteClick);
      btn.addEventListener('click', handleDeleteClick);
    });
  }

  // Initial attachment
  attachEditDeleteListeners();

  // Edit form submission
  document.getElementById('edit-user-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    console.log('Updating user:', data);
    
    // Disable submit button to prevent multiple submissions
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    fetch('/api/users?action=update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
      if(result.success) {
        alert('User updated successfully!');
        closeModal(editModal);
        location.reload();
      } else {
        alert('Error: ' + result.message);
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while updating the user: ' + error.message);
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    });
  });

  // Delete confirmation
  document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    console.log('Deleting user ID:', currentUserId);
    
    // Validate that we have a user ID
    if (!currentUserId) {
      alert('Error: No user selected for deletion');
      return;
    }
    
    // Disable delete button to prevent multiple submissions
    const deleteBtn = this;
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    const payload = { userID: parseInt(currentUserId) };
    console.log('Delete payload:', payload);
    
    fetch('/api/users?action=delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(result => {
      if(result.success) {
        alert('User deleted successfully!');
        closeModal(deleteModal);
        location.reload();
      } else {
        alert('Error: ' + result.message);
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while deleting the user: ' + error.message);
      deleteBtn.disabled = false;
      deleteBtn.textContent = originalText;
    });
  });

  // Close modal handlers
  document.querySelectorAll('.modal-close, .modal-cancel').forEach(btn => {
    btn.addEventListener('click', function() {
      const modal = this.closest('.modal');
      closeModal(modal);
    });
  });

  // Close on overlay click
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function() {
      const modal = this.closest('.modal');
      closeModal(modal);
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') {
      if(editModal.classList.contains('active')) closeModal(editModal);
      if(deleteModal.classList.contains('active')) closeModal(deleteModal);
    }
  });
})();
</script>
