<?php

namespace UCI\AuditoriaBundle\Driver\ClassList;

use Doctrine\Common\Annotations\Annotation;
/**
 * Definición de la anotacion
 * @Annotation * 
 */
final class Traza extends Annotation
{
    public $descripcion = null;
}