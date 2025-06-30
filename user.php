<?php
    session_start();
    if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

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
            <h4 class="title" >Manage Client <a href="add-client.php">Add Client</a></h4>
           <table class="main-table">
<tr>
<th>name</th>
<th>Client Code</th>
<th>No. of linked contacts</th>
<th>Action</th>
    </tr>

               <tr>
                <td> Marcelline M</td>
                <td>ABC1325</td>
                <td>2</td>
                <td>
                    <a href="">Edit</a>
                    <a href="">Delete</a>

                </td>
            </tr>

           </table>
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