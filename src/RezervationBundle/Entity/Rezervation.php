<?php

namespace RezervationBundle\Entity;

/**
 * Rezervation
 */
class Rezervation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $details;

    /**
     * @var string
     */
    private $seats;

    /**
     * @var \SpectateBundle\Entity\Reprezentation
     */
    private $reprezentation;

    /**
     * @var \UserBundle\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return Rezervation
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set seats
     *
     * @param string $seats
     *
     * @return Rezervation
     */
    public function setSeats($seats)
    {
        if($seats){
        $seats = implode(" ",$seats);
        $seats = trim($seats);
        $this->seats = $seats;
        return $this;
        }
        else return $this;
    }

    /**
     * Get seats
     *
     * @return string
     */
    public function getSeats()
    {
        $seatsArray = explode(" ",$this->seats);
        return $seatsArray;
    }

    /**
     * Set reprezentation
     *
     * @param \SpectateBundle\Entity\Reprezentation $reprezentation
     *
     * @return Rezervation
     */
    public function setReprezentation(\SpectateBundle\Entity\Reprezentation $reprezentation = null)
    {
        $this->reprezentation = $reprezentation;

        return $this;
    }

    /**
     * Get reprezentation
     *
     * @return \SpectateBundle\Entity\Reprezentation
     */
    public function getReprezentation()
    {
        return $this->reprezentation;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Rezervation
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
