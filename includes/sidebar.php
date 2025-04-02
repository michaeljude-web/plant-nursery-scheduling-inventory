<nav class="sidebar d-none d-md-block col-md-3 col-lg-2 px-3 border-end">
    <h4 class="mt-3 text-primary ">Plant Nursery</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="admin_dashboard.php">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#productsMenu">
            <i class="fa-solid fa-seedling me-2"></i> Seeds
            </a>
            <ul class="collapse list-unstyled ps-3" id="productsMenu">
                <li><a class="nav-link text-dark" href="admin_seeds_add.php">Add Seeds</a></li>
                <li><a class="nav-link text-dark" href="admin_seeds_list.php">Seeds List</a></li>
            </ul>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#usersMenu">
                <i class="fas fa-users me-2"></i> Staffs
            </a>
            <ul class="collapse list-unstyled ps-3" id="usersMenu">
                <li><a class="nav-link text-dark" href="admin_employee_add.php">Add Employee</a></li>
                <li><a class="nav-link text-dark" href="admin_employee_list.php">Employee List</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<div class="d-flex align-items-center d-md-none px-3 py-2 bg-primary text-white">
    <button class="btn me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
        <i class="fas fa-bars"></i>
    </button>
    <h5 class="mb-0">Plant Nursery</h5>
</div>

<!-- Mobile -->
<div class="offcanvas offcanvas-start bg-light" id="mobileSidebar">
    <div class="offcanvas-header bg-danger text-black">
        <h5 class="offcanvas-title">Plant Nursery</h5>
        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="admin_dashboard.php">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#productsMenu">
            <i class="fa-solid fa-seedling me-2"></i> Seeds
            </a>
            <ul class="collapse list-unstyled ps-3" id="productsMenu">
                <li><a class="nav-link text-dark" href="admin_seeds_add.php">Add Seeds</a></li>
                <li><a class="nav-link text-dark" href="admin_seeds_list.php">Seeds List</a></li>
            </ul>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#usersMenu">
                <i class="fas fa-users me-2"></i> Staffs
            </a>
            <ul class="collapse list-unstyled ps-3" id="usersMenu">
                <li><a class="nav-link text-dark" href="admin_staff_add.php">Add staff</a></li>
                <li><a class="nav-link text-dark" href="admin_staff_list.php">Staff List</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
    </div>
</div>
