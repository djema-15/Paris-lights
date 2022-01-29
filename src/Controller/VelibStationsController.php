<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VelibStationsController extends AbstractController
{
    /**
     * @Route("/velib/stations", name="velib_stations")
     */
    public function index()
    {
        return $this->render('velib_stations/index.html.twig', [
            'controller_name' => 'VelibStationsController',
        ]);
    }
}



