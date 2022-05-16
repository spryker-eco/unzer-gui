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
class CreateUnzerCredentialsController extends AbstractUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $unzerCredentialsFormDataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();
        $unzerCredentialsForm = $this->getFactory()
            ->getUnzerCredentialsCreateForm(
                $unzerCredentialsFormDataProvider->getData(),
                $unzerCredentialsFormDataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($unzerCredentialsForm->isSubmitted() && $unzerCredentialsForm->isValid()) {
            return $this->handleUnzerCredentialsForm($request, $unzerCredentialsForm);
        }

        return $this->viewResponse([
            'unzerCredentialsForm' => $unzerCredentialsForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function handleUnzerCredentialsForm(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::PARAMETER_REDIRECT_URL, static::URL_UNZER_CREDENTIALS_LIST);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->createUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_ERROR);
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->viewResponse([
                'unzerCredentialsForm' => $unzerCredentialsForm->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
