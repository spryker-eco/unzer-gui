<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class DeleteUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAMETER_ID_UNZER_CREDENTIALS));
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
            $this->addErrorMessage(static::MESSAGE_CSRF_TOKEN_INVALID_ERROR);

            return $this->redirectResponse(static::ROUTE_UNZER_CREDENTIALS_LIST);
        }

        $idUnzerCredentials = $this->castId($request->get(static::PARAMETER_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->redirectResponse(static::ROUTE_UNZER_CREDENTIALS_LIST);
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS);

        return $this->redirectResponse(static::ROUTE_UNZER_CREDENTIALS_LIST);
    }
}
