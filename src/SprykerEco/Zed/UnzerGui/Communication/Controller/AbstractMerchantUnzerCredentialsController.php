<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;

abstract class AbstractMerchantUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @var string
     */
    protected const PARAM_PARENT_ID_UNZER_CREDENTIALS = 'parent-id-unzer-credentials';

    /**
     * @var string
     */
    protected const FRAGMENT_TAB_EXTERNAL_MERCHANT_CREDENTIALS = 'tab-content-externalMerchantCredentials';

    /**
     * @param int $parentIdUnzerCredentials
     *
     * @return string
     */
    protected function buildRedirectUrl(int $parentIdUnzerCredentials): string
    {
        return Url::generate(
            EditMarketplaceUnzerCredentialsController::URL_MARKETPLACE_UNZER_CREDENTIALS_EDIT,
            [
                static::PARAM_ID_UNZER_CREDENTIALS => $parentIdUnzerCredentials,
            ],
            [
                Url::FRAGMENT => static::FRAGMENT_TAB_EXTERNAL_MERCHANT_CREDENTIALS,
            ],
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $idUnzerCredentials
     *
     * @return array<string, mixed>
     */
    protected function prepareViewResponse(FormInterface $form, int $idUnzerCredentials): array
    {
        return $this->viewResponse([
            'unzerCredentialsForm' => $form->createView(),
            'redirectUrl' => $this->buildRedirectUrl($idUnzerCredentials),
        ]);
    }
}
