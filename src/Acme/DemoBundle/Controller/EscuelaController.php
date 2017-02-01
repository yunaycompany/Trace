<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\DemoBundle\Entity\Escuela;
use Acme\DemoBundle\Form\EscuelaType;

/**
 * Escuela controller.
 *
 */
class EscuelaController extends Controller
{
    /**
     * Lists all Escuela entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Escuela')->findAll();

        return $this->render('AcmeDemoBundle:Escuela:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Escuela entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Escuela();
        $form = $this->createForm(new EscuelaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

           // return $this->redirect($this->generateUrl('escuela_show', array('id' => $entity->getId())));
        }
 $entity  = new Escuela();
        return $this->render('AcmeDemoBundle:Escuela:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Escuela entity.
     *
     */
    public function newAction()
    {
        $entity = new Escuela();
        $form   = $this->createForm(new EscuelaType(), $entity);

        return $this->render('AcmeDemoBundle:Escuela:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Escuela entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeDemoBundle:Escuela:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Escuela entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $editForm = $this->createForm(new EscuelaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeDemoBundle:Escuela:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Escuela entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EscuelaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('escuela_edit', array('id' => $id)));
        }

        return $this->render('AcmeDemoBundle:Escuela:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Escuela entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeDemoBundle:Escuela')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Escuela entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('escuela'));
    }

    /**
     * Creates a form to delete a Escuela entity by id.
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
