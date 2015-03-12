<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DocumentStorageEntity
 * @package BespokeSupport\DocumentStorage\Entity
 * @ORM\Table("document_storage_entity", indexes={
 *      @ORM\Index(name="entity_class_id", columns={"entity_class","entity_id"})
 * })
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository")
 */
class DocumentStorageEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(name="entity_class", type="string", length=255, nullable=false)
     */
    private $entity_class;
    /**
     * @var string
     * @ORM\Column(name="entity_id", type="string", length=255, nullable=false)
     */
    private $entity_id;
    /**
     * @var DocumentStorageFile[]
     *
     * @ORM\ManyToMany(targetEntity="DocumentStorageFile", mappedBy="entities")
     **/
    private $files;
    /**
     * @var DocumentStorageText[]
     *
     * @ORM\ManyToMany(targetEntity="DocumentStorageText", mappedBy="entities")
     **/
    private $texts;
}
