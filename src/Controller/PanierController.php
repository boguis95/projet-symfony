<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;
use App\Repository\ProduitRepository;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Session $session)
    {
        $panier = $session->get("panier");
        return $this->render('panier/index.html.twig', compact("panier"));
    }

    /**
     * @Route("/ajouter-panier/{id}", name="ajouter_panier", requirements={"id"="\d+"})
     */
    public function ajouter(Request $rq, Session $session, ProduitRepository $pr, $id)
    {
        $panier = $session->get("panier", []); // le 2ième paramètre est renvoyé si "panier" n'existe pas dans la session
        $produit = $pr->find($id);
        $qte = $rq->query->get("qte");
        $qte = empty($qte) ? 1 : $qte;
        $produitExiste = false;
        if($produit){
            foreach($panier as $indice => $ligne){
                if($produit->getId() == $ligne["produit"]->getId() ){
                    $qte += $ligne["quantite"];
                    $panier[$indice]["quantite"] = $qte;
                    $produitExiste = true;
                }
            }
            if(!$produitExiste){
                $panier[] = [ "produit" => $produit, "quantite" => $qte ];
            }
            $this->addFlash("success", "Le produit <strong>" . $produit->getReference() . "</strong> a été ajouté au panier");
            $session->set("panier", $panier);
        }
        else{
            $this->addFlash("danger", "Le produit n°$id n'existe pas");
        }
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/vider-panier", name="vider_panier")
     */
    public function vider(Session $session)
    {
        $session->remove("panier");
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/supprimer-produit/{id}", name="supprimer_produit_panier", requirements={"id"="\d+"})
     */
    public function supprimer(Session $session, $id)
    {
        $panier = $session->get("panier");
        foreach($panier as $indice => $ligne){
            if( $ligne["produit"]->getId() == $id ){
                unset($panier[$indice]);
                break;
            }
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/modifier-quantite/{id}", name="modifier_produit_panier", requirements={"id"="\d+"})
     */
    public function modifier(Request $rq, Session $session, $id)
    {
        $panier = $session->get("panier");
        $qte = $rq->query->get("qte");
        $qte = empty($qte) ? 1 : $qte;
        foreach($panier as $indice => $ligne){
            if( $ligne["produit"]->getId() == $id ){
                $panier[$indice]["quantite"] = $qte;
                break;
            }
        }

        $session->set("panier", $panier);
        return $this->redirectToRoute("panier");
    }


}
