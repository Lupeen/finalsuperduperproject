<!DOCTYPE html>
<html>
<head>
    <title>View Ponies</title>
    <style>
        /* CSS styling */
    </style>
</head>
<body>
    <?php
    // Database configuration
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Include the template engine
    class TemplateEngine {
        public function render($template, $data = []) {
            extract($data);

            ob_start();
            require $template;
            $output = ob_get_clean();

            echo $output;
        }
    }

    // Handle actions (view, new, edit, delete)
    $action = isset($_GET['action']) ? $_GET['action'] : 'view';
    switch ($action) {
        case 'view':
            // Fetch all ponies from the database
            $sql = "SELECT * FROM Ponies";
            $result = $conn->query($sql);

            // Render the view template and pass the data
            $template = new TemplateEngine();
            $template->render(__FILE__, ['ponies' => $result]);
            break;

        case 'new':
            // Render the new template
            $template = new TemplateEngine();
            $template->render(__FILE__);
            break;

        case 'edit':
            // Retrieve the pony ID from the query parameters
            $ponyId = isset($_GET['id']) ? $_GET['id'] : '';

            if ($ponyId) {
                // Fetch the pony record from the database
                $sql = "SELECT * FROM Ponies WHERE id = $ponyId";
                $result = $conn->query($sql);

                // Render the edit template and pass the data
                $template = new TemplateEngine();
                $template->render(__FILE__, ['pony' => $result->fetch_assoc()]);
            } else {
                // Invalid pony ID
                echo "Invalid pony ID.";
            }
            break;

        case 'delete':
            // Retrieve the pony ID from the query parameters
            $ponyId = isset($_GET['id']) ? $_GET['id'] : '';

            if ($ponyId) {
                // Delete the pony record from the database
                $sql = "DELETE FROM Ponies WHERE id = $ponyId";
                $conn->query($sql);

                // Redirect to the view page
                header("Location: index.php?action=view");
                exit();
            } else {
                // Invalid pony ID
                echo "Invalid pony ID.";
            }
            break;

        default:
            echo "Invalid action.";
            break;
    }

    $conn->close();
    ?>

    <?php if ($action === 'view') : ?>
        <h1>View Ponies</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $ponies->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="index.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="index.php?action=delete&id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="index.php?action=new">Add New Pony</a></p>
    <?php elseif ($action === 'new') : ?>
        <h1>Add New Pony</h1>
        <!-- New pony form -->
    <?php elseif ($action === 'edit') : ?>
        <h1>Edit Pony</h1>
        <?php if ($pony) : ?>
            <!-- Edit pony form -->
        <?php else : ?>
            <p>Invalid pony ID.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
