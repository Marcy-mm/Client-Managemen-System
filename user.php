<?php
    session_start();

    // Prevent back navigation after logout
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

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
              <?php if (isset($_GET['success'])) {?>
                    <div class="success" role="alert">
                        <?php echo stripcslashes($_GET['success']) ?>
                    </div>
                <?php }?>
<?php if (! empty($clients)) {
        ?>
           <table class="main-table">
<tr>
<th class="client_name">Name</th>
<th class="client_code">Client Code</th>
<th>No. of linked contacts</th>
<th></th> <!-- No heading for unlink -->

    </tr>
<?php foreach ($clients as $client) {?>

               <tr>
                <td><?php echo $client['name'] ?></td>
                <td><?php echo $client['client_code'] ?></td>
                <td><?php echo $client['linked_contacts'] ?></td>
<td>
    <?php if (! empty($client['contact_ids'])): ?>
        <a href="linked-contacts.php?client_id=<?php echo $client['client_id']?>" class="edit-btn">View/Unlink Contacts</a>
    <?php else: ?>
        No contacts linked
    <?php endif; ?>
</td>



            </tr>
<?php }?>
           </table>
            <?php } else {?>
<h3> No client(s) found</h3>
           <?php }?>
	</section>

	</div>
<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
	active.classList.add("active");
</script>
</body>
</html>
<?php } else {
        $em = "First Login";
        header("Location: login.php?error=$em");
        exit();
}
?>