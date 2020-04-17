<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\CreateUser;

use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;
use Toddy\Domain\User\UserRepositoryInterface;


/**
 * Class CreateUserHandler
 * @package Toddy\Application\UseCases\CreateUser
 * @author Faley Aliaksandr
 */
class CreateUserHandler
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * CreateUserHandler constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param CreateUserCommand $command
     * @throws \Exception
     */
    public function handle(CreateUserCommand $command): void
    {
        $this->userRepository->save(
            new User(UserId::next(), $command->username)
        );
    }
}