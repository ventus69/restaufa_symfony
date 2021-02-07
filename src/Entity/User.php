<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email que vous avez utiliser est deja utiliser"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Your first name must be at least {{ limit }} characters long")
     */
    private $password_user;

    /**
     * @Assert\EqualTo(propertyPath="password_user" , message="your password is not equal to your password")
    */

    public $confirm_password_user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 8, max = 20, minMessage = "la valeur de phone doit = 8 chiffres", maxMessage = "la valeur de phone doit = 8 chiffres")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="you need to enter only numbers") 
     */
    private $user_phone;

    /**
     * @ORM\Column(type="string", length=255)
    * @Assert\File(mimeTypes={ "image/png", "image/jpeg" }) 
     */
    private $user_photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->password_user;
    }

    public function setPasswordUser(string $password_user): self
    {
        $this->password_user = $password_user;

        return $this;
    }

    public function getUserPhone(): ?string
    {
        return $this->user_phone;
    }

    public function setUserPhone(string $user_phone): self
    {
        $this->user_phone = $user_phone;

        return $this;
    }

    public function getUserPhoto(): ?string
    {
        return $this->user_photo;
    }

    public function getPhoto()
    {
        return $this->user_photo;
    }

    public function setPhoto($user_photo)
    {
        $this->user_photo = $user_photo;

        return $this;
    }

    public function setUserPhoto(string $user_photo): self
    {
        $this->user_photo = $user_photo;

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
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->password_user;
    }

    public function getUsername(): ?string
    {
        return $this->fullname;
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


}
