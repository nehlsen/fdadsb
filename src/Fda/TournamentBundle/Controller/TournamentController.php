<?php

namespace Fda\TournamentBundle\Controller;

use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Form\TournamentType;
use Fda\TournamentBundle\Form\TournamentWizard\WizardData;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tournament controller.
 */
class TournamentController extends Controller
{
    /**
     * Lists all Tournament entities.
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $tournaments = $this->getRepository()->findAll();
        $tournament = $this->get('fda.tournament.engine')->getTournament();

        return $this->render('FdaTournamentBundle:Tournament:index.html.twig', array(
            'tournaments' => $tournaments,
            'tournament'  => $tournament,
        ));
    }

    /**
     * Displays a form to create a new Tournament entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction()
    {
        $wizardFormData = new WizardData();

        $flow = $this->get('fda.tournament.new_tournament_wizard');
        $flow->bind($wizardFormData);

        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                $form = $flow->createForm();
            } else {
                $tournament = $wizardFormData->createTournament($this->get('fda.player.manager'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($tournament);
                $em->flush();

                $flow->reset();
                return $this->redirectToRoute('tournament_show', array('id' => $tournament->getId()));
            }
        }

        return $this->render('FdaTournamentBundle:Tournament:new.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
    }

    /**
     * Finds and displays a Tournament entity.
     * @Secure(roles="ROLE_USER")
     */
    public function showAction($id)
    {
        $tournament = $this->getTournament($id);

        return $this->render('FdaTournamentBundle:Tournament:show.html.twig', array(
            'tournament'  => $tournament,
        ));
    }

    /**
     * Displays a form to edit an existing Tournament entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

        if (!$tournament) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $editForm = $this->createEditForm($tournament);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FdaTournamentBundle:Tournament:edit.html.twig', array(
            'entity'      => $tournament,
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
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

        if (!$tournament) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($tournament);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tournament_edit', array('id' => $id)));
        }

        return $this->render('FdaTournamentBundle:Tournament:edit.html.twig', array(
            'entity'      => $tournament,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tournament entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tournament = $em->getRepository('FdaTournamentBundle:Tournament')->find($id);

            if (!$tournament) {
                throw $this->createNotFoundException('Unable to find Tournament entity.');
            }

            $em->remove($tournament);
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

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('FdaTournamentBundle:Tournament');
    }

    /**
     * @param int $id Tournament-ID
     * @return Tournament
     */
    protected function getTournament($id)
    {
        $tournament = $this->getRepository()->find($id);

        if (!$tournament) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        return $tournament;
    }
}
