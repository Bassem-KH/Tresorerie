<?php

namespace Ositel\TresorerieBundle\Entity;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="Ositel\TresorerieBundle\Repository\TransactionRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Transaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=30)
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_input", type="boolean")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $isInput;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_valid", type="boolean")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $isValid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Ositel\TresorerieBundle\Entity\Category")
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private $category;
    /**
     * @ORM\ManyToMany(targetEntity="Ositel\TresorerieBundle\Entity\Tag")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Groups({"TransactionGroup"})
     * @Serializer\Expose()
     */
    private  $tags;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Transaction
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set isInput
     *
     * @param boolean $isInput
     *
     * @return Transaction
     */
    public function setIsInput($isInput)
    {
        $this->isInput = $isInput;

        return $this;
    }

    /**
     * Get isInput
     *
     * @return bool
     */
    public function getIsInput()
    {
        return $this->isInput;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Transaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     *
     * @return Transaction
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return bool
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Transaction
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function getCategory() {
        return $this->category;
    }

    function setCategory($category) {
        $this->category = $category;
    }

    function getTags() {
        return $this->tags;
    }

    function setTags($tags) {
        $this->tags = $tags;
    }
    public function __construct() {
        $this->createdAt = new \DateTime('NOW'); 
        $this->updatedAt = new \DateTime('NOW'); 
    }


}

