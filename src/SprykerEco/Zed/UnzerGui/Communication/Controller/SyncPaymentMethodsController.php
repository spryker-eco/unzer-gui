<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;

class SyncPaymentMethodsController extends AbstractUnzerCredentialsController
{
    /**
     * @var string
     */
    protected const MESSAGE_PAYMENT_METHODS_SYNC_SUCCESS = 'Payment methods synced successfully.';

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $this->getFactory()->createUnzerCredentialsFormDataProvider()->getData($idUnzerCredentials);

        $this->getFactory()
            ->getUnzerFacade()
            ->performPaymentMethodsImport($unzerCredentialsTransfer->getUnzerKeypair());

        $this->addSuccessMessage(static::MESSAGE_PAYMENT_METHODS_SYNC_SUCCESS);

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
