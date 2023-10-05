<?php

namespace App\Entity;

use App\Repository\ReportCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportCommentRepository::class)]
class ReportComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'reportComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'reportComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCommentId(): ?Comment
    {
        return $this->comment_id;
    }

    public function setCommentId(?Comment $comment_id): static
    {
        $this->comment_id = $comment_id;

        return $this;
    }
}
