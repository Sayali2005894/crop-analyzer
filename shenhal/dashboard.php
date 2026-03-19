<?php
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Analytics
$total_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM analyses WHERE user_id=$user_id");
$total_count = mysqli_fetch_assoc($total_query)['count'];

$risk_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM analyses WHERE user_id=$user_id AND risk_level='High'");
$risk_count = mysqli_fetch_assoc($risk_query)['count'];

// Fetch History
$history_query = mysqli_query($conn, "SELECT * FROM analyses WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Smart Crop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <div style="margin-bottom: 48px; padding-left: 20px;">
            <h2 class="gradient-text" style="font-size: 24px;">SmartCrop AI</h2>
        </div>
        
        <nav style="flex: 1;">
            <a href="dashboard.php" class="nav-link active">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="select_crop.php" class="nav-link">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>New Analysis</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>History</span>
            </a>
        </nav>

        <a href="logout.php" class="nav-link" style="color: var(--danger);">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Sign Out</span>
        </a>
    </aside>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; margin-bottom: 8px;">Welcome, <?php echo explode(' ', $_SESSION['name'])[0]; ?>! 👋</h1>
                <p style="color: var(--text-muted);">Monitor and optimize your agricultural yields with AI.</p>
            </div>
            <a href="select_crop.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Start Crop Analysis
            </a>
        </header>

        <!-- Stats Grid -->
        <div class="stat-grid">
            <div class="card stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary);">
                    <i class="fa-solid fa-microscope"></i>
                </div>
                <div>
                    <p style="color: var(--text-muted); font-size: 14px;">Total Analyses</p>
                    <h3 style="font-size: 28px;"><?php echo $total_count; ?></h3>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <p style="color: var(--text-muted); font-size: 14px;">High Risk Crops</p>
                    <h3 style="font-size: 28px;"><?php echo $risk_count; ?></h3>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <p style="color: var(--text-muted); font-size: 14px;">Active Fields</p>
                    <h3 style="font-size: 28px;">Maharashtra</h3>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 20px;">Recent Analyses</h2>
                <a href="#" style="color: var(--primary); font-size: 14px; text-decoration: none; font-weight: 600;">View All</a>
            </div>

            <?php if(mysqli_num_rows($history_query) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Crop</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Risk Level</th>
                            <th>Suitability</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($history_query)): ?>
                        <tr>
                            <td><span style="font-weight: 600;"><?php echo $row['crop']; ?></span></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <span style="background: <?php echo $row['risk_level'] == 'High' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)'; ?>; 
                                              color: <?php echo $row['risk_level'] == 'High' ? 'var(--danger)' : 'var(--primary)'; ?>; 
                                              padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <?php echo $row['risk_level']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['suitability']; ?></td>
                            <td>
                                <a href="predict.php?id=<?php echo $row['id']; ?>" style="color: var(--secondary);"><i class="fa-solid fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <i class="fa-solid fa-folder-open" style="font-size: 48px; color: var(--glass-heavy); margin-bottom: 16px;"></i>
                    <p style="color: var(--text-muted);">No analysis history found. Start your first analysis!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>