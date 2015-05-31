<?php

namespace Movidon\ImageBundle\Controller;

use Movidon\FrontendBundle\Controller\CustomController;
use Movidon\FrontendBundle\Util\FunctionsHelper;
use Movidon\ImageBundle\Util\FileHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\ImageBundle\Entity\ImagePost;
use Movidon\ImageBundle\Entity\ImageCopy;
use Movidon\ImageBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends CustomController
{
    /**
     * @ParamConverter("image", class="ImageBundle:Image")
     */
    public function deleteImageAction(Image $image, Request $request)
    {
        $jsonResponse = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            if (!$image) {
                return $this->noPermission();
            }
            foreach ($image->getImageCopies() as $copy)
            {
                $copy->setDateRemove(new \DateTime('now'));
                $em->flush();
                FileHelper::removeFileFromDirectory($copy->getImageName(), $copy->getSubdirectory());
            }
            $image->setDeletedDate(new \DateTime('now'));
            if (FunctionsHelper::isClass($image, "ImagePost") && $image->getMain()) {
                $image->setMain(false);
            }
            $em->flush();
            FileHelper::removeFileFromDirectory($image->getImage(), $image->getSubdirectory());
            $jsonResponse = json_encode(array('ok' => true));
        }

        return $this->getHttpJsonResponse($jsonResponse);
    }

    /**
     * @ParamConverter("imagePost", class="ImageBundle:ImagePost")
     */
    public function changeImageMainAction(ImagePost $imagePost, Request $request)
    {
        $jsonResponse = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            if (!$imagePost) {
                return $this->noPermission();
            }
            $imageMain = $imagePost->getItem()->getImageMain();
            if ($imageMain && $imagePost->getId() != $imageMain->getId()) {
                $imageMain->setMain(false);
                $imagePost->setMain(true);
                $em->persist($imageMain);
                $em->persist($imagePost);
                $em->flush();
                $jsonResponse = json_encode(array('ok' => true));
            }
            if (!$imageMain) {
                $imagePost->setMain(true);
                $em->persist($imagePost);
                $em->flush();
                $jsonResponse = json_encode(array('ok' => true));
            }
        }

        return $this->getHttpJsonResponse($jsonResponse);
    }

    /**
     * @ParamConverter("image", class="ImageBundle:Image")
     */
    public function updateImageAction(Image $image, Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            $data = $request->request->all();
            $data = $data['image'];

            $updatedValues = array();
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($image, $method)) {
                    $image->$method($value);
                    $updatedValues[$key] = $value;
                }
            }

            $em->persist($image);
            $em->flush();
            $json_response = json_encode(array('ok' => true, 'updatedValues' => $updatedValues));
        }

        return $this->getHttpJsonResponse($json_response);
    }
}
