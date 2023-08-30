<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\DeveloperRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function indexGame(Request $request, GameRepository $gameRepository, GenreRepository $genreRepository): Response
    {
        $genreId = $request->query->get('genre');

        if ($genreId) {
            $genre = $genreRepository->find($genreId);
            if(!$genre) {
                return $this->json(['message' => 'Not found genre with this id!']);
            }
            $games = $gameRepository->findGamesByGenre($genreId);
            return $this->json($games);
        }

        $games = $gameRepository->findAllGame();
        return $this->json($games);
    }

    public function getGame(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findGameById($id);
        if($game[0]['id']==null) {
            return $this->json(['message' => 'Not found game!']);
        }
        return $this->json($game);
    }

    public function createGame(
        Request $request,
        GameRepository $gameRepository,
        ValidationService $validationService,
        DeveloperRepository $developerRepository,
        GenreRepository $genreRepository
    ): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $validate = $validationService->parametersValidate($parameters);
        if (!empty($validate)){
            return $this->json(['message' => $validate]);
        }

        $developer = $developerRepository->find($parameters['developer_id']);
        if(!$developer) {
            return $this->json(['message' => 'Not found developer with this developer_id!']);
        }

        foreach ($parameters['genre_id'] as $genreId){
            $genre = $genreRepository->find($genreId);
            if(!$genre) {
                return $this->json(['message' => 'Not found genre with genre_id='.$genreId]);
            }
        }

        $game = new Game();

        foreach ($parameters['genre_id'] as $genreId){
            $genre = $genreRepository->find($genreId);
            $game->setName($parameters['name']);
            $game->setDeveloper($developer);
            $game->addGenre($genre);
        }
        $gameRepository->save($game, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function editGame(
        int $id,
        Request $request,
        GameRepository $gameRepository,
        ValidationService $validationService,
        DeveloperRepository $developerRepository,
        GenreRepository $genreRepository,
    ): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $game = $gameRepository->find($id);

        if(!$game) {
            return $this->json(['message' => 'Not found game!']);
        }

        $validate = $validationService->parametersValidate($parameters);
        if (!empty($validate)){
            return $this->json(['message' => $validate]);
        }

        $developer = $developerRepository->find($parameters['developer_id']);
        if(!$developer) {
            return $this->json(['message' => 'Not found developer with this developer_id!']);
        }

        foreach ($parameters['genre_id'] as $genreId){
            $genre = $genreRepository->find($genreId);
            if(!$genre) {
                return $this->json(['message' => 'Not found genre with genre_id='.$genreId]);
            }
        }

        $idGenresOfThisGame = $genreRepository->findGenresWithThisGame($id);
        foreach($idGenresOfThisGame as $genreId){
            $game->removeGenre($genreRepository->find($genreId['id']));
        }

        foreach ($parameters['genre_id'] as $genreId){
            $genre = $genreRepository->find($genreId);
            $game->setName($parameters['name']);
            $game->setDeveloper($developer);
            $game->addGenre($genre);
        }

        $gameRepository->save($game, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function deleteGame(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);
        if(!$game) {
            return $this->json(['message' => 'Not found game!']);
        }

        $gameRepository->remove($game, true);

        return $this->json(['message' => 'Successful!']);
    }
}
