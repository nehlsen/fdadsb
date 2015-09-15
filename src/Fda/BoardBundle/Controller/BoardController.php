<?php

namespace Fda\BoardBundle\Controller;

use Fda\BoardBundle\Entity\Board;
use Fda\BoardBundle\Form\BoardType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Board controller.
 *
 */
class BoardController extends Controller
{

    /**
     * Lists all Board entities.
     *
     */
    public function indexAction()
    {
        $boards = $this->getRepository()->findAll();

        return $this->render('FdaBoardBundle:Board:index.html.twig', array(
            'boards' => $boards,
        ));
    }
    /**
     * Creates a new Board entity.
     *
     */
    public function createAction(Request $request)
    {
        $board = new Board();
        $form = $this->createCreateForm($board);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($board);
            $em->flush();

            return $this->redirect($this->generateUrl('board_show', array('id' => $board->getId())));
        }

        return $this->render('FdaBoardBundle:Board:new.html.twig', array(
            'entity' => $board,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Board entity.
     *
     * @param Board $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Board $entity)
    {
        $form = $this->createForm(new BoardType(), $entity, array(
            'action' => $this->generateUrl('board_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Board entity.
     *
     */
    public function newAction()
    {
        $board = new Board();
        $form = $this->createCreateForm($board);

        return $this->render('FdaBoardBundle:Board:new.html.twig', array(
            'entity' => $board,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Board entity.
     *
     */
    public function showAction($id)
    {
        $board = $this->getBoard($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaBoardBundle:Board:show.html.twig', array(
            'entity'      => $board,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Board entity.
     *
     */
    public function editAction($id)
    {
        $board = $this->getBoard($id);

        $editForm = $this->createEditForm($board);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaBoardBundle:Board:edit.html.twig', array(
            'entity'      => $board,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Board entity.
    *
    * @param Board $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Board $entity)
    {
        $form = $this->createForm(new BoardType(), $entity, array(
            'action' => $this->generateUrl('board_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Board entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $board = $this->getBoard($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($board);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('board_edit', array('id' => $id)));
        }

        return $this->render('FdaBoardBundle:Board:edit.html.twig', array(
            'entity'      => $board,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Board entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $board = $this->getBoard($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($board);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('board'));
    }

    /**
     * Creates a form to delete a Board entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('board_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('FdaBoardBundle:Board');
    }

    /**
     * @param int $id board-ID
     * @return Board
     */
    protected function getBoard($id)
    {
        $board = $this->getRepository()->find($id);

        if (!$board) {
            throw $this->createNotFoundException('Unable to find Board entity.');
        }

        return $board;
    }
}
