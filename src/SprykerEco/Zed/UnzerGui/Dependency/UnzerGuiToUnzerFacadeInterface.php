<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Dependency;

use Generated\Shared\Transfer\MerchantUnzerParticipantTransfer;

interface UnzerGuiToUnzerFacadeInterface
{
    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantUnzerParticipantTransfer
     */
    public function getUnzerMerchantByMerchantReference(string $merchantReference): MerchantUnzerParticipantTransfer;
}
