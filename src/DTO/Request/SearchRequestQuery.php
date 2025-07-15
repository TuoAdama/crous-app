<?php

namespace App\DTO\Request;

use App\Validator\IsExtent;
use App\Validator\IsTypeLocation;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\When;

class SearchRequestQuery
{

    #[IsExtent]
    public ?string $extent = null;

    #[IsTypeLocation]
    public ?string $type = null;
    #[SerializedName("min_price")]
    #[When(
        expression: 'this.minPrice !== null',
        constraints: [new PositiveOrZero()]
    )]
    public ?int $minPrice =  0;
    #[SerializedName("min_area")]
    #[When(
        expression: 'this.minArea !== null',
        constraints: [new PositiveOrZero()]
    )]
    public ?int $minArea = 0;

    #[NotNull]
    public ?string $name = "";
}
