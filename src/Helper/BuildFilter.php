<?php

namespace App\Helper;

use App\Entity\Components\Brand;
use App\Entity\Components\Fuel;
use App\Entity\Components\Gearbox;
use App\Entity\Components\Model;
use App\Entity\Components\Type;
use Doctrine\ORM\EntityManagerInterface;

class BuildFilter
{
    private EntityManagerInterface $em;
    private array $filter_list;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->filter_list = array();
    }

    public function buildFilter(array $list_id): Array {
        if($list_id['brand'] != ''){
            $this->getBrand($list_id['brand']);
        }
        if($list_id['model'] != ''){
            $this->getModel($list_id['model']);
        }
        if($list_id['type'] != ''){
            $this->getType($list_id['type']);
        }
        if($list_id['fuel'] != ''){
            $this->getFuel($list_id['fuel']);
        }
        if($list_id['gearbox'] != ''){
            $this->getGearbox($list_id['gearbox']);
        }
        
        return $this->filter_list;
    }

    public function getBrand(array $ids) {
        $this->filter_list['brand'] = array();
        foreach($ids as $id){
            $brand = $this->em->getRepository(Brand::class)->find($id);
            array_push($this->filter_list['brand'], array(
                'id' => $brand->getId(),
                'brand' => $brand->getBrand()
            ));
        }
        $this->filter_list['brand'] = json_encode($this->filter_list['brand']);
    }
    
    public function getModel(array $ids) {
        $this->filter_list['model'] = array();
        foreach($ids as $id) {
            $model = $this->em->getRepository(Model::class)->find($id);
            array_push($this->filter_list['model'], array(
                'id' => $model->getId(),
                'model' => $model->getModel()
            ));
        }
        $this->filter_list['model'] = json_encode($this->filter_list['model']);
    }

    public function getType(array $ids) {
        $this->filter_list['type'] = array();
        foreach($ids as $id) {
            $type = $this->em->getRepository(Type::class)->find($id);
            array_push($this->filter_list['type'], array(
                'id' => $type->getId(),
                'type' => $type->getType()
            ));
        }
        $this->filter_list['type'] = json_encode($this->filter_list['type']);
    }

    public function getFuel(string $id) {
        $fuel = $this->em->getRepository(Fuel::class)->findOneBy(['id' => $id]);
        $this->filter_list['fuel'] = array(
            'id' => $fuel->getId(),
            'fuel' => $fuel->getFuel()
        );
    }

    public function getGearbox(string $id) {
        $gearbox = $this->em->getRepository(Gearbox::class)->findOneBy(['id' => $id]);
        $this->filter_list['gearbox'] = array(
            'id' => $gearbox->getId(),
            'gearbox' => $gearbox->getGearbox()
        );
    }
}