<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container-fluid">
        <!-- Content -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include 'connect.php';

                // $sql = "SELECT * FROM users";
                // $result = $conn->query($sql);

                // while ($row = $result->fetch_assoc()) {
                //     echo '<tr>';
                //     echo '<td>' . $row['id'] . '</td>';
                //     echo '<td>' . $row['name'] . '</td>';
                //     echo '<td>' . $row['email'] . '</td>';
                //     echo '<td>' . $row['role'] . '</td>';
                //     echo '</tr>';
                // }

                // $conn->close();
                ?>

                <!-- Dummy data to demonstrate -->
                <tr>
                    <td>1</td>
                    <td>User 1</td>
                    <td>Admin</td>
                    <td><a href="remove_user.php?id=1"><i class="fas fa-trash"></i></a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>User 2</td>
                    <td>User</td>
                    <td><a href="remove_user.php?id=2"><i class="fas fa-trash"></i></a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>User 3</td>
                    <td>User</td>
                    <td><a href="remove_user.php?id=3"><i class="fas fa-trash"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>