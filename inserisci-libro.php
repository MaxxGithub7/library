<?php
include_once __DIR__ . '/includes/globals.php';
//phpcs:ignore Generic.Files.LineEndings
if (isset($_GET['stato'])) {
    \DataHandling\Utils\show_alert('inserimento', $_GET['stato']);
}
?>
    <form action="includes/libri.php" method="POST" class="container">
    <div class="row mb-3">
      <div class="col">
        <label for="nome" class="form-label">Titolo</label>
        <input type="text" class="form-control" name="nome" id="nome" required>
      </div>
      <div class="col">
        <label for="autore" class="form-label">Autore</label>
        <input type="text" class="form-control" name="autore" id="autore" required>
      </div>
    </div>
    </div>

      <input type="submit" class="btn btn-primary" value="Aggiungi Libro">
    </form>
  </main>
</body>
</html>
