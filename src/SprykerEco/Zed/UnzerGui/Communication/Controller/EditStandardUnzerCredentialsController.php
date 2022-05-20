<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class EditStandardUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAMETER_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        if (!$unzerCredentialsTransfer->getIdUnzerCredentials()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_UNZER_CREDENTIALS_LIST);
        }

        $form = $this->getFactory()
            ->getUnzerCredentialsEditForm(
                $dataProvider->getData($idUnzerCredentials),
                $dataProvider->getOptions($idUnzerCredentials),
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleUnzerCredentialsForm($request, $form);
        }

        return $this->prepareViewResponse($form, $idUnzerCredentials);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function handleUnzerCredentialsForm(Request $request, FormInterface $unzerCredentialsForm)
    {
        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->prepareViewResponse($unzerCredentialsForm, $unzerCredentialsTransfer->getIdUnzerCredentialsOrFail());
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS);
        $redirectUrl = $request->get(static::PARAMETER_REDIRECT_URL, static::ROUTE_UNZER_CREDENTIALS_LIST);

        return $this->redirectResponse($redirectUrl);
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
            'idUnzerCredentials' => $idUnzerCredentials,
        ]);
    }
}
