<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\UnzerCredentialsConditionsTransfer;
use Generated\Shared\Transfer\UnzerCredentialsCriteriaTransfer;
use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Generated\Shared\Transfer\UnzerKeypairTransfer;
use SprykerEco\Shared\Unzer\UnzerConstants;
use SprykerEco\Zed\UnzerGui\Communication\Finder\MerchantFinderInterface;
use SprykerEco\Zed\UnzerGui\Communication\Form\UnzerCredentialsCreateForm;
use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;

class UnzerCredentialsFormDataProvider
{
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

        if (!$idUnzerCredentials) {
            return $unzerCredentialsTransfer;
        }

        $unzerCredentialsCriteriaTransfer = (new UnzerCredentialsCriteriaTransfer())
            ->setUnzerCredentialsConditions(
                (new UnzerCredentialsConditionsTransfer())->addId($idUnzerCredentials),
            );

        $unzerCredentialsCollectionTransfer = $this->unzerFacade->getUnzerCredentialsCollection($unzerCredentialsCriteriaTransfer);

        $unzerCredentialsTransfer = $unzerCredentialsCollectionTransfer->getUnzerCredentials()[0] ?? $unzerCredentialsTransfer;
        if ($unzerCredentialsTransfer->getType() === UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) {
            $unzerCredentialsTransfer = $this->setChildUnzerCredentials($unzerCredentialsTransfer);
        }

        return $unzerCredentialsTransfer;
    }

    /**
     * @param int|null $idUnzerCredentials
     *
     * @return array
     */
    public function getOptions(?int $idUnzerCredentials = null): array
    {
        return [
            'data_class' => UnzerCredentialsTransfer::class,
            'label' => false,
            UnzerCredentialsCreateForm::OPTION_CURRENT_ID => $idUnzerCredentials,
            UnzerCredentialsCreateForm::CREDENTIALS_TYPE_CHOICES_OPTION => $this->unzerGuiConfig->getUnzerCredentialsTypeChoices(),
            UnzerCredentialsCreateForm::MERCHANT_REFERENCE_CHOICES_OPTION => $this->merchantFinder->getMerchants(),
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
                (new UnzerCredentialsConditionsTransfer())->addParentId($unzerCredentialsTransfer->getIdUnzerCredentials()),
            );

        $unzerCredentialsCollectionTransfer = $this->unzerFacade->getUnzerCredentialsCollection($unzerChildCredentialsCriteriaTransfer);

        return $unzerCredentialsTransfer->setChildUnzerCredentials($unzerCredentialsCollectionTransfer->getUnzerCredentials()[0] ?? null);
    }
}
