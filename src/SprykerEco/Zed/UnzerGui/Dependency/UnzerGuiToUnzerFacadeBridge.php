<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Dependency;

use Generated\Shared\Transfer\MerchantUnzerParticipantCollectionTransfer;
use Generated\Shared\Transfer\MerchantUnzerParticipantCriteriaTransfer;

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
     * @param \Generated\Shared\Transfer\MerchantUnzerParticipantCriteriaTransfer $merchantUnzerParticipantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUnzerParticipantCollectionTransfer
     */
    public function getMerchantUnzerParticipantCollection(
        MerchantUnzerParticipantCriteriaTransfer $merchantUnzerParticipantCriteriaTransfer
    ): MerchantUnzerParticipantCollectionTransfer {
        return $this->unzerFacade->getMerchantUnzerParticipantCollection($merchantUnzerParticipantCriteriaTransfer);
    }
}
