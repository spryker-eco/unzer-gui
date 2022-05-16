<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use SprykerEco\Shared\Unzer\UnzerConstants;
use SprykerEco\Zed\UnzerGui\Communication\Form\MerchantUnzerCredentialsCreateForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class CreateMerchantUnzerCredentialsController extends AbstractMerchantUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $parentIdUnzerCredentials = (int)$request->get(static::PARAMETER_PARENT_ID_UNZER_CREDENTIALS);

        $unzerCredentialsFormDataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();
        $unzerCredentialsForm = $this->getFactory()
            ->getMerchantUnzerCredentialsCreateForm(
                $unzerCredentialsFormDataProvider->getData(),
                array_merge(
                    [MerchantUnzerCredentialsCreateForm::FIELD_PARENT_ID_UNZER_CREDENTIALS => $parentIdUnzerCredentials],
                    $unzerCredentialsFormDataProvider->getOptions(),
                ),
            )
            ->handleRequest($request);

        if ($unzerCredentialsForm->isSubmitted() && $unzerCredentialsForm->isValid()) {
            return $this->handleUnzerCredentialsForm($request, $unzerCredentialsForm);
        }

        return $this->prepareViewResponse($unzerCredentialsForm, $parentIdUnzerCredentials);
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
        $unzerCredentialsTransfer->setType(UnzerConstants::UNZER_CONFIG_TYPE_MARKETPLACE_MERCHANT);

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->createUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->prepareViewResponse(
                $unzerCredentialsForm,
                $unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail(),
            );
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_SUCCESS);
        $redirectUrl = $this->buildRedirectUrl($unzerCredentialsTransfer->getParentIdUnzerCredentialsOrFail());

        return $this->redirectResponse($redirectUrl);
    }
}
