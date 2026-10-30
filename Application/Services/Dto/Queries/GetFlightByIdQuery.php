<?php

declare(strict_types=1);

final class GetFlightByIdQuery
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = trim($id);
    }

    public function getId(): string
    {
        return $this->id;
    }
}