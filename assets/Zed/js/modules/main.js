/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
