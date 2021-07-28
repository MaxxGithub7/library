<?php
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/util.php';
?>

    <h1>Registra nuovo utente</h1>
    <?php
    if (isset($_GET['stato'])) {
        \DataHandling\Utils\show_alert('registrazione', $_GET['stato']);
    }
    ?>
    <form method="POST" action="includes/registrazione.php" class="container">
      <div class="col">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control">
      </div>
      <div class="col">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <div class="col">
        <label for="password-check" class="form-label">Ripeti Password</label>
        <input type="password" name="password-check" id="password-check" class="form-control">
      </div>
      <input type="submit" class="btn btn-primary" value="Registrati">
    </form>
  </main>
</body>
</html>
