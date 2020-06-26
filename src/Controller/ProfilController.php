<?php

namespace App\Controller;

use App\Entity\Commande, App\Entity\Detail;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index()
    {
        // la méthode getUser renvoie un objet Membre contenant les informations de l'utilisateur connecté
        // $membre = $this->getUser();
        return $this->render('profil/index.html.twig');
    }

    /**
     * @Route("/commander", name="commander")
     */
    public function commander(Session $session, EntityManager $em, ProduitRepository $pr)
    {
        $panier = $session->get("panier");
        $cmd = new Commande;
        $cmd->setMembre($this->getUser());
        $cmd->setDateEnregistrement(new \DateTime("now"));
        $cmd->setEtat("en attente");
        $montant = 0;
        foreach($panier as $ligne){
            $montant += $ligne["produit"]->getPrix() * $ligne["quantite"];
            $detail = new Detail;
            $detail->setQuantite($ligne["quantite"]);    
            $detail->setPrix($ligne["produit"]->getPrix());
            $detail->setProduit($pr->find($ligne["produit"]->getId()));
            $detail->setCommande($cmd);
            $em->persist($detail);
        }
        $cmd->setMontant($montant);
        $em->persist($cmd);
        $em->flush();
        $session->remove("panier");
        return $this->redirectToRoute("profil");
    }


}
