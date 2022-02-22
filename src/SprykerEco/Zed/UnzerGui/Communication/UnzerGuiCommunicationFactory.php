<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface;
use SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantReferenceFinder;
use SprykerEco\Zed\UnzerGui\Communication\Form\Constraint\UnzerCredentialsConstraint;
use SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider;
use SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\UnzerCredentialsFormDataProvider;
use SprykerEco\Zed\UnzerGui\Communication\Form\MerchantUnzerCredentialsCreateForm;
use SprykerEco\Zed\UnzerGui\Communication\Form\MerchantUnzerCredentialsEditForm;
use SprykerEco\Zed\UnzerGui\Communication\Form\UnzerCredentialsCreateForm;
use SprykerEco\Zed\UnzerGui\Communication\Form\UnzerCredentialsDeleteForm;
use SprykerEco\Zed\UnzerGui\Communication\Form\UnzerCredentialsEditForm;
use SprykerEco\Zed\UnzerGui\Communication\Formatter\UnzerGuiFormatter;
use SprykerEco\Zed\UnzerGui\Communication\Formatter\UnzerGuiFormatterInterface;
use SprykerEco\Zed\UnzerGui\Communication\Table\AnotherMerchantUnzerCredentialsTable;
use SprykerEco\Zed\UnzerGui\Communication\Table\MerchantUnzerCredentialsTable;
use SprykerEco\Zed\UnzerGui\Communication\Table\UnzerCredentialsTable;
use SprykerEco\Zed\UnzerGui\Communication\Tabs\UnzerCredentialsFormTabs;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeInterface;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;
use SprykerEco\Zed\UnzerGui\UnzerGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 */
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

    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Table\UnzerCredentialsTable
     */
    public function createUnzerCredentialsTable(): UnzerCredentialsTable
    {
        return new UnzerCredentialsTable(
            $this->getUnzerCredentialsPropelQuery(),
        );
    }

    /**
     * @return \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery
     */
    public function getUnzerCredentialsPropelQuery(): SpyUnzerCredentialsQuery
    {
        return $this->getProvidedDependency(UnzerGuiDependencyProvider::PROPEL_UNZER_CREDENTIALS_QUERY);
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Tabs\UnzerCredentialsFormTabs
     */
    public function createUnzerCredentialsFormTabs(): UnzerCredentialsFormTabs
    {
        return new UnzerCredentialsFormTabs();
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\UnzerCredentialsFormDataProvider
     */
    public function createUnzerCredentialsFormDataProvider(): UnzerCredentialsFormDataProvider
    {
        return new UnzerCredentialsFormDataProvider(
            $this->getUnzerFacade(),
            $this->getConfig(),
            $this->createMerchantFinder(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUnzerCredentialsCreateForm(UnzerCredentialsTransfer $unzerCredentialsTransfer, array $options): FormInterface
    {
        return $this->getFormFactory()->create(UnzerCredentialsCreateForm::class, $unzerCredentialsTransfer, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUnzerCredentialsEditForm(UnzerCredentialsTransfer $unzerCredentialsTransfer, array $options): FormInterface
    {
        return $this->getFormFactory()->create(UnzerCredentialsEditForm::class, $unzerCredentialsTransfer, $options);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(UnzerGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): UnzerGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(UnzerGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Formatter\UnzerGuiFormatterInterface
     */
    public function createUnzerGuiFormatter(): UnzerGuiFormatterInterface
    {
        return new UnzerGuiFormatter();
    }

    /**
     * @param array $options
     *
     * @return \SprykerEco\Zed\UnzerGui\Communication\Form\Constraint\UnzerCredentialsConstraint
     */
    public function createUnzerCredentialsConstraint(array $options = []): UnzerCredentialsConstraint
    {
        return new UnzerCredentialsConstraint(
            $this->getUnzerFacade(),
            $options,
        );
    }

    /**
     * @param int $idUnzerCredentials
     *
     * @return \SprykerEco\Zed\UnzerGui\Communication\Table\MerchantUnzerCredentialsTable
     */
    public function createMerchantUnzerCredentialsTable(int $idUnzerCredentials): MerchantUnzerCredentialsTable
    {
        return new MerchantUnzerCredentialsTable(
            $this->getUnzerCredentialsPropelQuery(),
            $idUnzerCredentials,
        );
    }

    /**
     * @param string $merchantReference
     *
     * @return \SprykerEco\Zed\UnzerGui\Communication\Table\AnotherMerchantUnzerCredentialsTable
     */
    public function createAnotherMerchantUnzerCredentialsTable(string $merchantReference): AnotherMerchantUnzerCredentialsTable
    {
        return new AnotherMerchantUnzerCredentialsTable(
            $this->getUnzerCredentialsPropelQuery(),
            $merchantReference,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUnzerCredentialsCreateForm(UnzerCredentialsTransfer $unzerCredentialsTransfer, array $options): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUnzerCredentialsCreateForm::class, $unzerCredentialsTransfer, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUnzerCredentialsEditForm(UnzerCredentialsTransfer $unzerCredentialsTransfer, array $options): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUnzerCredentialsEditForm::class, $unzerCredentialsTransfer, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteUnzerCredentialsForm(): FormInterface
    {
        return $this->getFormFactory()->create(UnzerCredentialsDeleteForm::class);
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface
     */
    public function createMerchantFinder(): MerchantFinderInterface
    {
        return new MerchantReferenceFinder($this->getMerchantFacade());
    }
}
