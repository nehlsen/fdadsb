<?php

namespace Fda\UserBundle\Controller;

use Fda\PlayerBundle\Entity\Player;
use Fda\UserBundle\Form\InvitationType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class InviteController extends Controller
{
    /**
     * @Secure(roles="ROLE_ADMIN")
     * @param Request $request
     * @param int $playerId player-ID
     * @return Response
     */
    public function inviteAction(Request $request, $playerId)
    {
        $playerRepository = $this->getDoctrine()->getManager()->getRepository('FdaPlayerBundle:Player');
        $player = $playerRepository->find($playerId);
        if (!$player) {
            throw $this->createNotFoundException('Unable to find Player entity.');
        }

        /** @var Session $session */
        $session = $request->getSession();

        if (null !== $player->getUser()) {
            $session->getFlashBag()->add('danger', 'player.user.invite_error.user_exists');
            return $this->redirectToRoute('player_show', ['id' => $playerId]);
        }

        $form = $this->createInviteForm($player);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // persist invitation
            // create and send email
            $this->sendInvitation($player);

            $session->getFlashBag()->add('success', 'player.user.invitation.send');
            return $this->redirectToRoute('player_show', ['id' => $playerId]);
        }

        if ($player->hasInvitation()) {
            // TODO add warning: re-invite makes previous invite useless
        }

        return $this->render('FdaUserBundle:Invite:invite.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function createInviteForm(Player $player)
    {
        $invitation = $player->getInvitation();
        $form = $this->createForm(new InvitationType(), $invitation, array(
            'action' => $this->generateUrl('user_invite', array('playerId' => $player->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'invite',
            'attr' => ['class' => 'btn-primary'],
        ));

        return $form;
    }

    private function sendInvitation(Player $player)
    {
        $tokenGenerator = $this->get('fos_user.util.token_generator');
        $player->getInvitation()->setToken($tokenGenerator->generateToken());

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        // TODO send email
    }
}
