<?php
// header.php - Role-based header with navigation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - <?php echo $page_title ?? 'Dashboard'; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .header .logo { font-size: 22px; font-weight: bold; }
        .header .user-info { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .header .role-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .role-badge.admin { background: #e74c3c; }
        .role-badge.manager { background: #f39c12; }
        .role-badge.student { background: #27ae60; }
        .header .nav-links { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .header .nav-links a {
            color: white;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            transition: background 0.3s;
            font-size: 14px;
        }
        .header .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .header .nav-links .logout-btn { background: rgba(231, 76, 60, 0.8); }
        .header .nav-links .logout-btn:hover { background: #e74c3c; }
        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .header .user-info { justify-content: center; }
            .header .nav-links { justify-content: center; }
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number { font-size: 36px; font-weight: bold; color: #0f172a; }
        .stat-label { color: #0f172a; font-size: 15px; margin-top: 8px; font-weight: 700; }
        .add-task-section {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .tasks-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        .tasks-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .tasks-table th, .tasks-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            color: #111827;
        }
        .tasks-table th { background: #eef2ff; color: #0f172a; font-weight: 700; }
        .status-badge {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .status-completed { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .btn-edit { background: #667eea; color: white; padding: 4px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; }
        .btn-delete { background: #e74c3c; color: white; padding: 4px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; }
        .btn-edit:hover, .btn-delete:hover { opacity: 0.8; }
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        .btn-submit {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 1200px;">
        <div class="header">
            <div class="logo">📋 TASK MANAGER</div>
            <div class="user-info">
                <span>👤 <?php echo $_SESSION['user_name'] ?? 'Guest'; ?></span>
                <span class="role-badge <?php echo $_SESSION['role'] ?? 'student'; ?>">
                    <?php echo ucfirst($_SESSION['role'] ?? 'guest'); ?>
                </span>
                <div class="nav-links">
                    <a href="dashboard.php">📊 Dashboard</a>
                    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager')): ?>
                        <a href="manage_users.php">👥 Users</a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="admin_panel.php" style="background:rgba(255,255,255,0.2);">⚙️ Admin</a>
                    <?php endif; ?>
                    <a href="profile.php">⚙️ Profile</a>
                    <a href="logout.php" class="logout-btn">🚪 Logout</a>
                </div>
            </div>
        </div>