<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;
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
    protected const REQUEST_ID_PARENT_UNZER_CREDENTIALS = 'parent-id-unzer-credentials';

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
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $parentIdUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));

        $table = $this->getFactory()
            ->createMerchantUnzerCredentialsTable($parentIdUnzerCredentials);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
     */
    protected function handleUnzerCredentialsForm(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);
        $childUnzerCredentialsTransfer = $unzerCredentialsTransfer->getChildUnzerCredentialsOrFail();
        $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($childUnzerCredentialsTransfer);

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
     * @return array<string, mixed>
     */
    protected function prepareViewResponse(FormInterface $form, int $idUnzerCredentials): array
    {
        return $this->viewResponse([
            'unzerCredentialsForm' => $form->createView(),
            'idUnzerCredentials' => $idUnzerCredentials,
            'unzerCredentialsFormTabs' => $this->getFactory()->createUnzerCredentialsFormTabs()->createView(),
            'merchantUnzerCredentialsTable' => $this->getFactory()->createMerchantUnzerCredentialsTable($idUnzerCredentials)->render(),
            'addMerchantActionUrl' => Url::generate(UnzerGuiConfig::URL_MERCHANT_UNZER_CREDENTIALS_ADD, [static::REQUEST_ID_PARENT_UNZER_CREDENTIALS => $idUnzerCredentials]),
        ]);
    }
}
