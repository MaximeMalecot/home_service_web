<?php
  if(isset($_POST['langue'])){
    require_once "Class/Reservation.php";
    session_start();
    $_SESSION['langue'] = $_POST['langue'];
    header("Location: index.php?langue=".$_POST['langue']);
  }
  else{
    header("Location: index.php");
  }


 ?>
