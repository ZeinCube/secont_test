<?php

namespace App\Model;

class Comment
{
    private ?int $id;
    private string $name;
    private string $text;

    public function __construct(string $name, string $text, ?int $id = null)
    {
        $this->name = $name;
        $this->text = $text;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}