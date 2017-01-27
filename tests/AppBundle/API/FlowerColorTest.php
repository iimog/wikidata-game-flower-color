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
        $this->assertEquals($desc['description']['en'], 'Assign flower colors to plants.');
        $this->assertEquals($desc['label']['en'], 'Flower Color');
        $this->assertEquals($desc['icon'], 'https://wikidatagame.iimog.org/assets/img/marguerite-1154604_960_720.jpg');
    }

    public function testTiles()
    {
        $tiles = $this->flowerColor->getTiles(7);
        $this->assertEquals(count($tiles), 7);
        $tiles = $this->flowerColor->getTiles(5);
        $this->assertEquals(count($tiles), 5);
        $tiles = $this->flowerColor->getTiles(2);
        $this->assertEquals(count($tiles), 2);
        $num_of_colors = count($this->doctrine->getManager()->getRepository('AppBundle:Color')->findAll());
        $this->assertEquals(count($tiles[0]['controls'][0]['entries']), $num_of_colors+1, 'There should be one button for each color and a skip button');
        $this->assertEquals(count($tiles[1]['controls'][0]['entries']), $num_of_colors+1, 'There should be one button for each color and a skip button');
    }
}