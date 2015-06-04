<?php

namespace Movidon\ImageBundle\Form\Handler;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\ImageBundle\Entity\ImageProfile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Movidon\ImageBundle\Form\Handler\ImageManager;
use Movidon\ImageBundle\Entity\Image;
use Movidon\BlogBundle\Entity\Post;
use Movidon\ImageBundle\Entity\ImagePost;
use Movidon\ImageBundle\Entity\ImageAvatar;
use Symfony\Component\Validator\Validator;

class CreateImageHandler
{
    private $imageManager;
    private $validator;

    public function __construct(ImageManager $imageManager, Validator $validator)
    {
        $this->imageManager = $imageManager;
        $this->validator = $validator;
    }

    public function handle(FormInterface $form, Request $request, AdminUser $user)
    {
        if ($request->isMethod('POST')) {
            $imageConstraint = new \Symfony\Component\Validator\Constraints\Image();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $file = $data['image'];
                $imageConstraint->maxSizeMessage = Image::ERROR_MESSAGE;
                $imageConstraint->maxSize = Image::MAX_SIZE;
                $errorList = $this->validator->validateValue($file, $imageConstraint);
                if (count($errorList) === 0) {
                    try {
                        $image = new ImageProfile();
                        $image->setFile($file);
                        $image->setUser($user);
                        $imageMain = $user->getImageProfile();
                        $imageMain->setMain(0);
                        $this->imageManager->saveImage($imageMain);
                        $this->imageManager->saveImage($image);
                        $this->imageManager->createImage($image);
                    } catch (\Exception $e) {
                        $this->imageManager->removeImage($image);
                        return false;
                    }
                } else {
                    return false;
                }

                return true;
            }
        }

        return false;
    }

    public function handleMultiple(FormInterface $formImg, Request $request, $imageClassName, Post $post = null)
    {
        if ($request->getMethod() == 'POST') {
            $formImg->handleRequest($request);
            $imageConstraint = new \Symfony\Component\Validator\Constraints\Image();

            $files = $formImg->getData();
            $files = $files['images'];
            $error = 0;
            if (!empty($files)) {
                $excludeFiles = explode(';', $request->request->get('not_upload_files'));
                foreach ($files as $file) {
                    if (!isset($file)) {
                        continue;
                    }
                    if(in_array($file->getClientOriginalName(), $excludeFiles)) {
                        continue;
                    }
                    $className = $this->getClassNameFromObjectName($imageClassName);
                    $imageConstraint->minWidthMessage = 'La imagen debe tener un mínimo de ' . $className::MIN_WIDTH . ' píxeles de ancho';
                    $imageConstraint->minWidth = $className::MIN_WIDTH;
                    $imageConstraint->minWidthMessage =  'La imagen debe tener un mínimo de ' . $className::MIN_HEIGHT . ' píxeles de alto';
                    $imageConstraint->minHeight = $className::MIN_HEIGHT;
                    $imageConstraint->minWidthMessage =  'La imagen debe tener un mínimo de ' . $className::MIN_HEIGHT . ' píxeles de alto';
                    $imageConstraint->maxSizeMessage = Image::ERROR_MESSAGE;
                    $imageConstraint->maxSize = Image::MAX_SIZE;
                    $errorList = $this->validator->validateValue($file, $imageConstraint);

                    if (count($errorList) > 0) $error++;
                    if (get_class($file) == "Symfony\\Component\\HttpFoundation\\File\\UploadedFile" and (count($errorList) == 0)) {
                        try {
                            $image = new $className();
                            if (isset($post)) {
                                $image->setPost($post);
                                if (!$post->getImageMain()) {
                                    $image->setMain(true);
                                }
                            }

                            $image->setFile($file);
                            $this->imageManager->saveImage($image);
                            $this->imageManager->createImage($image);
                        } catch (\Exception $e) {
                            $this->imageManager->removeImage($image);
                            return false;
                        }
                    }
                }
            }
            if ($error > 0) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    private function getClassNameFromObjectName($objName)
    {
        return "Movidon\\ImageBundle\\Entity\\" . $objName;
    }
}