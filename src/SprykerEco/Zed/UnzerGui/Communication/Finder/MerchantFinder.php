<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Finder;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeInterface;

class MerchantFinder implements MerchantFinderInterface
{
    /**
     * @var \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(UnzerGuiToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return array<string, string>
     */
    public function getMerchants(MerchantCriteriaTransfer $merchantCriteriaTransfer): array
    {
        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);

        return $this->getMerchantLabelsIndexedByMerchantReference($merchantCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getMerchantLabelsIndexedByMerchantReference(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        $merchantLabelsIndexedByMerchantReference = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantReference = $merchantTransfer->getMerchantReferenceOrFail();
            $merchantLabelsIndexedByMerchantReference[$merchantReference] = $this->createMerchantLabel($merchantTransfer);
        }

        return $merchantLabelsIndexedByMerchantReference;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    protected function createMerchantLabel(MerchantTransfer $merchantTransfer): string
    {
        $merchantName = $merchantTransfer->getNameOrFail();
        $merchantReference = $merchantTransfer->getMerchantReferenceOrFail();

        return sprintf('%s (%s)', $merchantName, $merchantReference);
    }
}
