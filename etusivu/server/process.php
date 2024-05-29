<?php
// Include MongoDB library
require '../../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

function start_session_if_not_started() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function setUserToken($user_id, $token, $collection) {
    $updateResult = $collection->updateOne(
        ['_id' => $user_id],
        ['$set' => ['token' => $token]]
    );
    return $updateResult->getModifiedCount() > 0;
}

function validatePassword($password) {
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    }

    if (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must include at least one special character.";
    }

    return $errors;
}

if (isset($_POST['email-username_1'], $_POST['password_1'])) {
    $login_input = $_POST['email-username_1'];
    $user_password = $_POST['password_1'];

    $collection = $db->users;
    $user = $collection->findOne(['$or' => [['email' => $login_input], ['username' => $login_input]]]);

    if ($user && password_verify($user_password, $user['user_password'])) {
        start_session_if_not_started();
        if (isset($_POST['muista_minut'])) {
            $token = generateToken();
            setUserToken($user['_id'], $token, $collection);
            setcookie('auth_token', $token, time() + (86400 * 30), "/"); // Cookie valid for 30 days
        }
        if ($user['is_admin'] == 1) {
            $_SESSION['admin'] = true;
            header("Location: ../../admin");
        } else {
            $_SESSION['user'] = $user['_id'];
            header("Location: ../../etusivu");
        }
        exit();
    } else {
        start_session_if_not_started();
        $_SESSION['login_error'] = "Väärä sähköposti/käyttäjänimi tai salasana";
        header("Location: ../../etusivu");
        exit();
    }
}

if (isset($_POST['name_2'], $_POST['email_2'], $_POST['password_2'])) {
    $username = $_POST['name_2'];
    $email = $_POST['email_2'];
    $user_password = $_POST['password_2'];
    $verificationToken = generateToken();

    $passwordErrors = validatePassword($user_password);

    if (!empty($passwordErrors)) {
        start_session_if_not_started();
        $_SESSION['register_error'] = implode("\n", $passwordErrors);
        $_SESSION['registration_attempt'] = true;
        header("Location: ../etusivu");
        exit();
    }

    $collection = $db->users;

    $existingUserByEmail = $collection->findOne(['email' => $email]);
    if ($existingUserByEmail) {
        start_session_if_not_started();
        $_SESSION['register_error'] = "Valitsemasi sähköposti on jo käytössä";
        $_SESSION['registration_attempt'] = true;
        header("Location: ../etusivu");
        exit();
    }

    $existingUserByUsername = $collection->findOne(['username' => $username]);
    if ($existingUserByUsername) {
        start_session_if_not_started();
        $_SESSION['register_error'] = "Valitsemasi käyttäjänimi on jo käytössä";
        $_SESSION['registration_attempt'] = true;
        header("Location: ../etusivu");
        exit();
    }

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
    $insertResult = $collection->insertOne([
        'username' => $username,
        'email' => $email,
        'user_password' => $hashed_password,
        'verification_token' => $verificationToken
    ]);

    if ($insertResult->getInsertedCount() > 0) {
        start_session_if_not_started();
        $_SESSION['register_success'] = "Käyttäjä lisätty järjestelmään, ole hyvä ja kirjaudu sisään";
        $_SESSION['registration_attempt'] = true;
        header("Location: ../etusivu");
        exit();
    } else {
        start_session_if_not_started();
        $_SESSION['register_error'] = "Virhe käyttäjän lisäämisessä";
        $_SESSION['registration_attempt'] = true;
        header("Location: ../etusivu");
        exit();
    }
}
?>
