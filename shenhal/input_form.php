<?php
include "config.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$selected_crop = $_GET['crop'] ?? 'Rice';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Details | Smart Crop</title>
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
            <div style="display: flex; gap: 12px; align-items: center; color: var(--primary); margin-bottom: 12px;">
                <a href="select_crop.php" style="color: inherit;"><i class="fa-solid fa-arrow-left"></i> Change Crop</a>
                <span>/</span>
                <span style="font-weight: 600;">Details for <?php echo $selected_crop; ?></span>
            </div>
            <h1 style="font-size: 32px; margin-bottom: 8px;">Field Intelligence</h1>
            <p style="color: var(--text-muted);">Step 2: Provide field-level details for accurate prediction.</p>
        </header>

        <form action="predict.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="crop" value="<?php echo $selected_crop; ?>">
            
            <div class="charts-grid">
                <!-- Left Column: Form Details -->
                <div class="card">
                    <h3 style="margin-bottom: 24px;"><i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Primary Details</h3>
                    
                    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Location / Region</label>
                            <select name="location" required>
                                <option value="">Select Region</option>
                                <option value="Pune, Maharashtra">Pune, Maharashtra</option>
                                <option value="Nashik, Maharashtra">Nashik, Maharashtra</option>
                                <option value="Vidarbha, Maharashtra">Vidarbha, Maharashtra</option>
                                <option value="Marathwada, Maharashtra">Marathwada, Maharashtra</option>
                                <option value="Konkan, Maharashtra">Konkan, Maharashtra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Soil Type</label>
                            <select name="soil_type" required>
                                <option value="">Select Soil</option>
                                <option value="Black Soil">Black Soil (Regur)</option>
                                <option value="Alluvial Soil">Alluvial Soil</option>
                                <option value="Red Soil">Red Soil</option>
                                <option value="Laterite Soil">Laterite Soil</option>
                                <option value="Sandy Soil">Sandy Soil</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Season</label>
                            <select name="season" required>
                                <option value="Kharif">Kharif (Monsoon)</option>
                                <option value="Rabi">Rabi (Winter)</option>
                                <option value="Zaid">Zaid (Summer)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Estimated Planting Date</label>
                            <input type="date" name="planting_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Crop Description / Observations</label>
                        <textarea name="notes" rows="5" placeholder="Mention any specific concerns, current plant height, or unusual leaf colors..."></textarea>
                    </div>
                </div>

                <!-- Right Column: Visuals -->
                <div class="card">
                    <h3 style="margin-bottom: 24px;"><i class="fa-solid fa-camera" style="color: var(--primary);"></i> Visual Data</h3>
                    
                    <div class="upload-zone" onclick="document.getElementById('photo-input').click()">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 48px; color: var(--primary); margin-bottom: 16px; display: block;"></i>
                        <p style="font-weight: 600; margin-bottom: 4px;">Upload Crop Photo</p>
                        <p style="font-size: 13px; color: var(--text-muted);">Snap a photo of the leaves or soil for visual analysis.</p>
                        <input type="file" name="photo" id="photo-input" style="display: none;" onchange="updatePreview(this)">
                        
                        <div id="preview-container" style="display: none; margin-top: 20px;">
                            <img id="image-preview" src="#" alt="Preview" style="max-width: 100%; border-radius: 12px; border: 1px solid var(--card-border);">
                            <p id="file-name" style="margin-top: 8px; font-size: 14px; color: var(--primary); font-weight: 600;"></p>
                        </div>
                    </div>

                    <div style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px;">
                            Generate Intelligence Report <i class="fa-solid fa-microchip"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<script>
    function updatePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
                document.getElementById('file-name').innerText = input.files[0].name;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
