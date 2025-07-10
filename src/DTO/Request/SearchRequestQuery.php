<?php

namespace App\DTO\Request;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SearchRequestQuery
{
    public ?string $extent = null;

    public ?string $type = null;
    #[SerializedName("min_price")]
    public ?int $minPrice =  null;
    #[SerializedName("min_area")]
    public ?int $minArea = null;

    public ?string $name = "";
}
