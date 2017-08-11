<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 11.02.17
 * Time: 21:35
 */

//устарело, перенесено в /grid
//namespace Core\Service;
//
//use Interop\Container\ContainerInterface;
//use Zend\ServiceManager\Factory\FactoryInterface;
//
//class JqGridDbalAdapterFactory implements FactoryInterface
//{
//    /**
//     * {@inheritDoc}
//     */
//    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
//    {
//        $form = (array_key_exists('form', $options)) ? $options['form'] : null;
//        if (array_key_exists('EntityManager', $options)) {
//            $em = $options['EntityManager'];
//        } else {
//            $em = $container->get('doctrine');
//        }
//
//        $ret = new JqGridDbalAdapter($container, $form, $em);
//
//        return $ret;
//    }
//}