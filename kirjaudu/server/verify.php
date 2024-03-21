<?php
include "../static/server/connect.php";

if (isset($_GET['token'])) {
    $verificationToken = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM käyttäjät WHERE verification_token = ?");
    $stmt->bind_param("s", $verificationToken);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        $stmt = $conn->prepare("UPDATE käyttäjät SET is_verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        echo "Sähköpostiosoite on vahvistettu. Voit nyt kirjautua sisään.";
    } else {
        echo "Virheellinen vahvistustoken.";
    }
} else {
    echo "Vahvistustoken puuttuu.";
}
