<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route; 

#[Route('/api')] // Base path for all routes in this controller
class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(TaskRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? null);

        $em->persist($task);
        $em->flush();

        return $this->json($task, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Task $task, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setIsCompleted($data['isCompleted'] ?? $task->isCompleted());

        $em->flush();

        return $this->json($task);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($task);
        $em->flush();

        return $this->json(null, 204);
    }
}
