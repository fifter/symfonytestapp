<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Please, type the product name.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Please, type the product thumbnail.")
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="text", length=150)
     * @Assert\NotBlank(message="Please, type the product description.")
     */
    private $description;

    /**
     * @ORM\Column(nullable=true)
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400,
     *     allowLandscape = false,
     *     allowPortrait = false
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="decimal")
     * @Assert\NotBlank(message="Please, type the product price.")
     */
    private $price;

    public function getId()
    {
        return $this->id;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setImage($image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

}