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
            // EXO : vérifier que la quantité commandée ne dépasse pas le stock
            //       sinon, réduire la quantité commandée (= stock)
            $prod = $pr->find($ligne["produit"]->getId());
            $prod->setStock( -$ligne["quantite"]);

            $detail->setProduit($prod);
            $detail->setCommande($cmd);
            $em->persist($detail);
        }
        $cmd->setMontant($montant);
        $em->persist($cmd);
        $em->flush();
        $session->remove("panier");
        return $this->redirectToRoute("profil");
    }

    /**
     * @Route("/profil/commande/{id}", name="profil_commande_detaille", methods={"GET"})
     */
    public function show(Commande $commande)
    {
        // EXO : vérifier que la commande que l'on veut afficher a été passé par le membre connecté
        //      sinon rediriger vers la page profil
        if( $this->getUser()->getId() == $commande->getMembre()->getId() ){
            return $this->render('commande/show.html.twig', [
                'commande' => $commande,
            ]);
        }
        $this->addFlash("danger", "Vous ne pouvez pas afficher cette commande");
        return $this->redirectToRoute("profil");
    }
}
