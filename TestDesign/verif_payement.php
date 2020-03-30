<html>
<head>
	<title>Home Service</title>
	<link rel="stylesheet" type="text/css" href="Style/bootstrap.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="Style/style.css">
	<meta charset="utf-8">
</head>
<body>
	<?php
		include('header.php');
	?>
	<main>
    <?php
      ini_set('display_errors', 1);
      require_once "config.php";
      require_once "requireStripe.php";
      \Stripe\Stripe::setApiKey('sk_test_qMXWSSMoE6DTqXNR7kMQ0k6V00sh4hnDbe');

			if(isset($_SESSION['mail']) && isset($_GET['session_id']) && isset($_GET['abonnement'])){
		    try{
						$req = $cx->prepare('SELECT * FROM user WHERE mail = ?');
		        $req->execute(array($_SESSION['mail']));
		        $user = $req->fetch();
		        $req2 = $cx->prepare('SELECT * FROM abonnement WHERE id_abonnement = ?');
		        $req2->execute(array($_GET['abonnement']));
		        $abo = $req2->fetch();

						$CurrentSession = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

						$req3 = $cx->prepare('SELECT * FROM souscription WHERE user_id_user = ?');
						$req3->execute(array($user['id_user']));
						$verif = $req3->fetch();

						if($verif == NULL){
							$req5 = $cx->prepare('INSERT INTO souscription(abonnement_id_abonnement,date,heure_restante,user_id_user,user_ville_reference,stripe_id) VALUES(?,NOW(), ?, ?, ?,?)');
							$req5->execute(array($abo['id_abonnement'],$abo['nb_heure'],$user['id_user'],$user['ville_reference'],$CurrentSession['subscription']));
							echo "<div class=\"container\">
											<h1 id=\"congratz\">Félicitations, vous êtes désormais abonné.e et pouvez donc désormais profitez de vos avantages !</h1>
										</div>";
						}
						else{
							$latestsub = \Stripe\Subscription::retrieve($verif['stripe_id']);
							$currentsub = \Stripe\Subscription::retrieve($CurrentSession['subscription']);
							if($currentsub['id'] != $latestsub['id']){
								$latestsub->delete();
								$req4 = $cx->prepare('UPDATE souscription SET abonnement_id_abonnement = ?, date = NOW(), stripe_id = ? WHERE user_id_user = ?');
								$req4->execute(array($abo['id_abonnement'], $CurrentSession['subscription'], $user['id_user']));
								echo "<div class=\"container\">
												<h1 id=\"congratz\">Félicitations pour avoir changer d'abonnement, profitez de vos nouveaux avantages dès maintenant!</h1>
											</div>";
							}
							else{
								echo "<div class=\"container\">
												<h1 id=\"congratz\">Une erreur est servenue au niveau de l'abonnement !</h1>
											</div>";
							}
						}
					}
					catch(Exception $e){
						echo "<div class=\"container\">
										<h1 id=\"congratz\">Une erreur est servenue au niveau du payement !</h1>
									</div>";
					}
      }
    ?>
  </main>
  <?php
    include('footer.php');
  ?>
</body>
</html>
