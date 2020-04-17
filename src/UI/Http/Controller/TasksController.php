<?php declare(strict_types=1);

namespace Toddy\UI\Http\Controller;

use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Toddy\Application\UseCases\CompleteTask\CompleteTaskCommand;
use Toddy\Application\UseCases\CreateTask\CreateTaskCommand;
use Toddy\Application\UseCases\RescheduleTask\RescheduleTaskCommand;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\Task\TaskRepositoryInterface;
use Toddy\Domain\User\UserId;
use Toddy\Domain\User\UserRepositoryInterface;
use Toddy\UI\Http\Request\ListTasksRequest;
use Toddy\UI\Http\Response\ViolationsFormatter;


/**
 * Class TasksController
 * @package Toddy\UI\Api
 * @author Faley Aliaksandr
 */
class TasksController
{
    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ViolationsFormatter
     */
    protected $violationsFormatter;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * TasksController constructor.
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     * @param ViolationsFormatter $violationsFormatter
     */
    public function __construct(
        CommandBus $commandBus,
        DenormalizerInterface $denormalizer,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ViolationsFormatter $violationsFormatter
    ) {
        $this->commandBus          = $commandBus;
        $this->denormalizer        = $denormalizer;
        $this->serializer          = $serializer;
        $this->validator           = $validator;
        $this->violationsFormatter = $violationsFormatter;
    }

    /**
     * @Route("/tasks/{task_id}", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getTask(Request $request, TaskRepositoryInterface $taskRepository)
    {
        $task = $taskRepository->getById(new TaskId($request->get('task_id')));

        return new JsonResponse($task);
    }

    /**
     * @Route("/tasks", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createTask(Request $request)
    {
        $command = $this->serializer->deserialize(
            $request->getContent(), CreateTaskCommand::class, 'json'
        );

        $this->commandBus->handle($command);

        return new JsonResponse(['created' => true], 201);
    }

    /**
     * @Route("/tasks/{task_id}", methods={"PATCH"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function changeTask(Request $request, TaskRepositoryInterface $taskRepository)
    {
        $taskToChange = $request->get('task_id');
        $data         = json_decode($request->getContent(), true);

        if (array_key_exists('due_date', $data)) {
            $dueDate = !is_null($data['due_date']) ? new \DateTimeImmutable($data['due_date']) : null;

            $this->commandBus->handle(new RescheduleTaskCommand($taskToChange, $dueDate));
        }

        if (array_key_exists('completed', $data)) {
            $isCompleted = filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN);

            if ($isCompleted) {
                $this->commandBus->handle(new CompleteTaskCommand($taskToChange));
            }
        }

        return new JsonResponse(null, 200);
    }

    /**
     * @Route("/tasks", methods={"GET"})
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     * @param TaskRepositoryInterface $taskRepository
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function listTasks(
        Request $request,
        UserRepositoryInterface $userRepository,
        TaskRepositoryInterface $taskRepository
    ) {
        /** @var ListTasksRequest $listTasksRequest */
        $listTasksRequest = $this->denormalizer->denormalize(
            $request->query->all(), ListTasksRequest::class, 'array'
        );

        $violations = $this->validator->validate($listTasksRequest);

        if ($violations->count()) {
            return new JsonResponse([
                'errors' => $this->violationsFormatter->fromViolations($violations)
            ], 400);
        }

        $user  = $userRepository->getById(new UserId($listTasksRequest->userId));
        $tasks = $taskRepository->getScheduledByUser($user, $listTasksRequest->dueDate);

        return new JsonResponse($tasks);
    }
}