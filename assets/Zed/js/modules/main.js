/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

'use strict';

var idUnzerMerchantCredentialsSubform = 'unzer-credentials_childUnzerCredentials';
var $unzerMerchantCredentialsSubformWrapper = $('#' + idUnzerMerchantCredentialsSubform).parent('.form-group');
var $unzerMerchantCredentialsSubformFields = $unzerMerchantCredentialsSubformWrapper.find('input');
var $credentialsTypeDropdown = $('#unzer-credentials_type');
var marketplaceOptionValue = '2';

function toggleMerchantCredentialsSubform() {
    var credentialsTypeDropdownValue = $credentialsTypeDropdown.val();

    if (credentialsTypeDropdownValue === marketplaceOptionValue) {
        $unzerMerchantCredentialsSubformWrapper.show();
        $unzerMerchantCredentialsSubformFields.prop('disabled', false);
    } else {
        $unzerMerchantCredentialsSubformWrapper.hide();
        $unzerMerchantCredentialsSubformFields.prop('disabled', true);
    }
}

$(document).ready(function () {
    toggleMerchantCredentialsSubform();

    $credentialsTypeDropdown.on('change', toggleMerchantCredentialsSubform);
});
