<?php

namespace BespokeSupport\DocumentStorageBundle\Service;

use BespokeSupport\DocumentStorageBundle\Base\DocumentStorageBaseEntity;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageContent;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageEntity;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageTag;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageText;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class DocumentStorageManager
 * @package BespokeSupport\DocumentStorageBundle\Service
 */
class DocumentStorageManager
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry = null)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function saveFile(DocumentStorageFile &$entity)
    {
        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_FILE);

        $entityManager->persist($entity);
        $entityManager->flush();

        return $entity;
    }




    public function saveFileContents(DocumentStorageFile &$entity)
    {
        if (is_null($entity->getFileInfo()) || !$entity->getFileInfo()->isReadable()) {
            throw new \Exception('Not Readable');
        }

        // entity
        $fileContent = new DocumentStorageContent();
        $fileContent->setContents(file_get_contents($entity->getFileInfo()->getRealPath()));
        $fileContent->setFile($entity);
        $entity->setContent($fileContent);

        // db
        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_CONTENT);

        // save both entities
        $entityManager->persist($entity);
        $entityManager->persist($fileContent);

        //
        $entityManager->flush();

        return true;
    }

    /**
     * @param null|string $tagString
     * @return DocumentStorageTag
     */
    public function getOrCreateTag($tagString = null)
    {
        if (!$tagString) return null;

        $entity = new DocumentStorageTag();

        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_TAG);

        $dbTag = $entityManager->find(DocumentStorageService::CLASS_TAG, $tagString);

        if ($dbTag) {
            return $dbTag;
        } else {
            return $entity->setTag($tagString);
        }
    }

    public function entitiesByTag($tag)
    {
        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_TAG);

//        $entities = $entityManager->findBy();

        return [];
    }


    public function removeAllTags($hash)
    {
        if (!($hash instanceof DocumentStorageBaseEntity)) {
            $entity = $this->getByHash($hash);
        } else {
            $entity = $hash;
        }

        if (!$entity) return false;

        $entity->setTags(array());

        return $this->persistStorageEntity($entity);
    }

    public function removeAllEntities($hash)
    {
        if (!($hash instanceof DocumentStorageBaseEntity)) {
            $entity = $this->getByHash($hash);
        } else {
            $entity = $hash;
        }

        if (!$entity) return false;

        $entity->setEntities(array());

        return $this->persistStorageEntity($entity);
    }



    /**
     * @param $entity
     * @return bool
     */
    public function persistStorageEntity($entity)
    {
        $em = $this->managerRegistry->getManager();

        $em->persist($entity);
        $em->flush();

        return true;
    }



    /**
     * @param $hash
     * @return DocumentStorageText|DocumentStorageFile|null
     */
    public function getByHash($hash)
    {
        $entity = $this->getFileByHash($hash);

        if (!$entity) {
            $entity = $this->getTextByHash($hash);
        }

        if ($entity) {
            $entity->newFile = false;
        }

        return $entity;
    }

    /**
     * @param $hash
     * @return DocumentStorageText
     */
    public function getTextByHash($hash)
    {
        $entityManager = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_TEXT);

        $entity = $entityManager->findOneBy([
            'hash' => $hash
        ]);

        return $entity;
    }

    /**
     * @param $hash
     * @return DocumentStorageFile
     */
    public function getFileByHash($hash)
    {
        $entityManager = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_FILE);

        $entity = $entityManager->findOneBy([
            'hash' => $hash
        ]);

        return $entity;
    }
}
