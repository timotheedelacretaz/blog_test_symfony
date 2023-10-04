<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $report = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Article $article_id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'comment_id', targetEntity: ReportComment::class, orphanRemoval: true)]
    private Collection $reportComments;

    public function __construct()
    {
        $this->reportComments = new ArrayCollection();
    }

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getReport(): ?int
    {
        return $this->report;
    }

    public function setReport(int $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getArticleId(): ?Article
    {
        return $this->article_id;
    }

    public function setArticleId(?Article $article_id): static
    {
        $this->article_id = $article_id;

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

    /**
     * @return Collection<int, ReportComment>
     */
    public function getReportComments(): Collection
    {
        return $this->reportComments;
    }

    public function addReportComment(ReportComment $reportComment): static
    {
        if (!$this->reportComments->contains($reportComment)) {
            $this->reportComments->add($reportComment);
            $reportComment->setCommentId($this);
        }

        return $this;
    }

    public function removeReportComment(ReportComment $reportComment): static
    {
        if ($this->reportComments->removeElement($reportComment)) {
            // set the owning side to null (unless already changed)
            if ($reportComment->getCommentId() === $this) {
                $reportComment->setCommentId(null);
            }
        }

        return $this;
    }
}
