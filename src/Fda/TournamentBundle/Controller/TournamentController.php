<?php

namespace Fda\TournamentBundle\Controller;

use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Form\TournamentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tournament controller.
 *
 */
class TournamentController extends Controller
{

    /**
     * Lists all Tournament entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FdaTournamentBundle:Tournament')->findAll();

        return $this->render('FdaTournamentBundle:Tournament:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tournament entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tournament();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tournament_show', array('id' => $entity->getId())));
        }

        return $this->render('FdaTournamentBundle:Tournament:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tournament entity.
     *
     * @param Tournament $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tournament $entity)
    {
        $form = $this->createForm(new TournamentType(), $entity, array(
            'action' => $this->generateUrl('tournament_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tournament entity.
     *
     */
    public function newAction()
    {
        $entity = new Tournament();
        $form   = $this->createCreateForm($entity);

        return $this->render('FdaTournamentBundle:Tournament:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tournament entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaTournamentBundle:Tournament:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tournament entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaTournamentBundle:Tournament:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tournament entity.
    *
    * @param Tournament $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tournament $entity)
    {
        $form = $this->createForm(new TournamentType(), $entity, array(
            'action' => $this->generateUrl('tournament_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tournament entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tournament_edit', array('id' => $id)));
        }

        return $this->render('FdaTournamentBundle:Tournament:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tournament entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tournament entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tournament'));
    }

    /**
     * Creates a form to delete a Tournament entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tournament_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
