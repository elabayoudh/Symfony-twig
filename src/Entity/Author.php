<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_books = 0;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
private Collection $books;

public function __construct()
{
    $this->books = new ArrayCollection();
}

/**
 * @return Collection<int, Book>
 */
public function getBooks(): Collection
{
    return $this->books;
}

public function addBook(Book $book): static
{
    if (!$this->books->contains($book)) {
        $this->books->add($book);
        $book->setAuthor($this);
    }
    return $this;
}

public function removeBook(Book $book): static
{
    if ($this->books->removeElement($book)) {
        if ($book->getAuthor() === $this) {
            $book->setAuthor(null);
        }
    }
    return $this;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nb_books;
    }

    public function setNbBooks(?int $nb_books): static
    {
        $this->nb_books = $nb_books;

        return $this;
    }

    
}