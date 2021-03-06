<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks()]
class Order
{

    CONST STEP1 = 'ordered';
    CONST STEP2 = 'shipped';
    CONST STEP3 = 'on the way';
    CONST STEP4 = 'delivered';

    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 10)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: ProductQuantity::class)]
    private $productQuantities;

    #[ORM\Column(type: 'string', length: 50, unique: true, nullable: true)]
    private $billNumber;

    public function __construct()
    {
        $this->productQuantities = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, ProductQuantity>
     */
    public function getProductQuantities(): Collection
    {
        return $this->productQuantities;
    }

    public function addProductQuantity(ProductQuantity $productQuantity): self
    {
        if (!$this->productQuantities->contains($productQuantity)) {
            $this->productQuantities[] = $productQuantity;
            $productQuantity->setOrders($this);
        }

        return $this;
    }

    public function removeProductQuantity(ProductQuantity $productQuantity): self
    {
        if ($this->productQuantities->removeElement($productQuantity)) {
            // set the owning side to null (unless already changed)
            if ($productQuantity->getOrders() === $this) {
                $productQuantity->setOrders(null);
            }
        }

        return $this;
    }


    public function step()
    {
        return [
            self::STEP1,
            self::STEP2,
            self::STEP3,
            self::STEP4,
        ];
    }

    public function getBillNumber(): ?string
    {
        return $this->billNumber;
    }

    public function setBillNumber(string $billNumber = null): self
    {
        $this->billNumber = $billNumber;

        return $this;
    }


}
