<?php /* filepath: /home/snake/Projects/Iskole/app/Views/templates/userDirectory.php */ ?>
<link rel="stylesheet" href="/css/userDirectory/userDirectory.css">

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
                    <tr class="table-row" data-user-id="166">
                        <td class="table-data" data-col="name">Thasindu Ramsitha</td>
                        <td class="table-data" data-col="type">parent</td>
                        <td class="table-data" data-col="email">parent@gmail.com</td>
                        <td class="table-data">
                            <div class="row">
                                <button class="btn edit-user-btn" data-user-id="166" type="button">Edit</button>
                                <button class="btn btn-red" data-user-id="166" type="button">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="table-row" data-user-id="165">
                        <td class="table-data" data-col="name">Aditha Anusara</td>
                        <td class="table-data" data-col="type">student</td>
                        <td class="table-data" data-col="email">student@gmail.com</td>
                        <td class="table-data">
                            <div class="row">
                                <button class="btn edit-user-btn" data-user-id="165" type="button">Edit</button>
                                <button class="btn btn-red" data-user-id="165" type="button">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="table-row" data-user-id="164">
                        <td class="table-data" data-col="name">Seniru Senaweera</td>
                        <td class="table-data" data-col="type">teacher</td>
                        <td class="table-data" data-col="email">teacher@gmail.com</td>
                        <td class="table-data">
                            <div class="row">
                                <button class="btn edit-user-btn" data-user-id="164" type="button">Edit</button>
                                <button class="btn btn-red" data-user-id="164" type="button">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="table-row" data-user-id="163">
                        <td class="table-data" data-col="name">Kalana JInendra</td>
                        <td class="table-data" data-col="type">mp</td>
                        <td class="table-data" data-col="email">manager@gmail.com</td>
                        <td class="table-data">
                            <div class="row">
                                <button class="btn edit-user-btn" data-user-id="163" type="button">Edit</button>
                                <button class="btn btn-red" data-user-id="163" type="button">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="table-row" data-user-id="45">
                        <td class="table-data" data-col="name">Ruwani Jayawardena</td>
                        <td class="table-data" data-col="type">student</td>
                        <td class="table-data" data-col="email">ruwani.jayawardena45@example.com</td>
                        <td class="table-data">
                            <div class="row">
                                <button class="btn edit-user-btn" data-user-id="45" type="button">Edit</button>
                                <button class="btn btn-red" data-user-id="45" type="button">Delete</button>
                            </div>
                        </td>
                    </tr>
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
