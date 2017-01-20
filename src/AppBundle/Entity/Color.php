<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="color")
 */
class Color
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $wikidata_id;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $color;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set wikidataId
     *
     * @param string $wikidataId
     *
     * @return Color
     */
    public function setWikidataId($wikidataId)
    {
        $this->wikidata_id = $wikidataId;

        return $this;
    }

    /**
     * Get wikidataId
     *
     * @return string
     */
    public function getWikidataId()
    {
        return $this->wikidata_id;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Color
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
