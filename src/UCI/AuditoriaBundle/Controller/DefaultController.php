<?php

namespace UCI\AuditoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $mesage = \Swift_Message::newInstance()
            ->setSubject('probando')
            ->addFrom('ogonzalezf@uci.cu')
            ->addTo('ybarrio@uci.cu')
            ->setBody('Probando');
        $this->get('mailer')->send($mesage);

        return $this->render('AuditoriaBundle:Default:index.html.twig', array(
                'name' => 'hola mundo'
            ));

    }
}
