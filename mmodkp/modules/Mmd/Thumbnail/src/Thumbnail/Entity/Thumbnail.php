<?php

namespace Mmd\Thumbnail\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Epos\UserCore\Entity\User;
use InvalidArgumentException;
use Mmd\Thumbnail\Entity\Enum\ScaleEnum;
use Mmd\Util\Guard\DateTimeGuardTrait;

/**
 * Class Thumbnail
 *
 * @package    Mmd\Thumbnail
 * @subpackage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="thumbnails", uniqueConstraints={@ORM\UniqueConstraint(name="unique_thumb", columns={"image_id", "scale"})})
 */
class Thumbnail implements ImageInterface
{

    use DateTimeGuardTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Mmd\Thumbnail\Entity\Image", inversedBy="thumbnails")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Image
     */
    protected $image;

    /**
     * @ORM\Column(name="scale", type="string", length=10, nullable=false)
     *
     * @var string
     */
    protected $scale = ScaleEnum::VALUE_ICON;

    /**
     * @ORM\Column(name="source", type="string", length=255)
     *
     * @var string
     */
    protected $source;

    /**
     * @ORM\ManyToOne(targetEntity="Epos\UserCore\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="SET NULL", nullable=true)
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

    public function __construct($scale)
    {
        $this->setScale($scale);
        $this->createdOn = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     *
     * @return self
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param string $scale
     *
     * @return self
     */
    public function setScale($scale)
    {
        if (!in_array($scale, ScaleEnum::getSupportedScales())) {
            throw new InvalidArgumentException(
                sprintf('Scale [%s] is not supported', $scale)
            );
        }
        $this->scale = $scale;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return string
     */
    public function setSource($source)
    {
        $this->source = (string)$source;

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
