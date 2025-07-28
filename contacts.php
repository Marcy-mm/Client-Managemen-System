<?php
    session_start();

    // Prevent back navigation after logout
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
        include "DB_connection.php";
        include "app/Model/Contact.php"; // Contains helper functions like get_clients_by_contact_id()
        $contacts = get_all_contacts($conn);

    ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Contacts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>
        <section class="section-1">
            <h4 class="title">View Contacts<a href="add-contact.php">Add Contact</a></h4>

                         <?php if (isset($_GET['success'])) {?>
                    <div class="success" role="alert">
                        <?php echo stripcslashes($_GET['success']) ?>
                    </div>
                <?php }?>

            <?php if (! empty($contacts)) {?>
                <table class="main-table">

                        <tr>
                             <th class="client_name">Full Name</th>
                            <th class="client_name">Email</th>
                            <th class="client_name" style="text-align: center;">No. of linked clients</th>
                            <th></th> <!-- No heading for unlink -->
                        </tr>


                    <?php foreach ($contacts as $contact) {?>

                        <tr>

                            <td><?php echo $contact['name'] . ' ' . $contact['surname'] ?></td>
                            <td><?php echo $contact['email'] ?></td>
                            <td>
                           <?php echo $contact['client_count']; ?>
                            </td>


                          <td>
    <?php if ($contact['client_count'] > 0): ?>
    <a href="linked-clients.php?contact_id=<?php echo $contact['contact_id']; ?>" class="edit-btn">
        View/ Unlink Clients
    </a>
<?php else: ?>
    <span>No Clients Linked</span>
<?php endif; ?>

</td>

                        </tr>
                    <?php }?>

                </table>
            <?php } else {?>
                <h3> No contact(s) found</h3>
            <?php }?>
        </section>
    </div>
    <script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");
</script>
</body>
</html>

<?php } else {
        $em = "First Login";
        header("Location: login.php?error=" . urlencode($em));
        exit();
}?>
