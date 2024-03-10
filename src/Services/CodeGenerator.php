<?php

namespace App\Services;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CodeGenerator implements CodeGeneratorInterface
{
    private int $digit;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    )
    {
        $this->digit = $this->parameterBag->get('sms.verification.digit');
    }

    /**
     * @throws Exception
     */
    public function generate(): int
    {
        if ($this->digit < 0){
            throw new Exception("Value must be great than zero");
        }
        return random_int(pow(10, $this->digit-1), pow(10, $this->digit)-1);
    }
}