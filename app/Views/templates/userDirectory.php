<?php /* filepath: /home/snake/Projects/Iskole/app/Views/templates/userDirectory.php */ ?>
<link rel="stylesheet" href="/css/userDirectory/userDirectory.css">

<?php 
    require_once __DIR__ . '/../../Controllers/userDirectoryController.php';
    $userDirectory = new UserDirectoryController();
    $users = $userDirectory->getRecentUsers();
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

<script>
(function(){
  const form = document.getElementById('user-search-form');
  const input = document.getElementById('user-search-input');
  const clearBtn = document.getElementById('user-search-clear');
  const rows = Array.from(document.querySelectorAll('#user-table-body .table-row'));

  function matches(row, q){
    if(!q) return true;
    const hay = [
      row.querySelector('[data-col="name"]').textContent,
      row.querySelector('[data-col="type"]').textContent,
      row.querySelector('[data-col="email"]').textContent,
    ].join(' ').toLowerCase();
    return hay.includes(q);
  }

  function applyFilter(){
    const q = (input.value || '').trim().toLowerCase();
    let visible = 0;
    rows.forEach(r => {
      const show = matches(r, q);
      r.style.display = show ? '' : 'none';
      if(show) visible++;
    });
    document.body.dataset.userFilterCount = String(visible);
  }

  form.addEventListener('submit', (e)=>{ e.preventDefault(); applyFilter(); });
  input.addEventListener('input', applyFilter);
  clearBtn.addEventListener('click', ()=>{ input.value=''; applyFilter(); });

  // initial
  applyFilter();
})();
</script>
