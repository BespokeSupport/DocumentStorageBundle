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
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(name="contents", type="blob", nullable=false)
     */
    private $contents;
    /**
     * @var DocumentStorageFile
     * @ORM\OneToOne(targetEntity="DocumentStorageFile", cascade={"persist"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    private $file;

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
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return DocumentStorageFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param DocumentStorageFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }




}
