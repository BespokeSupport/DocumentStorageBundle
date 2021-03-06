<?php

namespace BespokeSupport\DocumentStorageBundle\Service;

use BespokeSupport\DocumentStorageBundle\Base\DocumentStorageBaseEntity;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageContent;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageEntity;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageTag;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageText;
use BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryFile;
use BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryEntity;
use BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryTag;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;

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

    /**
     * @param DocumentStorageFile $entity
     * @return DocumentStorageFile|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveFile(DocumentStorageFile $entity)
    {
        $repo = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_FILE);

        $existing = $repo->findOneBy(['hash' => $entity->getHash()]);

        if ($existing) {
            return $existing;
        }

        $entityManager = $this->getEntityManager();

        $entity = $entityManager->merge($entity);

        $entityManager->flush();

        return $entity;
    }

    /**
     * @param DocumentStorageFile $entity
     * @return DocumentStorageFile|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateFile(DocumentStorageFile $entity)
    {
        $entityManager = $this->getEntityManager();

        $entity = $entityManager->merge($entity);

        $entityManager->flush();

        return $entity;
    }

    /**
     * @return ObjectManager|EntityManager
     */
    public function getEntityManager()
    {
        $manager = $this->managerRegistry->getManagerForClass(DocumentStorageFile::class);

        return $manager;
    }

    /**
     * @return DocumentStorageRepositoryFile|ObjectRepository
     */
    public function getRepositoryFile()
    {
        $repo = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_FILE);

        return $repo;
    }

    /**
     * @return DocumentStorageRepositoryEntity|ObjectRepository
     */
    public function getRepositoryEntity()
    {
        $repo = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_ENTITY);

        return $repo;
    }

    /**
     * @return DocumentStorageRepositoryTag|ObjectRepository
     */
    public function getRepositoryTag()
    {
        $repo = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_TAG);

        return $repo;
    }

    /**
     * @param DocumentStorageFile $entity
     * @return bool
     * @throws \Exception
     */
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
        $entityManager = $this->getEntityManager();

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

        $dbTag = $entityManager->find(DocumentStorageService::CLASS_TAG, (string)$tagString);

        if ($dbTag) {
            return $dbTag;
        } else {
            return $entity->setTag($tagString);
        }
    }

    /**
     * @param null|string $tagString
     * @return DocumentStorageTag
     */
    public function getOrCreateEntity($entityStr, $entityId)
    {
        $repo = $this->managerRegistry->getRepository(DocumentStorageService::CLASS_ENTITY);

        $docEntity = $repo->findOneBy([
            'entityClass' => $entityStr,
            'entityId' => $entityId,

        ]);

        if ($docEntity) {
            return $docEntity;
        }

        $docEntity = new DocumentStorageEntity($entityStr, $entityId);

        $entityManager = $this->getEntityManager();

        $entityManager->persist($docEntity);

        $entityManager->flush();

        return $docEntity;
    }

    /**
     * $entity - String or the Entity
     *
     * @param $tag
     * @param null $entity
     * @param null $id
     * @return DocumentStorageFile|null
     */
    public function fileByTag($tag, $entity = null, $id = null, $latestFirst = true)
    {
        if (is_string($tag)) {
            $tag = $this->getOrCreateTag($tag);
        }

        /**
         * @var $repo DocumentStorageRepositoryTag
         */
        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_FILE);
        $repo = $entityManager->getRepository(DocumentStorageService::CLASS_FILE);
        //
        $builder = $repo->createQueryBuilder('zz');
        $builder->leftJoin('zz.tags','t');
        $builder->where('t.tag = :tag');
        $builder->setParameter('tag', $tag);

        $builder->orderBy('zz.created', (($latestFirst) ? 'DESC' : 'ASC'));

        if ($entity && $id) {
            $entityString = (is_string($entity))?$entity:get_class($entity);
            $builder->leftJoin('zz.entities','e');
            $builder->andWhere('e.entityClass = :class');
            $builder->andWhere('e.entityId = :id');
            $builder->setParameter('class', $entityString);
            $builder->setParameter('id', $id);
        }

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * $entity - String or the Entity
     *
     * @param $tag
     * @param array $order
     * @param null $entity
     * @param null $id
     * @return array
     */
    public function filesByTag($tag, $order = array(), $entity = null, $id = null)
    {
        if (is_string($tag)) {
            $tag = $this->getOrCreateTag($tag);
        }

        /**
         * @var $repo DocumentStorageRepositoryTag
         */
        $entityManager = $this->managerRegistry->getManagerForClass(DocumentStorageService::CLASS_FILE);
        $repo = $entityManager->getRepository(DocumentStorageService::CLASS_FILE);

        $builder = $repo->createQueryBuilder('zz');
        $builder->leftJoin('zz.tags','t');
        $builder->where('t.tag = :tag');
        $builder->setParameter('tag', $tag);
        $builder->orderBy('zz.created', 'DESC');

        if ($entity && $id) {
            $entityString = (is_string($entity))?$entity:get_class($entity);
            $builder->leftJoin('zz.entities','e');
            $builder->andWhere('e.entityClass = :class');
            $builder->andWhere('e.entityId = :id');
            $builder->setParameter('class', $entityString);
            $builder->setParameter('id', $id);
        }

//        foreach ($order as $oK =>) {
//            $builder->addOrderBy($)
//        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param $hash
     * @return bool
     */
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

    /**
     * @param $hash
     * @return bool
     */
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
        $entityManager = $this->getEntityManager();

        $entityManager->persist($entity);

        $entityManager->flush();

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
