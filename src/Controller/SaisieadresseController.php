<?php

namespace App\Controller;
  /*
    * Controlleur principale
    * @author Djema Menouar Zineddine 
    * @version 1.0 
    */


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\SaisieAdresse;
use App\Entity\Velib;
use App\Entity\Ecoles;
use App\Entity\Transport;
use App\Entity\Geolocalisation;
use App\Repository\VelibRepository;
use App\Repository\EcolesRepository;
use App\Repository\TransportRepository;
use App\Form\SaisieAdresseType;
use App\Form\GeolocalisationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SaisieadresseController extends AbstractController
{
   
   
     /**
     * fonction qui recupere les valeurs du formulaire    
     *@Route("/", name="saisieadresse")
     * @param  mixed $request
     * @return void
     */

    //fonction qui recupere les valeurs du formulaire 
    public function index(Request $request)
     {

     
        //Creation de l'objet adresse qui va servir a la creation du formulaire
        $adresse= new SaisieAdresse();
        $form=$this->createForm(SaisieAdresseType::class,$adresse);
        $form->handleRequest($request);
        
        $error=" ";

        
       
        //verification de la validite et la soummision du formulaire saisie
        if ($form->isSubmitted() && $form->isValid())
         {
            //recuperation de l'adresse et du rayon saisie 
            $myData = $form->get('adresse')->getData();
            $rayon=$form->get('rayon')->getData();
            
           
            //stockage du rayon et l'adresse saisie dans une session 
            $session = $request->getSession();
            $session->set('adresse',$myData);
            $session->set('rayon',$rayon);
       

            if (strlen($myData)<10){
              $error="veuillez saisir une adresse valide ";

              return $this->render('saisieadresse/index.html.twig', [
                'form'=> $form->createView()  ,'erreur'=>$error,  ]);
              

            }else {

     
              $recup = curl_init();
              //remplacer les espaces dans l'adresse recuperé par des + 
              $myData= preg_replace( "# #", "+", $myData);
              $encode=utf8_decode($myData);
              //acces a l'api
              curl_setopt($recup, CURLOPT_URL, "https://api-adresse.data.gouv.fr/search/?q=$encode");
              //chemin de certificat pour effectuer la requette https
              curl_setopt($recup, CURLOPT_CAINFO, '/var/www/html/certif.cer');
              curl_setopt ($recup, CURLOPT_RETURNTRANSFER, true);
    
              $data=curl_exec($recup);
    if( $data === false)
    {
      echo 'Erreur Curl : ' . curl_error($recup );
    }
    
    else {
      $parsed_json = json_decode($data);
      $city=$parsed_json->{'features'}[0]->{'properties'}->{'city'};
  
    }
    var_dump($city)
;     
    if($city!="Paris"){
      $error="veuillez saisir une adresse de paris";
  
      return $this->render('saisieadresse/index.html.twig', [
        'form'=> $form->createView() ,   'erreur'=>$error, ]);
    }


            }

            //redirection vers la route resultat_suite_a_la_saisie
            return $this->redirectToRoute('resultat_suite_a_la_saisie');
           


          }
          $response = new Response();
         
          $response->headers->set('Access-Control-Allow-Origin', '*');
          //renvoie la page d'acceuil qui contient le formulaire
      return $this->render('saisieadresse/index.html.twig', [
      'form'=> $form->createView() , 'erreur'=>$error,  ]);
    }
      
       /**
          * get_distance_m
          *la fonction qui calculle la distance entre 2 points gps
          * @param  mixed $lat1
          * @param  mixed $lng1
          * @param  mixed $lat2
          * @param  mixed $lng2
          * @return void
          */
         public  function get_distance_m($lat1, $lng1, $lat2, $lng2) 
         {
           $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
           $rlo1 = deg2rad($lng1);
           $rla1 = deg2rad($lat1);
           $rlo2 = deg2rad($lng2);
           $rla2 = deg2rad($lat2);
           $dlo = ($rlo2 - $rlo1) / 2;
           $dla = ($rla2 - $rla1) / 2;
           $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin(
             $dlo));
           $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
           return ($earth_radius * $d);
         }


  
    /**
     * @Route("/resultat", name="resultat_suite_a_la_saisie")
     *  @param VelibRepository 
     * @param EcolesRepository
     * @param TransportRepository
     *@param  mixed $request
     *@return void
     */


    

    public function resultat (VelibRepository $repository ,EcolesRepository $repository_ecoles,TransportRepository $repository_transport,Request $request)
    {
      $adresse= new SaisieAdresse();
      $form1=$this->createForm(SaisieAdresseType::class,$adresse);
      $session = $request->getSession();
      $adressesaisieenpage1 = $session->get('adresse');
      $form1->get('adresse')->setData($adressesaisieenpage1);
      $form1->handleRequest($request);
       $error=" ";
      if ($form1->isSubmitted() && $form1->isValid())
      {
         //recuperation de l'adresse et du rayon saisie 
         $myData = $form1->get('adresse')->getData();
         $rayon=$form1->get('rayon')->getData();

        
         //stockage du rayon et l'adresse saisie dans une session 
         $session = $request->getSession();
         $session->set('adresse',$myData);
         $session->set('rayon',$rayon);
        

            if (strlen($myData)<10){
              $error="veuillez saisir une adresse valide ";
              $session->set('adresse',null);
              return $this->render('resultat_de_la_recherche/index.html.twig',[
        'form1'=> $form1->createView() ,'erreur'=>$error,'adresserecherchée'=>" ",'nombres_transport'=>"0",'nombres_ecoles'=>"0",'nombres_velos'=>"0",'nombres_pharmacies'=>"0",'nombres_kiosques'=>"0",'nombres_parking'=>"0",'nombres_bornederecharge'=>"0",'nombres_bureaudevote'=>"0",'nombres_espacevert'=>"0", 'nombres_bibliotheque'=>"0",'nombres_hotels'=>"0",'nombres_events'=>"0",'etoiles_transport'=>" ",'etoiles_parking'=>" ",'etoiles_hotels'=>" ",'etoiles_biblio'=>" ",'etoiles_velib'=> " ",'etoiles_pharmacies'=>" ",'etoiles_ecoles'=>" ",'etoiles_espaces_verts'=>" ",'etoiles_events'=>" ",'etoiles_kiosques'=> " ",'etoiles_bornes_recharges'=>" ",'etoiles_bureaux_de_votes'=>" ",'note_finale'=>" ",'latitude'=> " ",'longitude'=>" ", ]);
              

            }else {

              
              $recup = curl_init();
              //remplacer les espaces dans l'adresse recuperé par des + 
              $myData= preg_replace( "# #", "+", $myData);
              $encode=utf8_decode($myData);
              //acces a l'api
              curl_setopt($recup, CURLOPT_URL, "https://api-adresse.data.gouv.fr/search/?q=$encode");
              //chemin de certificat pour effectuer la requette https
              curl_setopt($recup, CURLOPT_CAINFO, '/var/www/html/certif.cer');
              curl_setopt ($recup, CURLOPT_RETURNTRANSFER, true);
    
              $data=curl_exec($recup);
    if( $data === false)
    {
      echo 'Erreur Curl : ' . curl_error($recup );
    }
    
    else {
      $parsed_json = json_decode($data);
      $city=$parsed_json->{'features'}[0]->{'properties'}->{'city'};
  
    }
    
    
    if($city!="Paris"){
      $error="veuillez saisir une adresse de paris";
      $session->set('adresse',null);
      return $this->render('resultat_de_la_recherche/index.html.twig',[
        'form1'=> $form1->createView() ,  'erreur'=>$error,'adresserecherchée'=>" ",'nombres_transport'=>"0",'nombres_ecoles'=>"0",'nombres_velos'=>"0",'nombres_pharmacies'=>"0",'nombres_kiosques'=>"0",'nombres_parking'=>"0",'nombres_bornederecharge'=>"0",'nombres_bureaudevote'=>"0",'nombres_espacevert'=>"0", 'nombres_bibliotheque'=>"0",'nombres_hotels'=>"0",'nombres_events'=>"0",'etoiles_transport'=>" ",'etoiles_parking'=>" ",'etoiles_hotels'=>" ",'etoiles_biblio'=>" ",'etoiles_velib'=> " ",'etoiles_pharmacies'=>" ",'etoiles_ecoles'=>" ",'etoiles_espaces_verts'=>" ",'etoiles_events'=>" ",'etoiles_kiosques'=> " ",'etoiles_bornes_recharges'=>" ",'etoiles_bureaux_de_votes'=>" ",'note_finale'=>" ",'latitude'=> " ",'longitude'=>" ", ]);
    }


            }


      

         //redirection vers la route resultat_suite_a_la_saisie
         return $this->redirect($this->generateUrl('resultat_suite_a_la_saisie'));


       }
       //recuperer la session 
   $session = $request->getSession();
   //recuperer l'adresse saisie 
   $monadresse = $session->get('adresse');
      //recuperer le rayon saisie 
      $rayon = $session->get('rayon');


        
     if ($monadresse==null){
      return $this->render('resultat_de_la_recherche/index.html.twig',[
        'form1'=> $form1->createView() ,   'erreur'=>$error,'adresserecherchée'=>" ",'nombres_transport'=>"0",'nombres_ecoles'=>"0",'nombres_velos'=>"0",'nombres_pharmacies'=>"0",'nombres_kiosques'=>"0",'nombres_parking'=>"0",'nombres_bornederecharge'=>"0",'nombres_bureaudevote'=>"0",'nombres_espacevert'=>"0", 'nombres_bibliotheque'=>"0",'nombres_hotels'=>"0",'nombres_events'=>"0",'etoiles_transport'=>" ",'etoiles_parking'=>" ",'etoiles_hotels'=>" ",'etoiles_biblio'=>" ",'etoiles_velib'=> " ",'etoiles_pharmacies'=>" ",'etoiles_ecoles'=>" ",'etoiles_espaces_verts'=>" ",'etoiles_events'=>" ",'etoiles_kiosques'=> " ",'etoiles_bornes_recharges'=>" ",'etoiles_bureaux_de_votes'=>" ",'note_finale'=>" ",'latitude'=> " ",'longitude'=>" ", ]);


     }else {

                  //Récuperation des coordonnées par la fonction recu
                    $coordonnees =$this->recupererCoord($monadresse);
                    $latitude = $coordonnees[0];
                    $logitude = $coordonnees[1];
    
              
                 //stocker les donnes dans une variable session
                $session->set('logitude_adresse_saisie',$logitude);
                $session->set('latitude_adresse_saisie',$latitude);
             
 
                

      
       

     
              
 
 
 //recuperation des ecoles 
 $properties_ecoles=json_decode(utf8_encode($this->listeecoles($repository_ecoles)->getContent()), true);
 $properties_transport=$repository_transport->finddata();
//var_dump($properties_transport);
 //initialisation du compteur velo
 $count=0;
 
 //initialisation du tableau qui va contenir les stations velib et leurs adresses 
 $tableau = array();
 $tab_nom_adresse_station=array();


//Recuperer les stations velib dans un rayon d'un km
for ($i=0;$i<1398;$i++) 
{
  $velibs = json_decode(utf8_encode($this->listevelib($repository)->getContent()), true);
$ad_station_velib=$velibs[$i]["adresse_velib"];
$nomdes=$velibs[$i]["Nom_de_la_station"];
$geo_des_stations=$velibs[$i]["geo"];
//on divise les donnes en 2 (longitude et latitude)
  $pieces = explode(",",$velibs[$i]["geo"] );
     $lat=$pieces[0];
         $lon=$pieces[1];

$pre_resultat=(($this->get_distance_m($latitude,$logitude, $lat, $lon) ));
$resultat=round($pre_resultat, 2);

    ;
     if ($resultat<=$rayon)
      {
         $count++;
            $ad_nom_station=array(
              'Nom_station'=>$nomdes,
              'distance3'=>$resultat,
              'adresse_station'=>$ad_station_velib,
              'geo_lat'=> $lat,
              'geo_long'=>$lon
            );
            $tableau[$count] =$ad_nom_station  ; 
       }


}
 foreach($tableau as $cle => $valeur) 
        //enregistrement de la colonne de distances dans le tableau $distance
        {
             $distance3[$cle] = $valeur['distance3']; 
        }
        //tri du tableau $tableau des stations velib en fonction de la colonne 'distance'
        array_multisort($distance3, SORT_ASC, $tableau);



$tableau_ecoles_1_km=array();
$count_ecoles=0;

//Recuperer les stations velib dans un rayon d'un km
for ($j=0;$j<1304;$j++) 
{
$nom_ecoles=$properties_ecoles[$j]["nom_ecole"];

$adresse_ecole=$properties_ecoles[$j]["adresse_ecole"];
$code_postale_adresse_ecole=$properties_ecoles[$j]["code_postale_ecole"];
$geo_des_ecoles=$properties_ecoles[$j]["geo"];
$type_ecole=$properties_ecoles[$j]["type_ecole"];
//on divise les donnes en 2 (longitude et latitude)
  $pieces_ecoles = explode(",",$properties_ecoles[$j]["geo"] );
     $lat_ecoles=$pieces_ecoles[0];
         $lon_ecoles=$pieces_ecoles[1];

$pre_resultat_ecoles=(($this->get_distance_m($latitude,$logitude, $lat_ecoles, $lon_ecoles) ));

$resultat_ecoles=round($pre_resultat_ecoles, 2);

    //recuperer 
     if ($resultat_ecoles<=$rayon)
      {
         $count_ecoles++;
            $tab_ecoles_a_renvoyer=array(
              'Nom_ecoles'=>$nom_ecoles,
              'distance2'=>$resultat_ecoles,
              'adresse_ecoles'=>$adresse_ecole,
              'code_postal_ecoles'=>$code_postale_adresse_ecole,
              'type_ecoles'=>$type_ecole
            );
            //var_dump($count_ecoles);
            $tableau_ecoles_1_km[$count_ecoles] =$tab_ecoles_a_renvoyer  ; 
       }


}

        foreach($tableau_ecoles_1_km as $cle => $valeur) 

        //enregistrement de la colonne de distances dans le tableau $distance
        {
             $distance2[$cle] = $valeur['distance2'];
            
        }


        //tri du tableau $tableau_transport_1_km en fonction de la colonne 'distance'
        array_multisort($distance2, SORT_ASC, $tableau_ecoles_1_km);





$tableau_transport_1_km=array();
$count_transport=0;

//Recuperer les stations velib dans un rayon d'un km
for ($k=0;$k<1147;$k++) 
{

//var_dump($nom_ecoles);
$nom_station_transport=$properties_transport[$k]["Nom_station_transport"];
$type_transport=$properties_transport[$k]["type_transport"];
$numero_ligne_transport=$properties_transport[$k]["numero_ligne_transport"];
$geo_transport=$properties_transport[$k]["geo_transport"];
$adresse_station_transport=$properties_transport[$k]["adresse_station_transport"];
//on divise les donnes en 2 (longitude et latitude)
  $pieces_transport = explode(",",$properties_transport[$k]["geo_transport"] );
     $lat_transport=$pieces_transport[0];
         $lon_transport=$pieces_transport[1];

$pre_resultat_transport=(($this->get_distance_m($latitude,$logitude, $lat_transport, $lon_transport) )). ' km';

$resultat_transport=round($pre_resultat_transport, 2);

    //recuperer 
     if ($resultat_transport<=$rayon)
      {
         $count_transport++;
            $tab_transport_a_renvoyer=array(
              'Nom_station'=>$nom_station_transport,
              'distance1'=>$resultat_transport,
              'type_transport'=>$type_transport,
              'Numero_ligne_transport'=>$numero_ligne_transport,
              
              'Adresse_station_transport'=>$adresse_station_transport

            );
            
            $tableau_transport_1_km[$count_transport] =$tab_transport_a_renvoyer ; 
       }


}


        foreach($tableau_transport_1_km as $cle => $valeur) 

        //enregistrement de la colonne de distances dans le tableau $distance
        {
             $distance1[$cle] = $valeur['distance1'];
             
        }

        
        
       
        array_multisort($distance1, SORT_ASC, $tableau_transport_1_km);
        

  $session = $request->getSession();
  $session->set('stationvelib',$tableau);
  $session->set('ecoles_1km', $tableau_ecoles_1_km);
  $session->set('transport_1km',$tableau_transport_1_km);
      
  
  $session->set('nombre',$count);

  






  if ($rayon==1000){
    $max_transport=25;
    $max_parking=100;
    $max_velib=50;
    $max_hotels=100;
    $max_pharmacies=58;
    $max_ecoles=70;
    $max_biblio=4;
    $max_espaces_verts=82;
    $max_events=100;
    $max_kiosques=29;
    $max_bornes_recharges=80;
    $max_bureaux_de_votes=42;
     $max_count=698;
  }
  if ($rayon==800){
    $max_transport=15;
    $max_parking=100;
    $max_velib=35;
    $max_hotels=79;
    $max_pharmacies=39;
    $max_ecoles=44;
    $max_biblio=3;
    $max_espaces_verts=56;
    $max_events=66;
    $max_kiosques=20;
    $max_bornes_recharges=55;
    $max_bureaux_de_votes=30;
     $max_count=542;
  }
  if ($rayon==600){
    $max_transport=9;
    $max_parking=100;
    $max_velib=21;
    $max_hotels=50;
    $max_pharmacies=23;
    $max_ecoles=27;
    $max_biblio=2;
    $max_espaces_verts=36;
    $max_events=40;
    $max_kiosques=11;
    $max_bornes_recharges=33;
    $max_bureaux_de_votes=18;
     $max_count=370;
  }
  if ($rayon==400){
    $max_transport=5;
    $max_parking=100;
    $max_velib=10;
    $max_hotels=23;
    $max_pharmacies=11;
    $max_ecoles=13;
    $max_biblio=1;
    $max_espaces_verts=19;
    $max_events=14;
    $max_kiosques=6;
    $max_bornes_recharges=15;
    $max_bureaux_de_votes=9;
     $max_count=226;
  }
  if ($rayon==200){
    $max_transport=2;
    $max_parking=100;
    $max_velib=4;
    $max_hotels=11;
    $max_pharmacies=4;
    $max_ecoles=6;
    $max_biblio=1;
    $max_espaces_verts=8;
    $max_events=7;
    $max_kiosques=3;
    $max_bornes_recharges=8;
    $max_bureaux_de_votes=4;
     $max_count=158;
  }
  
 
 
  
 
 
 
 

  $etoiles_transport=$this->get_notation($max_transport,$count_transport);
 
  $etoiles_velib=$this->get_notation($max_velib,$count);
  
  $etoiles_ecoles=$this->get_notation($max_ecoles,$count_ecoles);
 
  $count_total= $count+$count_transport+$count_ecoles;

 $NoteFinal=$this->get_notation($max_count,$count_total);


  $session = $request->getSession();
  $adresse_recherche=  $session->get('adresse');

}
      return $this->render('resultat_de_la_recherche/index.html.twig',[
        'form1'=> $form1->createView(),'z'=>"zizou",'erreur'=>$error,'adresserecherchée'=>$adresse_recherche,'nombres_transport'=>$count_transport,'nombres_ecoles'=>$count_ecoles,'nombres_velos'=>$count,'etoiles_velib'=> $etoiles_velib,'etoiles_transport'=>$etoiles_transport,'etoiles_ecoles'=>$etoiles_ecoles,'note_finale'=>$NoteFinal,'latitude'=> $latitude,'longitude'=>$logitude,]);

  }
  
  
    /**
     * @Route("/velib", name="velibstation")
     * @param  mixed $request
     * @return void
     * 
     */
    public function velib (Request $request)
    {
      $session = $request->getSession();
      $adresse =  $session->get('adresse');
      $parametre_1_api_filtre_geo = $session->get('latitude_adresse_saisie');
      $parametre_2_api_filtre_geo = $session->get('logitude_adresse_saisie');
      $nom_des_stations_rayon_selectione = $session->get('stationvelib');
     

     
       //var_dump( $nom_des_stations_rayon_selectione );
      return $this->render('velib_stations/index.html.twig',[
        'velib'=>$nom_des_stations_rayon_selectione,'adresse_saisie'=>$adresse,'latitude'=>$parametre_1_api_filtre_geo,'longitude'=>$parametre_2_api_filtre_geo,]);
      
    
    }
    /**
     * @Route("/transport_paris", name="transport_paris")
     * @param  mixed $request
     * @return void
     * 
     */
    public function moyen_transport(Request $request)
    {
      $session = $request->getSession();
      $adresse =  $session->get('adresse');

      $transport_vers_vue = $session->get('transport_1km');
     
      return $this->render('transport/index.html.twig',[
        'transports'=>$transport_vers_vue,'adresse_saisie'=>$adresse,]);
      
    
    }

    /**
     * @Route("/Ecoles", name="ecoles")
     * @param  mixed $request
     * @return void
     * 
     */
    public function Ecoles (Request $request)
    {
      $session = $request->getSession();
      $adresse =  $session->get('adresse');

      $ecoles_vers_vue = $session->get('ecoles_1km');
      
      
    
       //var_dump( $nom_des_stations_rayon_selectione );
      return $this->render('ecoles/index.html.twig',[
        'ecoles'=> $ecoles_vers_vue,'adresse_saisie'=>$adresse,]);
      
    
    }
  







    
    
