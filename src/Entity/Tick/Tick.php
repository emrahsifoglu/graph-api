<?php

namespace App\Entity\Tick;

use App\Vendor\Database\Entity\AbstractEntity;

class Tick extends AbstractEntity
{

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $title;

    /** @var int */
    protected $data;

    /** @var string */
    protected $backgroundColor;

    /** @var string */
    protected $backgroundColorOpacity;

    /** @var string */
    protected $borderColor;

    /** @var string */
    protected $borderColorOpacity;

    /** @var int */
    protected $borderWidth;

    /** @param $id int */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getBackgroundColor() {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return int
     */
    public function getBackgroundColorOpacity() {
        return $this->backgroundColorOpacity;
    }

    /**
     * @param string $backgroundColorOpacity
     */
    public function setBackgroundColorOpacity($backgroundColorOpacity) {
        $this->backgroundColorOpacity = $backgroundColorOpacity;
    }
    
    /**
     * @return string
     */
    public function getBorderColor() {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     */
    public function setBorderColor($borderColor) {
        $this->borderColor = $borderColor;
    }

    /**
     * @return int
     */
    public function getBorderColorOpacity() {
        return $this->borderColorOpacity;
    }

    /**
     * @param string $borderColorOpacity
     */
    public function setBorderColorOpacity($borderColorOpacity) {
        $this->borderColorOpacity = $borderColorOpacity;
    }

    /**
     * @param int $borderWidth
     */
    public function setBorderWidth($borderWidth) {
        $this->borderWidth = $borderWidth;
    }

    /**
     * @return int
     */
    public function getBorderWidth() {
        return $this->borderWidth;
    }

    /**
     * @return array
     */
    public function getArrayCopy() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'data' => $this->data,
            'backgroundColor' => $this->backgroundColor,
            'backgroundColorOpacity' => $this->backgroundColorOpacity,
            'borderColor' => $this->borderColor,
            'borderColorOpacity' => $this->borderColorOpacity,
            'borderWidth' => $this->borderWidth
        ];
    }
}
