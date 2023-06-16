

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the dashboard
    header("Location: index.php");
    exit(); 
}
// var_dump(isset($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <!-- CSS and other head elements -->
    <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
    <style>
        .fixed-image {
            height: 200px;
            /* Adjust the desired height */
            object-fit: cover;
        }
    </style>
</head>

<body>

    <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="sortSelect" class="form-label">Sort by:</label>
                <select id="sortSelect" class="form-select" onchange="sortUsers(this)">
                    <option value="">Default</option>
                    <option value="username">Username</option>
                    <option value="email">Email</option>
                    <option value="added">Added</option>
                </select>
            </div>
        </div>

        <button type="button" class="btn btn-primary mb-3" data-mdb-toggle="modal"
            data-mdb-target="#addUserModal">Add User</button>
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Active</th>
                    <th>Added</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("./db_config.php");

                // Function to escape user input for security
                function escape($value)
                {
                    global $conn;
                    return $conn->real_escape_string($value);
                }

                // Retrieve the user details with optional sorting
                function getUsers($sortCriteria = "")
                {
                    global $conn;

                    $selectQuery = "SELECT id, username, email, password, active, added FROM users";
                    if (!empty($sortCriteria)) {
                        $allowedCriteria = array("username", "email", "added");
                        if (in_array($sortCriteria, $allowedCriteria)) {
                            $selectQuery .= " ORDER BY " . $sortCriteria;
                        }
                    }

                    $result = $conn->query($selectQuery);
                    return $result;
                }

                // Sort and retrieve the user details
                $sortCriteria = isset($_GET['sort']) ? $_GET['sort'] : "";
                $result = getUsers($sortCriteria);

                // Loop through the users and display rows in the table
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $username = $row['username'];
                    $email = $row['email'];
                    $active = $row['active'];
                    $added = $row['added'];
                    ?>
                    <tr>
                        <td>
                            <?php echo $id; ?>
                        </td>
                        <td>
                            <?php echo $username; ?>
                        </td>
                        <td>
                            <?php echo $email; ?>
                        </td>
                        <td>
                            <?php echo $active; ?>
                        </td>
                        <td>
                            <?php echo $added; ?>
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-primary me-2" onclick="editUser(<?php echo $id; ?>)">Edit</button>
                                <form method="post" action="delete_user.php"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your form elements for adding a new user -->
                        <form method="POST" action="add_user.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="active" class="form-label">Active</label>
                                <select class="form-select" id="active" name="active" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                           
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your form elements for editing a user -->
                        <form method="POST" action="update_user.php">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="editPassword" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="editActive" class="form-label">Active</label>
                                <select class="form-select" id="editActive" name="active" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function sortUsers(select) {
            var sortCriteria = select.value;
            window.location.href = "dashboard.php?sort=" + sortCriteria;
        }

        function editUser(userId) {
            // Fetch the user details from the server and populate the edit user modal fields
            fetch("get_user.php?id=" + userId)
                .then(response => response.json())
                .then(user => {
                    document.getElementById("editUserId").value = user.id;
                    document.getElementById("editUsername").value = user.username;
                    document.getElementById("editEmail").value = user.email;
                    document.getElementById("editPassword").value = user.password;
                    document.getElementById("editActive").value = user.active;

                    // Show the edit user modal
                    var modal = new mdb.Modal(document.getElementById("editUserModal"));
                    modal.show();
                });
        }
    </script>
</body>

</html>
