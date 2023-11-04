<?php

namespace App\Message;

class CriteriaMessage
{
    public function __construct(
        /** @var int[] */
        private readonly array $ids
    )
    {
    }

    /**
     * @return int[]
     */
    public function getId(): array
    {
        return $this->ids;
    }
}