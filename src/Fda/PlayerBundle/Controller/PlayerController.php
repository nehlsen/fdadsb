<?php

namespace Fda\PlayerBundle\Controller;

use Fda\PlayerBundle\Entity\Player;
use Fda\PlayerBundle\Form\PlayerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Player controller.
 *
 */
class PlayerController extends Controller
{
    /**
     * Lists all Player entities.
     *
     */
    public function indexAction()
    {
        $players = $this->getPlayers();

        return $this->render('FdaPlayerBundle:Player:index.html.twig', array(
            'players' => $players,
        ));
    }

    /**
     * Creates a new Player entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $player = new Player();
        $form = $this->createCreateForm($player);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            return $this->redirect($this->generateUrl('player_show', array('id' => $player->getId())));
        }

        return $this->render('FdaPlayerBundle:Player:new.html.twig', array(
            'player' => $player,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Player entity.
     *
     * @param Player $player The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Player $player)
    {
        $form = $this->createForm(new PlayerType(), $player, array(
            'action' => $this->generateUrl('player_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Player entity.
     *
     */
    public function newAction()
    {
        $player = new Player();
        $form = $this->createCreateForm($player);

        return $this->render('FdaPlayerBundle:Player:new.html.twig', array(
            'player' => $player,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Player entity.
     * @param int $id player-ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $player = $this->getPlayer($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaPlayerBundle:Player:show.html.twig', array(
            'player'      => $player,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Player entity.
     * @param int $id player-ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $player = $this->getPlayer($id);

        $editForm = $this->createEditForm($player);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaPlayerBundle:Player:edit.html.twig', array(
            'player'      => $player,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Player entity.
    *
    * @param Player $player The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Player $player)
    {
        $form = $this->createForm(new PlayerType(), $player, array(
            'action' => $this->generateUrl('player_update', array('id' => $player->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Player entity.
     * @param Request $request
     * @param int     $id      player-ID
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $player = $this->getPlayer($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($player);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('player_edit', array('id' => $id)));
        }

        return $this->render('FdaPlayerBundle:Player:edit.html.twig', array(
            'entity'      => $player,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Player entity.
     * @param Request $request
     * @param int     $id      player-ID
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $player = $this->getPlayer($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('player'));
    }

    /**
     * Creates a form to delete a Player entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('player_delete', array('id' => $id)))
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
        return $em->getRepository('FdaPlayerBundle:Player');
    }

    /**
     * @param int $id player-ID
     * @return Player
     */
    protected function getPlayer($id)
    {
        $player = $this->getRepository()->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Unable to find Player entity.');
        }

        return $player;
    }

    /**
     * @return Player[]
     */
    protected function getPlayers()
    {
        $players = $this->getRepository()->findBy(array(), array('name' => 'ASC'));

        return $players;
    }
}
