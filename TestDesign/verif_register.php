<?php
session_start();

include('config.php');
if(strtoupper($_POST['captcha'])!=strtoupper($_SESSION['captcha'])){
  header('Location: register.php?error=Captcha');
  session_destroy();
  die();
}

if (isset($_POST['Nom']) && !empty($_POST['Nom'])){
  $Nom = htmlspecialchars($_POST['Nom']);
}
else{
  header('Location: register.php?error=3');
  die();
}

if (isset($_POST['Prenom']) && !empty($_POST['Prenom'])){
  $Prenom = htmlspecialchars($_POST['Prenom']);
}
else{
  header('Location: register.php?error=3');
  die();
}

if (isset($_POST['mail']) && !empty($_POST['mail'])){
  $mail = htmlspecialchars($_POST['mail']);
}
else{
  header('Location: register.php?error=3');
  die();
}

$req = $cx->prepare('SELECT Prenom FROM user WHERE mail = :mail');
$req->execute(array('mail' => $mail));

$answers= [];
while($user = $req->fetch()){
  $answers [] = $user;
}
if (count($answers) != 0){
  header('Location: register.php?error=mail_taken');
  exit();
}
$req2 = $cx->prepare('SELECT mail FROM user WHERE Prenom = :Prenom');
$req2->execute(array('Prenom' => $Prenom));

$answers= [];
while($user = $req->fetch()){
  $answers [] = $user;
}
if (count($answers) != 0){
  header('Location: register.php?error=Prenom_taken');
  exit();
}



if (isset($_POST['mail2']) && !empty($_POST['mail2'])){
  $mail2 = htmlspecialchars($_POST['mail2']);
}
else{
  header('Location: register.php?error=3');
  die();
}

if (isset($_POST['mdp']) && !empty($_POST['mdp'])){
  $mdp = hash('sha512',$_POST['mdp']);
}
else{
  header('Location: register.php?error=3');
  die();
}

if (isset($_POST['mdp2']) && !empty($_POST['mdp2'])){
  $mdp2 = hash('sha512',$_POST['mdp2']);
}
else{
  header('Location: register.php?error=3');
  die();
}

if($mail != $mail2){
  echo "Vos adresses mail ne sont pas similaires";
  header('Location: register.php?error=1');
  die();
}

if($mdp != $mdp2){
  echo "Vos mots de passe ne sont pas similaires recommencez";
  header('Location: register.php?error=2');
  die();
}
/*$sender = 'boopursr@services.com';
$recipient = $mail;
$subject = "Verification de votre user BoopUrSR";
$key = md5(microtime(TRUE)*100000);
$link = "http://51.77.159.247/activation.php?log=".urlencode($Prenom) . "&key=" . urlencode($key) ;
$message = "Merci d'avoir créé un user sur BoopUrSR !

Profitez dès maintenant de notre site en validant votre e-mail. Cliquez simplement sur ce lien : " . $link . "

Ceci est un e-mail automatique, merci de ne pas y répondre.";

$headers = 'From:' . $sender;
mail($recipient, $subject, $message, $headers);*/
$take = $cx -> prepare("INSERT INTO user(Nom,Prenom,mdp,mail,date_inscription)VALUES(:Nom,:Prenom,:mdp,:mail,NOW())");
$take -> execute(array(
  'Nom'=>$Nom,
  'Prenom'=>$Prenom,
  'mdp'=>$mdp,
  'mail'=>$mail

));
$_SESSION['mail'] = $_POST['mail'];
$_SESSION['Nom'] = $_POST['Nom'];
header('Location: index.php?verif=ok');
exit;

?>
