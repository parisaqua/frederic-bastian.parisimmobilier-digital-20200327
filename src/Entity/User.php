<?php

namespace App\Entity;

use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="Cette adresse email existe déjà !")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    
    const GENDER = [
        0 => 'M.',
        1 => 'Mme'
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un nom")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner une adresse e-mail valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Confirmation erronée !")
     *
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min=10, minMessage="Ne peut être inférieur à 10 caractères")
     */
    private $presentation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="author")
     */
    private $properties;

    /**
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="manager")
     */
    private $propertiesManaged;

    /**
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" }),
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar")
     */
    private $avatarFile;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->propertiesManaged = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug(){
        // if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName. ' ' . $this->lastName.' '.$this->id);
        // }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGenderType(): string {
        return self::GENDER[$this->gender];
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFullName() {
        return "{$this->firstName} {$this->lastName}"; 
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

    public function setAvatarFile(? File $avatar = null) : void {
        $this->avatarFile = $avatar;
 
        if (null !== $avatar) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
 
 
    public function getAvatarFile() : ? File {
            return $this->avatarFile;
    }
    
    
    public function getAvatar(){
            return $this->avatar;
    }
    
    /**
     * @param File|UploadedFile $image
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this->avatar;
    }


    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }


    /**
     * @return Collection|Property[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setAuthor($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getAuthor() === $this) {
                $property->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles(): array   {
        $roles = $this->roles;    
        $roles[] = 'ROLE_USER';
        return array_unique($roles); 
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function resetRoles()
    {
        $this->roles = [];
    }

    public function getPassword() {
        return $this->hash;
    }

    public function getSalt() {
    }

    public function getUsername() {
        return $this->email;
    }

    public function eraseCredentials() {
    }

    

    /**
     * @return Collection|Property[]
     */
    public function getPropertiesManaged(): Collection
    {
        return $this->propertiesManaged;
    }

    public function addPropertiesManaged(Property $propertiesManaged): self
    {
        if (!$this->propertiesManaged->contains($propertiesManaged)) {
            $this->propertiesManaged[] = $propertiesManaged;
            $propertiesManaged->setManager($this);
        }

        return $this;
    }

    public function removePropertiesManaged(Property $propertiesManaged): self
    {
        if ($this->propertiesManaged->contains($propertiesManaged)) {
            $this->propertiesManaged->removeElement($propertiesManaged);
            // set the owning side to null (unless already changed)
            if ($propertiesManaged->getManager() === $this) {
                $propertiesManaged->setManager(null);
            }
        }

        return $this;
    }


}
