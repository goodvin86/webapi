<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperController extends AbstractController
{
    public function indexDeveloper(Request $request, DeveloperRepository $developerRepository): Response
    {
        $developer = $developerRepository->findAllDevelopers();
        return $this->json($developer);
    }

    public function getDeveloper(int $id, DeveloperRepository $developerRepository): Response
    {
        $developer = $developerRepository->findDeveloperById($id);
        if(!$developer) {
            return $this->json(['message' => 'Not found developer!']);
        }

        return $this->json($developer);
    }

    public function createDeveloper(Request $request, DeveloperRepository $developerRepository): Response
    {
        $parameters = json_decode($request->getContent(), true);

        if(empty($parameters)) {
            return $this->json(['message' =>'Error! Empty parameters!']);
        }

        $developer = new Developer();
        $developer->setName($parameters['name']);
        $developerRepository->save($developer, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function editDeveloper(int $id, Request $request, DeveloperRepository $developerRepository): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $developer = $developerRepository->find($id);
        if(!$developer) {
            return $this->json(['message' => 'Not found developer!']);
        }

        if(empty($parameters)) {
            return $this->json(['message' =>'Error! Empty parameters!']);
        }

        $developer->setName($parameters['name']);
        $developerRepository->save($developer, true);

        return $this->json(['message' => 'Successful!']);
    }

    public function deleteDeveloper(int $id, DeveloperRepository $developerRepository): Response
    {
        $developer = $developerRepository->find($id);
        if(!$developer) {
            return $this->json(['message' => 'Not found developer!']);
        }

        $developersGame = $developerRepository->findGamesWithThisDeveloper($id);
        if($developersGame) {
            return $this->json(['message' => 'First, delete all the games of this developer!']);
        }

        $developerRepository->remove($developer, true);

        return $this->json(['message' => 'Successful!']);
    }
}
