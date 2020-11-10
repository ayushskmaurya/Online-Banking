<head>
  <link rel="stylesheet" href="styles/header-styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet">
</head>

<body>
  <div class="header">
    <div class="logo-name">
      <a class="logo" href="index.php">₹</a>     
      <a class="name" href="index.php">MVMT Bank</a>
    </div>

    <div class="login">
      <?php
        if(isset($_SESSION['login']))
          echo "<a class='login' href='logout.php'>Logout</a>";
        else
          echo "<a class='login' href='login.php'>Login</a>";
      ?>
    </div>

    <div class="create">
      <a class="create" href="create.php">Create Account</a>
    </div>
  </div>

  <div class="user">
    <?php
      if(isset($_SESSION['login']))
          echo "<marquee><p>".$_SESSION['login']."</p></marquee>";
    ?>
  </div>
</body>
