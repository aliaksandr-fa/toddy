<?php declare(strict_types=1);

namespace Toddy\UI\Cli;

use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Toddy\Application\UseCases\CreateUser\CreateUserCommand as CreateUserCmd;


/**
 * Class CreateUserCommand
 * @package Toddy\UI\Cli
 * @author Faley Aliaksandr
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'toddy:user:create';


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
            ->setDescription('Creates new user.')
            ->setHelp('This command allows you to create a user...')

            ->addArgument('username', InputArgument::REQUIRED, 'Username')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $createUserCommand = new CreateUserCmd();
        $createUserCommand->username = $input->getArgument('username');

        $this->commandBus->handle($createUserCommand);
    }
}