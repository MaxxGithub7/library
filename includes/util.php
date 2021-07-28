<?php
namespace DataHandling\Utils;

function get_table_head($assoc_array)
{
    $keys = array_keys($assoc_array);
    $html = '';

    foreach ($keys as $key) {
        $html .= '<th scope="col">' . ucwords($key) . '</th>';
    }
    if (!isset($_GET['id'])) {
        $html .= '<th></th>';
    }
    return $html;
}

function get_table_body($items)
{
    $html = '';

    foreach ($items as $row) {
        $html .= '<tr>';
        foreach ($row as $key => $value) {
            if ($key === 'Titolo') {
                if (isset($_GET['id'])) {
                    $html .= "<td>$value</td>";
                } else {
                    $html .= "<td><a href='/library/dettaglio-libro.php?id={$row['ID']}'
                    class='text-decoration-none link-light'>$value</a></td>";
                }
            } elseif ($key === 'Autore') {
                $html .= "<td><a href='tel:$value' class='text-decoration-none link-light'>$value</a></td>";
            } else {
                $html .= "<td>$value</td>";
            }
        }
        if (!isset($_GET['id'])) {
            // phpcs:disable
            $html .= '<td><a class="text-decoration-none link-light" href="/library/includes/cancella-libro.php?id=' . $row['ID'] . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill-rule="evenodd" d="M16 1.75V3h5.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H8V1.75C8 .784 8.784 0 9.75 0h4.5C15.216 0 16 .784 16 1.75zm-6.5 0a.25.25 0 01.25-.25h4.5a.25.25 0 01.25.25V3h-5V1.75z"></path><path d="M4.997 6.178a.75.75 0 10-1.493.144L4.916 20.92a1.75 1.75 0 001.742 1.58h10.684a1.75 1.75 0 001.742-1.581l1.413-14.597a.75.75 0 00-1.494-.144l-1.412 14.596a.25.25 0 01-.249.226H6.658a.25.25 0 01-.249-.226L4.997 6.178z"></path><path d="M9.206 7.501a.75.75 0 01.793.705l.5 8.5A.75.75 0 119 16.794l-.5-8.5a.75.75 0 01.705-.793zm6.293.793A.75.75 0 1014 8.206l-.5 8.5a.75.75 0 001.498.088l.5-8.5z"></path></svg></a><a class="text-decoration-none link-light" href="/library/modifica-libro.php?id=' . $row['ID'] . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill-rule="evenodd" d="M17.263 2.177a1.75 1.75 0 012.474 0l2.586 2.586a1.75 1.75 0 010 2.474L19.53 10.03l-.012.013L8.69 20.378a1.75 1.75 0 01-.699.409l-5.523 1.68a.75.75 0 01-.935-.935l1.673-5.5a1.75 1.75 0 01.466-.756L14.476 4.963l2.787-2.786zm-2.275 4.371l-10.28 9.813a.25.25 0 00-.067.108l-1.264 4.154 4.177-1.271a.25.25 0 00.1-.059l10.273-9.806-2.94-2.939zM19 8.44l2.263-2.262a.25.25 0 000-.354l-2.586-2.586a.25.25 0 00-.354 0L16.061 5.5 19 8.44z"></path></svg></a></td>';
            // phpcs:enable
        }
        $html .= '</tr>';
    }

    return $html;
}

function show_alert($action_type, $state)
{
    if ($state === 'ko') {
      echo '<div class="alert alert-danger" role="alert">Ops! C\'è stato un problema, riprova nuovamente.</div>';
      return false;
    }

    if ($state === "errore") {
      echo '<div class="alert alert-danger" role="alert"><ul>';
      $error_messages = explode('|', $_GET['messages']);
      foreach ($error_messages as $error) {
          echo "<li>$error</li>";
      }
      echo '</ul></div>';
    }

    if ($action_type === 'cancellazione' && $state === 'ok') {
      echo '<div class="alert alert-success" role="alert">Libro eliminato con successo.</div>';
    } elseif ($action_type === 'modifica' && $state === 'ok') {
      echo '<div class="alert alert-success" role="alert">Libro aggiornato con successo.</div>';
    } elseif ($action_type === 'inserimento' && $state === 'ok') {
      echo '<div class="alert alert-success" role="alert">Libro inserito con successo.</div>';
    }
}

trait InputSanitize
{
    public static function cleanInput($data)
    {
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_ADD_SLASHES);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
}
