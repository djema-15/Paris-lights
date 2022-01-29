<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResultatDeLaRechercheController extends AbstractController
{
    /**
     * @Route("/resultat/de/la/recherche", name="resultat_de_la_recherche")
     */
    public function index()
    {
        return $this->render('resultat_de_la_recherche/index.html.twig');
    }
}
