<?php

namespace SprykerEco\Zed\UnzerGui\Dependency;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;

class UnzerGuiToMerchantFacadeBridge implements UnzerGuiToMerchantFacadeInterface
{
    /**
     * @var MerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param MerchantFacadeInterface $merchantFacade
     */
    public function __construct($merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }
}