/**
 * recupererCoord
 *
 * @param  mixed $monadresse
 * @return void
 */
public function recupererCoord($monadresse){
  
     //Initialise une session cURL
     $recup = curl_init();
     //remplacer les espaces dans l'adresse recuperé par des + 
     $monadresse= preg_replace( "# #", "+", $monadresse);
    
     
     //acces a l'api 
     $encode=utf8_decode($monadresse);
    
     curl_setopt($recup, CURLOPT_URL, "https://api-adresse.data.gouv.fr/search/?q=$encode");
     //chemin de certificat pour effectuer la requette https
     curl_setopt($recup, CURLOPT_CAINFO, '/var/www/html/certif.cer');
     curl_setopt ($recup, CURLOPT_RETURNTRANSFER, true);

     $data=curl_exec($recup);
     $response = new Response();

     $response->setContent(' <html><body><h1>Desolé un probleme daccès a lapi est survenue veuillez reesayez plus tard !</h1></body></html>');
       if( $data === false){
      
         return  $response;
      
       }
         else
         
             {
               
               //decoder le resultat data
               $parsed_json = json_decode($data);
              
             
               //recupere la longitude de l'adresse saisie
               
               $latitude  = $parsed_json->{'features'}[0]->{'geometry'}->{'coordinates'}[1];
               //recuperer la lattitude de l'adresse saisie 
               $logitude = $parsed_json->{'features'}[0]->{'geometry'}->{'coordinates'}[0];
               $post_code_adresse_decod =$parsed_json->{'features'}[0]->{'properties'}->{'postcode'} ;


               return([$latitude,$logitude]);
             }
               
}

