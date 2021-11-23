<?php

namespace App\Entity\Address;

use App\Entity\Office;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\Address\CityRepository::class)
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
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="cities")
     * @ORM\JoinColumn(name="department_id", nullable=false)
     */
    private $department_id;

    /**
     * @ORM\OneToMany(targetEntity=Office::class, mappedBy="city_id")
     */
    private $office;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="city_id")
     */
    private $users;

    public function __construct()
    {
        $this->office = new ArrayCollection();
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

    public function getDepartmentId(): ?Department
    {
        return $this->department_id;
    }

    public function setDepartmentId(?Department $department_id): self
    {
        $this->department_id = $department_id;

        return $this;
    }

    /**
     * @return Collection|Office[]
     */
    public function getOffice(): Collection
    {
        return $this->office;
    }

    public function addOffice(Office $office): self
    {
        if (!$this->office->contains($office)) {
            $this->office[] = $office;
            $office->setCityId($this);
        }

        return $this;
    }

    public function removeOffice(Office $office): self
    {
        if ($this->office->removeElement($office)) {
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
