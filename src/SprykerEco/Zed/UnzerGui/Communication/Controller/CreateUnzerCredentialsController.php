<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string,mixed>
     */
    protected function handleUnzerCredentialsForm(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, UnzerGuiConfig::URL_UNZER_CREDENTIALS_LIST);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->createUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_ERROR)->getMessage());
            $this->addExternalApiErrorMessages($unzerCredentialsResponseTransfer);

            return $this->viewResponse([
                'unzerCredentialsForm' => $unzerCredentialsForm->createView(),
            ]);
        }

        $this->addSuccessMessage((new MessageTransfer())->setMessage(static::MESSAGE_UNZER_CREDENTIALS_CREATE_SUCCESS)->getMessage());

        return $this->redirectResponse($redirectUrl);
    }
}
