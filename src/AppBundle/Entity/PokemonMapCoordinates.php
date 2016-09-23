<?
// src/AppBundle/Entity/PokemonMapCoordinates.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PokemonMapCoordinates")
 */

//Coordinates per id in Pokemon for a square to be placed around a zone
class PokemonMapCoordinates
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(targetEntity="Pokemon")
     * @ORM\JoinColumn(name="coordinatesId", referencedColumnName="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/**
 	* @ORM\Column(type="integer")
 	*/
	private $topLeft;
	

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
     * Set topLeft
     *
     * @param integer $topLeft
     *
     * @return PokemonMapCoordinates
     */
    public function setTopLeft($topLeft)
    {
        $this->topLeft = $topLeft;

        return $this;
    }

    /**
     * Get topLeft
     *
     * @return integer
     */
    public function getTopLeft()
    {
        return $this->topLeft;
    }
}
