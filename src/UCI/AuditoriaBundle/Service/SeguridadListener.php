<?php

namespace UCI\AuditoriaBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use UCI\AuditoriaBundle\Entity\LogTrace;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class SeguridadListener {

    protected $container;
    protected $username;
    protected $em;
    protected $ip;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->ip = $event->getRequest()->getClientIp();
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->setUsername($securityContext->getToken()->getUsername());
        } else {
            $this->setUsername('Anonimo');
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

    public function get_data($changes) {
        $serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $changes = $serializer->encode($changes, 'json');
        return $changes;
    }

    public function onSecurityAuthenticationFailure(\Symfony\Component\Security\Core\Event\AuthenticationFailureEvent $event) {
        $this->ip = $this->container->get('request')->getClientIp();
        $usuario = $event->getAuthenticationToken()->getUser();
        $acction = 'Autentication';
        $data = array(
            'tipo' => 'Failure',
            'mensaje' => $event->getAuthenticationException()->getPrevious()->getMessage(),
        );
        $detalles = $this->get_data($data);
        $trace = new LogTrace();
        $trace->setIp($this->ip);
        $trace->setAccion($acction);
        $trace->setUsuario($usuario);
        $trace->setDetalles($detalles);
        $this->em->persist($trace);
        $this->em->flush();
    }

    public function onSecurityAuthenticationSuccess(\Symfony\Component\Security\Core\Event\AuthenticationEvent $event) {
        $this->ip = $this->container->get('request')->getClientIp();
        $usuario = $event->getAuthenticationToken()->getUser();       
        $acction = 'Autenticacion';
         $data = array(
            'tipo' => 'Success',
            'mensaje' => 'El usuario se ha autenticado de manera correcta',
        );
        $detalles = $this->get_data($data);
        $trace = new LogTrace();
        $trace->setIp($this->ip);
        $trace->setAccion($acction);
        $trace->setUsuario($usuario->getUsername());
        $trace->setDetalles($detalles);
        $this->em->persist($trace);
        $this->em->flush();
    }

}
