<?php

namespace App\Entity;

use App\Repository\PortraitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PortraitRepository::class)
 */
class Portrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="portrait", cascade={"persist", "remove"})
     */
    private $user;



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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getPortrait() !== $this) {
            $user->setPortrait($this);
        }

        $this->user = $user;

        return $this;
    }
}
