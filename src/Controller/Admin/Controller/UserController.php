<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminUserType;
use App\Entity\User;
use App\Normalizer\UserNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends CrudController
{
    protected string $index_route = 'admin_user_index';
    protected string $template = 'user';
    protected EntityManagerInterface $em;
    protected object $entity;
    protected string $formClass;
    protected Request $request;
    protected UserPasswordEncoderInterface $passwordEnconder;
    
    public function __construct(
        EntityManagerInterface $em, 
        RequestStack $requestStack,
        UserPasswordEncoderInterface $passwordEnconder
    )
    {
        $this->em = $em;
        $this->entity = new User();
        $this->formClass = AdminUserType::class;
        $this->request = $requestStack->getCurrentRequest();
        $this->passwordEnconder = $passwordEnconder;
    }

    #[Route('/admin/utilisateur', name:'admin_user_index')]
    public function index(UserNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/utilisateur/ajouter', name:'admin_user_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/utilisateur/{id}/modifier', name:'admin_user_edit')]
    public function edit(string $id) {
        $entity = $this->em->getRepository($this->entity::class)->findOneBy(['id' => $id]);
        $form = $this->createForm($this->formClass, $entity);
        $form->remove('password');
        $form->handleRequest($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $this->em->flush();
            $this->addFlash('success', 'Élement modifié avec succès.');
            return $this->redirectToRoute($this->index_route);
        }

        return $this->render("admin/{$this->template}/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/utilisateur/{id}/supprimer', name:'admin_user_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer cet utilisateur, car il est associé à une ou plusieurs locations.');
            return $this->redirectToRoute('admin_user_index');
        }
    }
}