<?php
include 'db_connect.php';

// Get the pose ID from URL parameter
$pose_id = isset($_GET['pose_id']) ? intval($_GET['pose_id']) : 0;

// Get the pose from the database
$stmt = $conn->prepare("SELECT * FROM poses WHERE id = ?");
$stmt->bind_param("i", $pose_id);
$stmt->execute();
$result = $stmt->get_result();
$pose = $result->fetch_assoc();

if (!$pose) {
    die("Pose not found");
}

// Set status to 1 for this pose
$updateStmt = $conn->prepare("UPDATE poses SET status = 1 WHERE id = ?");
$updateStmt->bind_param("i", $pose['id']);
$updateStmt->execute();
$updateStmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Run Pose</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --secondary: #4cc9f0;
            --accent: #f72585;
            --success: #4ade80;
            --warning: #fbbf24;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --card-bg: rgba(255, 255, 255, 0.95);
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: var(--light);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .run-container {
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--card-shadow);
            color: var(--dark);
            text-align: center;
        }
        
        .card-header {
            margin-bottom: 30px;
        }
        
        .card-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .pose-id {
            font-size: 18px;
            color: var(--gray);
            margin-bottom: 30px;
        }
        
        .servo-values {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 600px) {
            .servo-values {
                grid-template-columns: 1fr;
            }
        }
        
        .servo-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .servo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .servo-name {
            font-weight: 600;
            font-size: 18px;
            color: var(--dark);
        }
        
        .servo-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .status-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .status-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            border-radius: 50px;
            background: var(--success);
            color: white;
            font-weight: 600;
            font-size: 18px;
        }
        
        .control-btn {
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--primary);
            color: white;
            margin-top: 20px;
        }
        
        .control-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .control-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="run-container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Pose Execution</h1>
                <div class="pose-id">Pose ID: #<?php echo $pose['id']; ?></div>
            </div>
            
            <div class="servo-values">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="servo-card">
                    <div class="servo-header">
                        <div class="servo-name">Servo <?php echo $i; ?></div>
                        <div class="servo-value"><?php echo $pose['servo'.$i]; ?>°</div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            
            <div class="status-container">
                <div class="status-title">Current Pose Status</div>
                <div class="status-indicator">
                    <i class="fas fa-check-circle"></i>
                    Active (1)
                </div>
            </div>
            
            <button id="updateStatusBtn" class="control-btn">
                <i class="fas fa-check"></i> Complete Execution
            </button>
        </div>
    </div>
    
    <script>
        document.getElementById('updateStatusBtn').addEventListener('click', function() {
            // Call update_status.php to set status to 0
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ pose_id: <?php echo $pose['id']; ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pose execution completed! Status updated to inactive.');
                    window.location.href = 'index.php';
                } else {
                    alert('Error updating status: ' + data.error);
                }
            });
        });
    </script>
</body>
</html>