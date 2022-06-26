<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Message
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    private $team;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $Customer;

    #[ORM\ManyToOne(targetEntity: Ticket::class)]
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTeam(): ?User
    {
        return $this->team;
    }

    public function setTeam(?User $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->Customer;
    }

    public function setCustomer(?User $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }


}
