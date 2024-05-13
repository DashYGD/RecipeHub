<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container-fluid">
        <!-- Content -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include MongoDB library
                require '../vendor/autoload.php';

                // Connect to MongoDB
                $mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
                $db = $mongoClient->reseptisovellus;

                // Select the users collection
                $collection = $db->users;

                // Fetch all documents from the collection
                $users = $collection->find();

                $admin = "";

                // Loop through each user and display its data
                foreach ($users as $user) {
                    if (isset($user['is_admin']) == 1) {
                        $admin = "Admin";
                    } else {
                        $admin = "User";
                    }
                    
                    echo "<tr>";
                    echo "<td>" . $user['_id'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['username'] . "</td>";
                    echo "<td>[REDACTED]</td>";
                    echo "<td>" . $admin . "</td>";
                    echo "<td>";
                    echo "<a href=\"edit_user.php?id=" . $user['_id'] . "\"><i class=\"fas fa-edit\"></i></a>";
                    echo " | ";
                    echo "<a href=\"remove_user.php?id=" . $user['_id'] . "\"><i class=\"fas fa-trash\"></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }

                echo "<tr>";
                echo "<td colspan=\"6\">";
                echo "<a href=\"add_user.php\">Add User</a>";
                echo "</td>";
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>