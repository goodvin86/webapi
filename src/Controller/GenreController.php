<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends AbstractController
{
    public function indexGenre(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAllGenres();
        return $this->json($genre);
    }

    public function getGenre(int $id, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findGenreById($id);
        if(!$genre) {
            return $this->json(['message' => 'Not found genre!']);
        }
        return $this->json($genre);
    }

    public function createGenre(Request $request, GenreRepository $genreRepository): Response
    {
        $parameters = json_decode($request->getContent(), true);

        if(empty($parameters)) {
            return $this->json(['message' =>'Error! Empty parameters!']);
        }

        $genre = new Genre();
        $genre->setName($parameters['name']);
        $genreRepository->save($genre, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function editGenre(int $id, Request $request, GenreRepository $genreRepository): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $genre = $genreRepository->find($id);
        if(!$genre) {
            return $this->json(['message' => 'Not found genre!']);
        }

        if(empty($parameters)) {
            return $this->json(['message' =>'Error! Empty parameters!']);
        }

        $genre->setName($parameters['name']);
        $genreRepository->save($genre, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function deleteGenre(int $id, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->find($id);
        if(!$genre) {
            return $this->json(['message' => 'Not found genre!']);
        }

        $genreGame = $genreRepository->findGamesWithThisGenre($id);
        if($genreGame) {
            return $this->json(['message' => 'First, delete all the games of this genre!']);
        }

        $genreRepository->remove($genre, true);

        return $this->json(['message' => 'Successful!']);
    }
}
