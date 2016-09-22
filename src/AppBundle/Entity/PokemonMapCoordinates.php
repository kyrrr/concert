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
 	* @ORM\Table(name="PokemonMapCoordinates")
 	*/
	private $topLeft;
	private $topRight;
	private $bottomLeft;
	private $bottomRight;
}