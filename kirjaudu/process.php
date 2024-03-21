<?php
include "../static/server/connect.php";

function start_session_if_not_started() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function setUserToken($user_id, $token, $conn) {
    $stmt = $conn->prepare("UPDATE käyttäjät SET token = ? WHERE id = ?");
    $stmt->bind_param("si", $token, $user_id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['email-username_1'], $_POST['password_1'])) {
    $login_input = $_POST['email-username_1'];
    $user_password = $_POST['password_1'];

    $stmt = $conn->prepare("SELECT * FROM käyttäjät WHERE (email = ? OR username = ?)");
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($user_password, $row['user_password'])) {
            start_session_if_not_started();
            if (isset($_POST['muista_minut'])) {
                setUserToken($row['id'], generateToken(), $conn);
            }
            if ($row['is_admin'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: admin");
            } else {
                $_SESSION['user'] = $row['id'];
                header("Location: kojelauta");
            }
            exit();
        } else {
            start_session_if_not_started();
            $_SESSION['login_error'] = "Väärä sähköposti/käyttäjänimi tai salasana";
            $stmt->close();
            header("Location: login");
            exit();
        }
    } else {
        start_session_if_not_started();
        $_SESSION['login_error'] = "Väärä sähköposti/käyttäjänimi tai salasana";
        $stmt->close();
        header("Location: login");
        exit();
    }
}

if (isset($_POST['name_2'], $_POST['email_2'], $_POST['password_2'])) {
    $username = $_POST['name_2'];
    $email = $_POST['email_2'];
    $user_password = $_POST['password_2'];
    $verificationToken = generateToken();

    $stmt = $conn->prepare("SELECT email FROM käyttäjät WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        start_session_if_not_started();
        $_SESSION['register_error'] = "Valitsemasi sähköposti on jo käytössä";
        $_SESSION['registration_attempt'] = true;
        $stmt->close();
        header("Location: login");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT username FROM käyttäjät WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            start_session_if_not_started();
            $_SESSION['register_error'] = "Valitsemasi käyttäjänimi on jo käytössä";
            $_SESSION['registration_attempt'] = true;
            $stmt->close();
            header("Location: login");
            exit();
        } else {
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
        
            $stmt = $conn->prepare("INSERT INTO käyttäjät (username, email, user_password, verification_token) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $verificationToken);
            
            if ($stmt->execute()) {
                // sendVerificationEmail($email, $verificationToken);
                start_session_if_not_started();
                $_SESSION['register_success'] = "Käyttäjä lisätty järjestelmään, ole hyvä ja kirjaudu sisään";
                $_SESSION['registration_attempt'] = true;
                
                $stmt->close();
                header("Location: login");
                exit();
            } else {
                
                start_session_if_not_started();
                $_SESSION['register_error'] = "Virhe käyttäjän lisäämisessä";
                $_SESSION['registration_attempt'] = true;
                echo "Error: " . $stmt->error;
                $stmt->close();
            }
        }
    }
}