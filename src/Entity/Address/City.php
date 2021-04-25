<?php

namespace App\Entity\Address;

use App\Entity\Offices;
use App\Entity\User;
use App\Repository\Address\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Offices::class, mappedBy="city_id")
     */
    private $offices;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="city_id")
     */
    private $users;

    public function __construct()
    {
        $this->offices = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Offices[]
     */
    public function getOffices(): Collection
    {
        return $this->offices;
    }

    public function addOffice(Offices $office): self
    {
        if (!$this->offices->contains($office)) {
            $this->offices[] = $office;
            $office->setCityId($this);
        }

        return $this;
    }

    public function removeOffice(Offices $office): self
    {
        if ($this->offices->removeElement($office)) {
            // set the owning side to null (unless already changed)
            if ($office->getCityId() === $this) {
                $office->setCityId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCityId($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCityId() === $this) {
                $user->setCityId(null);
            }
        }

        return $this;
    }
}
