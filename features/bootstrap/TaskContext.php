<?php declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * Class TaskContext
 * @author Faley Aliaksandr
 */
class TaskContext implements Context
{
    use FixturesTrait;
    use UserContextTrait;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \DateTimeImmutable
     */
    protected $scheduledAt;

    /**
     * TaskContext constructor.
     * @param KernelInterface $kernel
     * @param Request $request
     */
    public function __construct(KernelInterface $kernel, Request $request)
    {
        $this->kernel  = $kernel;
        $this->request = $request;
    }

    /**
     * @When I get a list of tasks
     */
    public function getAListOfTasks()
    {
        $queryParams = [
            'user_id' => $this->currentUser->getId()->getValue()
        ];

        if (null !== $this->scheduledAt) {
            $queryParams['due_date'] = $this->scheduledAt->format('Y-m-d');
        }

        return $this->request->send('GET', '/tasks?' . http_build_query($queryParams));
    }

    /**
     * @Given Today is :date
     */
    public function todayIsDate(string $date)
    {
        $this->scheduledAt = new \DateTimeImmutable($date);
    }
}