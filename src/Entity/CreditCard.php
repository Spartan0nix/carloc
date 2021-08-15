<?php

namespace App\Entity;

use App\Repository\CreditCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreditCardRepository::class)
 */
class CreditCard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $owner;

    /**
     * @ORM\Column(type="date")
     */
    private $expiration_date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $cvv;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="creditCards")
     */
    private $user_id;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->user_id->removeElement($userId);

        return $this;
    }
}
