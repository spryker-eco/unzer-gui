<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;

class DeleteUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            foreach ($unzerCredentialsResponseTransfer->getMessages() as $message) {
                $this->addErrorMessage($message->getMessage());
            }
        } else {
            $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS);
        }

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
