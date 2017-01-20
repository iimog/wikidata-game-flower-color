<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plant")
 */
class Plant
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
    private $scientific_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished;

    /**
     * Many Plants have Many FlowerColors.
     * @ORM\ManyToMany(targetEntity="Color")
     * @ORM\JoinTable(name="plant_colors",
     *      joinColumns={@ORM\JoinColumn(name="plant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="color_id", referencedColumnName="id")}
     *      )
     */
    private $flower_colors;

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
     * @return Tile
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
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Tile
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished()
    {
        return $this->finished;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flower_colors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set scientificName
     *
     * @param string $scientificName
     *
     * @return Plant
     */
    public function setScientificName($scientificName)
    {
        $this->scientific_name = $scientificName;

        return $this;
    }

    /**
     * Get scientificName
     *
     * @return string
     */
    public function getScientificName()
    {
        return $this->scientific_name;
    }

    /**
     * Add flowerColor
     *
     * @param \AppBundle\Entity\Color $flowerColor
     *
     * @return Plant
     */
    public function addFlowerColor(\AppBundle\Entity\Color $flowerColor)
    {
        $this->flower_colors[] = $flowerColor;

        return $this;
    }

    /**
     * Remove flowerColor
     *
     * @param \AppBundle\Entity\Color $flowerColor
     */
    public function removeFlowerColor(\AppBundle\Entity\Color $flowerColor)
    {
        $this->flower_colors->removeElement($flowerColor);
    }

    /**
     * Get flowerColors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlowerColors()
    {
        return $this->flower_colors;
    }
}
