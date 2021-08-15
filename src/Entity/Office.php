<?php

namespace App\Entity;

use App\Entity\Address\City;
use App\Entity\Address\Department;
use App\Repository\OfficeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfficeRepository::class)
 */
class Office
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tel_number;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="Office")
     * @ORM\JoinColumn(name="city_id", nullable=false)
     */
    private $city_id;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="pickup_office_id")
     */
    private $pickup_rents;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="return_office_id")
     */
    private $return_rents;

    /**
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="office_id")
     */
    private $cars;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="offices")
     * @ORM\JoinColumn(name="department_id", nullable=false)
     */
    private $department_id;

    public function __construct()
    {
        $this->pickup_rents = new ArrayCollection();
        $this->return_rents = new ArrayCollection();
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getTelNumber(): ?string
    {
        return $this->tel_number;
    }

    public function setTelNumber(string $tel_number): self
    {
        $this->tel_number = $tel_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCityId(): ?City
    {
        return $this->city_id;
    }

    public function setCityId(?City $city_id): self
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getPickupRents(): Collection
    {
        return $this->pickup_rents;
    }

    public function addPickupRent(Rent $pickupRent): self
    {
        if (!$this->pickup_rents->contains($pickupRent)) {
            $this->pickup_rents[] = $pickupRent;
            $pickupRent->setPickupOfficeId($this);
        }

        return $this;
    }

    public function removePickupRent(Rent $pickupRent): self
    {
        if ($this->pickup_rents->removeElement($pickupRent)) {
            // set the owning side to null (unless already changed)
            if ($pickupRent->getPickupOfficeId() === $this) {
                $pickupRent->setPickupOfficeId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getReturnRents(): Collection
    {
        return $this->return_rents;
    }

    public function addReturnRent(Rent $returnRent): self
    {
        if (!$this->return_rents->contains($returnRent)) {
            $this->return_rents[] = $returnRent;
            $returnRent->setReturnOfficeId($this);
        }

        return $this;
    }

    public function removeReturnRent(Rent $returnRent): self
    {
        if ($this->return_rents->removeElement($returnRent)) {
            // set the owning side to null (unless already changed)
            if ($returnRent->getReturnOfficeId() === $this) {
                $returnRent->setReturnOfficeId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setOfficeId($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getOfficeId() === $this) {
                $car->setOfficeId(null);
            }
        }

        return $this;
    }

    public function getDepartmentId(): ?Department
    {
        return $this->department_id;
    }

    public function setDepartmentId(?Department $department_id): self
    {
        $this->department_id = $department_id;

        return $this;
    }
}
