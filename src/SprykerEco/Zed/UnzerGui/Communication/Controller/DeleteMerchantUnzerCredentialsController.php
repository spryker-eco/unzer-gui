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
class DeleteMerchantUnzerCredentialsController extends AbstractMerchantUnzerCredentialsController
{
    /**
     * @var string
     */
    public const URL_MERCHANT_UNZER_CREDENTIALS_DELETE = '/unzer-gui/delete-merchant-unzer-credentials';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAMETER_ID_UNZER_CREDENTIALS));
        $parentIdUnzerCredentials = $this->castId($request->get(static::PARAMETER_PARENT_ID_UNZER_CREDENTIALS));

        $unzerCredentialsDeleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm();

        return $this->viewResponse([
            'idUnzerCredentials' => $idUnzerCredentials,
            'parentIdUnzerCredentials' => $parentIdUnzerCredentials,
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
        $idUnzerCredentials = $this->castId($request->get(static::PARAMETER_ID_UNZER_CREDENTIALS));

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $this->getFactory()->createUnzerCredentialsFormDataProvider()->getData($idUnzerCredentials);
        $parentIdUnzerCredentials = $unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail();
        $redirectUrl = $this->buildRedirectUrl($parentIdUnzerCredentials);

        $unzerCredentialsDeleteForm = $this->getFactory()->getUnzerCredentialsDeleteForm()->handleRequest($request);
        if (!$unzerCredentialsDeleteForm->isSubmitted() || !$unzerCredentialsDeleteForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_CSRF_TOKEN_INVALID_ERROR);

            return $this->redirectResponse($redirectUrl);
        }

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
