<?php
    session_start();
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

            <?php if ($contacts != 0) {?>
                <table class="main-table">

                        <tr>
                             <th class="client_name">Full Name</th>
                            <th class="client_name">Email</th>
                            <th class="client_name">Client(s)</th>
                            <th></th> <!-- No heading for unlink -->
                        </tr>


                    <?php foreach ($contacts as $contact) {?>

                        <tr>

                            <td><?php echo $contact['name'] . ' ' . $contact['surname'] ?></td>
                            <td><?php echo $contact['email'] ?></td>
                            <td>

                                       <?php echo ! empty($contact['client_codes']) ? $contact['client_codes'] : 'No Clients Linked'; ?>

                            </td>
                          <td>
    <?php
        if (! empty($contact['client_codes']) && ! empty($contact['client_ids'])):
                    $codes = explode(', ', $contact['client_codes']);
                    $ids   = array_map('trim', explode(',', $contact['client_ids']));

                for ($i = 0; $i < count($codes); $i++): ?>
	            <div>
	                
	             <a
  href="unlink-client.php?contact_id=<?= $contact['contact_id'] ?>&client_id=<?= $ids[$i] ?>"
  onclick="return confirm('Unlink this client?')"
  class="edit-btn"
>
  Unlink
</a>
	            </div>
	        <?php endfor;
                        else:
                            echo 'No Clients Linked';
                        endif;
                    ?>
</td>

                        </tr>
                    <?php }?>

                </table>
            <?php } else {?>
                <p>No contacts found.</p>
            <?php }?>
        </section>
    </div>
</body>
</html>

<?php } else {
        $em = "First Login";
        header("Location: login.php?error=" . urlencode($em));
        exit();
}?>
