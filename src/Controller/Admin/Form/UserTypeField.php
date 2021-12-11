<?php

namespace App\Controller\Admin\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform($user)
    {
        return null === $user ? '' : $user->getId();
    }

    public function reverseTransform($userId)
    {
        if(!$userId) {
            return null;
        }

        $user = $this->em->getRepository(User::class)->find($userId);

        return $user;
    }
}