<?php

namespace AppBundle\API;

use Symfony\Bridge\Doctrine\RegistryInterface;

class API
{
    private $registry;

    private $label = array ( "en" => "Flower Color" );
	private $description = array ( "en" => "Assign flower colors to plants." );
	private $icon = 'https://cdn.pixabay.com/photo/2016/01/21/19/57/marguerite-1154604_960_720.jpg';

    /**
     * API constructor.
     * @param $registry RegistryInterface
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getDesc()
    {
        return array(
            "label" => $this->label,
    		"description" => $this->description,
    		"icon" => $this->icon
        );
    }

    /**
     * @param $num int - Number of tiles to return
     * @param $lang string - Preferred language code (default: "en")
     * @return array
     */
    public function getTiles($num, $lang="en")
    {
        $plants = $this->registry->getRepository('AppBundle:Plant')->findAll();
        $random_keys = array_rand($plants, $num);
        return array("keys" => $random_keys);
    }

    public function getLogAction()
    {
        return array(array("a" => 13));
    }
}