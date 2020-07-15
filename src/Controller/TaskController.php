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
     * @Route("task/{id}", name="get_one_task", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $task->getId(),
            'name' => $task->getName(),
            'description' => $task->getDescription(),
            'completed' => $task->getCompleted(),
            'start' => $task->getStart(),
            'end' => $task->getEnd()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("tasks", name="get_all_tasks", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $tasks = $this->taskRepository->findAll();
        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'name' => $task->getName(),
                'description' => $task->getDescription(),
                'completed' => $task->getCompleted(),
                'start' => $task->getStart(),
                'end' => $task->getEnd()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("task", name="add_task", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
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
