<?php

namespace SpectateBundle\Entity;

class Reprezentation
{
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $location;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $distribution;

    /**
     * @var integer
     */
    private $numberOfSeats;

    /**
     * @var \SpectateBundle\Entity\Spectate
     */
    private $spectate;


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
     * Set location
     *
     * @param string $location
     *
     * @return Reprezentation
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reprezentation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set distribution
     *
     * @param string $distribution
     *
     * @return Reprezentation
     */
    public function setDistribution($distribution)
    {
        $this->distribution = $distribution;

        return $this;
    }

    /**
     * Get distribution
     *
     * @return string
     */
    public function getDistribution()
    {
        return $this->distribution;
    }

    /**
     * Set numberOfSeats
     *
     * @param integer $numberOfSeats
     *
     * @return Reprezentation
     */
    public function setNumberOfSeats($numberOfSeats)
    {
        $this->numberOfSeats = $numberOfSeats;

        return $this;
    }

    /**
     * Get numberOfSeats
     *
     * @return integer
     */
    public function getNumberOfSeats()
    {
        return $this->numberOfSeats;
    }

    /**
     * Set spectate
     *
     * @param \SpectateBundle\Entity\Spectate $spectate
     *
     * @return Reprezentation
     */
    public function setSpectate(\SpectateBundle\Entity\Spectate $spectate = null)
    {
        $this->spectate = $spectate;

        return $this;
    }

    /**
     * Get spectate
     *
     * @return \SpectateBundle\Entity\Spectate
     */
    public function getSpectate()
    {
        return $this->spectate;
    }

    public function __toString()
    {
        $s = $this->location." - ".$this->date->format('D h:i');
        return $s;
    }
}
