<?php

namespace Fda\UserBundle\Controller;

use Fda\PlayerBundle\Form\PlayerType;
use Fda\UserBundle\Entity\User;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    /**
     * Show the user
     * @Secure(roles="ROLE_USER")
     */
    public function showAction()
    {
        // TODO nicer UI
        // TODO display player info (if available)
        // TODO provide edit button

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FdaUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }

    // EDIT action for user & player

    /**
     * Show the user
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('fos_user_profile_show');
        }

        $player = $user->getPlayer();
        $playerForm = $this->createForm(new PlayerType(), $player);
        $playerForm->handleRequest($request);
        if ($form->isValid()) {
        }

        return $this->render('FdaUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'playerForm' => $playerForm->createView(),
        ));
    }
}
