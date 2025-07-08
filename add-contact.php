<?php
    session_start();
    // Prevent back navigation after logout
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (isset($_SESSION['role'], $_SESSION['id'])) {
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Contact</title>
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
                Create Contact
                <a href="contacts.php">Back to Contacts</a>
            </h4>

           <?php if (isset($_GET['error'])) {?>
    <div id="errorBox" class="danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
   </div>
<?php
}?>
<?php if (isset($_GET['success'])) {?>

    <div class="success" role="alert">
        <?php echo stripcslashes($_GET['success']); ?>
   </div>
<?php
}?>

            <!-- Create Contact Form -->
            <form class="form-1" method="POST" action="app/add-contact.php" id="createContactForm">
                <div class="input-holder">
                    <label for="name">First Name</label><br><br>
                    <input type="text"  name="name" id="name" class="input-1" placeholder="Enter first name"><br><br>
                </div>

                <div class="input-holder">
                    <label for="surname">Surname</label>
                    <input type="text" name="surname" id="surname"  class="input-1"   placeholder="Enter surname"><br><br>
                </div>

                <div class="input-holder">
                    <label for="email">Email Address</label><br>
                    <input type="email" name="email" id="email" class="input-1" placeholder="Enter email" ><br><br>
                </div>

                <button type="submit" class="edit-btn">

                    Create Contact
                </button>
            </form>



        </section>
    </div>

 <script>
    document.getElementById('createContactForm').addEventListener('submit', function(e){
const email = this.email.value.trim();
const errorBox = document.getElementById('errorBox');
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
errorBox.textContent ='';
    if(!re.test(email)){
        e.preventDefault();
        errorBox.textContent = 'Please enter a valid email address.';
        errorBox.style.display = 'block';

    }
}
);
    </script>
    <script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(5)");
	active.classList.add("active");
</script>
</body>
</html>
<?php
    } else {
        header("Location: login.php?error=" . urlencode("First login"));
        exit();
    }
?>
