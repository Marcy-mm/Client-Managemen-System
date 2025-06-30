<?php
    session_start();
    if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
        include "DB_connection.php";
        include "app/Model/User.php";
        $clients = get_all_clients($conn);

    ?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Users</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
<?php include "inc/header.php"?>
	<div class="body">
		<?php include "inc/nav.php"?>
		<section class="section-1">
            <h4 class="title" >Manage Clients <a href="add-client.php">Add Client</a></h4>
            <?php if ($clients != 0) {
                    ?>
           <table class="main-table">
<tr>
<th>name</th>
<th>Client Code</th>
<th>No. of linked contacts</th>
<th>Action</th>
    </tr>
<?php foreach ($clients as $client) {?>

               <tr>
                <td><?php echo $client['client_name']?></td>
                <td><?php echo $client['client_code']?></td>
                <td><?php echo $client['linked_contacts']?></td>
                <td>
                    <a href="edit-client.php?id=<?=$client['client_id']?>" class="edit-btn">Edit</a>
                    <a href="delete-client.php?id=<?=$client['client_id']?>" class="delete-btn">Delete</a>

                </td>
            </tr>
<?php }?>
           </table>
            <?php } else {?>
<h3> No client(s) found</h3>
           <?php }?>
	</section>

	</div>

</body>
</html>
<?php } else {
        $em = "First Login";
        header("Location: login.php?error=$em");
        exit();
}
?>