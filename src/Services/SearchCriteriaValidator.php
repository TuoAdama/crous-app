<?php

namespace App\Services;

use App\Entity\SearchCriteria;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchCriteriaValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function validate(SearchCriteria $searchCriteria): array
    {
        $constraints = $this->validator->validate($searchCriteria);
        $errors = [];
        if ($constraints->count()){
            foreach ($constraints as $constraint) {
                $errors[$constraint->getPropertyPath()] = $constraint->getMessage();
            }
        }
        return $errors;
    }
}