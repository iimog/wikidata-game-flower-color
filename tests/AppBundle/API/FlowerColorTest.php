<?php

namespace Tests\AppBundle\API;

use AppBundle\API\FlowerColor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FlowerColorTest extends KernelTestCase
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;
    private $flowerColor;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->doctrine = static::$kernel->getContainer()
            ->get('doctrine');

        $this->flowerColor = new FlowerColor($this->doctrine);
    }

    public function testDescription()
    {
        $desc = $this->flowerColor->getDesc();
        $this->assertEquals($desc['description']['en'], FlowerColor::DESCRIPTION_EN);
        $this->assertEquals($desc['label']['en'], FlowerColor::LABEL_EN);
        $this->assertEquals($desc['icon'], FlowerColor::ICON);
    }

    public function testTiles()
    {
        $tiles = $this->flowerColor->getTiles(7);
        $this->assertEquals(count($tiles), 7, 'Correct number of tiles: 7');
        $tiles = $this->flowerColor->getTiles(5);
        $this->assertEquals(count($tiles), 5, 'Correct number of tiles: 5');
        $tiles = $this->flowerColor->getTiles(2);
        $this->assertEquals(count($tiles), 2, 'Correct number of tiles: 2');
        $num_of_colors = count($this->doctrine->getManager()->getRepository('AppBundle:Color')->findAll());
        $this->assertEquals(count($tiles[0]['controls'][0]['entries']), $num_of_colors+1, 'There should be one button for each color and a skip button');
        $this->assertEquals(count($tiles[1]['controls'][0]['entries']), $num_of_colors+1, 'There should be one button for each color and a skip button');
        $this->assertEquals($tiles[0]['sections'][0]['type'], 'item', 'First section should be of type item');
        $this->assertEquals($tiles[0]['sections'][1]['type'], 'wikipage', 'Second section should be of type wikipage');
        $this->assertEquals(substr($tiles[0]['sections'][0]['q'],0,1), 'Q', 'Query starts with Q');
        $plant = $this->doctrine->getManager()->getRepository('AppBundle:Plant')->find($tiles[0]['id']);
        $this->assertEquals($tiles[0]['sections'][0]['q'], $plant->getWikidataId(), 'Query in first tile correct');
        $this->assertEquals($tiles[0]['sections'][1]['title'], $plant->getScientificName(), 'Scientific name in first tile correct');
        $plant = $this->doctrine->getManager()->getRepository('AppBundle:Plant')->find($tiles[1]['id']);
        $this->assertEquals($tiles[1]['sections'][0]['q'], $plant->getWikidataId(), 'Query in second tile correct');
        $this->assertEquals($tiles[1]['sections'][1]['title'], $plant->getScientificName(), 'Scientific name in second tile correct');
    }

    public function testLogAction()
    {
        $plant_id = 42;
        $plant = $this->doctrine->getManager()->getRepository('AppBundle:Plant')->find($plant_id);
        $this->assertEquals($plant->getFinished(), false, 'Before log action: plant is not finished');
        $this->assertEquals(count($plant->getFlowerColors()), 0, 'Before log action: plant has no colors');
        $color = $this->doctrine->getManager()->getRepository('AppBundle:Color')->find(7);
        $this->flowerColor->getLogAction($plant_id, $color->getWikidataId());
        $plant = $this->doctrine->getManager()->getRepository('AppBundle:Plant')->find($plant_id);
        $this->assertEquals($plant->getFinished(), true, 'After log action: plant is finished');
        $this->assertEquals(count($plant->getFlowerColors()), 1, 'After log action: plant has one color');
        $this->assertEquals($plant->getFlowerColors()[0]->getWikidataID(), $color->getWikidataId(), 'After log action: plant has color from decision');

    }
}