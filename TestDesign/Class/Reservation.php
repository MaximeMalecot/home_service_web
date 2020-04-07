<?php

  include( __DIR__ . "/../config.php");
  ini_set('display_errors', 1);

  class Reservation implements JsonSerializable {

    ////////////////ATTRIBUTS POUR LA TABLE RESERVATION : //////////////
    private $date_debut;
    private $date_fin;
    private $nb_unit;
    private $id_supplement;
    private $nb_supplement;
    private $user_id_user;
    private $user_ville_reference;
    private $user_stripe_id;
    private $prestation_id_prestation;
    private $prestation_ville;

    //////////////ATTRIBUTS POUR LA TABLE FACTURATION /////////////////
    private $cout;
    private $prestataire_id;
    private $prestataire_ville;
    //private $cout;
    //

    public function __construct(string $dd, string $df, float $h, int $idsup, int $sup, string $m, int $pid, int $prestataire)
    {
      ////////////RESERVE//////////////
      $this->date_debut = $dd;
      $this->date_fin = $df;
      $this->nb_unit = $h;
      $this->id_supplement = $idsup;
      $this->nb_supplement = $sup;
      $this->setUser($m);
      $this->setPresta($pid);
      $this->setPrestataire($prestataire);
    }

    public function setUser(string $mail):void{
      global $cx;
      $req = $cx->prepare('SELECT * FROM user WHERE mail = ?');
      $req->execute(array($mail));
      $user = $req->fetch();

      $this->user_id_user = $user['id_user'];
      $this->user_ville_reference = $user['ville_reference'];
      $this->user_stripe_id = $user['stripe_id'];
    }

    public function setPresta(int $idp):void{
      global $cx;
      $reqPresta = $cx->prepare('SELECT * FROM prestation WHERE id_prestation = ?');
      $reqPresta ->execute(array($idp));
      $presta = $reqPresta ->fetch();

      $this->prestation_id_prestation = $idp;
      $this->prestation_ville = $presta['categorie_ville'];

    }

    public function setPrestataire(int $id):void{
      global $cx;
      $req1 = $cx->prepare('SELECT * FROM prestataire WHERE id_prestataire = ?');
      $req1->execute(array($id));
      $prestataire = $req1->fetch();

      $this->prestataire_id = $prestataire['id_prestataire'];
      $this->prestataire_ville = $prestataire['categorie_ville'];
    }

/*
    public function setCout(string $cn):void{
      global $cx;
      $req = $cx->prepare('SELECT * FROM prestataire WHERE categorie_nom = ?');
      $req->execute(array($cn));
      $prestataires = $req->fetchAll();

      $this->cout= 0;
      foreach($prestataires as $prestataire){
        if(strcmp($this->supplement, "aucun") != 0){
          if( ($prestataire['prix_heure'] + $prestataire['supplement']) > $this->cout){
            $this->cout = ($prestataire['prix_heure'] + $prestataire['supplement']);
          }
        }
        else{
          if($prestataire['prix_heure'] > $this->cout){
            $this->cout = $prestataire['prix_heure'];
          }
        }
      }
      $this->cout *= 1.3;
      if(strcmp($this->date_fin, $this->date_debut ) != 0){
        $nbJoursTime = strtotime($this->date_fin) - strtotime($this->date_debut);
        $nbJours = ($nbJoursTime/86400) + 1;
        $this->cout *= $nbJours;
      }
    }

//////////////////////SETTERS/////////////////////////
    public function setNbHeure(int $h):void{
      $this->nb_heure = $h;
    }
    public function setDateDebut(string $dd):void{
      $this->date_debut = $dd;
    }
    public function setDateFin(string $df):void{
      $this->date_fin = $df;
    }
    public function setSupplement(string $sup):void{
      $this->supplement = $sup;
    }
    public function setUID(int $id):void{
      $this->user_id_user = $id;
    }
    public function setUVR(string $vr):void{
      $this->user_ville_reference = $vr;
    }
    public function setPID(int $id):void{
      $this->prestation_id_prestation = $id;
    }
    public function setUSD(int $id):void{
      $this->user_stripe_id = $id;
    }
    public function SimpleCout(float $c):void{
      $this->cout = $c;
    }

////////////////GETTERS/////////////////////
    public function getNbHeure():int{
      return $this->nb_heure;
    }
    public function getDateDebut():string{
      return $this->date_debut;
    }
    public function getDateFin():string{
      return $this->date_fin;
    }
    public function getSupplement():string{
      return $this->supplement;
    }
    public function getUID():int{
      return $this->user_id_user;
    }
    public function getUVR():string{
      return $this->user_ville_reference;
    }
    public function getPID():int{
      return $this->prestation_id_prestation;
    }
    public function getCout():float{
      return $this->cout;
    }
    public function getUSD():string{
      return $this->user_stripe_id;
    }

*/

    public function jsonSerialize()
      {
          return
          [
              'nb_heure'   => $this->nb_heure,
              'date_debut' => $this->date_debut,
              'date_fin' => $this->date_fin,
              'supplement' => $this->supplement,
              'user_id_user' => $this->user_id_user,
              'user_ville_reference' => $this->user_ville_reference,
              'prestation_id_prestation' => $this->prestation_id_prestation,
              'cout' => $this->cout,
              'user_stripe_id' => $this->user_stripe_id
          ];
      }
}


?>
