<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SyncPaymentMethodsController extends AbstractUnzerCredentialsController
{
    /**
     * @var string
     */
    protected const MESSAGE_PAYMENT_METHODS_SYNC_SUCCESS = 'Payment methods synced successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $this->getFactory()->createUnzerCredentialsFormDataProvider()->getData($idUnzerCredentials);

        $this->getFactory()
            ->getUnzerFacade()
            ->performPaymentMethodsImport($unzerCredentialsTransfer->getUnzerKeypairOrFail());

        $this->addSuccessMessage((new MessageTransfer())->setMessage(static::MESSAGE_PAYMENT_METHODS_SYNC_SUCCESS));

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
