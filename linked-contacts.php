<?php
    session_start();

    if (! isset($_GET['client_id'])) {
        header("Location:user.php?error=Client not found");
        exit();
    }

    include "DB_connection.php";

    // Fetch client name
    $client_id = $_GET['client_id'];
    $stmt      = $conn->prepare("SELECT name FROM clients WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $client) {
        header("Location: user.php?error=Client not found");
        exit();
    }

    // Fetch linked contacts
    $stmt = $conn->prepare("
    SELECT c.contact_id, c.name, c.email
    FROM contacts c
    JOIN client_contact cc ON cc.contact_id = c.contact_id
    WHERE cc.client_id = ?
");
    $stmt->execute([$client_id]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Linked Contacts</title>
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
     <?php include "inc/header.php"?>
<div class="body">
   <?php include "inc/nav.php"?>
    <section class="section-1">

        <h4 class="title">Linked Contacts for Client:<?php echo htmlspecialchars($client['name']) ?> <a href="user.php" class="btn" style="margin-bottom: 20px;">Back to Clients</a></h4>
   <?php if (isset($_GET['success'])) {?>
                    <div class="success" role="alert">
                       <?php echo htmlspecialchars($_GET['success']) ?>
                    </div>
                <?php }?>

        <?php if (count($contacts) > 0): ?>
            <table class="main-table">
                <tr>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']) ?></td>
                        <td><?php echo htmlspecialchars($contact['email']) ?></td>
                        <td>
                            <a href="unlink-contact.php?client_id=<?php echo $client_id ?>&contact_id=<?php echo $contact['contact_id'] ?>"
                               onclick="return confirm('Are you sure you want to unlink this contact?')"
                               class="edit-btn">Unlink</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No contacts linked to this client.</p>
        <?php endif; ?>
    </section>
</div>
<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
	active.classList.add("active");
</script>
</body>
</html>
