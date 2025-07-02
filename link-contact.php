<?php
    session_start();
    if (isset($_SESSION['role'], $_SESSION['id'])) {
        include "DB_connection.php";
        include "app/Model/Contact.php";

        // Get all clients
        $clients = get_all_clients($conn);

        // Get contacts not already linked
        $unlinked_contacts = get_unlinked_contacts($conn);
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Link Client to Contact</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<input type="checkbox" id="checkbox">
<?php include "inc/header.php"; ?>
<div class="body">
    <?php include "inc/nav.php"; ?>
    <section class="section-1">
        <h4 class="title">
            Link Contact to Client
            <a href="user.php">View Clients</a>
            <a href="contacts.php">View Contacts</a>
        </h4>

        <?php if (isset($_GET['error'])): ?>
            <div class="danger" id="error-box"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form class="form-1" method="POST" action="app/link-contact.php">
            <div class="input-holder">
                <label>Client</label><br>
                <select name="client_id" class="input-1" >
                    <option value="" disabled selected>Select Client</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['client_id'] ?>"><?php echo htmlspecialchars($client['name']) ?> (<?php echo $client['client_code'] ?>)</option>
                    <?php endforeach; ?>
                </select><br><br>
            </div>

            <div class="input-holder">
                <label>Contact</label><br>
                <select name="contact_id" class="input-1" >
                    <option value="" disabled selected>Select Contact</option>
                    <?php foreach ($unlinked_contacts as $contact): ?>
                        <option value="<?php echo $contact['contact_id'] ?>"><?php echo htmlspecialchars($contact['name'] . ' ' . $contact['surname']) ?></option>
                    <?php endforeach; ?>
                </select><br><br>
            </div>

            <button class="edit-btn" type="submit">Link</button>
        </form>
    </section>
</div>
<script>
document.getElementById('linkForm').addEventListener('submit', function(e) {
  const client  = this.client_id;
  const contact = this.contact_id;
  const errorBox = document.getElementById('errorBox');

  // clear any old message
  errorBox.style.display = 'none';
  errorBox.textContent   = '';

  if (client.value === "") {
    e.preventDefault();
    errorBox.textContent = "Please select a client.";
    errorBox.style.display = 'block';
    return;
  }
  if (contact.value === "") {
    e.preventDefault();
    errorBox.textContent = "Please select a contact.";
    errorBox.style.display = 'block';
    return;
  }
});
</script>

</body>
</body>
</html>
<?php
    } else {
        header("Location: login.php?error=" . urlencode("First login"));
        exit();
    }
?>
