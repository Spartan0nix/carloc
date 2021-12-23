<?php

namespace App\Controller;

use App\Form\User\UserChangeAddress;
use App\Form\User\UserChangeInfo;
use App\Normalizer\RentNormalizer;
use App\Normalizer\UserNormalizer;
use App\Repository\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserNormalizer $normalizer, private EntityManagerInterface $em){}

    #[Route('/compte', name:'user_account')]
    public function account(Request $request) {
        $user = $this->getUser();
        if(!$user) {
            $response = new Response();
            $response->setStatusCode(401);
            return $this->render('exceptions/unauthorized.html.twig', [], $response);
        }
        
        $user_info_form = $this->createForm(UserChangeInfo::class, $user);
        $user_info_form->handleRequest($request);

        if($user_info_form->isSubmitted() && $user_info_form->isValid()) {
            $this->addFlash('success', 'Informations mises à jour avec succès.');
            $this->em->flush();
        }

        $user_address_form = $this->createForm(UserChangeAddress::class, $user);
        $user_address_form->handleRequest($request);

        if($user_address_form->isSubmitted() && $user_address_form->isValid()) {
            $this->addFlash('success', 'Informations mises à jour avec succès.');
            $this->em->flush();
        }
        
        return $this->render('user/account.html.twig', [
            'info_form' => $user_info_form->createView(),
            'address_form' => $user_address_form->createView(),
        ]);
    }

    #[Route('/compte/mes-locations', name:'user_rent_list')]
    public function listUserRent(RentNormalizer $rent_normalizer): Response {
        
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $rents = $user->getRents();
        $normalize_rents = $rents->map(fn($rent) => $rent_normalizer->normalize($rent, 'extended'));

        return $this->render('user/rent_list.html.twig', [
            'rents' => $normalize_rents
        ]);
    }

    #[Route('/compte/annulation-location', name:'user_rent_cancel_form')]
    public function cancelUserRentForm(RentRepository $repository, RentNormalizer $rent_normalizer): Response {
        
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $rents = $repository->findCancelableRent($user->getId());
        $normalize_rents = $rents->map(fn($rent) => $rent_normalizer->normalize($rent, 'extended'));

        return $this->render('user/rent_cancel.html.twig', [
            'rents' => $normalize_rents
        ]);
    }

    #[Route('/compte/annulation', name:'user_rent_cancel', methods:['POST'])]
    public function cancelUserRent(Request $request, EntityManagerInterface $em, RentRepository $repository) {
        $req = $request->request->all();
        if(!$req) {
            $this->addFlash('error', 'Erreur lors du traitement de la requête.');
            return $this->redirectToRoute('user_rent_cancel_form');
        }

        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $rent = $repository->findOneBy(['id' => $req['rent_id']]);
        if(!$rent){
            $this->addFlash('warning', 'Cette location ne semble pas exister.');
            return $this->redirectToRoute('user_rent_cancel_form');
        }
        
        $em->remove($rent);
        $em->flush();

        $this->addFlash('success', 'Location annulée avec succès.');
        return $this->redirectToRoute('user_rent_list');
    }
}