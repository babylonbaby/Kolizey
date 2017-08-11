<?php
namespace Application\Service;

use Application\Entity\User;
use Application\Entity\Rbacrole;
use Application\Entity\UserStatus;
use PHPUnit\Framework\Exception;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UserManager
{
    /**
     * Doctrine entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Zend\Authentication\AuthenticationService;
     */
    private $authService;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    /**
     * This method adds a new user.
     */
    public function addUser($data)
    {
        // Do not allow several users with the same email address.
        if ($this->checkUserExists($data['login'])) {
            throw new \Exception("Пользователь с логином " . $data['login'] . " уже существует");
        }

        // Create new User entity.
        $user = new User();
        $user = $this->prepareUser($user, $data);

        // Add the entity to the entity manager.
        $this->entityManager->persist($user);

        // Apply changes to database.
        $this->entityManager->flush();

        return $user;
    }

    /**
     * This method updates data of an existing user.
     */
    public function updateUser(User $user, $data)
    {
        if (isset($data['password'])) {
            unset($data['password']);
        }
        $user = $this->prepareUser($user, $data);
        $this->entityManager->persist($user);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * Экстракт данных в сущность пользователя
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    private function prepareUser(User $user, array $data)
    {
        if (isset($data['login'])) {
            // Do not allow to change userName if another user with such userName already exits.
            if ($user->getUserName() != $data['login'] && $this->checkUserExists($data['login'])) {
                throw new \Exception("Пользователь с логином " . $data['login'] . " уже существует");
            }
            $user->setUserName($data['login']);
        }
        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        $fio = $user->getFIO();
        if (isset($data['lastName']) && isset($data['firstName'])) {
            $fio = $data['lastName'] . ' ' . $data['firstName'];
        }
        if (isset($data['middleName'])) {
            $fio = $data['lastName'] . ' ' . $data['firstName'] . ' ' . $data['middleName'];
            $user->setMiddleName($data['middleName']);
        }
        $user->setFIO($fio);
        if (isset($data['role'])) {
            $role = $this->entityManager->getRepository(Rbacrole::class)->findOneBy(['id' => $data['role']]);
            $user->setRoleId($role);
        }
        if (isset($data['password'])) {
            // Encrypt password and store the password in encrypted state.
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create($data['password']);
            $user->setPassword($passwordHash);
        }
//        if (isset($data['active'])) {
//            if ($data['active'] === '1') {
//                $active = $this->entityManager->getRepository(UserStatus::class)->findOneBy(['title' => User::STATUS_ACTIVE]);
//            } else {
//                $active = $this->entityManager->getRepository(UserStatus::class)->findOneBy(['title' => User::STATUS_BLOCK]);
//            }
//            $user->setActive($active);
//        }
        return $user;
    }

    /**
     * This method checks if at least one user presents, and if not, creates
     * 'Admin' user with userName 'admin' and password 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user == null) {
            $user = new User();
            $user->setUserName('admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Secur1ty');
            $user->setPassword($passwordHash);
//            $active = $this->entityManager->getRepository(UserStatus::class)->findOneBy(['title' => User::STATUS_ACTIVE,]);
//            $user->setActive($active);
            $adminRole = $this->entityManager->getRepository(Rbacrole::class)->findOneBy(['name' => 'administrator',]);
            $user->setRoleId($adminRole);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * Проверка на сщществование пользователя с введенным логином
     * Checks whether an active user with given email address already exists in the database.
     */
    public function checkUserExists($userName)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneByUserName($userName);

        return $user !== null;
    }

    /**
     * Валидация введенного пароля
     * Checks that the given password is correct.
     */
    public function validatePassword(User $user, $password)
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }

//    /**
//     * Generates a password reset token for the user. This token is then stored in database and
//     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is
//     * directed to the Set Password page.
//     */
//    public function generatePasswordResetToken($user)
//    {
//        // Generate a token.
//        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
//        $user->setPasswordResetToken($token);
//
//        $currentDate = date('Y-m-d H:i:s');
//        $user->setPasswordResetTokenCreationDate($currentDate);
//
//        $this->entityManager->flush();
//
//        $subject = 'Password Reset';
//
//        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
//        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token;
//
//        $body = 'Please follow the link below to reset your password:\n';
//        $body .= "$passwordResetUrl\n";
//        $body .= "If you haven't asked to reset your password, please ignore this message.\n";
//
//        // Send email to user.
//        mail($user->getEmail(), $subject, $body);
//    }
//
//    /**
//     * Checks whether the given password reset token is a valid one.
//     */
//    public function validatePasswordResetToken($passwordResetToken)
//    {
//        $user = $this->entityManager->getRepository(User::class)
//            ->findOneByPasswordResetToken($passwordResetToken);
//
//        if($user==null) {
//            return false;
//        }
//
//        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
//        $tokenCreationDate = strtotime($tokenCreationDate);
//
//        $currentDate = strtotime('now');
//
//        if ($currentDate - $tokenCreationDate > 24*60*60) {
//            return false; // expired
//        }
//
//        return true;
//    }
//
//    /**
//     * This method sets new password by password reset token.
//     */
//    public function setNewPasswordByToken($passwordResetToken, $newPassword)
//    {
//        if (!$this->validatePasswordResetToken($passwordResetToken)) {
//            return false;
//        }
//
//        $user = $this->entityManager->getRepository(User::class)
//            ->findOneByPasswordResetToken($passwordResetToken);
//
//        if ($user==null) {
//            return false;
//        }
//
//        // Set new password for user
//        $bcrypt = new Bcrypt();
//        $passwordHash = $bcrypt->create($newPassword);
//        $user->setPassword($passwordHash);
//
//        // Remove password reset token
//        $user->setPasswordResetToken(null);
//        $user->setPasswordResetTokenCreationDate(null);
//
//        $this->entityManager->flush();
//
//        return true;
//    }

    /**
     * Получение текущего пользователя системы
     * @return \Application\Entity\User
     */
    public function getActiveUser()
    {
        $userName = $this->authService->getIdentity();
        $user = $this->entityManager->getRepository(User::class)->findOneByUserName($userName);
        return $user;
    }

    /**
     * Сброс пароля
     * @param User $user
     * @return User
     */
    public function resetPassword(User $user)
    {
        if ($user->getPasswordReset()) {
            throw new Exception('У пользователя уже сброшен пароль.');
        }
        $user->setPasswordReset(true);
        $user->setPassword(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * Установка пароля после сброса
     * @param array $data
     * @return User
     * @throws \PHPUnit\Framework\Exception
     */
    public function setPassword(array $data)
    {
        /* @var $user User*/
        $user = $this->entityManager->getRepository(User::class)->findOneByUserName($data['userName']);
        if (empty($user)) {
            throw new Exception('Пользователь с таким именем пользователя не найден.');
        }
        if (!$user->getPasswordReset()) {
            throw new Exception('Обратитесь к администратору системы для сброса пароля.');
        }
        if ($user->getActive()->getTitle() == User::STATUS_BLOCK) {
            throw new Exception('Пользователь с таким логином заблокирован');
        }
        $user->setPassword($data['password']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}

