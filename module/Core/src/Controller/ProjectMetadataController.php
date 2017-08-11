<?php
/**
 * Created by PhpStorm.
 * User: khovanskih
 * Date: 18.01.16
 * Time: 10:13
 */

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Core\Service\ProjectMetadataService as ProjectMetadataService;

class ProjectMetadataController  extends AbstractActionController
{
    public function getAction() {
        /** @var $vs ProjectMetadataService */
        $vs = $this->getServiceLocator()->get('Core\Service\ProjectMetadata');

        return new JsonModel([
            'version' => $vs->getVersion(),
            'branch' => $vs->getBranch()
        ]);
    }
}