<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStoragePermission
 * @package BespokeSupport\DocumentStorageBundle\Entity
 * @ORM\Table("document_storage_permissions", indexes={
 *      @ORM\Index(name="file_id", columns={"file_id"}),
 *      @ORM\Index(name="text_id", columns={"text_id"})
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryPermission")
 */
class DocumentStoragePermission
{
    /**
     * @var string
     * @ORM\Column(name="permission", type="string", length=191)
     * @ORM\Id
     */
    private $permission;

    /**
     * @var DocumentStorageFile
     * @ORM\ManyToOne(targetEntity="DocumentStorageFile", inversedBy="permissions", cascade={"all"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    private $file;

    /**
     * @var DocumentStorageText
     * @ORM\ManyToOne(targetEntity="DocumentStorageText", inversedBy="permissions", cascade={"all"})
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", nullable=true)
     */
    private $text;

    /**
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
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

    /**
     * @return DocumentStorageText
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param DocumentStorageText $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
