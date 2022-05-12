<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class EditMerchantUnzerCredentialsController extends AbstractMerchantUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        if (!$unzerCredentialsTransfer->getIdUnzerCredentials()) {
            $this->addErrorMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_NOT_FOUND));

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $form = $this->getFactory()
            ->getMerchantUnzerCredentialsEditForm(
                $unzerCredentialsTransfer,
                $dataProvider->getOptions($idUnzerCredentials),
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleUnzerCredentialsForm($request, $form);
        }

        return $this->prepareViewResponse($form, $unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
     */
    protected function handleUnzerCredentialsForm(Request $request, FormInterface $unzerCredentialsForm)
    {
        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR));
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->prepareViewResponse($unzerCredentialsForm, $unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail());
        }

        $this->addSuccessMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS));

        return $this->redirectResponse($this->buildRedirectUrl($unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail()));
    }
}
