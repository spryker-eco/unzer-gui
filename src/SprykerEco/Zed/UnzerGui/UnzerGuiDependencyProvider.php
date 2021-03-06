<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui;

use Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\UnzerGui\Communication\Exception\UnzerGuiException;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeBridge;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeBridge;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 */
class UnzerGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_UNZER = 'FACADE_UNZER';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_UNZER_CREDENTIALS = 'PROPEL_QUERY_UNZER_CREDENTIALS';

    /**
     * @var string
     */
    public const PLUGIN_STORE_RELATION_FORM_TYPE = 'PLUGIN_STORE_RELATION_FORM_TYPE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUnzerFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addUnzerCredentialsPropelQuery($container);
        $container = $this->addStoreRelationFormTypePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUnzerFacade(Container $container): Container
    {
        $container->set(static::FACADE_UNZER, function (Container $container) {
            return new UnzerGuiToUnzerFacadeBridge($container->getLocator()->unzer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new UnzerGuiToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }

    /**
     * @module Unzer
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUnzerCredentialsPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_UNZER_CREDENTIALS, $container->factory(function () {
            return SpyUnzerCredentialsQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreRelationFormTypePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return $this->getStoreRelationFormTypePlugin();
        });

        return $container;
    }

    /**
     * @throws \SprykerEco\Zed\UnzerGui\Communication\Exception\UnzerGuiException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        throw new UnzerGuiException(
            sprintf(
                'Missing instance of %s! You need to configure StoreRelationFormType ' .
                'in your own UnzerGuiDependencyProvider::getStoreRelationFormTypePlugin() ' .
                'to be able to manage Unzer Credentials.',
                FormTypeInterface::class,
            ),
        );
    }
}
