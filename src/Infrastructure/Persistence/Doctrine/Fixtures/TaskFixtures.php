<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @var int
     */
    protected $titleCounter = 0;

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
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
        $manager->persist(new Task(TaskId::next(), $this->nextTitle($user), $user));
        $manager->persist(new Task(TaskId::next(), $this->nextTitle($user), $user, new \DateTimeImmutable()));
        $manager->persist(new Task(TaskId::next(), $this->nextTitle($user), $user,
            (new \DateTimeImmutable())->modify('+ 1 day')));

        $completedTask = new Task(TaskId::next(), $this->nextTitle($user), $user);
        $completedTask->complete();

        $manager->persist($completedTask);
    }

    protected function nextTitle(User $user): string
    {
        return sprintf("%s:%s", $user->getUsername(), $this->titleCounter++);
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
