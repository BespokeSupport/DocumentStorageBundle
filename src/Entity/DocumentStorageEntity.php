<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageEntity
 * @package BespokeSupport\DocumentStorageBundle\Entity
 * @ORM\Table(
 *     "document_storage_entity",
 *     indexes={
 *      @ORM\Index(name="entity_class_id", columns={"entity_class","entity_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryEntity")
 */
class DocumentStorageEntity
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @var object
     */
    public $entity;
    /**
     * @var string
     * @ORM\Column(name="entity_class", type="string", length=191, nullable=false)
     */
    public $entityClass;
    /**
     * @var string
     * @ORM\Column(name="entity_id", type="string", length=191, nullable=false)
     */
    public $entityId;
    /**
     * @var DocumentStorageFile[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="DocumentStorageFile", mappedBy="entities")
     **/
    public $files;
    /**
     * @var DocumentStorageText[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="DocumentStorageText", mappedBy="entities")
     **/
    public $texts;

    /**
     * DocumentStorageEntity constructor.
     * @param null $class
     * @param null $id
     */
    public function __construct($class = null, $id = null)
    {
        $this->files = new ArrayCollection();
        $this->texts = new ArrayCollection();

        if ($id) {
            $this->setEntityId($id);
        }

        if ($class) {
            $this->setEntityClass($class);
        }
    }

    /**
     * @deprecated
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string|object $entityClass
     */
    public function setEntityClass($entityClass)
    {
        if ($entityClass) {
            if (is_string($entityClass)) {
                $this->entityClass = $entityClass;
            } elseif (is_object($entityClass)) {
                $this->entityClass = get_class($entityClass);
                $this->entity = $entityClass;
            } else {
                $this->entityClass = null;
            }
        } else {
            $this->entityClass = null;
        }
    }

    /**
     * @deprecated
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @deprecated
     * @param string $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @deprecated
     * @return DocumentStorageFile[]|ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @deprecated
     * @param DocumentStorageFile[] $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @deprecated
     * @return DocumentStorageText[]|ArrayCollection
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @deprecated
     * @param DocumentStorageText[] $texts
     */
    public function setTexts($texts)
    {
        $this->texts = $texts;
    }
}
