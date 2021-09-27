<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;
use SprykerEco\Zed\UnzerGui\UnzerGuiDependencyProvider;

class UnzerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider
     */
    public function createMerchantUnzerFormDataProvider(): MerchantUnzerFormDataProvider
    {
        return new MerchantUnzerFormDataProvider($this->getUnzerFacade());
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface
     */
    public function getUnzerFacade(): UnzerGuiToUnzerFacadeInterface
    {
        return $this->getProvidedDependency(UnzerGuiDependencyProvider::FACADE_UNZER);
    }
}
