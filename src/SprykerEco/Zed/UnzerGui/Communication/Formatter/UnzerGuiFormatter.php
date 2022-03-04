<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Formatter;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

class UnzerGuiFormatter implements UnzerGuiFormatterInterface
{
    /**
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCE = 'merchantReference';

    /**
     * @var string
     */
    protected const KEY_TEXT = 'text';

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return array<string, string>
     */
    public function formatMerchantCollectionTransferToSuggestionsArray(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        $formattedSuggestMerchantList = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $formattedSuggestMerchantList[] = [
                static::KEY_MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                static::KEY_TEXT => sprintf(
                    '%s (Reference: %s)',
                    $merchantTransfer->getName(),
                    $merchantTransfer->getMerchantReference(),
                ),
            ];
        }

        return $formattedSuggestMerchantList;
    }
}
