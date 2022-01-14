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
     * @param UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return UnzerCredentialsResponseTransfer
     */
    public function createUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsResponseTransfer
    {
        return $this->unzerFacade->createUnzerCredentials($unzerCredentialsTransfer);
    }

    /**
     * @param UnzerCredentialsCriteriaTransfer $unzerCredentialsCriteriaTransfer
     *
     * @return UnzerCredentialsCollectionTransfer
     */
    public function getUnzerCredentialsCollection(
        UnzerCredentialsCriteriaTransfer $unzerCredentialsCriteriaTransfer
    ): UnzerCredentialsCollectionTransfer
    {
        return $this->unzerFacade->getUnzerCredentialsCollection($unzerCredentialsCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @throws \SprykerEco\Zed\Unzer\Business\Exception\UnzerException
     *
     * @return void
     */
    public function setUnzerNotificationUrl(UnzerCredentialsTransfer $unzerCredentialsTransfer): void
    {
        $this->unzerFacade->setUnzerNotificationUrl($unzerCredentialsTransfer);
    }
}
