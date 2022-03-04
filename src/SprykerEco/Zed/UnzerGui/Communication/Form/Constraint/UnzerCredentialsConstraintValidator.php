<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UnzerCredentialsParameterMessageTransfer;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UnzerCredentialsConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer|string|null $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnzerCredentialsConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                UnzerCredentialsConstraint::class,
                get_class($constraint),
            ));
        }

        $unzerValidationResponseTransfer = $constraint->getUnzerFacade()->validateUnzerCredentials($value);

        if (!$unzerValidationResponseTransfer->getIsSuccessful()) {
            foreach ($unzerValidationResponseTransfer->getMessages() as $message) {
                $this->addViolations($message);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentParameterMessageTransfer $unzerCredentialsParameterMessageTransfer
     *
     * @return void
     */
    protected function addViolations(UnzerCredentialsParameterMessageTransfer $unzerCredentialsParameterMessageTransfer): void
    {
        foreach ($unzerCredentialsParameterMessageTransfer->getMessages() as $messageTransfer) {
            $this->context
                ->buildViolation($messageTransfer->getValue(), $messageTransfer->getParameters())
                ->atPath(sprintf('[%s]', $unzerCredentialsParameterMessageTransfer->getParameter()))
                ->addViolation();
        }
    }
}
