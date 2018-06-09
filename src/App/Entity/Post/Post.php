<?php

namespace App\Entity\Post;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime_immutable", name="create_date")
     */
    private $createDate;
    /**
     * @ORM\Column(type="datetime_immutable", name="update_date", nullable=true)
     */
    private $updateDate;
    /**
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    /**
     * @var Meta
     * @ORM\Embedded(class="Meta")
     */
    private $meta;
    /**
     * @var ArrayCollection|Comment[]
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $comments;

    public function __construct(\DateTimeImmutable $date, string $title, string $content, Meta $meta)
    {
        $this->createDate = $date;
        $this->title = $title;
        $this->content = $content;
        $this->meta = $meta;
        $this->comments = new ArrayCollection();
    }

    public function edit(string $title, string $content, Meta $meta): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->meta = $meta;
        $this->updateDate = new \DateTimeImmutable();
    }

    public function addComment(string $author, string $content): void
    {
        $this->comments->add(new Comment($this, $author, $content));
    }

    public function removeComment(int $id): void
    {
        foreach ($this->comments as $comment) {
            if ($comment->getId() === $id) {
                $this->comments->removeElement($comment);
            }
        }
        throw new \DomainException('Comment is not found.');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getUpdateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMeta(): Meta
    {
        return $this->meta;
    }

    public function getMetaTitle(): string
    {
        return $this->meta->getTitle() ?: $this->title;
    }
}
