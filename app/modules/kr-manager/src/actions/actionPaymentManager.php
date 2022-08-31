<?php

/**
 * Admin dashboard page
 *
 * @package Krypto
 * @author Ovrley <hello@ovrley.com>
 */

session_start();

require "../../../../../config/config.settings.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/vendor/autoload.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/MySQL/MySQL.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/App/App.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/App/AppModule.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/User/User.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/Lang/Lang.php";

// Load app modules
$App = new App(true);
$App->_loadModulesControllers();

try {

  $User = new User();
  if(!$User->_isLogged()) throw new Exception("User are not logged", 1);
  if(!$User->_isAdmin() && !$User->_isManager()) throw new Exception("Permission denied", 1);

  if(empty($_POST) || !isset($_POST['act'])) throw new Exception("Permission denied", 1);

  $infosAction = explode('-', App::encrypt_decrypt('decrypt', $_POST['act']));
  if(count($infosAction) != 2) throw new Exception("Permission denied", 1);

  $Manager = new Manager($App);
  $Manager->_submitActionPayment($infosAction[0], $infosAction[1], $_POST['args']);

  die(json_encode([
    'error' => 0,
    'msg' => 'Done !'
  ]));

} catch (\Exception $e) {
  die(json_encode([
    'error' => 1,
    'msg' => $e->getMessage()
  ]));
}


?>
