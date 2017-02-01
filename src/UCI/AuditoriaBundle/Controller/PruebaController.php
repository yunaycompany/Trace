<?php

namespace UCI\AuditoriaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UCI\AuditoriaBundle\Entity\Prueba;
use UCI\AuditoriaBundle\Form\PruebaType;
use UCI\AuditoriaBundle\Entity\LogTrace;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Prueba controller.
 *
 */
class PruebaController extends Controller
{
    /**
     * Lists all Prueba entities.
     * @todo Esto me queda pendiente sacar el grafico por dias cantidad de acciones
     */
    private function UpdateAccion($accion, $valor)
    {
        $a = array("Create"=>0,"Update"=>0,"Delete"=>0,"Autentication"=>0);
        $a[$accion] = $valor;
        return $a;
    }
    /**
     * Listo si viene un usuario
     */
    public function pruebaAction(){
         $em = $this->getDoctrine()->getManager();       
         $resultado= $em->getRepository('AuditoriaBundle:LogTrace')->getTrazasByUser('anonimo');
         $respuesta = array();
         $respuesta['success'] = true;
         $respuesta['datos'] = $resultado;
         $prueba = array(
             'success' => true,
             'total' => 10,
             'datos' => array(
                 array('accion'=>'Crear','total'=>25),
                 array('accion'=>'Update','total'=>30),
                 array('accion'=>'Delete','total'=>12),                 
             )
        );
        return new JsonResponse($prueba);
        die();
         //         $acciones = array("Create"=>'0',"Update"=>'0',"Delete"=>'0',"Autentication"=>'0');
//         list($accion,$valor) = $detalles;
//         $respuesta['detalles'] = array(); 
//         foreach ($resultado as $valor)
//         {
//             $acciones[$valor['accion']]= $valor['total'];
//         }
//        /**
//         * detalles = array(
//         *          array('accion'=>'create','total'=>24) ,   
//         * )
//         * 
//         */
//         $data = array();
//         foreach ($acciones as $accion){
//              var_dump($accion);
//             $data['accion']= $accion[0];
//             $data['total']= $accion[0];
//             $respuesta['detalles']+= $data;
//         }
//         //$prueba = $em->getRepository('AuditoriaBundle:LogTrace')->getUUser();
       return new JsonResponse($respuesta);
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();       
        $resultado= $em->getRepository('AuditoriaBundle:LogTrace')->getTrazasMes('2013-03-01','2013-03-22');
        
        
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['respuesta'] = array(); 
        $fechai = '01-01-1999';
       foreach($resultado as $valor)
       {
           $fecha = $valor['fecha']->format('d-m-Y');            
           if($fechai !== $fecha){             
               $fechai = $fecha;
               $data = array(
                    'fecha' => $fechai,
                    
                );
               array_push( $respuesta['respuesta'], $data);
           }         
           
       }
       
       //var_dump($respuesta);
       die();
        return new JsonResponse($respuesta);
    }

    /**
     * Creates a new Prueba entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Prueba();
        $form = $this->createForm(new PruebaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('prueba_show', array('id' => $entity->getId())));
        }

        return $this->render('AuditoriaBundle:Prueba:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Prueba entity.
     *
     */
    public function newAction()
    {
        $entity = new Prueba();
        $form   = $this->createForm(new PruebaType(), $entity);

        return $this->render('AuditoriaBundle:Prueba:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Prueba entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuditoriaBundle:Prueba')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Prueba entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AuditoriaBundle:Prueba:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Prueba entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuditoriaBundle:Prueba')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Prueba entity.');
        }

        $editForm = $this->createForm(new PruebaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AuditoriaBundle:Prueba:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Prueba entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuditoriaBundle:Prueba')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Prueba entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PruebaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('prueba_edit', array('id' => $id)));
        }

        return $this->render('AuditoriaBundle:Prueba:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Prueba entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AuditoriaBundle:Prueba')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Prueba entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('prueba'));
    }

    /**
     * Creates a form to delete a Prueba entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
