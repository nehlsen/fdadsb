<?php

namespace Fda\UserBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Fda\UserBundle\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function setUrlGenerator($urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'checkForToken',
            FOSUserEvents::REGISTRATION_COMPLETED => 'finishRegistration',
        );
    }

    // check for invite and deny reg if not found
    // pre-fill username and email address
    public function checkForToken(GetResponseUserEvent $event)
    {
        // check for invite and deny reg if not found
        $token = $this->getToken($event->getRequest());
        $invitation = $this->getInvitation($token);
        if (null === $invitation) {
            $this->failRegistration('invitation not found', $event);
            return;
        }

        // pre-fill username and email address
        $user = $event->getUser();
        $user->setUsername($invitation->getPlayer()->getName());
        $user->setEmail($invitation->getEmail());
    }

    private function failRegistration($message, GetResponseUserEvent $event)
    {
//        throw new \Exception($message);

        /** @var Session $session */
        $session = $event->getRequest()->getSession();
        $session->getFlashBag()->set('error', $message);
        $event->setResponse(new RedirectResponse(
            $this->urlGenerator->generate('fda_dsb_homepage')
        ));
    }

    // delete invitation and associate user and player
    public function finishRegistration(FilterUserResponseEvent $event)
    {
        $token = $this->getToken($event->getRequest());
        $invitation = $this->getInvitation($token);
        $player = $invitation->getPlayer();

        /** @var User $user */
        $user = $event->getUser();
        $user->setPlayer($player);
        $player->setName($user->getUsername());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // TODO delete invitation? keep it?
    }

    private function getToken(Request $request)
    {
        /** @var Session $session */
        $session = $request->getSession();

        $token = $request->get('token');
        if (null === $token) {
            $token = $session->getFlashBag()->get('token');
        }

        if (!$session->getFlashBag()->has('token')) {
            $session->getFlashBag()->set('token', $token);
        }

        return $token;
    }

    private function getInvitation($token)
    {
        if (null == $token) {
            return null;
        }

        $repository = $this->entityManager->getRepository('FdaUserBundle:Invitation');
        $invitation = $repository->findOneBy(array('token' => $token));

        return $invitation;
    }
}
