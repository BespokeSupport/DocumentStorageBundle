<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityIsDeletedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageTag
 * @package BespokeSupport\DocumentStorage\Entity
 * @ORM\Table("document_storage_tag", indexes={
 *      @ORM\Index(name="is_deleted", columns={"is_deleted"})
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository")
 */
class DocumentStorageTag
{
    use EntityIsDeletedTrait;

    /**
     * @var string
     * @ORM\Column(name="tag", type="string", length=255, unique=true)
     * @ORM\Id
     */
    private $tag;

    /**
     * @var DocumentStorageFile[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageFile", mappedBy="tags")
     **/
    private $files;

    /**
     * @var DocumentStorageText[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageText", mappedBy="tags")
     **/
    private $texts;

    /**
     * @param null|string $tag
     */
    public function __construct($tag = null)
    {
        if ($tag) {
            $this->setTag($tag);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return DocumentStorageTag
     */
    public function setTag($tag)
    {
        $tagString = trim((string)$tag);

        $tagString = mb_convert_case($tagString, MB_CASE_UPPER, mb_detect_encoding($tagString));

        $this->tag = $tagString;

        return $this;
    }
}
