<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Formatter;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface UnzerGuiFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return array<int, array<string, string|null>>
     */
    public function formatMerchantCollectionTransferToSuggestionsArray(MerchantCollectionTransfer $merchantCollectionTransfer): array;
}
