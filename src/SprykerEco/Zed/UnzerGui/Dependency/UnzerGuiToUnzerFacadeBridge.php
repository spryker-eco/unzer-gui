<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Dependency;

use Generated\Shared\Transfer\UnzerCredentialsCollectionTransfer;
use Generated\Shared\Transfer\UnzerCredentialsCriteriaTransfer;
use Generated\Shared\Transfer\UnzerCredentialsResponseTransfer;
use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Generated\Shared\Transfer\UnzerKeypairTransfer;

class UnzerGuiToUnzerFacadeBridge implements UnzerGuiToUnzerFacadeInterface
{
    /**
     * @var \SprykerEco\Zed\Unzer\Business\UnzerFacadeInterface
     */
    protected $unzerFacade;

    /**
     * @param \SprykerEco\Zed\Unzer\Business\UnzerFacadeInterface $unzerFacade
     */
    public function __construct($unzerFacade)
    {
        $this->unzerFacade = $unzerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsResponseTransfer
     */
    public function createUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsResponseTransfer
    {
        return $this->unzerFacade->createUnzerCredentials($unzerCredentialsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsCriteriaTransfer $unzerCredentialsCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsCollectionTransfer
     */
    public function getUnzerCredentialsCollection(
        UnzerCredentialsCriteriaTransfer $unzerCredentialsCriteriaTransfer
    ): UnzerCredentialsCollectionTransfer {
        return $this->unzerFacade->getUnzerCredentialsCollection($unzerCredentialsCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return void
     */
    public function setUnzerNotificationUrl(UnzerCredentialsTransfer $unzerCredentialsTransfer): void
    {
        $this->unzerFacade->setUnzerNotificationUrl($unzerCredentialsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsResponseTransfer
     */
    public function updateUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsResponseTransfer
    {
        return $this->unzerFacade->updateUnzerCredentials($unzerCredentialsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsResponseTransfer
     */
    public function deleteUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsResponseTransfer
    {
        return $this->unzerFacade->deleteUnzerCredentials($unzerCredentialsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerKeypairTransfer $unzerKeypairTransfer
     *
     * @return void
     */
    public function performPaymentMethodsImport(UnzerKeypairTransfer $unzerKeypairTransfer): void
    {
        $this->unzerFacade->performPaymentMethodsImport($unzerKeypairTransfer);
    }

    /**
     * @param UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return UnzerCredentialsResponseTransfer
     */
    public function validateUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsResponseTransfer
    {
        return $this->unzerFacade->validateUnzerCredentials($unzerCredentialsTransfer);
    }
}
