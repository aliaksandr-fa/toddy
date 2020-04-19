<?php declare(strict_types=1);

use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;


/**
 * Trait UserContextTrait
 * @author Faley Aliaksandr
 */
trait UserContextTrait
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * @var User
     */
    protected $userBob;

    /**
     * @var User
     */
    protected $userAlice;

    /**
     * @Given As a user
     */
    public function IamAUser()
    {
        $this->IamABobUser();
    }

    /**
     * @Given As a Bob user
     */
    public function IamABobUser()
    {
        $this->userBob = new User(
            new UserId('1a233480-1d08-49b4-808d-4a44d88467d7'),
            'user one'
        );
        $this->currentUser = $this->userBob;
    }

    /**
     * @Given As an Alice user
     */
    public function IamAnAliceUser()
    {
        $this->userAlice = new User(
            new UserId('a135c82e-bf94-4c37-aa85-12c5ddb63cc7'),
            'user two'
        );
        $this->currentUser = $this->userAlice;
    }
}