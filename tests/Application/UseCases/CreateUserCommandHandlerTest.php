<?php declare(strict_types=1);

namespace Toddy\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Toddy\Application\UseCases\CreateUser\CreateUserCommand;
use Toddy\Application\UseCases\CreateUser\CreateUserHandler;
use Toddy\Domain\User\User;
use Toddy\Domain\User\UserRepositoryInterface;


class CreateUserCommandHandlerTest extends TestCase
{
    public function testHandlerCallsRepository()
    {
        $command           = new CreateUserCommand();
        $command->username = 'test';

        /** @var UserRepositoryInterface $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepositoryInterface::class)->getMock();

        $userRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($command) {
                return $user->getUsername() === $command->username;
            }));

        $handler = new CreateUserHandler($userRepositoryMock);
        $handler->handle($command);
    }
}