<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class EditMarketplaceUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @var string
     */
    public const URL_MARKETPLACE_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-marketplace-unzer-credentials';

    /**
     * @var string
     */
    protected const REQUEST_ID_PARENT_UNZER_CREDENTIALS = 'parent-id-unzer-credentials';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $unzerCredentialsFormDataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsFormDataProvider->getData($idUnzerCredentials);

        if (!$unzerCredentialsTransfer->getIdUnzerCredentials()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_NOT_FOUND);

            return $this->redirectResponse(static::URL_UNZER_CREDENTIALS_LIST);
        }

        $unzerCredentialsEditForm = $this->getFactory()
            ->getUnzerCredentialsEditForm(
                $unzerCredentialsFormDataProvider->getData($idUnzerCredentials),
                $unzerCredentialsFormDataProvider->getOptions($idUnzerCredentials),
            )
            ->handleRequest($request);

        if ($unzerCredentialsEditForm->isSubmitted() && $unzerCredentialsEditForm->isValid()) {
            return $this->handleUnzerCredentialsForm($request, $unzerCredentialsEditForm);
        }

        return $this->prepareViewResponse($unzerCredentialsEditForm, $idUnzerCredentials);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $parentIdUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));

        $merchantUnzerCredentialsTable = $this->getFactory()
            ->createMerchantUnzerCredentialsTable($parentIdUnzerCredentials);

        return $this->jsonResponse($merchantUnzerCredentialsTable->fetchData());
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

        $childUnzerCredentialsTransfer = $unzerCredentialsTransfer->getChildUnzerCredentialsOrFail();
        $childUnzerCredentialsTransfer->setStoreRelation($unzerCredentialsTransfer->getStoreRelationOrFail());
        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);
        $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($childUnzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->prepareViewResponse($unzerCredentialsForm, $unzerCredentialsTransfer->getIdUnzerCredentialsOrFail());
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS);
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::URL_UNZER_CREDENTIALS_LIST);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsEditForm
     * @param int $idUnzerCredentials
     *
     * @return array<string, mixed>
     */
    protected function prepareViewResponse(FormInterface $unzerCredentialsEditForm, int $idUnzerCredentials): array
    {
        return $this->viewResponse([
            'unzerCredentialsForm' => $unzerCredentialsEditForm->createView(),
            'idUnzerCredentials' => $idUnzerCredentials,
            'unzerCredentialsFormTabs' => $this->getFactory()->createUnzerCredentialsFormTabs()->createView(),
            'merchantUnzerCredentialsTable' => $this->getFactory()->createMerchantUnzerCredentialsTable($idUnzerCredentials)->render(),
            'addMerchantActionUrl' => Url::generate(CreateMerchantUnzerCredentialsController::URL_MERCHANT_UNZER_CREDENTIALS_ADD, [static::REQUEST_ID_PARENT_UNZER_CREDENTIALS => $idUnzerCredentials]),
        ]);
    }
}
