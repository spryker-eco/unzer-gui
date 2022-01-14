<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Formatter;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface UnzerGuiFormatterInterface
{
    /**
     * @param MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return array<string, string>
     */
    public function formatMerchantCollectionTransferToSuggestionsArray(MerchantCollectionTransfer $merchantCollectionTransfer): array;
}
