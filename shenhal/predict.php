<?php
include "config.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

// Process New Analysis or View Old One
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $crop = mysqli_real_escape_string($conn, $_POST['crop']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $soil_type = mysqli_real_escape_string($conn, $_POST['soil_type']);
    $season = mysqli_real_escape_string($conn, $_POST['season']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    // Simulate Prediction Data
    $humidity = rand(40, 85);
    $rainfall = rand(10, 200);
    $temp = rand(15, 38);
    
    // Simple Logic for Risk & Suitability
    $risk_level = "Low";
    $suitability = "High";
    
    if($temp > 35 || $rainfall < 50){ $risk_level = "High"; $suitability = "Low"; }
    elseif($humidity > 75){ $risk_level = "Medium"; $suitability = "Moderate"; }

    // Hande Photo Upload
    $photo_path = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $photo_path = "uploads/" . time() . "_" . $_FILES['photo']['name'];
        if (!is_dir('uploads')) mkdir('uploads');
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
    }

    // Save to Database
    $sql = "INSERT INTO analyses (user_id, crop, location, soil_type, season, notes, photo_path, humidity, rainfall, temp, risk_level, suitability) 
            VALUES ($user_id, '$crop', '$location', '$soil_type', '$season', '$notes', '$photo_path', $humidity, $rainfall, $temp, '$risk_level', '$suitability')";
    mysqli_query($conn, $sql);
    $analysis_id = mysqli_insert_id($conn);
} else if(isset($_GET['id'])){
    $analysis_id = (int)$_GET['id'];
} else {
    header("Location: dashboard.php");
    exit();
}

// Fetch Analysis Details
$res = mysqli_query($conn, "SELECT * FROM analyses WHERE id=$analysis_id AND user_id=$user_id");
$data = mysqli_fetch_assoc($res);

if(!$data){ header("Location: dashboard.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Report | Smart Crop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <div style="margin-bottom: 48px; padding-left: 20px;">
            <h2 class="gradient-text" style="font-size: 24px;">SmartCrop AI</h2>
        </div>
        <nav style="flex: 1;">
            <a href="dashboard.php" class="nav-link"><i class="fa-solid fa-chart-pie"></i><span>Dashboard</span></a>
            <a href="select_crop.php" class="nav-link"><i class="fa-solid fa-wand-magic-sparkles"></i><span>New Analysis</span></a>
            <a href="#" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i><span>History</span></a>
        </nav>
        <a href="logout.php" class="nav-link" style="color: var(--danger);"><i class="fa-solid fa-right-from-bracket"></i><span>Sign Out</span></a>
    </aside>

    <main class="main-content">
        <header style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="dashboard.php" style="color: var(--primary); text-decoration: none; font-size: 14px; font-weight: 600;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>
                <h1 style="font-size: 32px; margin-top: 12px;"><?php echo $data['crop']; ?> Intelligence Report</h1>
                <p style="color: var(--text-muted);">Generated for <?php echo $data['location']; ?> • <?php echo date('M d, Y', strtotime($data['created_at'])); ?></p>
            </div>
            <div style="text-align: right;">
                <span style="display: block; font-size: 14px; color: var(--text-muted); margin-bottom: 8px;">Analysis Status</span>
                <span class="btn" style="background: rgba(16, 185, 129, 0.1); color: var(--primary); padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                    <i class="fa-solid fa-check-circle"></i> Complete
                </span>
            </div>
        </header>

        <div class="charts-grid">
            <!-- Left: Predictions & Recommendations -->
            <div class="col">
                <!-- Health Cards -->
                <div class="stat-grid">
                    <div class="card stat-card" style="margin-bottom: 0;">
                        <div>
                            <p style="color: var(--text-muted); font-size: 13px;">Suitability</p>
                            <h4 style="font-size: 22px; color: var(--primary);"><?php echo $data['suitability']; ?></h4>
                        </div>
                    </div>
                    <div class="card stat-card" style="margin-bottom: 0;">
                        <div>
                            <p style="color: var(--text-muted); font-size: 13px;">Risk Level</p>
                            <h4 style="font-size: 22px; color: <?php echo $data['risk_level'] == 'High' ? 'var(--danger)' : 'var(--primary)'; ?>;">
                                <?php echo $data['risk_level']; ?>
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="card" style="margin-top: 24px;">
                    <h3 style="margin-bottom: 24px;"><i class="fa-solid fa-chart-line" style="color: var(--secondary);"></i> Prediction Metrics</h3>
                    <div style="height: 300px;">
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>

                <!-- Risk Analysis -->
                <div class="card">
                    <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-mask-face" style="color: var(--danger);"></i> Risk Assessment</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="padding: 15px; background: rgba(0,0,0,0.2); border-radius: 12px; border-left: 4px solid var(--danger);">
                            <p style="font-size: 13px; color: var(--text-muted);">Disease Risk</p>
                            <p style="font-weight: 600;"><?php echo $data['humidity'] > 70 ? 'High (Fungal)' : 'Low'; ?></p>
                        </div>
                        <div style="padding: 15px; background: rgba(0,0,0,0.2); border-radius: 12px; border-left: 4px solid var(--accent);">
                            <p style="font-size: 13px; color: var(--text-muted);">Pest Probability</p>
                            <p style="font-weight: 600;"><?php echo $data['temp'] > 30 ? 'Moderate' : 'Low'; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Photo & Recommendations -->
            <div class="col">
                <?php if($data['photo_path']): ?>
                <div class="card" style="padding: 15px;">
                    <img src="<?php echo $data['photo_path']; ?>" style="width: 100%; border-radius: 12px; display: block;" alt="Crop Photo">
                    <p style="margin-top: 10px; font-size: 12px; color: var(--text-muted); text-align: center;">Field Observation Image</p>
                </div>
                <?php endif; ?>

                <div class="card" style="background: linear-gradient(to bottom right, var(--card-bg), rgba(16, 185, 129, 0.05));">
                    <h3 style="margin-bottom: 20px; color: var(--primary);"><i class="fa-solid fa-lightbulb"></i> Recommendations</h3>
                    <div style="font-size: 15px; color: var(--text-white);">
                        <p style="margin-bottom: 15px;">Based on the <b><?php echo $data['soil_type']; ?></b> and <b><?php echo $data['season']; ?></b> season in <b><?php echo $data['location']; ?></b>:</p>
                        
                        <div style="margin-bottom: 20px;">
                            <p style="font-weight: 600; color: var(--primary); margin-bottom: 8px;">Fertilizer Advice:</p>
                            <p style="font-size: 14px; opacity: 0.8;">Apply NPK (20:10:10) during the vegetative stage. Soil nutrients seem adequate but phosphorus boost recommended.</p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 8px;">Irrigation Plan:</p>
                            <p style="font-size: 14px; opacity: 0.8;">Keep soil moist. Predicted rainfall is <?php echo $data['rainfall']; ?>mm, so supplemental watering might be needed mid-season.</p>
                        </div>

                        <div style="padding: 15px; background: rgba(16, 185, 129, 0.1); border-radius: 12px;">
                            <p style="font-size: 13px; margin-bottom: 4px;">Yield Est. Possibility:</p>
                            <p style="font-size: 20px; font-weight: 700;">85% ~ 90%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const ctx = document.getElementById('predictionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Humidity (%)', 'Rainfall (mm)', 'Temperature (°C)'],
            datasets: [{
                label: 'Field Metrics',
                data: [<?php echo $data['humidity']; ?>, <?php echo $data['rainfall']; ?>, <?php echo $data['temp']; ?>],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
</script>

</body>
</html>