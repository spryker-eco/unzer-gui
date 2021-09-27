<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;

class MerchantUnzerFormDataProvider
{
    /**
     * @var \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface
     */
    protected $unzerFacade;

    /**
     * @param \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface $unzerFacade
     */
    public function __construct(UnzerGuiToUnzerFacadeInterface $unzerFacade)
    {
        $this->unzerFacade = $unzerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string|null
     */
    public function getData(MerchantTransfer $merchantTransfer): ?string
    {
        $unzerMerchant = $this->unzerFacade->getUnzerMerchantByMerchantReference($merchantTransfer->getMerchantReference());

        return $unzerMerchant->getParticipantId();
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return [
           'label' => 'Unzer Participant ID',
        ];
    }
}
