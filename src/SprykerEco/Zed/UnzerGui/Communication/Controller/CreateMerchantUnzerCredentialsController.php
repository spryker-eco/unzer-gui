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

class CreateMerchantUnzerCredentialsController extends AbstractMerchantUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $parentIdUnzerCredentials = (int)$request->get(static::PARAM_PARENT_ID_UNZER_CREDENTIALS);

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
            return $this->createUnzerCredentials($request, $unzerCredentialsForm);
        }

        return $this->prepareViewResponse($unzerCredentialsForm, $parentIdUnzerCredentials);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function createUnzerCredentials(Request $request, FormInterface $unzerCredentialsForm)
    {
        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();
        $unzerCredentialsTransfer->setType(UnzerConstants::UNZER_CONFIG_TYPE_MARKETPLACE_MERCHANT);

        $redirectUrl = $this->buildRedirectUrl($unzerCredentialsTransfer->getParentIdUnzerCredentials());

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->createUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage($this->concatErrorMessages($unzerCredentialsResponseTransfer));

            return $this->prepareViewResponse(
                $unzerCredentialsForm,
                $unzerCredentialsTransfer->getParentIdUnzerCredentials(),
            );
        }

        return $this->redirectResponse($redirectUrl);
    }
}
