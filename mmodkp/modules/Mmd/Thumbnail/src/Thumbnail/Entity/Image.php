<?php

namespace Mmd\Thumbnail\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Epos\UserCore\Entity\User;
use Mmd\Util\Guard\DateTimeGuardTrait;
use Traversable;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class Image
 *
 * @package    Mmd\Thumbnail
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class Image implements ImageInterface
{

    use DateTimeGuardTrait;
    use ArrayOrTraversableGuardTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="original_source", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $originalSource;

    /**
     * @ORM\OneToMany(
     *  targetEntity="Mmd\Thumbnail\Entity\Thumbnail",
     *  mappedBy="image",
     *  cascade={"ALL"},
     *  orphanRemoval=true,
     *  indexBy="scale"
     * )
     *
     * @var Collection
     */
    protected $thumbnails;

    /**
     * @ORM\ManyToOne(targetEntity="Epos\UserCore\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var User
     */
    protected $createdBy;

    /**
     * @ORM\Column(name="created_on", type="datetime")
     *
     * @var DateTime
     */
    protected $createdOn;

    public function __construct()
    {
        $this->thumbnails = new ArrayCollection();
        $this->createdOn  = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginalSource()
    {
        return $this->originalSource;
    }

    /**
     * Alias for self::getOriginalSource()
     *
     * @return string
     */
    public function getSource()
    {
        return $this->getOriginalSource();
    }

    /**
     * @param string $originalSource
     *
     * @return self
     */
    public function setOriginalSource($originalSource)
    {
        $this->originalSource = (string)$originalSource;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    /**
     * @param string $scale
     *
     * @return Thumbnail|null
     */
    public function getThumbnail($scale)
    {
        if ($this->thumbnails->offsetExists($scale)) {
            return $this->thumbnails->offsetGet($scale);
        }

        return null;
    }

    /**
     * @param Thumbnail $thumbnail
     *
     * @return self
     */
    public function addThumbnail(Thumbnail $thumbnail)
    {
        $this->thumbnails[$thumbnail->getScale()] = $thumbnail;
        $thumbnail->setImage($this);

        return $this;
    }

    /**
     * @param Thumbnail $thumbnail
     *
     * @return self
     */
    public function removeThumbnail(Thumbnail $thumbnail)
    {
        if (isset($this->thumbnails[$thumbnail->getScale()])) {
            unset($this->thumbnails[$thumbnail->getScale()]);

            return $this;
        }

        if ($this->thumbnails->contains($thumbnail)) {
            $this->thumbnails->removeElement($thumbnail);
        }

        return $this;
    }

    /**
     * @param array|Traversable $thumbnails
     *
     * @return self
     */
    public function setThumbnails($thumbnails)
    {
        $this->guardForArrayOrTraversable($thumbnails);

        $this->thumbnails->clear();

        foreach ($thumbnails as $thumbnail) {
            $this->addThumbnail($thumbnail);
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return self
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param DateTime $createdOn
     *
     * @return self
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $this->guardForDateTime($createdOn);

        return $this;
    }

}
