<?php
namespace DataHandling;

use \DataHandling\Utils\InputSanitize;
use Mysqli;

class Utenti
{
    use \DataHandling\Utils\InputSanitize;
    public static function registerUser($form_data)
    {

        $fields = array(
        'username'        => $form_data['username'],
        'password'        => $form_data['password'],
        'password-check'  => $form_data['password-check']
        );

        $fields = self::sanitize($fields);

        if ($fields['password'] !== $fields['password-check']) {
          //phpcs:disable
            header('Location: https://localhost/library/registrazione.php?stato=errore&messages=Le password non corrispondono');
            exit;
          //phpcs:enable
        }

        $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

        if ($mysqli->connect_errno) {
            echo 'Connessione al database fallita: ' . $mysqli->connect_error;
            exit();
        }

        $query_user = $mysqli->query("SELECT username FROM utenti WHERE username = '" . $fields['username'] . "'");

        if ($query_user->num_rows > 0) {
            header('Location: https://localhost/library/registrazione.php?stato=errore&messages=Username già preso');
            exit;
        }

        $query_user->close();

        $query = $mysqli->prepare('INSERT INTO utenti(username, password) VALUES (?, MD5(?))');
        $query->bind_param('ss', $fields['username'], $fields['password']);
        $query->execute();

        if ($query->affected_rows === 0) {
            error_log('Error MySQL: ' . $query->error_list[0]['error']);
            header('Location: https://localhost/library/registrazione.php?stato=ko');
            exit;
        }

        header('Location: https://localhost/library/registrazione.php?stato=ok');
        exit;
    }

    public static function loginUser($form_data)
    {

        $fields = array(
        'username'  => $_POST['username'],
        'password'  => $_POST['password']
        );

        $fields = self::sanitize($fields);

        $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

        if ($mysqli->connect_errno) {
            echo 'Connessione al database fallita: ' . $mysqli->connect_error;
            exit();
        }

        $query_user = $mysqli->query("SELECT * FROM utenti WHERE username = '" . $fields['username'] . "'");

        if ($query_user->num_rows === 0) {
            header('Location: https://localhost/library/login.php?stato=errore&messages=Utente non presente');
            exit;
        }

        $user = $query_user->fetch_assoc();

        if ($user['password'] !== md5($fields['password'])) {
            header('Location: https://localhost/library/login.php?stato=errore&messages=Password errata');
            exit;
        }

        return array(
        'id'  => $user['id'],
        'username' => $user['username']
        );
    }

    public static function deleteUser($userId) {

      $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

      if ($mysqli->connect_errno) {
          echo 'Connessione al database fallita: ' . $mysqli->connect_error;
          exit();
      }

      $query = $mysqli->prepare( 'DELETE FROM utenti WHERE ID = ?' );
      $query->bind_param('i', $userId);
      $query->execute();

      if ($query->affected_rows === 0) {
        error_log('Error MySQL: ' . $query->error_list[0]['error']);
        header('Location: https://localhost/library/admin.php?stato=ko');
        exit;
      }

      header('Location: https://localhost/library/includes/login.php?logout=1');
      exit;

    }

    protected static function sanitize($fields)
    {
        $fields['username'] = self::cleanInput($fields['username']);

        return $fields;
    }
}
