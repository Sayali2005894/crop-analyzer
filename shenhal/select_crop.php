<?php
include "config.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Crop | Smart Crop</title>
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
            <a href="dashboard.php" class="nav-link"><i class="fa-solid fa-chart-pie"></i><span>Dashboard</span></a>
            <a href="select_crop.php" class="nav-link active"><i class="fa-solid fa-wand-magic-sparkles"></i><span>New Analysis</span></a>
            <a href="#" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i><span>History</span></a>
        </nav>
        <a href="logout.php" class="nav-link" style="color: var(--danger);"><i class="fa-solid fa-right-from-bracket"></i><span>Sign Out</span></a>
    </aside>

    <main class="main-content">
        <header style="margin-bottom: 40px;">
            <h1 style="font-size: 32px; margin-bottom: 8px;">New Crop Analysis</h1>
            <p style="color: var(--text-muted);">Step 1: Choose the crop you wish to analyze for this season.</p>
        </header>

        <div class="card" style="max-width: 600px; margin: 0 auto; padding: 48px;">
            <form action="input_form.php" method="GET">
                <div class="form-group" style="margin-bottom: 40px;">
                    <label style="font-size: 16px;">Select Crop Species</label>
                    <select name="crop" required style="font-size: 16px; padding: 18px;">
                        <option value="">Choose a crop...</option>
                        <option value="Rice">🍚 Rice (Paddy)</option>
                        <option value="Wheat">🌾 Wheat</option>
                        <option value="Cotton">☁️ Cotton</option>
                        <option value="Maize">🌽 Maize (Corn)</option>
                        <option value="Soybean">🌱 Soybean</option>
                        <option value="Sugarcane">🎋 Sugarcane</option>
                        <option value="Turmeric">🟡 Turmeric</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="dashboard.php" style="color: var(--text-muted); text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Continue to Details <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Featured Crops Suggestion -->
        <div style="margin-top: 60px; text-align: center;">
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Recommended for your central region:</p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <div class="card" style="padding: 15px 30px; margin: 0; min-width: 150px;">
                    <p style="font-size: 24px;">🌾</p>
                    <p style="font-weight: 600;">Wheat</p>
                </div>
                <div class="card" style="padding: 15px 30px; margin: 0; min-width: 150px;">
                    <p style="font-size: 24px;">🍚</p>
                    <p style="font-weight: 600;">Rice</p>
                </div>
                <div class="card" style="padding: 15px 30px; margin: 0; min-width: 150px;">
                    <p style="font-size: 24px;">🎋</p>
                    <p style="font-weight: 600;">Sugarcane</p>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
