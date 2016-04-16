<?php

namespace SpectateBundle\Entity;

/**
 * Reprezentation
 */
class Reprezentation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $distribution;

    /**
     * @var int
     */
    private $numberOfSeats;


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
     * Set data
     *
     * @param string $data
     *
     * @return Reprezentation
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
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
     * @return int
     */
    public function getNumberOfSeats()
    {
        return $this->numberOfSeats;
    }
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \SpectateBundle\Entity\Spectate
     */
    private $spectate;


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
        return $this->location;
    }
}
