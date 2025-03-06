<?php
// fetch_new_chatlogs.php

// Remote connection details
$remote_host = '207.127.89.39';
$remote_port = 22; // default SSH port
$remote_file = '/srv/logs/chat_logs.json';
$local_file  = __DIR__ . '/chat_logs.json';

$username = 'salem';
$password = getenv('REMOTE_PASSWORD');

if (!$password) {
    die("Error: Password is not set in environment variables.\n");

// Check if the SSH2 extension is available
if (!function_exists('ssh2_connect')) {
    die("Error: The ssh2 extension is not installed. Please install it to proceed.\n");
}

// Establish an SSH connection
$connection = ssh2_connect($remote_host, $remote_port);
if (!$connection) {
    die("Error: Could not connect to $remote_host on port $remote_port.\n");
}

// Authenticate using the provided credentials
if (!ssh2_auth_password($connection, $username, $password)) {
    die("Error: Authentication failed for user $username.\n");
}

// Use SCP to retrieve the file from the remote server
if (!ssh2_scp_recv($connection, $remote_file, $local_file)) {
    die("Error: Failed to download $remote_file to $local_file.\n");
}

echo "Success: File downloaded to $local_file.\n";
?>
