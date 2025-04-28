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
            <i class="fa-solid fa-seedling me-2"></i> Seedling
            </a>
            <ul class="collapse list-unstyled ps-3" id="productsMenu">
                <li><a class="nav-link text-dark" href="admin_seeds_add.php">Add Seedling</a></li>
                <li><a class="nav-link text-dark" href="admin_seeds_list.php">Seedling List</a></li>
            </ul>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#usersMenu">
                <i class="fas fa-users me-2"></i> Employee
            </a>
            <ul class="collapse list-unstyled ps-3" id="usersMenu">
                <li><a class="nav-link text-dark" href="admin_employee_add.php">Add Employee</a></li>
                <li><a class="nav-link text-dark" href="admin_employee_list.php">Employee List</a></li>
            </ul>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="admin_seedling_inventory.php">
            <i class="fa-solid fa-boxes-stacked me-2 me-2"></i> Inventory
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#fertilizersMenu">
            <i class="fas fa-vial me-2"></i> Fertilizers
            </a>
            <ul class="collapse list-unstyled ps-3" id="fertilizersMenu">
                <li><a class="nav-link text-dark" href="admin_fertilizer_add.php">Add fertilizers</a></li>
                <li><a class="nav-link text-dark" href="admin_fertilizer_inventory.php">Inventory</a></li>
                <li><a class="nav-link text-dark" href="admin_fertilizer_deduction_history.php">Deduction</a></li>
                <li><a class="nav-link text-dark" href="admin_fertilizer_history.php">History</a></li>
            </ul>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="admin_damage_report.php">
            <i class="fas fa-triangle-exclamation me-2"></i> Reports
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="admin_delivery_history.php">
            <i class="fa-solid fa-cart-shopping me-2"></i> Order History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="admin_login.php">
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
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">Plant Nursery</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-dark" href="admin_dashboard.php">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#mobileSeedlingMenu">
                    <i class="fa-solid fa-seedling me-2"></i> Seedling
                </a>
                <ul class="collapse list-unstyled ps-3" id="mobileSeedlingMenu">
                    <li><a class="nav-link text-dark" href="admin_seeds_add.php">Add Seedling</a></li>
                    <li><a class="nav-link text-dark" href="admin_seeds_list.php">Seedling List</a></li>
                </ul>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#mobileEmployeeMenu">
                    <i class="fas fa-users me-2"></i> Employee
                </a>
                <ul class="collapse list-unstyled ps-3" id="mobileEmployeeMenu">
                    <li><a class="nav-link text-dark" href="admin_employee_add.php">Add Employee</a></li>
                    <li><a class="nav-link text-dark" href="admin_employee_list.php">Employee List</a></li>
                </ul>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark" href="admin_seedling_inventory.php">
                    <i class="fa-solid fa-boxes-stacked me-2"></i> Inventory
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#mobileFertilizersMenu">
                    <i class="fas fa-vial me-2"></i> Fertilizers
                </a>
                <ul class="collapse list-unstyled ps-3" id="mobileFertilizersMenu">
                    <li><a class="nav-link text-dark" href="admin_fertilizer_add.php">Add fertilizers</a></li>
                    <li><a class="nav-link text-dark" href="admin_fertilizer_inventory.php">Fertilizers inventory</a></li>
                    <li><a class="nav-link text-dark" href="admin_fertilizer_deduction_history.php">Fertilizers deduction</a></li>
                </ul>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark" href="admin_damage_report.php">
                    <i class="fas fa-triangle-exclamation me-2"></i> Reports
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark" href="admin_delivery_history.php">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Order History
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