/**
 * reversegeocode
 *
 * @param  mixed $latitude
 * @param  mixed $longitude
 * @return void
 */
public function reversegeocode($latitude,$longitude)
{
  $reverse_parametre_lien="lon=$longitude&lat=$latitude";
                  
                $connexion_api_reverse = curl_init();
   
                //acces a l'api
                curl_setopt($connexion_api_reverse, CURLOPT_URL, "https://api-adresse.data.gouv.fr/reverse/?$reverse_parametre_lien");
                //chemin de certificat pour effectuer la requette https
                curl_setopt($connexion_api_reverse, CURLOPT_CAINFO, '/var/www/html/certif.cer');
                curl_setopt ($connexion_api_reverse, CURLOPT_RETURNTRANSFER, true);
              
                $execution_api_reverse=curl_exec($connexion_api_reverse);
                  if( $execution_api_reverse === false)
                  {
                    echo 'Erreur Curl : ' . curl_error($connexion_api_reverse );
                  }
                     else
                      { 
   
   
                           //decoder le resultat data
                            $parsed_json_reverse = json_decode($execution_api_reverse);
                            //recupere la longitude de l'adresse saisie 
                         return $parsed_json_reverse->{'features'}[0]->{'properties'}->{'label'};
   
   
   
                        }

}

    /**
 * get_notation
 *
 * @param  mixed $max
 * @param  mixed $count
 * @return void
 */
     
    public function  get_notation($max,$count){
    
    if ($count==0){
       $etoiles=" ☆☆☆☆☆";
    }
    if ($count>0&&$count<=(0.3*$max)){
       $etoiles="★☆☆☆☆";
    }
    if ($count>(0.3*$max)&&$count<=(0.6*$max)){
       $etoiles="★★☆☆☆";
    }
    if ($count>(0.6*$max)&&$count<=(0.9*$max)){
       $etoiles="★★★☆☆";
    }
    if ($count>(0.9*$max)&&$count<=(1.1*$max)){
       $etoiles="★★★★☆";
    }
    if ($count>(1.1*$max)){
       $etoiles="★★★★★";
    }
    return $etoiles;
  }
         
     /**
     * @Route("/api", name="api" , methods={"GET"})
     */
    public function listevelib(VelibRepository $repository)
    {
        $properties= $repository->finddata();

        //var_dump($properties);
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($properties, 'json');
        // On instancie la réponse
    $response = new Response($jsonContent);
 
    // On ajoute l'entête HTTP
   $response->headers->set('Content-Type', 'application/json');
   //dd($response);
    // On envoie la réponse

   return $response;
  
    

    }       
  
 /**
     * @Route("/api/ecoles", name="api_ecoles" , methods={"GET"})
     */
    public function listeecoles(EcolesRepository $eco_repository)
    {
        $ecoles= $eco_repository->finddata();

        //var_dump($properties);
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($ecoles, 'json');
        // On instancie la réponse
    $response = new Response($jsonContent);
 
    // On ajoute l'entête HTTP
   $response->headers->set('Content-Type', 'application/json');
   //dd($response);
    // On envoie la réponse

   return $response;
  
    
    }
  
        
}


