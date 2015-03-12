<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityCreatedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityIsDeletedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageText
 * @package BespokeSupport\DocumentStorage\Entity
 *
 * @ORM\Table("document_storage_text", indexes={
 *      @ORM\Index(name="is_deleted", columns={"is_deleted"})
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository")
 */
class DocumentStorageText
{
    use EntityCreatedTrait;
    use EntityUpdatedTrait;
    use EntityIsDeletedTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
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
    private $entities;
    /**
     * @var DocumentStoragePermission[]
     * @ORM\OneToMany(targetEntity="DocumentStoragePermission", mappedBy="text")
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", nullable=true)
     **/
    private $permissions;
    /**
     * @var \SplFileInfo|null
     */
    private $splFile;
    /**
     * @var DocumentStorageTag[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageTag", inversedBy="texts")
     * @ORM\JoinTable(name="document_storage_text_tags",
     *      joinColumns={@ORM\JoinColumn(name="text_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag", referencedColumnName="tag")}
     * )
     **/
    private $tags;
    /**
     * @var string
     * @ORM\Column(name="text_content", type="text", nullable=false)
     */
    private $content;
    /**
     * @var string
     * @ORM\Column(name="text_source", type="string", length=255, nullable=false)
     */
    private $source;




}
