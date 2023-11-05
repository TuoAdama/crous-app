<?php

namespace App\Message;

class SearchResultMessage
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
    public function getIds(): array
    {
        return $this->ids;
    }
}