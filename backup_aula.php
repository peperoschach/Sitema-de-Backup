<?php

include_once('function.php');

if(!empty($_GET['f'])) {
  $f = $_GET['f'];
} else {
  $f = '';
}

if($f == 'backup') {
  $html = Backup();

  if($html) {
    $data = array(
      'status' => 200
    );
  } else {
    $data = array(
      'status' => 400
    );
  }

  header("Content-type: application/json");
  echo json_encode($data);
  exit();
}

?>