<?php
include __DIR__ . '/includes/globals.php';
?>

    <a href="includes/cancella-libro.php" class="btn btn-primary">Cancella Libro</a>
    <a href="includes/cancella-utente.php" class="btn btn-primary">Cancella Account</a>
    <a href="#" id="export" class="btn btn-primary">Esporta Libri</a>


  <script>
  function exportCSV(e) {
    e.preventDefault();
    const conferma = confirm("Vuoi esportare tutti i libri?");
    if (conferma) {
      window.open("includes/export.php", '_blank');
    }
  }
  document.getElementById("export").addEventListener("click", exportCSV);
  </script>
  </main>
</body>
</html>
