<nav class="side-bar">
			<div class="user-p">
				<img src="img/profile.jpg">
				<h4><?php echo $_SESSION['username'] ?></h4>
			</div>

			<?php

                if ($_SESSION['role'] == "employee") {
                ?>
			<!--Employee Navigation Bar-->
			<ul>
				<li>
					<a href="index.php">
						<i class="fa fa-tachometer" aria-hidden="true"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-users" aria-hidden="true"></i>
						<span>My Clients</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-user" aria-hidden="true"></i>
						<span>Profile</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-bell" aria-hidden="true"></i>
						<span>Notifications</span>
					</a>
				</li>
				<li>
					<a href="logout.php">
						<i class="fa fa-sign-out" aria-hidden="true"></i>
						<span>Logout</span>
					</a>
				</li>
			</ul>
			<?php } else {?>
				<!--Admin Navigation Bar-->
					<ul id="navList">
				<li>
					<a href="index.php">
						<i class="fa fa-tachometer" aria-hidden="true"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li >
					<a href="user.php">
						<i class="fa fa-users" aria-hidden="true"></i>
						<span>Manage Clients</span>
					</a>
				</li>
				<li>
					<a href="add-client.php">
						<i class="fa fa-plus" aria-hidden="true"></i>
						<span>Create Client</span>
					</a>
				</li>
				<li>
					<a href="contacts.php">
						<i class="fa fa-list" aria-hidden="true"></i>
						<span>Manage Contacts</span>
					</a>
				</li>
				<li>
					<a href="add-contact.php">
						<i class="fa fa-plus" aria-hidden="true"></i>
						<span>Create Contacts</span>
					</a>
				</li>
				<li>
					<a href="link-contact.php">
						<i class="fa fa-link" aria-hidden="true"></i>
						<span>Link Contacts/<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Clients</span>
					</a>
				</li>
				<li>

				</li>
			</ul>
			<?php }?>
		</nav>