<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UnzerCredentialsConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer|mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof UnzerCredentialsTransfer) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                UnzerCredentialsTransfer::class,
                get_class($value),
            ));
        }

        if (!$constraint instanceof UnzerCredentialsConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                UnzerCredentialsConstraint::class,
                get_class($constraint),
            ));
        }

        $unzerCredentialsResponseTransfer = $constraint->getUnzerFacade()->validateUnzerCredentials($value);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            foreach ($unzerCredentialsResponseTransfer->getMessages() as $messageTransfer) {
                $this->addViolation($messageTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    protected function addViolation(MessageTransfer $messageTransfer): void
    {
        $this->context
            ->buildViolation($messageTransfer->getMessageOrFail(), $messageTransfer->getParameters())
            ->addViolation();
    }
}
