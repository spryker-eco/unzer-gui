<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\UnzerCredentialsFormDataProvider;
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
  * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
  */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $dataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $dataProvider->getData($idUnzerCredentials);

        if (!$unzerCredentialsTransfer->getIdUnzerCredentials()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_NOT_FOUND);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $form = $this->getForm($dataProvider, $idUnzerCredentials)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->updateUnzerCredentials($request, $form);
        }

        return $this->prepareViewResponse($form, $idUnzerCredentials);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function updateUnzerCredentials(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);
        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR);

            return $this->prepareViewResponse($unzerCredentialsForm, $unzerCredentialsTransfer->getIdUnzerCredentials());
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $idUnzerCredentials
     *
     * @return array
     */
    protected function prepareViewResponse(FormInterface $form, int $idUnzerCredentials): array
    {
        return $this->viewResponse([
            'unzerCredentialsForm' => $form->createView(),
            'idUnzerCredentials' => $idUnzerCredentials,
        ]);
    }

    /**
     * @param \SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\UnzerCredentialsFormDataProvider $unzerCredentialsFormDataProvider
     * @param int $idUnzerCredentials
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm(UnzerCredentialsFormDataProvider $unzerCredentialsFormDataProvider, int $idUnzerCredentials): FormInterface
    {
        return $this->getFactory()
            ->getUnzerCredentialsEditForm(
                $unzerCredentialsFormDataProvider->getData($idUnzerCredentials),
                $unzerCredentialsFormDataProvider->getOptions($idUnzerCredentials),
            );
    }
}
