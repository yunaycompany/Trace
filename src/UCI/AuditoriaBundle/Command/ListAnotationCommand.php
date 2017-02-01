<?php

namespace UCI\AuditoriaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Mostrará el listado de todas las clases con anotaciones
 *
 * @author Yosbel
 */
class ListAnotationCommand extends ContainerAwareCommand {
    protected function configure() {
           $this->setName('Auditoria:ListAnotationClass')
                ->setDescription('Clases que tienen anotaciones para auditar');
           
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $clases = $this->getContainer()->get('classlist')->load();
       print_r($clases);
        $output->writeln($clases);
    }
}

