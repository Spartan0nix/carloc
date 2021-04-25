<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentRepository::class)
 */
class Rent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pickup_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $return_date;

    /**
     * @ORM\ManyToOne(targetEntity=Offices::class, inversedBy="pickup_rents")
     * @ORM\JoinColumn(name="pickup_office_id", nullable=false)
     */
    private $pickup_office_id;

    /**
     * @ORM\ManyToOne(targetEntity=Offices::class, inversedBy="return_rents")
     * @ORM\JoinColumn(name="return_office_id", nullable=false)
     */
    private $return_office_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rents")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPickupDate(): ?\DateTimeInterface
    {
        return $this->pickup_date;
    }

    public function setPickupDate(\DateTimeInterface $pickup_date): self
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getPickupOfficeId(): ?Offices
    {
        return $this->pickup_office_id;
    }

    public function setPickupOfficeId(?Offices $pickup_office_id): self
    {
        $this->pickup_office_id = $pickup_office_id;

        return $this;
    }

    public function getReturnOfficeId(): ?Offices
    {
        return $this->return_office_id;
    }

    public function setReturnOfficeId(?Offices $return_office_id): self
    {
        $this->return_office_id = $return_office_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
