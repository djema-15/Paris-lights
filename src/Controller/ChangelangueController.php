<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChangelangueController extends AbstractController
{
    /**
     * @Route("/changelangue/{locale}", name="changelangue")
     */
    public function changelangue($locale,Request $request)
    {
            //on stock la langue demmandé dans la session
        $request->getSession()->set('_locale',$locale);
        //on reviens vers la page precedente 
        return $this->redirect ($request->headers->get('referer'));

      
    }
}
