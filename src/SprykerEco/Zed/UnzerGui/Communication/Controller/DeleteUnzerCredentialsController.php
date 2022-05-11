<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $unzerCredentialsDeleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm();

        return $this->viewResponse([
            'idUnzerCredentials' => $idUnzerCredentials,
            'deleteForm' => $unzerCredentialsDeleteForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $unzerCredentialsDeleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm()->handleRequest($request);
        if (!$unzerCredentialsDeleteForm->isSubmitted() || !$unzerCredentialsDeleteForm->isValid()) {
            $this->addErrorMessage((new MessageTransfer())->setMessage(static::MESSAGE_CSRF_TOKEN_INVALID_ERROR));

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_ERROR));
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $this->addSuccessMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS));

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
