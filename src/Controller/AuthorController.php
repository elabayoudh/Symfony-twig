<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AuthorType;
use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;



class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'show_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('Author/show.html.twig', [
            'name' => $name 
        ]);
    }

    #[Route('/authors' , name:'list_authors')]
    public function listAuthors(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

#[Route('/author/details/{id}', name: 'author_details')]
public function authorDetails(int $id): Response
{
    $authors = [
        1 => ['picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        2 => ['picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        3 => ['picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300]
    ];

    if (!isset($authors[$id])) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    return $this->render('author/showAuthor.html.twig', [
        'author' => $authors[$id]
    ]);
}

#[Route('/author/add-static', name: 'add_author_static')]
    public function addAuthorStatic(EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $author->setUsername('J.K. Rowling');
        $author->setEmail('jk.rowling@gmail.com');
        $author->setNbBooks(50);

        $entityManager->persist($author);
        $entityManager->flush();

        $this->addFlash('success', 'Auteur ajouté avec succès !');
        return $this->redirectToRoute('list_authors');
    }

    #[Route('/author/add', name: 'add_author')]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('list_authors');
        }

        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/edit/{id}', name: 'edit_author')]
    public function editAuthor(int $id, Request $request, AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Auteur modifié avec succès !');
            return $this->redirectToRoute('list_authors');
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'delete_author')]
    public function deleteAuthor(int $id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'Auteur supprimé avec succès !');
        return $this->redirectToRoute('list_authors');
    }


    #[Route('/authors/clean', name: 'clean_authors')]
public function cleanAuthors(AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
{
    $authors = $authorRepository->findBy(['nb_books' => 0]);
    foreach ($authors as $author) {
        $entityManager->remove($author);
    }
    $entityManager->flush();

    $this->addFlash('success', 'Auteurs sans livres supprimés !');
    return $this->redirectToRoute('list_authors');
}
}
