<?php declare(strict_types=1);

namespace Toddy\Domain\User;


/**
 * Class User
 * @package Toddy\Domain\User
 * @author Faley Aliaksandr
 */
class User implements \JsonSerializable
{
    /**
     * @var UserId
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * User constructor.
     * @param UserId $id
     * @param string $username
     */
    public function __construct(UserId $id, string $username)
    {
        $this->id       = $id;
        $this->username = $username;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id->getValue(),
            'username' => $this->username
        ];
    }
}