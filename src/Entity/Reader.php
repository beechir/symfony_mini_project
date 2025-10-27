<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $useName = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readers')]
    private Collection $bookReader;

    public function __construct()
    {
        $this->bookReader = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUseName(): ?string
    {
        return $this->useName;
    }

    public function setUseName(string $useName): static
    {
        $this->useName = $useName;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBookReader(): Collection
    {
        return $this->bookReader;
    }

    public function addBookReader(Book $bookReader): static
    {
        if (!$this->bookReader->contains($bookReader)) {
            $this->bookReader->add($bookReader);
        }

        return $this;
    }

    public function removeBookReader(Book $bookReader): static
    {
        $this->bookReader->removeElement($bookReader);

        return $this;
    }
}
