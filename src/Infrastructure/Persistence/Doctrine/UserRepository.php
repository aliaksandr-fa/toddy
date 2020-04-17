<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Toddy\Domain\User\Exception\UserNotFoundException;
use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;
use Toddy\Domain\User\UserRepositoryInterface;


/**
 * Class UserRepository
 * @package Toddy\Infrastructure\Persistence\Doctrine
 * @author Faley Aliaksandr
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param UserId $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getById(UserId $userId): User
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, $userId);

        if (null == $user) {
            throw new UserNotFoundException("User with id $userId not found.");
        }

        return $user;
    }
}