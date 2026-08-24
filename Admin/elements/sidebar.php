<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li><a class="ai-icon" href="index.php">
                    <i class="flaticon-dashboard-1"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="ai-icon" href="notifications.php">
                    <i class="fa-solid fa-bell"></i>
                    <span class="nav-text">Notifications<?php
                        if (isset($pdo)) {
                            $unread_msg = (int) $pdo->query("SELECT COUNT(*) c FROM messages WHERE is_read = 0")->fetch()['c'];
                            $unread_orders = (int) $pdo->query("SELECT COUNT(*) c FROM orders WHERE is_read = 0")->fetch()['c'];
                            $pending_reviews = (int) $pdo->query("SELECT COUNT(*) c FROM reviews WHERE status = 'pending'")->fetch()['c'];
                            $notif_total = $unread_msg + $unread_orders + $pending_reviews;
                            if ($notif_total > 0) echo ' <span class="badge badge-danger">' . $notif_total . '</span>';
                        }
                    ?></span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="nav-text">Orders<?php
                        if (isset($pdo)) {
                            $unread_orders_nav = (int) $pdo->query("SELECT COUNT(*) c FROM orders WHERE is_read = 0")->fetch()['c'];
                            if ($unread_orders_nav > 0) echo ' <span class="badge badge-danger">' . $unread_orders_nav . '</span>';
                        }
                    ?></span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="orders.php">Orders List</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text">Plans</span>

                </a>
                <ul aria-expanded="false">
                    <li><a href="property-list.php">Plans</a></li>
                    <li><a href="add-property.php">Create Plan</a></li>
                    <li><a href="plan-categories.php">Plan Categories</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-architecture-and-city"></i>
                    <span class="nav-text">Projects</span>

                </a>
                <ul aria-expanded="false">
                    <li><a href="project-list.php">Projects</a></li>
                    <li><a href="add-project.php">Create Project</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-file"></i>
                    <span class="nav-text">Blog</span>

                </a>
                <ul aria-expanded="false">
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="add-blog.php">Add Blog</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-database-1"></i>
                    <span class="nav-text">CMS</span>

                </a>
                <ul aria-expanded="false">
                    <li><a href="menus.php">Menu</a></li>
                    <li><a href="headers.php">Headers</a></li>
                    <li><a href="footer-settings.php">Footer</a></li>
                    <li><a href="hero-slides.php">Hero / Slides</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text">Sections</span>
                </a>
                <ul aria-expanded="false">
                    <?php $section_registry_nav = require __DIR__ . '/../config/section_registry.php'; ?>
                    <?php foreach ($section_registry_nav as $page_key => $page_def): ?>
                        <li><a href="sections.php?page=<?php echo urlencode($page_key); ?>"><?php echo htmlspecialchars($page_def['label']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-form-1"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="form-elements.php">Form Elements</a></li>
                    <li><a href="blog-categories.php">Categories</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-user-7"></i>
                    <span class="nav-text">Users</span>

                </a>
                <ul aria-expanded="false">
                    <li><a href="add-user.php">Add User</a></li>
                    <li><a href="all-users.php">All Users</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i>
                    <span class="nav-text">Settings</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="payment-settings.php">Payment Settings</a></li>
                    <li><a href="email-settings.php">Email &amp; Notifications</a></li>
                    <li><a href="uploads-check.php">Uploads Health Check</a></li>
                    <li><a href="migrate.php">Database Migration</a></li>
                </ul>
            </li>
        </ul>

        <div class="copyright">
            <p><strong>Mars Construction</strong> © <?php echo date('Y'); ?> All Rights Reserved</p>
        </div>
    </div>
</div>