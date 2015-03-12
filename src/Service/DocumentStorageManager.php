<?php

namespace BespokeSupport\DocumentStorageBundle\Service;

use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageContent;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageTag;
use Doctrine\Common\Persistence\ManagerRegistry;

class DocumentStorageManager
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry = null)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function saveFile(DocumentStorageFile $entity)
    {
        $entityManager = $this->managerRegistry->getManagerForClass(get_class($entity));


        if ($entity->getRelatedEntity()) {




        }


        $entityManager->persist($entity);
        $entityManager->flush();
    }


    public function saveFileContents(DocumentStorageFile $entity)
    {
        $fileContent = new DocumentStorageContent();

        if (is_null($entity->getFileInfo()) || !$entity->getFileInfo()->isReadable()) {
            throw new \Exception('Not Readable');
        }

        $fileContent->setContents(file_get_contents($entity->getFileInfo()->getRealPath()));

        $fileContent->setFile($entity);

        $entityManager = $this->managerRegistry->getManagerForClass(get_class($fileContent));
        $entityManager->persist($entity);
//        $entityManager->persist($fileContent);
        $entityManager->flush();
    }


    /**
     * @param null|string $tagString
     * @return DocumentStorageTag
     */
    public function getOrCreateTag($tagString = null)
    {
        if (!$tagString) return null;

        $entity = new DocumentStorageTag();
        $entityManager = $this->managerRegistry->getManagerForClass(get_class($entity));

        $dbTag = $entityManager->find(get_class($entity), $tagString);

        if ($dbTag) {
            return $dbTag;
        } else {
            return $entity->setTag($tagString);
        }
    }
}
