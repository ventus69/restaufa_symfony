<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 * @UniqueEntity(
 * fields={"restau_mail"},
 * message="L'email que vous avez utiliser est deja utiliser"
 * )
 */
class Restaurant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restau_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $restau_mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restau_phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 8, max = 20, minMessage = "la valeur de phone doit = 8 chiffres", maxMessage = "la valeur de phone doit = 8 chiffres")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="you need to enter only numbers") 
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Your first name must be at least {{ limit }} characters long")
     */
    private $restau_password;

    /**
     * @Assert\EqualTo(propertyPath="restau_password" , message="your password is not equal to your password")
    */

    public $confirme_restau_password;

    /**
     * @ORM\Column(type="array")
     */
    private $gallery = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restau_description;

    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restau_image;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="restaurant", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accepted;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRestauName(): ?string
    {
        return $this->restau_name;
    }

    public function getUsername(): ?string
    {
        return $this->restau_name;
    }

    public function setRestauName(string $restau_name): self
    {
        $this->restau_name = $restau_name;

        return $this;
    }

    public function getRestauMail(): ?string
    {
        return $this->restau_mail;
    }

    public function setRestauMail(string $restau_mail): self
    {
        $this->restau_mail = $restau_mail;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->restau_mail;
    }

    public function setEmail(string $restau_mail): self
    {
        $this->restau_mail = $restau_mail;

        return $this;
    }

    public function getRestauPhone(): ?string
    {
        return $this->restau_phone;
    }

    public function setRestauPhone(string $restau_phone): self
    {
        $this->restau_phone = $restau_phone;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getRestauPassword(): ?string
    {
        return $this->restau_password;
    }

    public function getPassword(): ?string
    {
        return $this->restau_password;
    }

    public function setRestauPassword(string $restau_password): self
    {
        $this->restau_password = $restau_password;

        return $this;
    }

    public function getGallery(): ?array
    {
        return $this->gallery;
    }

    public function setGallery(array $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

        public function getRestauDescription(): ?string
    {
        return $this->restau_description;
    }

    public function setRestauDescription(string $restau_description): self
    {
        $this->restau_description = $restau_description;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getRestauImage(): ?string
    {
        return $this->restau_image;
    }

    public function setImage($restau_image)
    {
        $this->restau_image = $restau_image;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->restau_image;
    }

    public function setRestauImage(string $restau_image): self
    {
        $this->restau_image = $restau_image;

        return $this;
    }

    public function eraseCredentials()
    {

    }

    public function getSalt()
    {

    }

    public function getRoles()
    {
        return ['ROLE_RESTAURANT'];
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRestaurant($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRestaurant() === $this) {
                $comment->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
    
}
