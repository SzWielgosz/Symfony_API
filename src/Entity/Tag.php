<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Symphony::class, inversedBy: 'tags')]
    private Collection $symphonies;

    public function __construct()
    {
        $this->symphonies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Symphony>
     */
    public function getSymphonies(): Collection
    {
        return $this->symphonies;
    }

    public function addSymphony(Symphony $symphony): static
    {
        if (!$this->symphonies->contains($symphony)) {
            $this->symphonies->add($symphony);
        }

        return $this;
    }

    public function removeSymphony(Symphony $symphony): static
    {
        $this->symphonies->removeElement($symphony);

        return $this;
    }
}
