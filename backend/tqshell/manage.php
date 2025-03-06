<?php
session_start();
require '../config.php';
require '../auth_check.php';

if (!isAuthenticated()) {
    header('Location: ../../login.php');
    exit;
}

if ($_SESSION['management_tq'] !== true) {
    header('Location: ../tq_shell.php');
    exit;
}

// Process service restart
if (isset($_POST['restart_service'])) {
    $output = shell_exec('sudo systemctl restart tqshell 2>&1');
    $message = $output ? "Restart error: " . htmlspecialchars($output) : "Service restarted successfully.";
}

// Process config update
$configFile = __DIR__ . '/acki.conf';
if (isset($_POST['update_config'])) {
    $currentContent = file_get_contents($configFile);
    if ($currentContent !== false) {
        // Only update specific values while preserving structure
        $lines = explode("\n", $currentContent);
        $newLines = [];
        foreach ($lines as $line) {
            if (preg_match('/^ListenPort\s+/', $line)) {
                $newLines[] = "ListenPort " . $_POST['ListenPort'];
            } elseif (preg_match('/^RestrictToLocalhost\s+/', $line)) {
                $newLines[] = "RestrictToLocalhost " . $_POST['RestrictToLocalhost'];
            } elseif (preg_match('/^AllowAnonymous\s+/', $line)) {
                $newLines[] = "AllowAnonymous " . $_POST['AllowAnonymous'];
            } else {
                $newLines[] = $line;
            }
        }
        
        if (file_put_contents($configFile, implode("\n", $newLines)) !== false) {
            $message = 'Configuration updated successfully.';
        } else {
            $message = 'Failed to update configuration.';
        }
    }
}

// Extract current values
$config = [
    'ListenPort' => '8080',
    'RestrictToLocalhost' => 'Yes',
    'AllowAnonymous' => 'No'
];

if (file_exists($configFile)) {
    $content = file_get_contents($configFile);
    if (preg_match('/ListenPort\s+(\d+)/', $content, $port)) {
        $config['ListenPort'] = $port[1];
    }
    if (preg_match('/RestrictToLocalhost\s+(Yes|No)/', $content, $restrict)) {
        $config['RestrictToLocalhost'] = $restrict[1];
    }
    if (preg_match('/AllowAnonymous\s+(Yes|No)/', $content, $anon)) {
        $config['AllowAnonymous'] = $anon[1];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TQ-Shell Management Interface</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
    <header class="nav-header">
        <div class="nav-container">
            <h1>TQ-Shell Management | Logged in as <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
            <nav>
                <ul class="nav-menu">
                    <li><a href="../dashboard.php">Dashboard</a></li>
                    <li><a href="manage.php" class="active">TQ-Shell Config</a></li>
                    <li><a href="../../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="dashboard-container">
        <?php if (isset($message)): ?>
            <div class="alert-card <?php echo strpos($message, 'error') !== false ? 'error' : 'success'; ?>">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <section class="config-editor">
            <h2>TQ-Shell Basic Configuration</h2>
            <p>Manage core TQ-Shell settings below. Other settings must be configured manually.</p>
            
            <form method="POST" class="settings-form">
                <div class="form-group">
                    <label for="ListenPort">Listen Port</label>
                    <input type="number" id="ListenPort" name="ListenPort" 
                           value="<?php echo htmlspecialchars($config['ListenPort']); ?>" 
                           min="1" max="65535">
                </div>

                <div class="form-group">
                    <label for="RestrictToLocalhost">Restrict To Localhost</label>
                    <select id="RestrictToLocalhost" name="RestrictToLocalhost">
                        <option value="Yes" <?php echo $config['RestrictToLocalhost'] === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo $config['RestrictToLocalhost'] === 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="AllowAnonymous">Allow Anonymous</label>
                    <select id="AllowAnonymous" name="AllowAnonymous">
                        <option value="Yes" <?php echo $config['AllowAnonymous'] === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo $config['AllowAnonymous'] === 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit" name="update_config" class="btn primary">Save Configuration</button>
                    <button type="submit" name="restart_service" class="btn warning">Restart TQ-Shell Service</button>
                </div>
            </form>
        </section>
    </div>
</body>
</html>

