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
}