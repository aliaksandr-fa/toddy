<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\User\User;

/**
 * Class TaskFixtures
 * @package Toddy\Infrastructure\Persistence\Doctrine\Fixtures
 * @author Faley Aliaksandr
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var string[]
     */
    protected static $uuids = [
        'd511c4f8-cd0a-4db3-87bd-24795652fb19',
        '44c975b5-1139-45c2-a389-4a24b41a89ec',
        'ab75590a-22ef-4d55-acdd-11840ec226bd',
        '0cd6c920-1942-47f2-8e04-6aaab2deeec8',
        '9b9d0e05-8029-431a-803c-f2505376ca7e',
        'c3cad1da-7464-4913-8ed5-3c1c1ec3e587',
        'd72ac305-03ef-4c5a-ae6a-934243962f2e',
        '01c30cd3-d9cf-4d8b-927e-fa538370ac5e'
    ];

    /**
     * @var int
     */
    protected $titleCounter = 0;

    /**
     * @var int
     */
    protected $uuidCounter = 0;

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->resetCounters();

        /** @var User $userOne */
        $userOne = $this->getReference(UserFixtures::USER_ONE_UUID);
        $this->loadTasksForUser($manager, $userOne);

        /** @var User $userTwo */
        $userTwo = $this->getReference(UserFixtures::USER_TWO_UUID);
        $this->loadTasksForUser($manager, $userTwo);

        $manager->flush();
    }

    protected function loadTasksForUser(ObjectManager $manager, User $user): void
    {
        $manager->persist(
            new Task(new TaskId($this->nextUuid()), $this->nextTitle($user), $user)
        );
        $manager->persist(
            new Task(new TaskId($this->nextUuid()), $this->nextTitle($user), $user, new \DateTimeImmutable())
        );
        $manager->persist(
            new Task(
                new TaskId($this->nextUuid()), $this->nextTitle($user), $user,
                (new \DateTimeImmutable())->modify('+ 1 day')
            )
        );

        $completedTask = new Task(new TaskId($this->nextUuid()), $this->nextTitle($user), $user);
        $completedTask->complete();

        $manager->persist($completedTask);
    }

    protected function nextTitle(User $user): string
    {
        return sprintf("%s:%s", $user->getUsername(), $this->titleCounter++);
    }


    protected function resetCounters(): void
    {
        $this->titleCounter = 0;
        $this->uuidCounter  = 0;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function nextUuid(): string
    {
        return isset(self::$uuids[$this->uuidCounter])
            ? self::$uuids[$this->uuidCounter++]
            : Uuid::uuid4()->toString();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
