<?php

namespace App\Command;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-authors')]
class AddAuthorsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authors = [
            ['username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        foreach ($authors as $data) {
            $author = new Author();
            $author->setUsername($data['username']);
            $author->setEmail($data['email']);
            $author->setNbBooks($data['nb_books']);
            $this->entityManager->persist($author);
        }

        $this->entityManager->flush();
        $output->writeln('Auteurs ajoutés avec succès !');

        return Command::SUCCESS;
    }
}