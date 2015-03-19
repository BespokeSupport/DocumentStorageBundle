<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityCreatedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityIsDeletedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityUpdatedTrait;
use BespokeSupport\DocumentStorageBundle\Base\DocumentStorageBaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageText
 * @package BespokeSupport\DocumentStorageBundle\Entity
 *
 * @ORM\Table("document_storage_text", indexes={
 *      @ORM\Index(name="is_deleted", columns={"is_deleted"})
 * },
 * uniqueConstraints={
 *      @ORM\UniqueConstraint(name="hash", columns={"hash"}),
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryText")
 */
class DocumentStorageText extends DocumentStorageBaseEntity
{
    use EntityCreatedTrait;
    use EntityUpdatedTrait;
    use EntityIsDeletedTrait;


    /**
     * Entities to which the Text relates to
     *
     * @var DocumentStorageEntity[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageEntity", inversedBy="texts", cascade={"persist"})
     * @ORM\JoinTable(name="document_storage_text_entities",
     *      joinColumns={@ORM\JoinColumn(name="text_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     **/
    protected $entities;
    /**
     * @var DocumentStoragePermission[]
     * @ORM\OneToMany(targetEntity="DocumentStoragePermission", mappedBy="text")
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", nullable=true)
     **/
    protected $permissions;
    /**
     * @var \SplFileInfo|null
     */
    protected $splFile;
    /**
     * @var DocumentStorageTag[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageTag", inversedBy="texts")
     * @ORM\JoinTable(name="document_storage_text_tags",
     *      joinColumns={@ORM\JoinColumn(name="text_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag", referencedColumnName="tag")}
     * )
     **/
    protected $tags;
    /**
     * @var string
     * @ORM\Column(name="text_content", type="text", nullable=false)
     */
    protected $content;
    /**
     * @var string
     * @ORM\Column(name="text_source", type="string", length=255, nullable=false)
     */
    protected $source;




    /**
     * @return null|\SplFileInfo
     */
    public function getSplFile()
    {
        return $this->splFile;
    }

    /**
     * @param null|\SplFileInfo $splFile
     */
    public function setSplFile($splFile)
    {
        $this->splFile = $splFile;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}
