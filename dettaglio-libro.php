<?php
include_once __DIR__ . '/includes/globals.php';
$args     = array(
    'id' => $_GET['id'],
    'userId' => $_SESSION['userId']
);
$libro = \DataHandling\Library::selectData($args);
if (count($libro) > 0) :
    ?>
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center" >
    <div class="d-inline-block me-3 rounded bg-light overflow-hidden">
    <h1 class="d-inline-block"><?php echo $libro[0]['Titolo']; ?></h1>
    </div>
    <h1 class="d-inline-block"><?php echo $libro[0]['Autore']; ?></h1>
    </div>
    <div>
      <a class="btn btn-primary" href="/library/modifica-libro.php?id=<?php echo $_GET['id']; ?>">
        Modifica Libro
      </a>
      <a
        class="btn btn-outline-danger"
        href="/library/includes/cancella-libro.php?id=<?php echo $_GET['id']; ?>">
        Cancella Libro
      </a>
    </div>
  </div>
  <hr />
  <table class="table table-striped table-dark table-hover table-bordered table-responsive">
    <thead>
    <?php echo \DataHandling\Utils\get_table_head($libro[0]); ?>
    </thead>
    <tbody>
    <?php echo \DataHandling\Utils\get_table_body($libro); ?>
    </tbody>
  </table>
<?php else : ?>
  <h1>Libro non trovato!</h1>
  <p>Spiacente, il libro con ID <?php echo $_GET['id']; ?> non esiste o è stato rimosso.
  <a class="link-light" href="/library">Torna alla lista dei libri</a></p>
<?php endif; ?>
  </main>
</body>
</html>




