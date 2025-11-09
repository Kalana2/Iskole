(function () {
  const roleSelect = document.getElementById("userType");

  const roleBlocks = {
    mp: ["nic"],
    teacher: ["nic", "grade", "class", "subject"],
    student: ["grade", "class"],
    parent: ["nic", "studentIndex", "relationship"],
  };

  function allRoleFields() {
    return Array.from(document.querySelectorAll("[data-role]"));
  }

  function setRequired(ids, required) {
    ids.forEach((id) => {
      const el =
        document.getElementById(id) || document.querySelector(`[name="${id}"]`);
      if (el) {
        if (required) {
          el.setAttribute("required", "required");
        } else {
          el.removeAttribute("required");
        }
      }
    });
  }

  function updateVisibility() {
    const val = roleSelect.value;
    const show = roleBlocks[val] || [];

    // Hide all
    allRoleFields().forEach((block) =>
      block.closest(".field").classList.add("hidden")
    );

    // Show required for current role
    show.forEach((key) => {
      const block = document.querySelector(`[data-role="${key}"]`);
      if (block) block.closest(".field").classList.remove("hidden");
    });

    // Required toggles per role
    setRequired(
      ["nic", "grade", "class", "subject", "studentIndex", "relationship"],
      false
    );
    if (val === "teacher")
      setRequired(["nic", "grade", "class", "subject"], true);
    if (val === "student") setRequired(["grade", "class"], true);
    if (val === "parent")
      setRequired(["nic", "studentIndex", "relationship"], true);
    if (val === "mp") setRequired(["nic"], true);
  }

  if (roleSelect) {
    roleSelect.addEventListener("change", updateVisibility);
    updateVisibility();
  }

  // Enforce DOB to be at least 3 years before today
  const dobInput = document.getElementById("dob");
  if (dobInput) {
    const today = new Date();
    const maxDate = new Date(
      today.getFullYear() - 3,
      today.getMonth(),
      today.getDate()
    );
    const maxStr = maxDate.toISOString().split("T")[0];
    dobInput.setAttribute("max", maxStr);

    const validateDob = () => {
      dobInput.setCustomValidity("");
      if (dobInput.value && new Date(dobInput.value) > maxDate) {
        dobInput.setCustomValidity(
          "Date of birth must be at least 3 years before today."
        );
      }
    };
    dobInput.addEventListener("input", validateDob);
    validateDob();
  }
})();
