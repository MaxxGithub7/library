<?php
include_once __DIR__ . '/includes/globals.php';
$args = array(
    'id' => $_GET['id'],
    'userId' => $_SESSION['userId']
);
$libro = \DataHandling\Library::selectData($args);
if (count($libro) > 0) :
    if (isset($_GET['stato'])) {
        \DataHandling\Utils\show_alert('modifica', $_GET['stato']);
    }
    ?>
            <form action="includes/libri.php?id=<?php echo $libro[0]['ID']; ?>" method="POST" class="container">
            <div class="row mb-3">
              <div class="col">
                <label for="nome" class="form-label">Titolo</label>
                <input type="text" class="form-control" name="nome" id="nome"
                value="<?php echo $libro[0]['Titolo']; ?>" required>
              </div>
            </div>
            <div class="col">
                <label for="nome" class="form-label">Autore</label>
                <input type="text" class="form-control" name="nome" id="nome"
                value="<?php echo $libro[0]['Autore']; ?>" required>
              </div>
              </div>
            </div>
              <input type="submit" class="btn btn-primary" value="Aggiorna Libro">
            </form>
<?php else : ?>
  <h1>Libro non trovato!</h1>
  <p>Spiacente, il libro con ID <?php echo $_GET['id']; ?> non esiste o è stato rimosso.
  <a class="link-light" href="/library">Torna alla lista dei libri</a></p>
<?php endif;?>
  </main>
</body>
</html>
