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
                $collection1 = $db->user_archive;

                // Fetch all documents from the collection
                $users = $collection->find();
                $users1 = $collection1->find();

                $admin = "";
                $selected = "";

                // Loop through each user and display its data
                foreach ($users as $user) {
                    if (isset($user['is_admin']) && $user['is_admin'] == 1) {
                        $admin = "Admin";
                        $selected = ($admin == "Admin") ? "selected" : "";
                    } else {
                        $admin = "User";
                        $selected = ($admin == "User") ? "selected" : "";
                    }

                    $userId = (string) $user['_id'];
                    
                    echo "<tr>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['username'] . "</td>";
                    echo "<td>[REDACTED]</td>";
                    echo "<td id='role-" . $user['_id'] . "'>" . $admin . "</td>";
                    echo "<td>";
                    echo "<a href=\"archive_user.php?id=" . $user['_id'] . "\"><i class=\"fas fa-archive\"></i></a>";
                    echo " | ";
                    echo "<a href=\"remove_user.php?id=" . $user['_id'] . "\"><i class=\"fas fa-trash\"></i></a>";
                    echo " | ";
                    echo "<a href=\"#\" onclick=\"editRole('" . $userId . "')\" id='edit-" . $user['_id'] . "'><i class=\"fas fa-edit\"></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }

                foreach ($users1 as $user) {
                    if (isset($user['is_admin']) && $user['is_admin'] == 1) {
                        $admin = "Admin";
                    } else {
                        $admin = "User";
                    }
                    
                    echo "<tr>";
                    echo "<td>" . $user['_id'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['username'] . "</td>";
                    echo "<td>[REDACTED]</td>";
                    echo "<td>";
                    echo $admin;
                    echo "</td>";
                    echo "<td>";
                    echo "<a href=\"restore_user.php?id=" . $user['_id'] . "\"><i class=\"fas fa-arrow-rotate-left\"></i></a>";
                    echo " | ";
                    echo "<a href=\"remove_user_archive.php?id=" . $user['_id'] . "\"><i class=\"fas fa-trash\"></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>    
        function editRole(userId) {
            // Get the current role text
            var roleElement = document.getElementById('role-' + userId);
            var currentRole = roleElement.innerText;

            // Create the select element
            var selectElement = document.createElement("select");
            selectElement.id = "select-role-" + userId;

            // Add options to the select element
            var options = ["User", "Admin"];
            options.forEach(function(option) {
                var optionElement = document.createElement("option");
                optionElement.value = option;
                optionElement.text = option;
                if (option == currentRole) {
                    optionElement.selected = true;
                }
                selectElement.appendChild(optionElement);
            });

            // Replace the role text with the select element
            roleElement.innerHTML = "";
            roleElement.appendChild(selectElement);

            var saveButton = document.createElement("a");
            saveButton.id = "save-" + userId;
            saveButton.innerHTML = "<i class=\"fas fa-save\"></i>";
            saveButton.addEventListener("click", function() {
                saveRole(userId);
            });

            // Change the edit button to a save button
            var editButton = document.getElementById('edit-' + userId);
            editButton.innerHTML = "";
            editButton.appendChild(saveButton);
        }

        function saveRole(userId) {
            // Get the selected role
            var selectElement = document.getElementById('select-role-' + userId);
            var selectedRole = selectElement.value;

            // Create a form and submit it
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "change_role.php";

            var inputElement = document.createElement("input");
            inputElement.type = "hidden";
            inputElement.name = "userId";
            inputElement.value = userId;
            form.appendChild(inputElement);

            var roleInputElement = document.createElement("input");
            roleInputElement.type = "hidden";
            roleInputElement.name = "role";
            roleInputElement.value = selectedRole;
            form.appendChild(roleInputElement);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>