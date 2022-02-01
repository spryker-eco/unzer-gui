<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UnzerGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\ListUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_LIST = '/unzer-gui/list-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditStandardUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_STANDARD_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-standard-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditMarketplaceUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_MARKETPLACE_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-marketplace-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\DeleteUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_DELETE = '/unzer-gui/delete-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\SyncPaymentMethodsController::indexAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_SYNC_PAYMENT_METHODS = '/unzer-gui/sync-payment-methods';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\DeleteMerchantUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_UNZER_CREDENTIALS_DELETE = '/unzer-gui/delete-merchant-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditMerchantUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-merchant-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\CreateMerchantUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_UNZER_CREDENTIALS_ADD = '/unzer-gui/create-merchant-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditStandardUnzerCredentialsController::activateAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_SYNC = '/unzer-gui/edit-unzer-credentials/sync';

    /**
     * @var int
     */
    protected const COMPANY_SUGGESTION_LIMIT = 20;

    /**
     * @var array
     */
    protected const UNZER_CREDENTIALS_TYPES = [
        1 => 'Standard',
        2 => 'Marketplace (Main channel)',
    ];

    /**
     * @api
     *
     * @return array<string>
     */
    public function getUnzerCredentialsTypeChoices(): array
    {
        return static::UNZER_CREDENTIALS_TYPES;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantSuggestionLimit(): int
    {
        return static::COMPANY_SUGGESTION_LIMIT;
    }
}
