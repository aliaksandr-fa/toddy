<?php declare(strict_types=1);

namespace Toddy\Domain\User;

use Toddy\Domain\User\Exception\UserNotFoundException;

/**
 * Interface UserRepositoryInterface
 * @package Toddy\Domain\User
 * @author Faley Aliaksandr
 */
interface UserRepositoryInterface
{
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;

    /**
     * @param UserId $userId
     * @return User|null
     * @throws UserNotFoundException
     */
    public function getById(UserId $userId): ?User;
}