<?php

namespace AppBundle\Service;

use AppBundle\Library\DoctrineTrait;
use AppBundle\Model\User as UserModel;
use AppBundle\Document\User as UserDocument;


/**
 * Class UserManager
 */
class UserManager
{
    use DoctrineTrait;

    /**
     * @param string $id
     * @return UserModel
     */
    public function getById($id)
    {
        $document = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

        return $this->mapDocumentToModel($document);
    }

    /**
     * Save a user model into the db storage.
     *
     * @param UserModel $userModel
     * @return UserModel
     */
    public function create(UserModel $userModel)
    {
        $userModel->setApiKey($this->generateApiToken());

        return $this->save($userModel);
    }

    /**
     * @param $userModel
     * @return UserModel
     */
    private function save($userModel)
    {
        $document = $this->mapModelToDocument($userModel);
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);

        $em->flush();

        return $this->getById($document->getId());
    }

    /**
     * @param UserDocument $userDocument
     * @return UserModel
     */
    private function mapDocumentToModel(UserDocument $userDocument)
    {
        $userModel = new UserModel();
        $userModel->setId($userDocument->getId());
        $userModel->setUsername($userDocument->getUsername());
        $userModel->setApiKey($userDocument->getApiKey());

        return $userModel;
    }

    /**
     * @param UserModel  $userModel
     * @return UserDocument
     */
    private function mapModelToDocument(UserModel $userModel)
    {
        $document =  ($userModel->getId())
            ? $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['id' => $userModel->getId()])
            : new UserDocument();

        $document->setUsername($userModel->getUsername());
        $document->setApiKey($userModel->getApiKey());

        return $document;
    }

    /**
     * Generate API token.
     *
     * @param int $length
     * @return string
     */
    private function generateApiToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }



}
