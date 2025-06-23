<?php

namespace App\DTO\Request;

class SearchRequestPayload
{
    public string $lon1;
    public string $lat1;
    public string $lon2;
    public string $lat2;

    public ?string $min;
    public ?string $max;
    public array $occupationModes = [];

}
