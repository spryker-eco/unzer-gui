<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\UnzerCredentialsConditionsTransfer;
use Generated\Shared\Transfer\UnzerCredentialsCriteriaTransfer;
use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Generated\Shared\Transfer\UnzerKeypairTransfer;
use SprykerEco\Shared\Unzer\UnzerConstants;
use SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;

class UnzerCredentialsFormDataProvider
{
    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Form\AbstractUnzerCredentialsForm::CREDENTIALS_TYPE_CHOICES_OPTION
     */
    protected const CREDENTIALS_TYPE_CHOICES_OPTION = 'type_choices';

    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Form\AbstractUnzerCredentialsForm::MERCHANT_REFERENCE_CHOICES_OPTION
     */
    protected const MERCHANT_REFERENCE_CHOICES_OPTION = 'merchant_reference_choices';

    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Form\AbstractUnzerCredentialsForm::OPTION_CURRENT_ID
     */
    protected const OPTION_CURRENT_ID = 'current_id';

    /**
     * @var \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface
     */
    protected $unzerFacade;

    /**
     * @var \SprykerEco\Zed\UnzerGui\UnzerGuiConfig
     */
    protected $unzerGuiConfig;

    /**
     * @var \SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface
     */
    protected $merchantFinder;

    /**
     * @param \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface $unzerFacade
     * @param \SprykerEco\Zed\UnzerGui\UnzerGuiConfig $unzerGuiConfig
     * @param \SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface $merchantFinder
     */
    public function __construct(
        UnzerGuiToUnzerFacadeInterface $unzerFacade,
        UnzerGuiConfig $unzerGuiConfig,
        MerchantFinderInterface $merchantFinder
    ) {
        $this->unzerFacade = $unzerFacade;
        $this->unzerGuiConfig = $unzerGuiConfig;
        $this->merchantFinder = $merchantFinder;
    }

    /**
     * @param int|null $idUnzerCredentials
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsTransfer
     */
    public function getData(?int $idUnzerCredentials = null): UnzerCredentialsTransfer
    {
        $unzerCredentialsTransfer = (new UnzerCredentialsTransfer())->setUnzerKeypair(new UnzerKeypairTransfer());

        if ($idUnzerCredentials) {
            $unzerCredentialsCriteriaTransfer = (new UnzerCredentialsCriteriaTransfer())
                ->setUnzerCredentialsConditions(
                    (new UnzerCredentialsConditionsTransfer())->addId($idUnzerCredentials),
                );
            $unzerCredentialsCollectionTransfer = $this->unzerFacade->getUnzerCredentialsCollection($unzerCredentialsCriteriaTransfer);
            $unzerCredentialsTransfer = $unzerCredentialsCollectionTransfer->getUnzerCredentials()->getIterator()->current() ?? $unzerCredentialsTransfer;
        }

        if ($unzerCredentialsTransfer->getType() === UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) {
            $unzerCredentialsTransfer = $this->setChildUnzerCredentials($unzerCredentialsTransfer);
        }

        if (!$unzerCredentialsTransfer->getStoreRelation()) {
            $unzerCredentialsTransfer->setStoreRelation(new StoreRelationTransfer());
        }

        return $unzerCredentialsTransfer;
    }

    /**
     * @param int|null $idUnzerCredentials
     *
     * @return array<string, mixed>
     */
    public function getOptions(?int $idUnzerCredentials = null): array
    {
        return [
            'data_class' => UnzerCredentialsTransfer::class,
            'label' => false,
            static::OPTION_CURRENT_ID => $idUnzerCredentials,
            static::CREDENTIALS_TYPE_CHOICES_OPTION => $this->unzerGuiConfig->getUnzerCredentialsTypeChoices(),
            static::MERCHANT_REFERENCE_CHOICES_OPTION => $this->merchantFinder->getMerchants(new MerchantCriteriaTransfer()),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @return \Generated\Shared\Transfer\UnzerCredentialsTransfer
     */
    protected function setChildUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsTransfer
    {
        $unzerChildCredentialsCriteriaTransfer = (new UnzerCredentialsCriteriaTransfer())
            ->setUnzerCredentialsConditions(
                (new UnzerCredentialsConditionsTransfer())->addParentId($unzerCredentialsTransfer->getIdUnzerCredentialsOrFail()),
            );

        $unzerCredentialsCollectionTransfer = $this->unzerFacade->getUnzerCredentialsCollection($unzerChildCredentialsCriteriaTransfer);

        return $unzerCredentialsTransfer->setChildUnzerCredentials(
            $unzerCredentialsCollectionTransfer->getUnzerCredentials()->getIterator()->current() ?? null,
        );
    }
}
