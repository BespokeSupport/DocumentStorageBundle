<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStoragePermission
 * @package BespokeSupport\DocumentStorageBundle\Entity
 * @ORM\Table(
 *     "document_storage_permissions",
 *     indexes={
 *      @ORM\Index(name="file_id", columns={"file_id"}),
 *      @ORM\Index(name="text_id", columns={"text_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryPermission")
 */
class DocumentStoragePermission
{
    /**
     * @var string
     * @ORM\Column(name="permission", type="string", length=191)
     * @ORM\Id
     */
    public $permission;

    /**
     * @var DocumentStorageFile
     * @ORM\ManyToOne(targetEntity="DocumentStorageFile", inversedBy="permissions", cascade={"all"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    public $file;

    /**
     * @var DocumentStorageText
     * @ORM\ManyToOne(targetEntity="DocumentStorageText", inversedBy="permissions", cascade={"all"})
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", nullable=true)
     */
    public $text;

    /**
     * @deprecated
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @deprecated
     * @param string $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
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

    /**
     * @deprecated
     * @return DocumentStorageText
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @deprecated
     * @param DocumentStorageText $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
