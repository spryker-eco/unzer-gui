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
    public const REDIRECT_URL_DEFAULT = '/unzer-gui/list-unzer-credentials';

    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    public const PARAM_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

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
    protected const MESSAGE_CREDENTIALS_CREATE_SUCCESS = 'Unzer Credentials created successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS = 'Unzer Credentials deleted successfully.';

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsResponseTransfer $unzerCredentialsResponseTransfer
     *
     * @return string
     */
    protected function concatErrorMessages(UnzerCredentialsResponseTransfer $unzerCredentialsResponseTransfer): string
    {
        $errorString = 'Next errors occur: ';
        foreach ($unzerCredentialsResponseTransfer->getMessages() as $messageTransfer) {
            $errorString .= $messageTransfer->getMessage() . ' ';
        }

        return $errorString;
    }
}
