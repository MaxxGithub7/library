<?php
include __DIR__ . '/includes/globals.php';

$libri = \DataHandling\Library::selectData(array( 'userId' => $_SESSION['userId'] ));

if (isset($_GET['statocanc'])) {
    \DataHandling\Utils\show_alert('cancellazione', $_GET['statocanc']);
}
if (count($libri) > 0) :
    ?>

<table class="table table-striped table-dark table-hover table-bordered table-responsive">
  <thead>
    <?php echo \DataHandling\Utils\get_table_head($libri[0]); ?>
  </thead>
  <tbody>
    <?php echo \DataHandling\Utils\get_table_body($libri); ?>
  </tbody>
</table>
<?php else : ?>
  <p class="alert alert-dark" role="alert">Nessun libro da mostrare,
  <a href="/library/inserisci-libro.php">vuoi aggiungerne uno?</a></p>
<?php endif; ?>
  </main>
</body>
</html>
