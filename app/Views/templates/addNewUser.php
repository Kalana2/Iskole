 <link rel="stylesheet" href="/css/addNewUser/addNewUser.css">
<section class="mp-management" aria-labelledby="mgmt-form-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="mgmt-form-title">Add New User</h2>
            <p class="subtitle">Create a user account and assign the correct role</p>
        </div>
    </header>

    <div class="card">
        <form action="../../Controllers/addNewUser/addNewUser.php" method="post" novalidate>
            <div class="form-grid">
                <div class="field">
                    <label for="fName">First Name</label>
                    <input type="text" id="fName" name="fName" placeholder="John" title="Enter first name" required>
                </div>
                <div class="field">
                    <label for="lName">Last Name</label>
                    <input type="text" id="lName" name="lName" placeholder="Doe" title="Enter last name" required>
                </div>

                <div class="field span-2">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="name@example.com" title="Enter email"
                        required>
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="07xxxxxxxx"
                        title="Enter phone number (07xxxxxxxx)" pattern="^07\d{8}$" inputmode="numeric" required>
                    <small class="hint">Format: 07XXXXXXXX</small>
                </div>
                <div class="field">
                    <label for="dob">Date of birth</label>
                    <input type="date" id="dob" name="dateOfBirth" title="Enter date of birth" required>
                </div>

                <div class="field span-2">
                    <label for="addressL1">Address line 1</label>
                    <input type="text" id="addressL1" name="addressLine1" placeholder="Street address"
                        title="Enter address line 1" required>
                </div>
                <div class="field span-2">
                    <label for="addressL2">Address line 2</label>
                    <input type="text" id="addressL2" name="addressLine2" placeholder="Apartment, suite, unit, etc."
                        title="Enter address line 2">
                </div>
                <div class="field span-2">
                    <label for="addressL3">Address line 3 (optional)</label>
                    <input type="text" id="addressL3" name="addressLine3" placeholder="City / Region"
                        title="Enter address line 3">
                </div>

                <div class="field">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="" selected disabled>Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="field">
                    <label for="userType">User Type</label>
                    <select name="role" id="userType" required>
                        <option value="" selected disabled>Select user type</option>
                        <option value="mp">Management</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="parent">Parent</option>
                    </select>
                </div>

                <!-- NIC (Management / Teacher / Parent) -->
                <div class="field role role-mp role-teacher role-parent span-2 hidden" data-role="nic">
                    <label for="nic">NIC number</label>
                    <input type="text" id="nic" name="nic" placeholder="xxxxxxxxxxxx"
                        title="Enter NIC number (xxxxxxxxxxxx)">
                </div>

                <!-- Grade & Class (Teacher / Student) -->
                <div class="field role role-teacher role-student hidden" data-role="grade">
                    <label for="grade">Grade</label>
                    <select name="grade" id="grade">
                        <option value="" selected disabled>Select grade</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>
                </div>
                <div class="field role role-teacher role-student hidden" data-role="class">
                    <label for="class">Class</label>
                    <select name="class" id="class">
                        <option value="" selected disabled>Select class</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                    </select>
                </div>

                <!-- Subject (Teacher) -->
                <div class="field role role-teacher span-2 hidden" data-role="subject">
                    <label for="subject">Subject</label>
                    <select name="subject" id="subject">
                        <option value="" selected disabled>Select subject</option>
                        <option value="1">Maths</option>
                        <option value="2">Science</option>
                        <option value="3">English</option>
                        <option value="4">History</option>
                        <option value="5">Geography</option>
                        <option value="6">Aesthetics</option>
                        <option value="7">PTS</option>
                        <option value="8">Religion</option>
                        <option value="9">Health and Physical Education</option>
                        <option value="10">Tamil</option>
                        <option value="11">Citizenship Education</option>
                        <option value="12">Sinhala</option>
                    </select>
                </div>

                <!-- Parent fields -->
                <div class="field role role-parent hidden" data-role="studentIndex">
                    <label for="studentIndex">Student index</label>
                    <input type="number" id="studentIndex" name="studentIndex" placeholder="e.g. 10234">
                </div>
                <div class="field role role-parent hidden" data-role="relationship">
                    <label for="relationship">Relationship type</label>
                    <select id="relationship" name="relationship">
                        <option value="" selected disabled>Select relationship</option>
                        <option value="father">Father</option>
                        <option value="mother">Mother</option>
                        <option value="gardian">Gardian</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-ghost" type="reset">Clear</button>
                <button class="btn btn-primary" id="add-new-user-submit-btn" type="submit" name="submitUser">Add New
                    User</button>
            </div>
        </form>
    </div>
</section>

<script>
    (function () {
        const roleSelect = document.getElementById('userType');

        const roleBlocks = {
            mp: ['nic'],
            teacher: ['nic', 'grade', 'class', 'subject'],
            student: ['grade', 'class'],
            parent: ['nic', 'studentIndex', 'relationship']
        };

        function allRoleFields() {
            return Array.from(document.querySelectorAll('[data-role]'));
        }

        function setRequired(ids, required) {
            ids.forEach(id => {
                const el = document.getElementById(id) || document.querySelector(`[name="${id}"]`);
                if (el) {
                    if (required) {
                        el.setAttribute('required', 'required');
                    } else {
                        el.removeAttribute('required');
                    }
                }
            });
        }

        function updateVisibility() {
            const val = roleSelect.value;
            const show = roleBlocks[val] || [];

            // Hide all
            allRoleFields().forEach(block => block.closest('.field').classList.add('hidden'));

            // Show required for current role
            show.forEach(key => {
                const block = document.querySelector(`[data-role="${key}"]`);
                if (block) block.closest('.field').classList.remove('hidden');
            });

            // Required toggles per role
            setRequired(['nic', 'grade', 'class', 'subject', 'studentIndex', 'relationship'], false);
            if (val === 'teacher') setRequired(['nic', 'grade', 'class', 'subject'], true);
            if (val === 'student') setRequired(['grade', 'class'], true);
            if (val === 'parent') setRequired(['nic', 'studentIndex', 'relationship'], true);
            if (val === 'mp') setRequired(['nic'], true);
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', updateVisibility);
            updateVisibility();
        }
    })();
</script>