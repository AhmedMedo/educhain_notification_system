<?php

namespace App\Controller;

use Pusher\Pusher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PusherAuthController extends AbstractController
{
    private $pusher;
    private $security;

    public function __construct(Pusher $pusher, Security $security)
    {
        $this->pusher = $pusher;
        $this->security = $security;
    }

    /**
     * @Route("/pusher/auth", name="pusher_auth", methods={"POST"})
     */
    public function auth(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Forbidden', 403);
        }

        $channelName = $request->request->get('channel_name');
        $socketId = $request->request->get('socket_id');

        // Only allow users to subscribe to their own private channel
        if ($channelName !== 'private-notification_user_' . $user->getId()) {
            return new Response('Forbidden', 403);
        }

        $auth = $this->pusher->socket_auth($channelName, $socketId);
        return new Response($auth, 200, ['Content-Type' => 'application/json']);
    }
} 