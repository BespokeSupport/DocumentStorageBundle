<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageEntity
 * @package BespokeSupport\DocumentStorageBundle\Entity
 * @ORM\Table("document_storage_content")
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryContent")
 */
class DocumentStorageContent
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(name="contents", type="blob", nullable=false)
     */
    public $contents;
    /**
     * @var DocumentStorageFile
     * @ORM\OneToOne(targetEntity="DocumentStorageFile", cascade={"persist"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    public $file;

    /**
     * @deprecated
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @deprecated
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @deprecated
     * @param mixed $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @deprecated
     * @return DocumentStorageFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @deprecated
     * @param DocumentStorageFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
