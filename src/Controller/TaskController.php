<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 * @package App\Controller
 *
 * @Route("/api/")
 */

 class TaskController
 {

     private $taskRepository;

     public function __construct(TaskRepository $taskRepository)
     {
         $this->taskRepository = $taskRepository;
     }

    /**
    * @Route("task", name="add_task", methods={"POST"})
    */
    public function add(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['completed'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $name = $data['name'];
        $description = empty($data['description']) ? NULL : $data['description'];;
        $completed = $data['completed'];
        $start = empty($data['start']) ? NULL : $data['start'];
        $end = empty($data['end']) ? NULL : $data['end'];

        $this->taskRepository->saveTask($name, $description, $completed, $start, $end);

        return new JsonResponse(['status' => 'Task created!'], Response::HTTP_CREATED);
    }
 }