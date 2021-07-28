<?php
namespace DataHandling;

use \DataHandling\Utils\InputSanitize;
use Mysqli;

class Libreria extends FormHandle
{

    protected static function sanitize($fields)
    {
        $errors        = array();
        $fields['nome'] = self::cleanInput($fields['nome']);
        
        $fields['telefono'] = self::cleanInput($fields['telefono']);
        if (self::isPhoneNumberValid($fields['telefono']) === 0) {
            $errors[] = new Exception('Numero di telefono non valido.');
        }


        
        if (isset($fields['email']) && $fields['email'] !== '') {
            $fields['email'] = self::cleanInput($fields['email']);
            if (! self::isEmailAddressValid($fields['email'])) {
                $errors[] = new Exception('Indirizzo email non valido.');
            }
        }

        if (isset($fields['indirizzo']) && $fields['indirizzo'] !== '') {
            $fields['indirizzo'] = self::cleanInput($fields['indirizzo']);
        }

        if (isset($fields['data_di_nascita']) && $fields['data_di_nascita'] !== '') {
            $fields['data_di_nascita'] = self::cleanInput($fields['data_di_nascita']);
            if (strtotime($fields['data_di_nascita'])) {
                
                $fields['data_di_nascita'] = date('Y-m-d', strtotime(str_replace('-', '/', $fields['data_di_nascita'])));
            } else {
                $errors[] = new Exception('Data di nascita non valida.');
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return $fields;
    }

    public static function insertData($form_data, $loggedInUserId)
    {
        $fields = array(
            'nome'           => $form_data['nome'],
            'telefono'       => $form_data['telefono'],
            'email'          => $form_data['email'],
            'indirizzo'      => $form_data['indirizzo'],
            'data_di_nascita'=> $form_data['data_di_nascita'],
        );

        $fields = self::sanitize($fields);

        if ($fields[0] instanceof Exception) {
            $error_messages = '';
            foreach ($fields as $key => $error) {
                $error_messages .= $error->getMessage();
                if ($key < count($fields) - 1) {
                    $error_messages .= '|';
                }
            }
            header('Location: https://localhost/library/inserisci-utente.php?stato=errore&messages='
             . $error_messages);
            exit;
        }

        if ($fields) {
            $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

            if ($mysqli->connect_errno) {
                echo 'Connessione al database fallita: ' . $mysqli->connect_error;
                exit();
            }

            $query = $mysqli->prepare('INSERT INTO users(Nome, Telefono, id_utente) VALUES (?, ?, ?)');
            $query->bind_param('ssi', $fields['nome'], $fields['telefono'], $loggedInUserId);
            $query->execute();

            if ($query->affected_rows === 0) {
                error_log('Errore MySQL: ' . $query->error_list[0]['error']);
                header('Location: https://localhost/library/inserisci-utente.php?stato=ko');
                exit;
            }

            $last_id = $query->insert_id;

            $query->close();

            $query_2    = $mysqli->prepare('INSERT INTO
            users(users_id, email, indirizzo, data_di_nascita)
            VALUES (?, ?, ?, ?, ?)');
            $email      = ( $fields['email'] !== '' ) ? $fields['email'] : null;
            $indirizzo  = ( $fields['indirizzo'] !== '' ) ? $fields['indirizzo'] : null;
            $data_di_nascita = ( $fields['data_di_nascita'] !== '' ) ? $fields['data_di_nascita'] : null;
            $query_2->bind_param('issss', $last_id, $email, $indirizzo, $data_di_nascita);
            $query_2->execute();

            if ($query_2->affected_rows === 0) {
                header('Location: https://localhost/library/inserisci-utente.php?stato=ko');
                exit;
            }

            header('Location: https://localhost/library/inserisci-utente.php?stato=ok');
            exit;
        }
    }

    public static function selectData($args = null)
    {
        $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

        if ($mysqli->connect_errno) {
            echo 'Connessione al database fallita: ' . $mysqli->connect_error;
            exit();
        }

        if (isset($args['id'])) {
            $args['id'] = intval($args['id']);
            $query      = $mysqli->prepare('SELECT users.ID, Nome, Telefono, email,
            indirizzo, data_di_nascita FROM users JOIN libri ON libri.ID=libri.libri_id
            WHERE libri.ID = ? AND libri.id_libro = ?');
            $query->bind_param('ii', $args['id'], $args['userId']);
            $query->execute();
            $query = $query->get_result();
        } else {
            $query = $mysqli->query('SELECT * FROM users WHERE id_user = ' . $args['userId']);
        }

        $results = array();

        while ($row = $query->fetch_assoc()) {
            $results[] = $row;
        }

        return $results;
    }

    public static function deleteData($id = null, $userId = null)
    {
      $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

      if ($mysqli->connect_errno) {
          echo 'Connessione al database fallita: ' . $mysqli->connect_error;
          exit();
      }

      if ( $id ) {

        $id = intval($id);

        $query = $mysqli->prepare('DELETE FROM libri WHERE ID = ?');
        $query->bind_param('i', $id);
        $query->execute();

        if ($query->affected_rows > 0) {
            header('Location: https://localhost/library/?statocanc=ok');
            exit;
        } else {
            header('Location: https://localhost/library/?statocanc=ko');
            exit;
        }
      } else {
        
        $query = $mysqli->prepare('DELETE FROM libri WHERE id_libro = ?');
        $query->bind_param('i', $userId);
        $query->execute();

        if ($query->affected_rows > 0) {
            header('Location: https://localhost/library/admin.php?statocanc=ok');
            exit;
        } else {
            header('Location: https://localhost/library/admin.php?statocanc=ko');
            exit;
        }
      }
    }

    public static function updateData($form_data, $id)
    {
        $fields = array(
            'nome'           => $form_data['nome'],
            'telefono'       => $form_data['telefono'],
            'email'          => $form_data['email'],
            'indirizzo'      => $form_data['indirizzo'],
            'data_di_nascita'=> $form_data['data_di_nascita'],
        );

        $fields = self::sanitize($fields);

        if ($fields) {
            $mysqli = new mysqli('localhost', 'root', '', 'librarydb');

            if ($mysqli->connect_errno) {
                echo 'Connessione al database fallita: ' . $mysqli->connect_error;
                exit();
            }

            $id          = intval($id);
            $is_in_error = false;

            try {
                $query = $mysqli->prepare('UPDATE libri SET Titolo = ?, Autore = ? WHERE ID = ?');
                if (is_bool($query)) {
                    $is_in_error = true;
                    throw new Exception('Query non valida. $mysqli->prepare ha restituito false.');
                }
                $query->bind_param('ssi', $fields['titolo'], $fields['autore'], $id);
                $query->execute();
            } catch (Exception $e) {
                error_log("Errore PHP in linea {$e->getLine()}: " . $e->getMessage() . "\n", 3, 'my-errors.log');
            }

            if (! is_bool($query)) {
                if (count($query->error_list) > 0) {
                    $is_in_error = true;
                    foreach ($query->error_list as $error) {
                        error_log("Errore MySQL n. {$error['errno']}: {$error['error']} \n", 3, 'my-errors.log');
                    }
                    header('Location: https://localhost/library/modifica-libro.php?id=' . $id . '&stato=ko');
                    exit;
                }
                try {
                    $query_2 = $mysqli->prepare('UPDATE libri
                    SET titolo = ?, autore = ?
                    WHERE libri = ?');
                    if (is_bool($query_2)) {
                        $is_in_error = true;
                        throw new Exception("Query non valida. $mysqli->prepare ha restituito false.");
                    }
                    $titolo        = ( $fields['titolo'] !== '' ) ? $fields['titolo'] : null;
                    $autore      = ( $fields['autore'] !== '' ) ? $fields['autore'] : null;
                    $query_2->bind_param('ssssi', $titolo, $autore, $id);
                    $query_2->execute();
                } catch (Exception $e) {
                    error_log('Errore PHP: ' . $e->getMessage() . "\n", 3, 'my-errors.log');
                }

                if (count($query_2->error_list) > 0) {
                    $is_in_error = true;
                    foreach ($query_2->error_list as $error) {
                        error_log("Errore MySQL n. {$error['errno']}: {$error['error']} \n", 3, 'my-errors.log');
                    }

                    header('Location: https://localhost/library/modifica-libro.php?stato=ko&id=' . $id);
                    exit;
                }
            }

            $stato = $is_in_error ? 'ko' : 'ok';
            header('Location: https://localhost/library/modifica-libro.php?id=' . $id . '&stato=' . $stato);
            exit;
        }
    }
}
