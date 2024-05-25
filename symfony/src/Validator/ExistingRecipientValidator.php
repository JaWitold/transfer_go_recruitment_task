<?php

namespace App\Validator;

use App\Service\UserProvider\UserProviderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistingRecipientValidator extends ConstraintValidator
{
    public function __construct(private readonly UserProviderInterface $userProvider)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        /** @var int $value */
        $product = $this->userProvider->getUser($value);

        if ($product === null) {
            /** @var ExistingRecipient $constraint */
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ userId }}', (string) $value)
                ->addViolation();
        }
    }
}
