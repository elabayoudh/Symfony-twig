<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/books', name: 'list_books')]
    public function listBooks(BookRepository $bookRepository): Response
    {
        $publishedBooks = $bookRepository->findBy(['published' => true]);
        $publishedCount = count($publishedBooks);
        $unpublishedCount = $bookRepository->count(['published' => false]);

        return $this->render('book/list.html.twig', [
            'books' => $publishedBooks,
            'published_count' => $publishedCount,
            'unpublished_count' => $unpublishedCount,
        ]);
    }

    #[Route('/book/add', name: 'add_book')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setPublished(true); // Par défaut, publié

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $entityManager->persist($book);
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté avec succès !');
            return $this->redirectToRoute('list_books');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/edit/{id}', name: 'edit_book')]
    public function editBook(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Livre modifié avec succès !');
            return $this->redirectToRoute('list_books');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/book/delete/{id}', name: 'delete_book')]
    public function deleteBook(int $id, BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $author = $book->getAuthor();
        $author->setNbBooks($author->getNbBooks() - 1);

        $entityManager->remove($book);
        $entityManager->persist($author);
        $entityManager->flush();

        $this->addFlash('success', 'Livre supprimé avec succès !');
        return $this->redirectToRoute('list_books');
    }

    #[Route('/book/show/{id}', name: 'show_book')]
    public function showBook(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}