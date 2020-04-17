<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;

/**
 * Class UserFixtures
 * @package Toddy\Infrastructure\Persistence\Doctrine\Fixtures
 * @author Faley Aliaksandr
 */
class UserFixtures extends Fixture
{
    public const USER_ONE_UUID = '1a233480-1d08-49b4-808d-4a44d88467d7';
    public const USER_TWO_UUID = 'a135c82e-bf94-4c37-aa85-12c5ddb63cc7';

    public function load(ObjectManager $manager): void
    {
        $userOne = new User(
            new UserId(self::USER_ONE_UUID),
            'user one'
        );
        $this->addReference(self::USER_ONE_UUID, $userOne);

        $userTwo = new User(
            new UserId(self::USER_TWO_UUID),
            'user two'
        );
        $this->addReference(self::USER_TWO_UUID, $userTwo);

        $manager->persist($userOne);
        $manager->persist($userTwo);

        $manager->flush();
    }
}