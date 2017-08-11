<?php
/**
 * Created by PhpStorm.
 * User: khovanskih
 * Date: 15.01.16
 * Time: 16:21
 */

namespace Core\Service;

use Core\ServiceManager\ServiceLocatorAwareInterface;
use Core\ServiceManager\ServiceLocatorAwareTrait;

class ProjectMetadataService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function getVersion() {
        $branch = $this->getBranch();

        $pattern = '/^\w+\-([\d*\.{0,1}]{1,})$/';
        preg_match($pattern, $branch, $matches);

        if (count($matches)>0) {
            return $matches[1];
        } else
            return $branch;
    }

    public function getBranch() {
        $config = $this->getServiceLocator()->get('config');

        if (empty($config['version']['file_path'])) {
            throw new \Exception('Param \'file_path\' not found in \'version\' config!');
        }
        if (empty($config['version']['file_name'])) {
            throw new \Exception('Param \'file_name\' not found in \'version\' config!');
        }

        $filePath = $config['version']['file_path'] . $config['version']['file_name'];

        if (!file_exists($filePath)) return '';
        $branch = file_get_contents($filePath);

        return $branch;
    }
} 