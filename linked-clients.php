<?php
    session_start();

    if (! isset($_GET['contact_id'])) {
        header("Location: contacts.php?error=" . urlencode("Contact not found."));
        exit();
    }

    include "DB_connection.php";

    // Fetch contact name
    $contact_id = $_GET['contact_id'];
    $stmt       = $conn->prepare("SELECT name, surname FROM contacts WHERE contact_id = ?");
    $stmt->execute([$contact_id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $contact) {
        header("Location: contacts.php?error=" . urlencode("Contact not found."));
        exit();
    }

    // Fetch linked clients
    $stmt = $conn->prepare("
    SELECT cl.client_id, cl.name, cl.client_code
    FROM clients cl
    JOIN client_contact cc ON cc.client_id = cl.client_id
    WHERE cc.contact_id = ?
");
    $stmt->execute([$contact_id]);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Linked Clients</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "inc/header.php"; ?>
<div class="body">
    <?php include "inc/nav.php"; ?>
    <section class="section-1">
        <h4 class="title">Linked Clients for Contact:                                                                                                                                                                                                                                                                                                                                                                                    <?php echo htmlspecialchars($contact['name'] . " " . $contact['surname']); ?>
            <a href="contacts.php" class="btn">Back to Contacts</a>
        </h4>

        <?php if (isset($_GET['success'])): ?>
            <div class="success" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (count($clients) > 0): ?>
            <table class="main-table">
                <tr>
                    <th>Client Name</th>
                    <th>Client Code</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['name']); ?></td>
                        <td><?php echo htmlspecialchars($client['client_code']); ?></td>
                        <td>
                            <a href="unlink-client.php?contact_id=<?php echo $contact_id; ?>&client_id=<?php echo $client['client_id']; ?>"
                               onclick="return confirm('Unlink this client?')" class="edit-btn">Unlink</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No clients linked to this contact.</p>
        <?php endif; ?>
    </section>
</div>
<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");
</script>
</body>
</html>
