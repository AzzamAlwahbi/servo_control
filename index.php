<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Robot Servo Control</title>
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
        }
        
        .app-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 10px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-icon {
            background: var(--primary);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, var(--secondary), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 1100px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            color: var(--dark);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-title i {
            color: var(--primary);
        }
        
        .control-panel {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .sliders-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .sliders-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .slider-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .slider-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .slider-label {
            font-weight: 600;
            color: var(--dark);
            font-size: 16px;
        }
        
        .slider-value {
            background: var(--primary);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .slider-container {
            padding: 0 10px;
        }
        
        .slider-input {
            -webkit-appearance: none;
            width: 100%;
            height: 10px;
            border-radius: 5px;
            background: linear-gradient(to right, var(--secondary), var(--primary));
            outline: none;
        }
        
        .slider-input::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary);
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        
        .slider-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 12px;
            color: var(--gray);
        }
        
        .control-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .control-buttons {
                grid-template-columns: 1fr;
            }
        }
        
        .control-btn {
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .control-btn i {
            font-size: 20px;
        }
        
        .reset-btn {
            background: var(--warning);
            color: var(--dark);
        }
        
        .save-btn {
            background: var(--success);
            color: white;
        }
        
        .run-btn {
            background: var(--primary);
            color: white;
        }
        
        .control-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .control-btn:active {
            transform: translateY(0);
        }
        
        .poses-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .poses-table thead th {
            background: var(--primary);
            color: white;
            padding: 16px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 16px;
        }
        
        .poses-table th:first-child {
            border-radius: 12px 0 0 0;
        }
        
        .poses-table th:last-child {
            border-radius: 0 12px 0 0;
        }
        
        .poses-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--dark);
        }
        
        .poses-table tr:nth-child(even) {
            background: rgba(0, 0, 0, 0.02);
        }
        
        .poses-table tr:hover {
            background: rgba(67, 97, 238, 0.05);
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .load-btn {
            background: var(--primary);
            color: white;
        }
        
        .remove-btn {
            background: var(--danger);
            color: white;
        }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        footer {
            text-align: center;
            padding: 30px 0 20px;
            color: var(--gray);
            font-size: 14px;
            margin-top: auto;
        }
        
        .notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 24px;
            background: var(--dark);
            color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.4s ease;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification i {
            font-size: 20px;
        }
        
        .notification.success i {
            color: var(--success);
        }
        
        .notification.error i {
            color: var(--danger);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <header>
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="logo-text">Servo Control</div>
            </div>
        </header>
        
        <div class="dashboard">
            <div class="control-panel">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-sliders-h"></i> Servo Control Panel</h2>
                    </div>
                    
                    <div class="sliders-grid" id="servoSliders">
                        <!-- Sliders will be generated by JavaScript -->
                    </div>
                    
                    <div class="control-buttons">
                        <button class="control-btn reset-btn" id="resetBtn">
                            <i class="fas fa-undo"></i> Reset All
                        </button>
                        <button class="control-btn save-btn" id="saveBtn">
                            <i class="fas fa-save"></i> Save Pose
                        </button>
                        <button class="control-btn run-btn" id="runBtn">
                            <i class="fas fa-play"></i> Run Pose
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="saved-poses">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-database"></i> Saved Poses</h2>
                        <div class="card-info">
                            <span id="poseCount">0 poses saved</span>
                        </div>
                    </div>
                    
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="poses-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Servo 1</th>
                                    <th>Servo 2</th>
                                    <th>Servo 3</th>
                                    <th>Servo 4</th>
                                    <th>Servo 5</th>
                                    <th>Servo 6</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="posesList">
                                <!-- Poses will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p>Robot Servo Control System &copy; 2023</p>
        </footer>
    </div>
    
    <!-- Notification Container -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notificationText">Notification message</span>
    </div>
    
    <script>
        // Servo data and state
        const servos = [
            { id: 1, name: 'Base', value: 90 },
            { id: 2, name: 'Shoulder', value: 90 },
            { id: 3, name: 'Elbow', value: 90 },
            { id: 4, name: 'Wrist', value: 90 },
            { id: 5, name: 'Gripper Rotate', value: 90 },
            { id: 6, name: 'Gripper', value: 90 }
        ];
        
        // DOM Elements
        const slidersContainer = document.getElementById('servoSliders');
        const posesList = document.getElementById('posesList');
        const resetBtn = document.getElementById('resetBtn');
        const saveBtn = document.getElementById('saveBtn');
        const runBtn = document.getElementById('runBtn');
        const poseCount = document.getElementById('poseCount');
        const notification = document.getElementById('notification');
        const notificationText = document.getElementById('notificationText');
        
        // Initialize sliders
        function initSliders() {
            slidersContainer.innerHTML = '';
            
            servos.forEach(servo => {
                const sliderCard = document.createElement('div');
                sliderCard.className = 'slider-card';
                
                sliderCard.innerHTML = `
                    <div class="slider-header">
                        <div class="slider-label">${servo.name}</div>
                        <div class="slider-value" id="value${servo.id}">${servo.value}°</div>
                    </div>
                    <div class="slider-container">
                        <input 
                            type="range" 
                            min="0" 
                            max="180" 
                            value="${servo.value}" 
                            class="slider-input" 
                            id="slider${servo.id}"
                            data-servo="${servo.id}"
                        >
                        <div class="slider-labels">
                            <span>0°</span>
                            <span>90°</span>
                            <span>180°</span>
                        </div>
                    </div>
                `;
                
                slidersContainer.appendChild(sliderCard);
                
                // Add event listener
                const slider = document.getElementById(`slider${servo.id}`);
                const valueDisplay = document.getElementById(`value${servo.id}`);
                
                slider.addEventListener('input', function() {
                    const value = this.value;
                    valueDisplay.textContent = `${value}°`;
                    servos[servo.id - 1].value = parseInt(value);
                });
            });
        }
        
        // Load poses from the database
        function loadPoses() {
            fetch('get_poses.php')
                .then(response => response.json())
                .then(poses => {
                    renderPoses(poses);
                    poseCount.textContent = `${poses.length} poses saved`;
                })
                .catch(error => {
                    console.error('Error loading poses:', error);
                    showNotification('Error loading poses', 'error');
                });
        }
        
        // Render poses in the table
        function renderPoses(poses) {
            posesList.innerHTML = '';
            
            poses.forEach(pose => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${pose.id}</td>
                    <td>${pose.servo1}°</td>
                    <td>${pose.servo2}°</td>
                    <td>${pose.servo3}°</td>
                    <td>${pose.servo4}°</td>
                    <td>${pose.servo5}°</td>
                    <td>${pose.servo6}°</td>
                    <td>
                        <button class="action-btn load-btn" data-id="${pose.id}">
                            <i class="fas fa-download"></i> Load
                        </button>
                        <button class="action-btn remove-btn" data-id="${pose.id}">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                `;
                
                posesList.appendChild(row);
            });
            
            // Add event listeners to action buttons
            document.querySelectorAll('.load-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const poseId = this.getAttribute('data-id');
                    loadPose(poseId);
                });
            });
            
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const poseId = this.getAttribute('data-id');
                    removePose(poseId);
                });
            });
        }
        
        // Load pose into sliders
        function loadPose(poseId) {
            fetch(`load_pose.php?id=${poseId}`)
                .then(response => response.json())
                .then(pose => {
                    if (pose) {
                        // Update sliders
                        for (let i = 1; i <= 6; i++) {
                            const servoValue = pose[`servo${i}`];
                            const slider = document.getElementById(`slider${i}`);
                            const valueDisplay = document.getElementById(`value${i}`);
                            
                            if (slider && valueDisplay) {
                                slider.value = servoValue;
                                valueDisplay.textContent = `${servoValue}°`;
                                servos[i - 1].value = servoValue;
                            }
                        }
                        
                        showNotification(`Pose #${poseId} loaded`, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error loading pose:', error);
                    showNotification('Error loading pose', 'error');
                });
        }
        
        // Remove pose
        function removePose(poseId) {
            const formData = new FormData();
            formData.append('id', poseId);
            
            fetch('delete_pose.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Pose #${poseId} removed`, 'success');
                    loadPoses(); // Reload poses
                } else {
                    showNotification(`Error: ${data.error}`, 'error');
                }
            })
            .catch(error => {
                console.error('Error removing pose:', error);
                showNotification('Error removing pose', 'error');
            });
        }
        
        // Reset all sliders to 90
        function resetSliders() {
            servos.forEach(servo => {
                const slider = document.getElementById(`slider${servo.id}`);
                const valueDisplay = document.getElementById(`value${servo.id}`);
                
                if (slider && valueDisplay) {
                    slider.value = 90;
                    valueDisplay.textContent = '90°';
                    servo.value = 90;
                }
            });
            
            showNotification('All servos reset to 90°', 'success');
        }
        
        // Save current pose
        function savePose() {
            const servoValues = servos.map(s => s.value);
            
            // Create form data
            const formData = new FormData();
            for (let i = 0; i < servoValues.length; i++) {
                formData.append(`servo${i+1}`, servoValues[i]);
            }
            
            fetch('save_pose.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Pose saved (ID: ${data.id})`, 'success');
                    loadPoses(); // Reload poses
                } else {
                    showNotification(`Error: ${data.error}`, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving pose:', error);
                showNotification('Error saving pose', 'error');
            });
        }
        
        // Run pose - redirect to get_run_pose.php
        function runPose() {
            // Save the current pose as the last pose to run
            const servoValues = servos.map(s => s.value);
            const formData = new FormData();
            for (let i = 0; i < servoValues.length; i++) {
                formData.append(`servo${i+1}`, servoValues[i]);
            }
            
            fetch('save_pose.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to get_run_pose.php
                    window.location.href = `get_run_pose.php?pose_id=${data.id}`;
                } else {
                    showNotification('Error saving pose for running', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving pose:', error);
                showNotification('Error saving pose', 'error');
            });
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            notificationText.textContent = message;
            notification.className = 'notification show';
            notification.className += ` ${type}`;
            
            if (type === 'success') {
                notification.querySelector('i').className = 'fas fa-check-circle';
            } else {
                notification.querySelector('i').className = 'fas fa-exclamation-circle';
            }
            
            setTimeout(() => {
                notification.className = 'notification';
            }, 3000);
        }
        
        // Initialize the application
        function initApp() {
            initSliders();
            loadPoses();
            
            // Add event listeners
            resetBtn.addEventListener('click', resetSliders);
            saveBtn.addEventListener('click', savePose);
            runBtn.addEventListener('click', runPose);
        }
        
        // Start the app when DOM is loaded
        document.addEventListener('DOMContentLoaded', initApp);
    </script>
</body>
</html>