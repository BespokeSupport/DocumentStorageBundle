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
 * @ORM\Table(
 *     "document_storage_text",
 *     indexes={
 *      @ORM\Index(name="document_storage_text_is_deleted", columns={"is_deleted"})
 *     },
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="document_storage_text_hash", columns={"hash"}),
 *     }
 * )
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
     * @var DocumentStorageEntity[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="DocumentStorageEntity", inversedBy="texts", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="document_storage_text_entities",
     *     joinColumns={@ORM\JoinColumn(name="text_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     **/
    public $entities;
    /**
     * @var DocumentStoragePermission[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="DocumentStoragePermission", mappedBy="text")
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", nullable=true)
     **/
    public $permissions;
    /**
     * @var DocumentStorageTag[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="DocumentStorageTag", inversedBy="texts")
     * @ORM\JoinTable(
     *     name="document_storage_text_tags",
     *     joinColumns={@ORM\JoinColumn(name="text_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag", referencedColumnName="tag")}
     * )
     **/
    public $tags;
    /**
     * @var string
     * @ORM\Column(name="text_content", type="text", nullable=false)
     */
    public $content;
    /**
     * @var string
     * @ORM\Column(name="text_source", type="string", length=191, nullable=false)
     */
    public $source;

    /**
     * @deprecated
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @deprecated
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @deprecated
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}
