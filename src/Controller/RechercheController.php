<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(ProduitRepository $pr, Request $rq)
    {
        $globaleGet = $rq->query;   // contient les valeurs de $_GET
        $mot = $globaleGet->get("recherche");
        $produits = $pr->findByWord($mot);
        return $this->render('recherche/index.html.twig', compact("produits", "mot"));
    }
}
