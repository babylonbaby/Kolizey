<?php
use Application\Entity\RbacPermission;
use Application\Entity\RbacRole;

/**
 * Конфигурация доступа к контроллерам и экшенам
 * roles - массив допущенных ролей
 * permissions - массив допущенных привелегий
 * '*' - доступ для всех
 * возможно указывать указывать ограничение как для конкретного действия, так и для всего контроллера
 */
return [
    'permissions' => [
//        'Application\Controller\LoginController' => [
//            'roles' => [
//                Rbacrole::ROLE_ADMINISTRATOR,
//                Rbacrole::ROLE_OPERATOR,
//                Rbacrole::ROLE_ADMINISTRATOR,
//            ],
//        ],
        'Application\Controller\RealtyController' => [
            'complexGrid' => [
                'roles' => [
                    RbacRole::ROLE_ADMINISTRATOR,
//                Rbacrole::ROLE_OPERATOR,
//                Rbacrole::ROLE_ADMINISTRATOR,
                ],
            ],
//            'roles' => [
//                Rbacrole::ROLE_ADMINISTRATOR,
//                Rbacrole::ROLE_OPERATOR,
//                Rbacrole::ROLE_ADMINISTRATOR,
//            ],
        ],
    ],
];