<?php

namespace UCI\AuditoriaBundle\Service;

use Symfony\Component\Config\FileLocator;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use UCI\AuditoriaBundle\Util\DirectoryLoader;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use UCI\AuditoriaBundle\Driver\Classlist\Traza;

/**
 * Contenedor del servicio que me da las anotaciones
 *
 * @author Yosbel
 */
class AnnotationLoader extends ContainerAware {

    protected $container;

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load() {
        $reader = new AnnotationReader();
        AnnotationRegistry::registerFile(__DIR__ . '/../Driver/ClassList/Traza.php');
        /**
         * si da bateo   __DIR__ . '/../../'
         */
        AnnotationRegistry::registerAutoloadNamespace('UCI\AuditoriaBundle\Driver\\');
        $bundles = $this->container->get('kernel')->getBundles();
        $classes = array();
        foreach ($bundles as $bundle) {            
            if (\in_array($bundle->getNamespace(), $this->getParseableBundles())) {               
                $filesLoader = new DirectoryLoader(new FileLocator('.'));               
                $files = $filesLoader->load($bundle->getPath() . '\Entity');
                if (!empty($files)) {
                    foreach ($files as $file) {
                        $reflessClass = new \ReflectionClass($file['class']);
                        $classAnnotations = $reader->getClassAnnotations($reflessClass);
                        foreach ($classAnnotations as $anotacion) {
                            if ($anotacion instanceof Traza) {
                                $class = array(
                                    'namespace' => $reflessClass->getNamespaceName(),
                                    'description' => (null !== $anotacion->descripcion) ? $anotacion->descripcion : 'No tiene descripción definida',
                                    'filename' => $reflessClass->getFileName(),
                                    'classname' => $this->get_class_obj($reflessClass->getName()),
                                );
                                $classes[] = $class;
                            }
                        }
                    }
                }
            }
        }
        return $classes;
    }

    public function getParseableBundles() {
        $bundles = array();
        $configs = $this->container->getParameter('uci.auditoria.bundles');
        return $configs;
    }
    public function get_class_obj($entity)
    {
        $className = join('', array_slice(explode("\\", $entity), -1));       
        return $className;
    }
}