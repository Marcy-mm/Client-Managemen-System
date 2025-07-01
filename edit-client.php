<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
        header("Location: user.php");
        exit();
    }
    
    $id = $_GET['id'];
    $client = get_client_by_id($conn, $id);

    if ($client == 0) {
        header("Location: user.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">Edit Client <a href="user.php">Back to Users</a></h4>
            
            <form class="form-1" method="POST" action="app/update-user.php">
                <?php if (isset($_GET['error'])) { ?>
                    <div class="danger" role="alert">
                        <?= stripcslashes($_GET['error']) ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['success'])) { ?>
                    <div class="success" role="alert">
                        <?= stripcslashes($_GET['success']) ?>
                    </div>
                <?php } ?>

                <div class="input-holder">
                    <label>Client Name</label>
                    <input type="text" name="clientName" class="input-1" value="<?= htmlspecialchars($client['name']) ?>" placeholder="Client Name"><br>
                </div>
                
                <div class="input-holder">
                    <label>Client Code</label>
                    <input type="text" class="input-1" value="<?= htmlspecialchars($client['client_code']) ?>" disabled><br>
                </div>

                <div class="input-holder">
                    <label>No. of linked contacts</label>
                    <input type="text" name="linked_contacts" class="input-1" value="<?= $client['linked_contacts'] ?? 0 ?>" disabled><br>
                </div>

                <input type="text" name="id" value="<?= $client['client_id'] ?>" hidden>

                <button class="edit-btn">Update</button>
            </form>
        </section>
    </div>

    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(2)");
        active.classList.add("active");
    </script>
</body>
</html>
<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
