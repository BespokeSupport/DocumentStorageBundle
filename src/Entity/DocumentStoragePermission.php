<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStoragePermission
 * @package BespokeSupport\DocumentStorage\Entity
 * @ORM\Table("document_storage_permissions", indexes={
 *      @ORM\Index(name="file_id", columns={"file_id"}),
 *      @ORM\Index(name="text_id", columns={"text_id"})
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository")
 */
class DocumentStoragePermission
{
    /**
     * @var integer
     * @ORM\Column(name="permission", type="string", length=255)
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




}
