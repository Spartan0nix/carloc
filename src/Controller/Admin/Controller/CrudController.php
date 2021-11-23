<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

abstract class CrudController extends AbstractController 
{
    public function create(){
        $form = $this->createForm($this->formClass,$this->entity);
        $form->handleRequest($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if($this->template === 'user'){
                $data->setPassword($this->passwordEnconder->encodePassword($data, $data->getPassword()));
            }
            $this->em->persist($data);
            $this->em->flush();

            $this->addFlash('success', 'Élément ajouté avec succès.');
            return $this->redirectToRoute($this->index_route);
        }

        return $this->render("admin/{$this->template}/add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    public function read(ContextAwareNormalizerInterface $normalizer) {
        $entities = $this->em->getRepository($this->entity::class)->findBy([], ['id' => 'ASC'], 20);
        $normalize_entities = [];
    
        foreach($entities as $entity){
           array_push($normalize_entities, $normalizer->normalize($entity, 'extended'));
        }
    
        return $this->render("admin/{$this->template}/index.html.twig", [
            'entities' => $normalize_entities
        ]);
    }

    public function update(string $id) {
        $entity = $this->em->getRepository($this->entity::class)->findOneBy(['id' => $id]);
        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            // $entityInformations = new ReflectionClass($entity);
            // $dataInformations = new ReflectionClass($data);
            // $entityProperties = $entityInformations->getProperties();
            // $dataProperties = $dataInformations->getProperties();
            // $accessor = new PropertyAccessor();
            // $index = 0;
            // $EXCEPTIONS = array('id', 'cars');

            // foreach($entityProperties as $property){
            //     $property_name = $property->getName();
            //     if(in_array($property_name, $EXCEPTIONS)){
            //         $index++;
            //         continue;
            //     }
            //     $value = $accessor->getValue($data, $dataProperties[$index]->getName());
            //     $accessor->setValue($entity, $property_name, $value);
            //     $index++;
            // }

            $this->em->flush();
            $this->addFlash('success', 'Élement modifié avec succès.');
            return $this->redirectToRoute($this->index_route);
        }

        return $this->render("admin/{$this->template}/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }
    public function delete (string $id) {
        $entity = $this->em->getRepository($this->entity::class)->findOneBy(['id' => $id]);
        if(!$entity){
            $this->addFlash('warning', 'Impossible de supprimer cet élément.');
            return $this->redirectToRoute($this->index_route);
        }

        $this->em->remove($entity);
        $this->em->flush();
        $this->addFlash('success', 'Élément supprimé avec succès.');
        return $this->redirectToRoute($this->index_route);
    }
}