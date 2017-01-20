<?php

namespace AppBundle\API;

use Symfony\Component\HttpFoundation\ParameterBag;

class API
{
    private $queryData;

    private $label = array ( "en" => "Flower Color" );
	private $description = array ( "en" => "Assign flower colors to plants." );
	private $icon = 'https://cdn.pixabay.com/photo/2016/01/21/19/57/marguerite-1154604_960_720.jpg';

    /**
     * API constructor.
     * @param $queryData ParameterBag
     */
    public function __construct($queryData)
    {
        $this->queryData = $queryData;
    }

    public function getDesc()
    {
        return array(
            "label" => $this->label,
    		"description" => $this->description,
    		"icon" => $this->icon
        );
    }

    public function getTiles()
    {
        return array(array("a" => 13));
    }

    public function getLogAction()
    {
        return array(array("a" => 13));
    }
}