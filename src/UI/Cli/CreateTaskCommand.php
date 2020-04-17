<?php declare(strict_types=1);

namespace Toddy\UI\Cli;

use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Toddy\Application\UseCases\CreateTask\CreateTaskCommand as CreateTaskCmd;

/**
 * Class CreateTaskCommand
 * @package Toddy\UI\Cli
 * @author Faley Aliaksandr
 */
class CreateTaskCommand extends Command
{
    protected static $defaultName = 'toddy:task:create';

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * CreateUserCommand constructor.
     * @param CommandBus $commandBus
     * @param string|null $name
     */
    public function __construct(CommandBus $commandBus, string $name = null)
    {
        parent::__construct($name);

        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a task.')
            ->setHelp('This command creates a new task for the user.')
            ->addArgument('title', InputArgument::REQUIRED, 'Task title.')
            ->addArgument('user_id', InputArgument::REQUIRED, 'User id')
            ->addArgument('due_date', InputArgument::OPTIONAL, 'User id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $createTaskCommand = new CreateTaskCmd();

        $createTaskCommand->title   = $input->getArgument('title');
        $createTaskCommand->userId  = $input->getArgument('user_id');
        $createTaskCommand->dueDate = new \DateTimeImmutable($input->getArgument('due_date'));

        $this->commandBus->handle($createTaskCommand);
    }
}