<?php
$host = 'firebird'; // Use the service name as the hostname
$database = '/firebird/data/ORDER.fdb'; // Update the path to the Firebird database
$user = 'sysdba';
$password = 'masterkey';

try {
    $dbh = new PDO("firebird:dbname={$host}:{$database}", $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Perform your database operations here
    
    // Close the connection
    echo 'Connection successful';
    $dbh = null;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
