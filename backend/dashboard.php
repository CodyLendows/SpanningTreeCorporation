<?php
require_once 'auth_check.php';
if (!isAuthenticated()) {
    header('Location: login.php');
    exit;
}

// Service-specific statistics
$service_stats = [
    'signalling_uptime' => '99.98%',
    'bitsplitter_scans' => '1,842',
    'jankbox_streams' => '356',
    'tree_requests' => '12'
];

$system_alerts = [
    ['level' => 'danger', 'service' => 'TQ Shell', 'message' => 'Internal use only, read announcement!!'],
    ['level' => 'warning', 'service' => 'BitSplitter', 'message' => '3 vulnerabilities detected today'],
    ['level' => 'info', 'service' => 'Tree Service', 'message' => '2 pending emergency calls'],
    ['level' => 'success', 'service' => 'Jankbox', 'message' => 'All streams operational']
];

$product_health = [
    'bitsplitter_load' => '67%',
    'signalling_capacity' => '42%',
    'jankbox_bandwidth' => '38%',
    'screensaver_downloads' => '892'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operations Dashboard - Spanning Tree Corporation</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="nav-header">
        <div class="nav-container">
            <h1>Network Operations Center | Authenticated as <?php echo $_SESSION['user']; ?></h1>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="employee_chat.php">Chat</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="alerts-panel">
            <h2>Service Alerts</h2>
            <div class="alerts-grid">
                <?php foreach($system_alerts as $alert): ?>
                <div class="alert-card <?php echo $alert['level']; ?>">
                    <h3><?php echo htmlspecialchars($alert['service']); ?></h3>
                    <p><?php echo htmlspecialchars($alert['message']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="quick-stats">
            <?php foreach($service_stats as $key => $value): ?>
            <div class="stat-card">
                <h3><?php echo ucwords(str_replace('_', ' ', $key)); ?></h3>
                <div class="value"><?php echo $value; ?></div>
                <div class="trend">Past 24 hours</div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="products-grid">
            <div class="product-card">
                <h3>BitSplitter Vulnerability Scanner</h3>
                <div class="chart-container">
                    <canvas id="vulnerabilitiesChart"></canvas>
                </div>
                <div class="quick-actions">
                    <a href="bitsplitter/queue.php" class="btn">View Scan Queue</a>
                    <a href="bitsplitter/signatures.php" class="btn">Update Signatures</a>
                </div>
            </div>

            <div class="product-card">
                <h3>Tree Service Dispatch</h3>
                <div class="dispatch-map" id="serviceMap">
                    <!-- Map will be inserted here by JS -->
                </div>
                <div class="quick-actions">
                    <a href="dispatch/pending.php" class="btn">View Pending (3)</a>
                    <a href="dispatch/teams.php" class="btn">Team Locations</a>
                </div>
            </div>
        </div>

        <h2>System Access</h2>
        <ul class="access-list">
            <?php foreach(['bitsplitter', 'employee_chat', 'jankbox', 'dispatch', 'tq_shell'] as $page): ?>
                <li class="<?php echo hasAccess($page) ? 'accessible' : 'restricted'; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $page)); ?>
                    <?php if (hasAccess($page)): ?>
                        <a href="<?php echo $page; ?>.php" class="btn">Access</a>
                    <?php else: ?>
                        <span>(Restricted)</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        // Vulnerabilities detected chart
        const vulnCtx = document.getElementById('vulnerabilitiesChart').getContext('2d');
        new Chart(vulnCtx, {
            type: 'bar',
            data: {
                labels: ['Buffer Overflow', 'C Usage', 'Bad Practices', 'Memory Leaks', 'Others'],
                datasets: [{
                    label: 'Vulnerabilities Detected Today',
                    data: [12, 19, 8, 15, 5],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(0, 123, 255, 0.5)',
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(108, 117, 125, 0.5)'
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Service map
        document.getElementById('serviceMap').innerHTML = `
            <div class="map-placeholder">
                <p>🌳 3 Active Service Teams</p>
                <p>📍 2 Pending Emergency Calls</p>
                <p>🚗 Average Response Time: 58min</p>
            </div>
        `;
    </script>
</body>
</html>
