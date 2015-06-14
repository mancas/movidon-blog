<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BlogBundle\Entity\Post;
use Movidon\BlogBundle\Form\Type\PostType;
use Movidon\ImageBundle\Entity\ImagePost;
use Movidon\ImageBundle\Form\Type\MultipleImagesType;
use Movidon\MessageBundle\Entity\InternalMessage;
use Movidon\MessageBundle\Entity\Thread;
use Movidon\MessageBundle\Form\Type\ThreadType;
use Movidon\MessageBundle\MessageBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MessageController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $threads = $em->getRepository('MessageBundle:Thread')->findAll($this->getCurrentUser());
        $users = $em->getRepository('BackendBundle:AdminUser')->findAllExcept($this->getCurrentUser());

        return $this->render('BackendBundle:Message:main.html.twig', array('threads' => $threads, 'users' => $users, 'user' => $this->getCurrentUser()));
    }

    public function newThreadAction(Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            $data = $request->request->get('participants');
            if (isset($data) && count($data) > 0) {
                $thread = new Thread();
                $repository = $em->getRepository('BackendBundle:AdminUser');
                foreach($data as $participant) {
                    $user = $repository->findOneBy(array('id' => $participant));
                    if (isset($user)) {
                        $thread->addParticipant($user);
                    }
                }

                $thread->addParticipant($this->getCurrentUser());

                $em->persist($thread);
                $em->flush();

                $template = $this->render('BackendBundle:Message:thread-template.html.twig',
                    array('thread' => $thread, 'user' => $this->getCurrentUser()));

                $chat = $this->render('BackendBundle:Message:chat-template.html.twig',
                    array('thread' => $thread, 'user' => $this->getCurrentUser()));

                $url = $this->generateUrl('admin_message_new_message', array('id' => $thread->getId()));

                $json_response = json_encode(array('ok' => true,
                                                   'thread' => $template->getContent(),
                                                   'chat' => $chat->getContent(),
                                                   'url' => $url,
                                                   'id' => $thread->getId()));
            }
        }

        return $this->getHttpJsonResponse($json_response);
    }

    /**
     * @Template("BackendBundle:Message:thread-template.html.twig")
     *
     * @return array
     */
    public function threadTemplateAction(Thread $thread, $active = false) {
        return array('thread' => $thread, 'user' => $this->getCurrentUser(), 'active' => $active);
    }

    /**
     * @Template("BackendBundle:Message:chat-template.html.twig")
     *
     * @return array
     */
    public function chatTemplateAction(Thread $thread) {
        return array('thread' => $thread, 'user' => $this->getCurrentUser());
    }

    /**
     * @ParamConverter("thread", class="MessageBundle:Thread")
     */
    public function getThreadAction(Thread $thread, Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $template = $this->render('BackendBundle:Message:chat-template.html.twig',
                array('thread' => $thread, 'user' => $this->getCurrentUser()));
            $url = $this->generateUrl('admin_message_new_message', array('id' => $thread->getId()));

            $json_response = json_encode(array('ok' => true,
                'chat' => $template->getContent(), 'url' => $url));
        }

        return $this->getHttpJsonResponse($json_response);
    }

    /**
     * @ParamConverter("thread", class="MessageBundle:Thread")
     */
    public function addMessageAction(Thread $thread, Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->get('message');
            if (isset($data)) {
                $user = $this->getCurrentUser();
                $message = new InternalMessage();
                $message->setBody($data);
                $message->setThread($thread);
                $message->setSender($user);

                $em = $this->getEntityManager();
                $em->persist($message);
                $em->flush();

                $imgProfile = $user->getImageProfile();
                $avatar = null;
                if(isset($imgProfile)) {
                    $avatar = $this->getRequest()->getUriForPath($imgProfile->getImageProfileAvatar()->getWebFilePath());
                }

                $json_response = json_encode(array('ok' => true,
                    'avatar' => $avatar, 'time' => $message->getCreateDate()->format('d/m/Y - H:i')));
            }

        }

        return $this->getHttpJsonResponse($json_response);
    }
}
