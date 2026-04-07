<?php
$mysqli = new mysqli("127.0.0.1", "root", "alamonc19", "ecoturismo");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$sql = "SELECT CONSTRAINT_NAME, CONSTRAINT_TYPE FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = 'ecoturismo' AND TABLE_NAME = 'reviews' AND CONSTRAINT_NAME = 'unique_user_reviewable'";
$result = $mysqli->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['CONSTRAINT_NAME'] . ' - ' . $row['CONSTRAINT_TYPE'] . "\n";
    }
    $result->free();
}
$sql2 = "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'ecoturismo' AND TABLE_NAME = 'reviews'";
$result2 = $mysqli->query($sql2);
if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        print_r($row);
    }
    $result2->free();
}
$mysqli->close();
