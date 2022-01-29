<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EcolesController extends AbstractController
{
    /**
     * @Route("/ecoles", name="ecoles")
     */
    public function index()
    {
        return $this->render('ecoles/index.html.twig', [
            'controller_name' => 'EcolesController',
        ]);
    }
}
