<?php
namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Application\Entity\User;

class AuthAdapter implements AdapterInterface
{
    /**
     * Username.
     * @var string
     */
    private $username;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Sets userName.
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Sets password.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }


    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        // Check the database if there is a user with such username.
        /* @var $user \Application\Entity\Repository\UserRepository*/
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(array('username' => $this->username));

        // If there is no such user, return 'Identity Not Found' status.
        if (empty($user)) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Пользователя с таким логином не найдено.']);
        }

        // If the user with such username exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        if (!$user->getActive()) {
            return new Result(
                Result::FAILURE,
                null,
                ['Ваша учетная запись не активна.']);
        }

        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Great! The password hash matches. Return user identity (userName) to be
            // saved in session for later use.
            return new Result(
                Result::SUCCESS,
                $this->username,
                ['Вы успешно авторизованы']);
        }

        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Вы ввели неправильный пароль.']);
    }
}
