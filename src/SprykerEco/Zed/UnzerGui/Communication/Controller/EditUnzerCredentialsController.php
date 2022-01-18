<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerEco\Shared\Unzer\UnzerConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class EditUnzerCredentialsController extends AbstractController
{
    /**
     * @var string
     */
    public const URL_PARAM_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

    /**
     * @var string
     */
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    public const REDIRECT_URL_DEFAULT = '/unzer-gui/list-unzer-credentials';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS = 'Unzer Credentials have been updated.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR = 'Unzer Credentials have not been updated.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_NOT_FOUND = 'Unzer Credentials not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idUnzerCredentials = $this->castId($request->get(static::URL_PARAM_ID_UNZER_CREDENTIALS));
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
            return $this->updateUnzerCredentials($request, $form);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idUnzerCredentials' => $idUnzerCredentials,
            'merchantFormTabs' => $this->getFactory()->createUnzerCredentialsFormTabs()->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function updateUnzerCredentials(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($unzerCredentialsTransfer);
        if ($unzerCredentialsTransfer->getType() === UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) {
            $childUnzerCredentialsTransfer = $unzerCredentialsTransfer->getChildUnzerCredentialsOrFail();
            $this->getFactory()->getUnzerFacade()->updateUnzerCredentials($childUnzerCredentialsTransfer);
        }

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_ERROR);

            return $this->viewResponse([
                'form' => $unzerCredentialsForm->createView(),
                'idUnzerCredentials' => $unzerCredentialsTransfer->getIdUnzerCredentials(),
                'merchantFormTabs' => $this->getFactory()->createUnzerCredentialsFormTabs()->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_UPDATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
