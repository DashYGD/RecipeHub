<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "Recipe ID: " . $id . " removed.";
}

// Redirect back to the recipes page with query string
header("Location: /admin/?page=recipes");

exit();
?>