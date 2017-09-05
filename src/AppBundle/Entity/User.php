<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    private $products;


    public function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
        // your own logic
    }

    public function getUserId()
    {
        return $this->id;
    }
}