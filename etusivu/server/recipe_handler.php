<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitButton'])) {
    if (isset($_POST['eventId'])) {
        $searchInput = $_POST['eventId'];
    }
    if (isset($_POST['submitButton'])) {
        $searchInput = $_POST['submitButton'];
    }
    $selectSql = "SELECT * FROM tapahtumakalenteri WHERE id = ?";
    $selectStmt = mysqli_prepare($conn, $selectSql);
    if ($selectStmt === false) {
        die('Error in preparing the SQL statement: ' . mysqli_error($conn));
    }
    $searchInput = (int)$searchInput;
    mysqli_stmt_bind_param($selectStmt, "i", $searchInput);
    $result = mysqli_stmt_execute($selectStmt);
    if ($result === false) {
        die('Error in executing the SQL statement: ' . mysqli_stmt_error($selectStmt));
    }
    $events = mysqli_stmt_get_result($selectStmt);
    if ($events === false) {
        die('Error in getting result set: ' . mysqli_stmt_error($selectStmt));
    }
    mysqli_stmt_close($selectStmt);
}