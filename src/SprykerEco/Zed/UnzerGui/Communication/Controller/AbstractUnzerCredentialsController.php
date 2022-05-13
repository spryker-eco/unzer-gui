<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\UnzerCredentialsResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
abstract class AbstractUnzerCredentialsController extends AbstractController
{
    /**
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_LIST = '/unzer-gui/list-unzer-credentials';

    /**
     * @var string
     */
    protected const PARAM_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const MESSAGE_CSRF_TOKEN_INVALID_ERROR = 'CSRF token is not valid.';

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
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_CREATE_SUCCESS = 'Unzer Credentials created successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_CREATE_ERROR = 'Unzer Credentials have not been created successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS = 'Unzer Credentials deleted successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_DELETE_ERROR = 'Unzer Credentials have not been deleted successfully.';

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsResponseTransfer $unzerCredentialsResponseTransfer
     *
     * @return void
     */
    protected function addExternalApiErrorMessages(UnzerCredentialsResponseTransfer $unzerCredentialsResponseTransfer): void
    {
        foreach ($unzerCredentialsResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getMessageOrFail(), $messageTransfer->getParameters());
        }
    }
}
