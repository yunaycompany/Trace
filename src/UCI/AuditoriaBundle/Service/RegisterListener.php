<?php

namespace UCI\AuditoriaBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use UCI\AuditoriaBundle\Entity\LogTrace;

class RegisterListener implements ContainerAwareInterface {

    protected $container;
    protected $username;
    protected $ip;

    const ACTION_CREATE = 'Create';
    const ACTION_UPDATE = 'Update';
    const ACTION_Delete = 'Delete';

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->ip = $event->getRequest()->getClientIp();
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->setUsername($securityContext->getToken()->getUsername());
        } else {
            $this->setUsername('anonimo');
        }
    }

    /**
     * Obteniendo el Usuario que realizó la acción
     */
    public function setUsername($username) {
        if (is_string($username)) {
            $this->username = $username;
        } elseif (is_object($username) && method_exists($username, 'getUsername')) {
            $this->username = (string) $username->getUsername();
        } else {
            throw new \Exception('falta el username');
        }
    }

    /**
     * Obteniendo el nombre de la Entidad
     */
    public function get_class_obj($entity) {
        $className = join('', array_slice(explode("\\", get_class($entity)), -1));
        return $className;
    }

    /**
     * Serializando datos a guardar
     */
    public function get_data($changes) {
        $serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $changes = $serializer->encode($changes, 'json');
        return $changes;
    }

    public function onFlush(OnFlushEventArgs $event) {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() AS $entity) {
            $data = $uow->getEntityChangeSet($entity);
            $this->Insertar_Traza($entity, self::ACTION_CREATE, $data, $em);
        }

        foreach ($uow->getScheduledEntityUpdates() AS $entity) {
            $data = $uow->getEntityChangeSet($entity);
            $this->Modificar_Traza($entity, self::ACTION_UPDATE, $data, $em);
        }

        foreach ($uow->getScheduledEntityDeletions() AS $entity) {          
            $this->Modificar_Traza($entity, self::ACTION_Delete, $data = null, $em);
        }
    }

    public function Insertar_Traza($class, $action, $data = null, $em) {
        $className = $this->get_class_obj($class);
        if ($this->isContainClass($className)) 
        {                 
            $data['clase']= $className;
            $data = $this->get_data($data);
            $uow = $em->getUnitOfWork();
            $trace = new LogTrace();
            $trace->setIp($this->ip);
            $trace->setAccion($action);
            $trace->setUsuario($this->username);
            $trace->setDetalles($data);
            $em->persist($trace);          
            $traceMeta = $em->getClassMetadata(get_class($trace));
            $uow->computeChangeSet($traceMeta, $trace);
        }
    }
    public function Modificar_Traza($class, $action, $data = null, $em) {
        $className = $this->get_class_obj($class);
        if ($this->isContainClass($className)) 
        {          
            $id = $class->getId();
            $data['id'] = $id;
            $data['clase']= $className;
            $data = $this->get_data($data);
            $uow = $em->getUnitOfWork();
            $trace = new LogTrace();
            $trace->setIp($this->ip);
            $trace->setAccion($action);
            $trace->setUsuario($this->username);
            $trace->setDetalles($data);
            $em->persist($trace);
            $traceMeta = $em->getClassMetadata(get_class($trace));
            $uow->computeChangeSet($traceMeta, $trace);
        }
    }

    /**
     * Verifica si la entidad que se modifica se encuentra marcada
     */
    public function isContainClass($entity) {
        $classes = $this->container->get('classlist')->load();
        foreach ($classes as $entidad) {
            if ($entity == $entidad['classname'])
                return true;
        }
        return false;
    }

}

