<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Finder;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;

interface MerchantFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return array<string, string>
     */
    public function getMerchants(MerchantCriteriaTransfer $merchantCriteriaTransfer): array;
}
