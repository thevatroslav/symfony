<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThreadRepository")
 */
class Thread
{
//    public $message;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */

    private $dateCreated;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade="persist")
     * @ORM\JoinColumns(@ORM\JoinColumn(name="user_id"))
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subforum", inversedBy="threads", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subforum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="thread", orphanRemoval=true, cascade="persist")
     *
     */
    private $messages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */

    private $lastMessageDate;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSubforum(): ?Subforum
    {
        return $this->subforum;
    }

    public function setSubforum(?Subforum $subforum): self
    {
        $this->subforum = $subforum;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setThread($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getThread() === $this) {
                $message->setThread(null);
            }
        }

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getLastMessageDate(): ?\DateTimeInterface
    {
        return $this->lastMessageDate;
    }

    public function setLastMessageDate(?\DateTimeInterface $lastMessageDate): self
    {
        $this->lastMessageDate = $lastMessageDate;

        return $this;
    }




}
