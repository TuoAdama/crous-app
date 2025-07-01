<?php

namespace App\DTO\Request;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SearchRequestQuery
{
    public ?string $q = "";
    public ?string $type = null;
    public ?string $area = null;
    #[SerializedName("price_min")]
    public ?int $minPrice =  null;
}
