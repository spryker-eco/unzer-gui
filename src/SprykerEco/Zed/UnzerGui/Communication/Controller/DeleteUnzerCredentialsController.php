<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;

class DeleteUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $deleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm();

        return $this->viewResponse([
            'idUnzerCredentials' => $idUnzerCredentials,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $redirectUrl = $this->redirectResponse(static::REDIRECT_URL_DEFAULT);

        $deleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm()->handleRequest($request);
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse($redirectUrl);
        }

        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            foreach ($unzerCredentialsResponseTransfer->getMessages() as $message) {
                $this->addErrorMessage($message->getMessageOrFail());
            }
        } else {
            $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS);
        }

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
