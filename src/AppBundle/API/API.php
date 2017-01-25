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
        $tiles = array();
        for($i=0; $i<$num; $i++){
            $tile = array();
            $tile['id'] = $plants[$random_keys[$i]]->getId();
            $tile['sections'] = array(
                array('type' => 'item', 'q' => $plants[$random_keys[$i]]->getWikidataId()),
                array('type' => 'wikipage', 'title' => $plants[$random_keys[$i]]->getScientificName(), 'wiki' => 'enwiki')
            );
            $tile['controls'] = array(array('type' => 'buttons', 'entries' => array(
                array('type' => 'blue', 'decision' => 'done', 'label' => 'Done'),
                array('type' => 'white', 'decision' => 'skip', 'label' => 'Skip')
            )));
            $colors = $this->registry->getRepository('AppBundle:Color')->findAll();
            foreach ($colors as $color){
                $tile['controls'][0]['entries'][] = array('type' => 'green', 'decision' => $color->getWikidataId(), 'label' => $color->getColor());
            }
            $tiles[] = $tile;
        }
        return $tiles;
    }

    public function getLogAction()
    {
        return array(array("a" => 13));
    }
}