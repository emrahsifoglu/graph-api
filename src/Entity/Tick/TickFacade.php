<?php

namespace App\Entity\Tick;

use App\Vendor\Database\Entity\EntityInterface;

class TickFacade
{

    /** @var TickRepository */
    public $tickRepository;

    /**
     * @param TickRepository $tickRepository
     */
    public function __construct(
        TickRepository $tickRepository
    ) {
        $this->tickRepository = $tickRepository;
    }

    /**
     * @return array
     */
    public function getAllTicks() {
        return $this->tickRepository->findAll();
    }

    /**
     * @param $id
     * @return Tick|EntityInterface|null
     */
    public function getTickFromId($id) {
        return $this->tickRepository->findById($id);
    }

    /**
     * @param array $params
     * @return Tick|int
     */
    public function createFromParams(array $params) {
        $title = normalize($params['title']);
        $name = normalize($params['name']);
        $data = normalize($params['data']);
        $borderColor = normalize($params['borderColor']);
        $borderColorOpacity = floatval(normalize($params['borderColorOpacity']));
        $backgroundColor = normalize($params['backgroundColor']);
        $backgroundColorOpacity = floatval(normalize($params['backgroundColorOpacity']));
        $borderWidth = normalize($params['borderWidth']);

        if (strlen($title) == 0 || strlen($title) > 255) {
            return -1;
        }

        if (strlen($name) == 0 || strlen($name) > 255) {
            return -2;
        }

        if (!is_numeric($data) || $data < 1 || $data > 255) {
            return -3;
        }

        if (!preg_match('/#([a-f0-9]{3}){1,2}\b/i', $borderColor)) {
            return -4;
        }  

        if (!is_numeric($borderColorOpacity) || $borderColorOpacity < 0 || $borderColorOpacity > 1) {
            return -5;
        }
        
        if (!preg_match('/#([a-f0-9]{3}){1,2}\b/i', $backgroundColor)) {
            return -6;
        }  
      
        if (!is_numeric($backgroundColorOpacity) || $backgroundColorOpacity < 0 || $backgroundColorOpacity > 1) {
            return -7;
        }

        if (!is_numeric($borderWidth) || $borderWidth < 1 || $borderWidth > 255) {
            return -8;
        }

        $tick = new Tick();
        $tick->setTitle($title);
        $tick->setName($name);
        $tick->setData($data);
        $tick->setBorderColor($borderColor);
        $tick->setBorderColorOpacity($borderColorOpacity);
        $tick->setBackgroundColor($backgroundColor);
        $tick->setBackgroundColorOpacity($backgroundColorOpacity);
        $tick->setBorderWidth($borderWidth);

        return $tick;
    }

    /**
     * @param Tick $tick
     */
    public function save(Tick &$tick) {
        $entity = [
            'title' => $tick->getTitle(),
            'name' => $tick->getName(),
            'data' => $tick->getData(),
            'border_color' => $tick->getBorderColor(),
            'border_color_opacity' => $tick->getBorderColorOpacity(),
            'background_color' => $tick->getBackgroundColor(),
            'background_color_opacity' => $tick->getBackgroundColorOpacity(),
            'border_width' => $tick->getBorderWidth()
        ];

        $id = $this->tickRepository->create($entity);
        $tick->setId($id);
    }
}
