<?php
/**
 *
 */

namespace BespokeSupport\DocumentStorageBundle\Base;

use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageEntity;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStoragePermission;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageTag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageBaseEntity
 * @package BespokeSupport\DocumentStorageBundle\Base
 */
abstract class DocumentStorageBaseEntity
{
    public $newFile = true;
    protected $entities;
    /**
     * @var string
     * @ORM\Column(name="hash", type="string", length=255, nullable=false)
     */
    protected $hash;
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    protected $permissions;
    protected $tags;

    function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->entities = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * @param DocumentStorageEntity $entity
     * @return boolean
     */
    public function addEntity(DocumentStorageEntity $entity = null)
    {
        if ($entity) {
            if (!$this->entities->contains($entity)) {
                $this->entities->add($entity);
                return true;
            }
        }

        return false;
    }

    /**
     * @param DocumentStoragePermission $entity
     * @return boolean
     */
    public function addPermission(DocumentStoragePermission $entity = null)
    {
        if ($entity) {
            if (!$this->permissions->contains($entity)) {
                $this->permissions->add($entity);
                return true;
            }
        }

        return false;
    }

    /**
     * @param DocumentStorageTag $entity
     * @return boolean
     */
    public function addTag(DocumentStorageTag $entity = null)
    {
        if ($entity) {
            if (!$this->tags->contains($entity)) {
                $this->tags->add($entity);
                return true;
            }
        }

        return false;
    }

    /**
     * @return DocumentStorageEntity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param DocumentStorageEntity[] $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DocumentStoragePermission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param DocumentStoragePermission[] $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return DocumentStorageTag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param DocumentStorageTag[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param DocumentStorageEntity $entity
     * @return bool
     */
    public function removeEntity(DocumentStorageEntity $entity = null)
    {
        if ($entity) {
            if ($this->entities->contains($entity)) {
                $this->entities->removeElement($entity);
            }
        }

        return false;
    }

    /**
     * @param DocumentStoragePermission $entity
     * @return bool
     */
    public function removePermission(DocumentStoragePermission $entity = null)
    {
        if ($entity) {
            if ($this->permissions->contains($entity)) {
                $this->permissions->removeElement($entity);
            }
        }

        return false;
    }

    /**
     * @param DocumentStorageTag $entity
     * @return bool
     */
    public function removeTag(DocumentStorageTag $entity = null)
    {
        if ($entity) {
            if ($this->tags->contains($entity)) {
                $this->tags->removeElement($entity);
            }
        }

        return false;
    }
}
