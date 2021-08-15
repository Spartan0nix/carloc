<?php

namespace App\Entity;

use App\Entity\Components\Brand;
use App\Entity\Components\Color;
use App\Entity\Components\Fuel;
use App\Entity\Components\Gearbox;
use App\Entity\Components\Model;
use App\Entity\Components\Option;
use App\Entity\Components\Type;
use App\Entity\Components\carImage;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
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
    private $horsepower;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Fuel::class, inversedBy="cars")
     * @ORM\JoinColumn(name="fuel_id", nullable=false)
     */
    private $fuel_id;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="cars")
     * @ORM\JoinColumn(name="brand_id", nullable=false)
     */
    private $brand_id;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="cars")
     * @ORM\JoinColumn(name="model_id", nullable=false)
     */
    private $model_id;

    /**
     * @ORM\ManyToOne(targetEntity=Color::class, inversedBy="cars")
     * @ORM\JoinColumn(name="color_id", nullable=false)
     */
    private $color_id;

    /**
     * @ORM\ManyToOne(targetEntity=Gearbox::class, inversedBy="cars")
     * @ORM\JoinColumn(name="gearbox_id", nullable=false)
     */
    private $gearbox_id;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="cars")
     */
    private $type_id;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="cars")
     */
    private $option_id;

    /**
     * @ORM\ManyToMany(targetEntity=carImage::class, inversedBy="cars")
     */
    private $image_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $daily_price;

    /**
     * @ORM\ManyToOne(targetEntity=Office::class, inversedBy="cars")
     * @ORM\JoinColumn(name="office_id", nullable=false)
     */
    private $office_id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $release_year;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class, mappedBy="car_id", orphanRemoval=true)
     */
    private $rents;

    public function __construct()
    {
        $this->type_id = new ArrayCollection();
        $this->option_id = new ArrayCollection();
        $this->image_id = new ArrayCollection();
        $this->rents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHorsepower(): ?int
    {
        return $this->horsepower;
    }

    public function setHorsepower(int $horsepower): self
    {
        $this->horsepower = $horsepower;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFuelId(): ?Fuel
    {
        return $this->fuel_id;
    }

    public function setFuelId(?Fuel $fuel_id): self
    {
        $this->fuel_id = $fuel_id;

        return $this;
    }

    public function getBrandId(): ?Brand
    {
        return $this->brand_id;
    }

    public function setBrandId(?Brand $brand_id): self
    {
        $this->brand_id = $brand_id;

        return $this;
    }

    public function getModelId(): ?Model
    {
        return $this->model_id;
    }

    public function setModelId(?Model $model_id): self
    {
        $this->model_id = $model_id;

        return $this;
    }

    public function getColorId(): ?Color
    {
        return $this->color_id;
    }

    public function setColorId(?Color $color_id): self
    {
        $this->color_id = $color_id;

        return $this;
    }

    public function getGearboxId(): ?Gearbox
    {
        return $this->gearbox_id;
    }

    public function setGearboxId(?Gearbox $gearbox_id): self
    {
        $this->gearbox_id = $gearbox_id;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypeId(): Collection
    {
        return $this->type_id;
    }

    public function addTypeId(Type $typeId): self
    {
        if (!$this->type_id->contains($typeId)) {
            $this->type_id[] = $typeId;
        }

        return $this;
    }

    public function removeTypeId(Type $typeId): self
    {
        $this->type_id->removeElement($typeId);

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptionId(): Collection
    {
        return $this->option_id;
    }

    public function addOptionId(Option $optionId): self
    {
        if (!$this->option_id->contains($optionId)) {
            $this->option_id[] = $optionId;
        }

        return $this;
    }

    public function removeOptionId(Option $optionId): self
    {
        $this->option_id->removeElement($optionId);

        return $this;
    }

    /**
     * @return Collection|carImage[]
     */
    public function getImageId(): Collection
    {
        return $this->image_id;
    }

    public function addImageId(carImage $imageId): self
    {
        if (!$this->image_id->contains($imageId)) {
            $this->image_id[] = $imageId;
        }

        return $this;
    }

    public function removeImageId(carImage $imageId): self
    {
        $this->image_id->removeElement($imageId);

        return $this;
    }

    public function getDailyPrice(): ?int
    {
        return $this->daily_price;
    }

    public function setDailyPrice(int $daily_price): self
    {
        $this->daily_price = $daily_price;

        return $this;
    }

    public function getOfficeId(): ?Office
    {
        return $this->office_id;
    }

    public function setOfficeId(?Office $office_id): self
    {
        $this->office_id = $office_id;

        return $this;
    }

    public function getReleaseYear(): ?string
    {
        return $this->release_year;
    }

    public function setReleaseYear(string $release_year): self
    {
        $this->release_year = $release_year;

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents[] = $rent;
            $rent->setCarId($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getCarId() === $this) {
                $rent->setCarId(null);
            }
        }

        return $this;
    }
}
