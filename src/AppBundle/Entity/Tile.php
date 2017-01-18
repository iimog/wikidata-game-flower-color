<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tile")
 */
class Tile
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $wikidata_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished;

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
}
