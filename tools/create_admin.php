<?php
// Run from CLI: php tools/create_admin.php admin pass123
if(PHP_SAPI !== 'cli'){ echo "Run via CLI\n"; exit; }
if($argc < 3){ echo "Usage: php create_admin.php username password\n"; exit; }
$username = $argv[1]; $password = $argv[2];
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Use this SQL to create admin user:\n";
echo "INSERT INTO users (username, password, role) VALUES ('".addslashes($username)."', '".$hash."', 'admin');\n";
?>