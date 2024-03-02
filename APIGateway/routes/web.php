<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Client\ClientController;
use App\Http\Controllers\v1\LdapGroup\LdapController;
use App\Http\Controllers\v1\WSSOBEAPI\WSSOBEAPIController;
use App\Http\Controllers\v1\Authentication\LocalUserController;
use App\Http\Controllers\v1\ControlPanel\ControlLdapController;
use App\Http\Controllers\v1\Asset\TelcoAssetSubscriptionController;
use App\Http\Controllers\v1\Saml\SamlController;
use App\Http\Controllers\v1\Authentication\AuthController;
use App\Http\Controllers\v1\LdapGroup\LdapGroupController;
use App\Http\Controllers\v1\UserGroup\UserGroupController;
use App\Http\Controllers\v1\Migration\MigrationController;
use App\Http\Controllers\v1\API\APIRequestController;
use App\Http\Controllers\v1\ReleaseNote\ReleaseNotesController;
use App\Http\Controllers\v1\LdapType\LdapTypeController;
use App\Http\Controllers\v1\ControlPanel\ControlPanelController;
use App\Http\Controllers\v1\ControlPanel\PageController;
use App\Http\Controllers\v1\ControlPanel\LDAPMappingController;
use App\Http\Controllers\v1\ControlPanel\SAMLMappingController;
use App\Http\Controllers\v1\Request\PortalController;
use App\Http\Controllers\v1\Request\OverviewController;
use App\Http\Controllers\v1\Location\LocationController;
use App\Http\Controllers\v1\Type\TypeController;
use App\Http\Controllers\v1\Faq\FaqController;
use App\Http\Controllers\v1\Model\ModelController;
use App\Http\Controllers\v1\AssetQueue\AssetQueueController;
use App\Http\Controllers\v1\Asset\AssetController;
use App\Http\Controllers\v1\Asset\NewTelcoController;
use App\Http\Controllers\v1\EspMdm\ESPMDMController;
use App\Http\Controllers\v1\Action\AssetActionController;
use App\Http\Controllers\v1\Log\ServerLogController;
use App\Http\Controllers\v1\Log\AssetLogController;
use App\Http\Controllers\v1\Log\RequestLogController;
use App\Http\Controllers\v1\MDM\AirWatchController;
use App\Http\Controllers\v1\MDM\MobileIronController;
use App\Http\Controllers\v1\MDM\MobileIronCloudController;
use App\Http\Controllers\v1\MDM\JAMFController;
use App\Http\Controllers\v1\MDM\InTunesController;
use App\Http\Controllers\v1\MDM\SamsungKnoxController;
use App\Http\Controllers\v1\MDM\MDMController;
use App\Http\Controllers\v1\Telco\TelcoController;
use App\Http\Controllers\v1\ExternalServices\ExternalServicesController;
use App\Http\Controllers\v1\EmailContentTemplate\EmailContentTemplateController;
use App\Http\Controllers\v1\Voucher\VoucherController;
use App\Http\Controllers\v1\ADVoucher\ADVoucherController;
use App\Http\Controllers\v1\EmailTemplate\EmailTemplateController;
use App\Http\Controllers\v1\EmailProjects\EmailProjectController;
use App\Http\Controllers\v1\Supplier\SupplierController;
use App\Http\Controllers\v1\Order\OrderController;
use App\Http\Controllers\v1\Shop\ShopController;
use App\Http\Controllers\v1\CustomerService\OwnTicketController;
use App\Http\Controllers\v1\CustomerService\CustomerServiceController;
use App\Http\Controllers\v1\CustomerService\TicketTypeController;
use App\Http\Controllers\v1\CustomerService\TicketController;
use App\Http\Controllers\v1\CustomerService\TicketHandlerController;
use App\Http\Controllers\v1\CustomerService\BotConfigController;
use App\Http\Controllers\v1\CustomerService\AssetBotConfigController;
use App\Http\Controllers\v1\CustomerService\BotController;
use App\Http\Controllers\v1\CustomerService\AssetBotController;
use App\Http\Controllers\v1\SamlOnboarding\SamlOnboardingController;
use App\Http\Controllers\v1\ControlPanel\DashBoardController;
use App\Http\Controllers\v1\ADSync\ADSyncController;
use App\Http\Controllers\v1\TelcoGroup\TelcoGroupController;
use App\Http\Controllers\v1\TelcoMigration\TelcoMigrationController;
use App\Http\Controllers\v1\Language\LanguageController;
use App\Http\Controllers\v1\Telco\TelcoSettingController;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

Route::get('/', function () {
    return "API Gateway for Micro Services ".app()->version();
});

Route::get('/api', function () {
    return "API Gateway for Micro Services ".app()->version();
});

Route::controller(ClientController::class)->group(function () {
    Route::post('api/v1/getAllTelcoSettings', 'getAllTelcoSettings');
});

Route::prefix('api/v1')->controller(LdapController::class)->group(function(){
    Route::post('/get-ldap-manager', 'getLdapManager');
    // didn't find where this API is being called!!!
    Route::get('/ldapGroup-userGroups/{username}', 'getLdapUserGroup');
});

Route::prefix('api/v1')->controller(WSSOBEAPIController::class)->group(function(){
    Route::get('/uloadBSCSBillingFileToSFTP', 'uloadBSCSBillingFileToSFTP');
    Route::get('/upload-asset-visibility', 'syncAssetVisibility');
    Route::get('/upload-asset-visibility-batch', 'syncAssetVisibilityWithBatch');
});

Route::prefix('api/v1')->controller(ClientController::class)->group(function() {
    // Disable Public route to Process Sync Asset Visibility
    Route::post('/getClientId', 'getClientId');
    Route::get('/getClientLogo/{domain}', 'getClientLogo');
    Route::get('/getClientLoginScreenImage/{domain}', 'getClientLoginScreenImage');
    Route::get('/getTenantConfigs/{domain}', 'getTenantConfigs');

    Route::get('/uploadLog', 'uploadLog');
    Route::get('/mdm-sync-crone', 'mdmSyncCrone');
    Route::get('/totalgroups', 'getSuperAdminUserGroups');
    Route::get('/thresholds', 'getThresholds');
    Route::post('/eisPopups', 'getEisPopups');

});

Route::prefix('api/v1')->controller(LanguageController::class)->group(function(){
    // Getting all languages
    Route::get('/get-languages', 'getLanguages');
    Route::get('/getLanguagesPerTenant/{domain}', 'getLanguagesPerTenant');    
});
    
Route::prefix('api/v1')->controller(CustomerServiceController::class)->group(function(){
    // Get Ticket and Ticket Reply ( Call from EMAIL JOB )
    Route::get('/ticket_info/{id}', 'getTicketInfo');
    Route::get('/ticket_reply_view/{id}', 'getTicketReplyDetail');

    // Update Ticket Repair Estimation
    Route::put('/public/ticket_repair_cost/{id}', 'updateTicketRepairCost');
});

Route::controller(CustomerServiceController::class)->group(function(){
    Route::put('register/{id}', 'updateTicketRepairCost');
});

Route::prefix('api/v1')->controller(TicketTypeController::class)->group(function(){
    Route::get('/ticket_type_view/{id}', 'getTicketTypeInfo');
});

Route::controller(WSSOBEAPIController::class)->group(function(){
    // Exposed SOAP service for the OBE/Accenture
    Route::get('/orderNotification', 'orderNotification');
    Route::post('/orderNotification', 'orderNotification');
});

Route::prefix('api/v1')->controller(CustomerServiceController::class)->group(function(){
    Route::get('/ticket_view_free/{id}', 'getTicketDetailView');
    Route::put("/customer-service_free/{id}", 'updateCustomerService');
    Route::post('/ticket_support_free', 'createTicketSupport');
    Route::post("/customer-service_free", 'createCustomerService');
});

Route::prefix('api/v1')->controller(AssetController::class)->group(function(){
    Route::get('/assets_free/{id}', 'show');
    Route::post('/assets_free', 'store');
    Route::put('/assets_free/{id}', 'update');
    Route::patch('/assets_free/{id}', 'update');
    Route::delete('/assets_free/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(LocalUserController::class)->group(function(){
    Route::get('/getClientUserEmail/{id}', 'getClientUserEmail');
});

// Localization middleware
Route::prefix('api/v1')->controller(LocalUserController::class)->middleware(['locale'])->group(function(){
    // Route::get('/getClientUserEmail/{id}', 'getClientUserEmail');
    Route::get('/getForgotClientUserEmail/{id}', 'getForgotClientUserEmail');
    Route::post('/localUser-resendEmail/{id}', 'sendResendMail');
    Route::post('/localUser-passwordupdate', 'setLocalUserPassword');
    Route::post('/localUser-forgotpasswordupdate', 'setLocalUserForgotPassword');
    Route::post('/localUser-sendforgotpasswordemail', 'sendForgotPasswordEmail');
});

Route::prefix('api/v1')->controller(ControlLdapController::class)->middleware(['locale'])->group(function(){
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // Below three APIs are getting call on portal login page for AD/dropdown
    Route::get('/getTenantDetails', 'getTenantAzureDetails');
    Route::get('/ldapDropDown', 'show');
});

Route::prefix('api/v1')->controller(TelcoAssetSubscriptionController::class)->middleware(['locale'])->group(function(){
    Route::post('/telco/telco-subscriptions', 'storeAssetSubscription');
    Route::post('/telco/telco-subscriptions/{id}', 'updateAssetSubscription');
});

Route::prefix('api/v1')->controller(SamlController::class)->middleware(['locale'])->group(function(){
    // SAML Routes
    Route::get('/getSAMLs', 'index');
}); 


Route::prefix('api/v1')->controller(AuthController::class)->group(function(){
    Route::post('/signup', 'signup');
    Route::post('/localUser-findOrCreate', 'LocalfindOrCreate');
    Route::post('/signin', 'signin');
    Route::get('/signout', 'signout');
    Route::get('/isLdapLoginActive', 'isLdapLoginActive');
    Route::post('/updatetooltip', 'updatetooltip');
    Route::post('/updateAcceptPolicy', 'updateAcceptPolicy');
    Route::get('/userGraphStatus', 'getUserGraphStatus');
    Route::post('/userGraphStatus', 'createUpdateGraphStatus');
    // SAML
    Route::get('/saml/signin', 'SAMLSignin');
    Route::put('/updateChartType', 'updateChartType');
    Route::put('/updateChartTableStatus', 'updateChartTableStatus');

    // Added on 15 Feb 2021 for shop API access token
    Route::post('/get_api_token', 'getAuthToken');

    // Added on 18 Feb 2022 for renewing a auth token
    Route::post('/refreshtoken', 'refreshtoken');

    // for auto-signin from superadmin to Tenant portal
    // no client_id is required to pass here....
    Route::get('/validate-auth-token', 'validate_auth_token');
});

Route::prefix('api/v1')->controller(LdapGroupController::class)->middleware(['locale'])->group(function(){
    Route::post('/ldapGroup-userGroups', 'userGroups');
});

Route::prefix('api/v1')->controller(UserGroupController::class)->middleware(['locale'])->group(function(){
    Route::post('/userGroup/getldapGroup-permission', 'getLdapGroupPermission');
});

Route::prefix('api/v1')->controller(MigrationController::class)->middleware(['locale'])->group(function(){
    // Migration (without authorization)
    Route::get('/migrations/migration-details/{code}', 'migrationDetails');
    Route::post('/migrations/retire', 'retire');
    Route::post('/migrations/register', 'register');
    Route::get('/migrations/send-reminder', 'sendReminder');
});

//////////////////////////// API Auth Middleware /////////////////////////////////
Route::prefix('api/v1')->controller(APIRequestController::class)->middleware(['locale','apiauth'])->group(function(){
    //Added on 22-02-2021 to call this API from Prestashop
    Route::post('/send-for-request-approval', 'sendFoRequestApproval');
});

/*Up to this completed step1*/

Route::prefix('api/v1')->controller(ReleaseNotesController::class)->middleware(['locale','auth'])->group(function(){
    Route::get('/releasenote', 'index');
    Route::get('/releasenotedetail', 'detail');
});

Route::prefix('api/v1')->controller(ControlLdapController::class)->middleware(['auth'])->group(function(){
    Route::get('ldapDropDown-auth', 'show');
});

Route::prefix('api/v1')->controller(AuthController::class)->middleware(['locale','auth'])->group(function(){
    Route::get('/verify-token', 'verify_token');
    ////////////////////////////////////////////////////////////////////////////
    Route::post('/logAcceptPrivacy', 'logAcceptPrivacy');
    Route::put('/setUserLanguage/{id}', 'setUserDefaultLanguage');
    Route::get('/getUserLanguage', 'getUserLanguage');
    Route::get('/getUserVIPStatus', 'getUserVIPStatus');
});

Route::prefix('api/v1')->controller(LocalUserController::class)->middleware(['locale','auth'])->group(function(){
    Route::get('/localUser-show', 'show');
    Route::get('/localUser/{id}', 'getUser');
    Route::get('/localUser-create', 'create');
    Route::post('/localUser-create', 'store');
    Route::get('/localUser-edit/{id}', 'edit');
    Route::put('/localUser-edit/{id}', 'store');
    Route::delete('/localUser-delete/{id}', 'destroy');
    Route::get('/get-shop-api-access-user', 'getShopAPIAccessUser');
});

Route::prefix('api/v1')->controller(LdapTypeController::class)->middleware(['locale','auth'])->group(function(){
    Route::get('/ldapType-show', 'show');
    Route::get('/ldapType-create', 'create');
    Route::post('/ldapType-create', 'store');
    Route::get('/ldapType-edit/{id}', 'edit');
    Route::put('/ldapType-edit/{id}', 'store');
    Route::delete('/ldapType-delete/{id}', 'destroy');
    Route::get('/ldapType-info/{groupname}', 'groupInfo');
    Route::post('/getGroupTypesId', 'groupTypes');
    Route::post('/ldap_type_group_has_type_delete/{id}', 'ldap_type_group_has_type_delete');
});

Route::prefix('api/v1')->controller(UserGroupController::class)->middleware(['locale','auth'])->group(function(){
    /*User group module*/
    Route::get('/userGroup/permission/{id}', 'permissionList');
    Route::get('/userGroup-create', 'create');
    Route::post('/userGroup-create', 'store');
    Route::get('/userGroup-edit/{id}', 'edit');
    Route::put('/userGroup-edit/{id}', 'store');
    Route::get('/userGroup-show', 'show');
    Route::get('/userGroup/user/{id}', 'userGroups');
    Route::post('/localUserHasGroup', 'localUserHasGroup');
    Route::delete('/localUserHasGroup/{id}', 'localUserHasGroup');
    Route::delete('/userGroup-delete/{id}', 'destroy');
    Route::get('/getLdapUserGroups/{id}', 'getLdapUserGroups');

    Route::post('/userGroup-reset', 'resetGroup');

    Route::get('getAccessRights/{id}', 'getAccessRights');
    Route::get('/permission-descriptions', 'getPermissionDescription');
});

Route::prefix('api/v1')->controller(LdapGroupController::class)->middleware(['locale','auth'])->group(function(){
    /*Ldap group module*/
    Route::get('/ldapGroup-show', 'show');
    Route::get('/ldapGroup-create', 'create');
    Route::post('/ldapGroup-create', 'store');
    Route::get('/ldapGroup-edit/{id}', 'edit');
    Route::put('/ldapGroup-edit/{id}', 'store');
    Route::delete('/ldapGroup-delete/{id}', 'destroy');
    Route::get('/ldapGroup-info/{groupname}', 'groupInfo');
});

Route::prefix('api/v1')->controller(LdapController::class)->middleware(['locale','auth'])->group(function(){
    Route::post('ldap-testConnection', 'testConnection');
    Route::post('ldap-login', 'auth');
    Route::post('/ldap-getManager', 'getManager');
    Route::get('/ldap-getUsers', 'getLdapUsers');
    Route::get('/ldap-getUsersEmail', 'getUsersEmail');
    Route::get('/ldap-isGroupNameExist/{groupname}', 'isGroupNameExist');
    Route::get('/ldap-accountInfo', 'accountInfo');

    //New LDAP
    Route::post('/ldap-group-sync', 'ldapGroupSync');
    Route::get('/ldap-get-users-groups', 'getUserADGroups');
});

Route::prefix('api/v1')->controller(ControlPanelController::class)->middleware(['locale','auth'])->group(function(){
    /*Control panel group*/
    Route::get('/controlPanel-show/{id}', 'show');
    Route::get('/controlPanel/{client_id}/{id}', 'type');
    Route::post('/mobileIron', 'mobile_iron');
    
    Route::post('/requestRecipient', 'request_recipient');
    Route::post('/reqRecipient', 'req_receipt');
    Route::post('/mail', 'mail');
    Route::post('/mail-external', 'mailExternal');
    Route::post('/mail-office365', 'mailOffice365');
    Route::post('/mail-test', 'mailTest');

    Route::post('/imap-settings', 'imapSettings');
    Route::get('/get-imap-settings/{id}', 'getImapSettings');
    Route::get('/get-imap-settings-cron/{id}', 'getImapSettingsCron');
    Route::post('/mailTemplate', 'mail_template');
    Route::post('/airWatch', 'air_watch');
    
    Route::post('/testConnection', 'test_connection');
    Route::post('/test-telco-connection', 'test_telco_connection');

    Route::post('/defaultMDM', 'default_mdm');
    Route::post('/send-mail', 'sendMail');
    Route::get('/controlPanel-mdm', 'mdm');

    Route::post('/jamf', 'jamf');
    Route::post('/intunes', 'intunes');
});

Route::prefix('api/v1')->controller(ControlLdapController::class)->middleware(['locale','auth'])->group(function(){
    /*==Ldap==*/
    Route::post('/ldap', 'create');
    Route::put('/ldap', 'update');
    Route::get('/ldap', 'show');
    Route::delete('/ldap', 'destroy');

    Route::post('/ldap-sync-add', 'ldap_sync_add');

    Route::post('/test-ldap', 'test');
    Route::post('/test-ldap-status', 'status');
    Route::get('/get-selected-ldapGroups', 'getSelectedLdapGroups');
    Route::get('/ldap-saml-groups', 'getLdapSAMLGroups');
});

Route::prefix('api/v1')->controller(PageController::class)->middleware(['locale','auth'])->group(function(){
    /* Page contents */
    Route::post('/page/show', 'show');
    Route::post('/page/showAll', 'showAll');
    Route::delete('/page/removePage/{name}', 'removePage');
    Route::post('/page/store', 'store');
    Route::put('/page/statusUpdate/{id}', 'updatePageStatus');
});

Route::prefix('api/v1')->controller(LDAPMappingController::class)->middleware(['locale','auth'])->group(function(){
    Route::get('/controlPanel-ldap', 'allLdaps');
    Route::post('/ldap-fields', 'index');
    Route::post('/ldap-fields-store', 'store');
});

Route::prefix('api/v1')->controller(SAMLMappingController::class)->middleware(['locale','auth'])->group(function(){
    Route::post('/saml-fields', 'index');
    Route::post('/saml-fields-store', 'store');
});

Route::prefix('api/v1')->controller(ControlPanelController::class)->middleware(['locale','auth'])->group(function(){
    Route::post('/save-freefield-button', 'saveFreeFieldButton');
    Route::get('/freefields/{client_id}', 'getFreeFields');
});

Route::prefix('api/v1')->controller(PortalController::class)->middleware(['locale','auth'])->group(function(){
    /* Requests API */
    /*==Request==*/
    Route::get('/requestPortal-create', 'create');
    Route::post('/requestPortal-create', 'store');
    Route::get('/requestPortal-edit/{id}', 'edit');
    Route::put('/requestPortal-edit/{id}', 'store');
    Route::get('/requestPortal-show', 'show');
    Route::get('/requestPortal-appointed-you', 'appointedYou');
    Route::delete('/requestPortal-delete/{id}', 'destroy');
    Route::put('/requestPortal-updateStatus/{id}', 'status');
    Route::get("/get-user-requests", "userRequests");
    Route::post('/requestPortal-export-csv', 'exportRequestsCsvFile');
    Route::get('/requestPortal/get-non-telco-request-details/{id}', 'getNonTelcoRequestDetails');
});

Route::prefix('api/v1')->controller(OverviewController::class)->middleware(['locale','auth'])->group(function(){
    /*==Overview==*/
    Route::get("/requestPortalOverview-approved", "overviewApproved");
    Route::get("/requestPortalOverview-pending", "overviewPending");
    Route::get("/requestPortalOverview-rejected", "overviewRejected");
    Route::get("/requestPortalOverview-inprogress", "overviewInprogress");
    Route::get("/requestPortalOverview-mixed", "overviewMixed");
});

Route::prefix('api/v1')->controller(LocationController::class)->middleware(['locale','auth'])->group(function(){
    // Routing for Location Micro Service
    Route::get('/locations/client/{client_id}', 'index');
    Route::post('/locations', 'store');
    Route::get('/locations/get-sorted-locations', 'getSortedLocations');

    Route::get('/locations/{id}', 'show');
    Route::put('/locations/{id}', 'update');
    Route::patch('/locations/{id}', 'update');
    Route::delete('/locations/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(TypeController::class)->middleware(['locale','auth'])->group(function(){
    // Routing for asset Type Micro Service
    Route::get('/types/graphdata', 'getgraphdata');
    Route::get('/types/client/{client_id}', 'index');
    Route::post('/types', 'store');
    Route::get('/types/{id}', 'show');
    Route::put('/types/{id}', 'update');
    Route::patch('/types/{id}', 'update');
    Route::delete('/types/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(FaqController::class)->middleware(['locale','auth'])->group(function(){
    // Routing for FAQs
    Route::post('/faqs/filter', 'index');
    Route::post('/faqs', 'store');
    Route::post('/faqs-multiple', 'storeMultiple');
    Route::get('/faqs/{id}', 'show');
    Route::put('/faqs/{id}', 'update');
    Route::patch('/faqs/{id}', 'update');
    Route::delete('/faqs/{id}', 'destroy');
    Route::post("faqs/exportData", "exportData");
    Route::post('/faqs/uploadFile', 'uploadFile');
    Route::post('/faqs/importFileData', 'importFileData');
});

Route::prefix('api/v1')->controller(ModelController::class)->middleware(['locale','auth'])->group(function(){
    // Routing for asset Model Micro Service
    Route::get('/models/client/{client_id}', 'index');
    Route::post('/models', 'store');
    Route::get('/models/{id}', 'show');
    Route::put('/models/{id}', 'update');
    Route::patch('/models/{id}', 'update');
    Route::delete('/models/{id}', 'destroy');
    Route::get('/models/bytypes/{id}', 'bytypes');
    Route::post('/models/byname/', 'byname');
    Route::post('/models/findorcreate', 'findorcreate');

    Route::get('/models/getModel/{id}', 'getModel');
    Route::get('/models/checkTelecomSubscription/{id}', 'checkTelecomSubscription');
    Route::get('/getPrestashopCategories', 'getPrestashopCategories');
});

Route::prefix('api/v1')->controller(AssetQueueController::class)->middleware(['locale','auth'])->group(function(){
    // Routing for Assets Queue Micro Service
    Route::get('/assetsqueue/client/{client_id}', 'index');

    Route::get('/assetsqueue/gets_assets_queue_by_status/{status}', 'gets_assets_queue_by_status');

    Route::post('/assetsqueue/resolve', 'resolve');

    Route::post('/assetsqueue', 'store');
    Route::get('/assetsqueue/{asset}', 'show');

    // update edited asset (updateEditedAsset.php)
    Route::get('/assetsqueue/overwrite/{asset}', 'overwrite');

    Route::get('/assetsqueue/show/{asset}', 'show');
    Route::get('/asset-queue-sync-status', 'queueSyncStatus');
});
    
Route::prefix('api/v1')->controller(AssetController::class)->middleware(['locale','auth'])->group(function(){    
    // Routing for Assets Micro Service
    Route::get('/assets/get_mdm_status', 'get_mdm_status');
    Route::post('/assets/get_csv_report', 'get_csv_report');

    Route::post('/assets/getAirwatchDataBasedOnDeviceID', 'getAirwatchDataBasedOnDeviceID');

    // Get Fields multi lang
    Route::get('assets/global-fields-multi-lang', 'getMultiLang');
    Route::put('assets/global-fields-multi-lang', 'updateMultiLang');

    Route::get('/assets/get_sum_of_voucher', 'get_sum_of_voucher');
    Route::get('/assets/get_number_of_voucher', 'get_number_of_voucher');
    Route::get('/assets/get_location_by_type', 'get_location_by_type');
    Route::get('/assets/get_mdm_by_type', 'get_mdm_by_type');
    Route::get('/assets/get_esp_by_type', 'get_esp_by_type');
    Route::get('/assets/get_models_by_type', 'get_models_by_type');
    Route::get('/assets/get_asset_source_by_type', 'get_asset_source_by_type');

    Route::post('/assets/fields', 'fields');
    Route::get('/assets/getFieldsForAssetsCsv', 'getFieldsWithSampleForAssetsCsv');

    Route::get('/assets/getglobalfields', 'getglobalfields');
    Route::post('/assets/getglobalfieldschart', 'getglobalfieldschart');

    Route::get('/assets/client/{id}', 'index');
    Route::get('/assets/getEISValues/{includeInactiveDevices}', 'obtainAllAssetEISScores');
    Route::get('/assets/getEISThresholds', 'getEISThresholds');

    Route::post('/assets/get_assets_by_owner', 'get_assets_by_owner');
    Route::post('/assets/get_assets_by_owner_count', 'get_assets_by_owner_count');
    Route::post('/assets/get_subscription', 'get_subscription');
    Route::get('/assets/get_available_assets_of_model/{id}', 'get_available_assets_of_model');

    Route::get('/assets/get_assets_by_model/{id}', 'get_assets_by_model');
    Route::get('/assets/get_assets_by_location/{id}', 'get_assets_by_location');

    Route::post('/assets/get_assets_by_model_name', 'get_assets_by_model_name');

    Route::post('/assets/get_assets_of_models', 'get_assets_of_models');
    Route::get('/assets/get_available_assets_of_model/{id}', 'get_available_assets_of_model');

    Route::post('/assets', 'store');
    Route::post('/assets/update_employee_assets', 'updateEmployeeAssets');

    Route::put('/assets/{id}', 'update');
    Route::patch('/assets/{id}', 'update');
    Route::delete('/assets/{id}', 'destroy');
    Route::delete('/assets/delete_field/{id}', 'delete_field');

    // set users fields as user's display columns
    Route::post('/assets/set_display_columns', 'set_display_columns');
    Route::get('/assets/get_display_columns/{id}', 'get_display_columns');

    Route::post('/assets/resolve', 'resolve');
    // Added on 16 Dec 2019
    Route::post('/assets/delete', 'deleteMultiple');
    // get/set black listed fields (based on MDM selection airwatch, mobileiron etc.)

    Route::post('/assets/update_asset_fields', 'update_asset_fields');

    Route::post('/assets/model_custom_fields', 'model_custom_fields');

    // Added on 23 Jan 2019 to edit multiple assets
    Route::post('/assets/update', 'updateMultiple');

    // Added on 16 March 2020 to upload assets through CSV
    Route::post('/assets/uploadCSV', 'uploadCSV');

    // Added on 1 July 2020 to upload vouchers through CSV
    Route::post('/assets/uploadVouchers', 'uploadVouchers');

    // Added on 29 Dec 2020 to upload SIMs through CSV
    Route::post('/assets/uploadSIMs', 'uploadSIMs');

    Route::get('/assets/getTelecomPhoneNumberList', 'getTelecomPhoneNumberList');

    Route::get('/assets/getBikValueReport', 'getBikValueReport');

    Route::get('/assets/getPolicyReport', 'getPolicyReport');
    Route::post('/assets/getPolicyReportForDashboard', 'getPolicyReport');

    Route::get('/assets/getVoucherReport', 'getVoucherReport');

    Route::get('/assets/getWebShopVoucherReport', 'getWebShopVoucherReport');

    Route::post('/assets/updateAssignedAssetsOwner', 'updateAssignedAssetsOwner');

    // getting assets by status
    Route::get('/assets/get_assets_by_status', 'getAssetsByStatus');

    Route::post('/assets/get_user_telco_group', 'getTelcoGroupSettings');

    Route::get('/assets/{id}', 'show');
    Route::get('mdm/getmigsyncstatus/{id}', 'getMigSyncStatus');

    // Get asset by serial number
    Route::get('/assets/get_asset_by_serial/{serial_number}', 'getAssetBySerialNumber');

    // Get asset by serial number & mobile number
    Route::get('/assets/get_asset_by_serial_and_mobile_number/{serial_number}/{mobile_number}', 'getAssetBySerialNumberAndMobileNumber');
    Route::get('/telco/active-esim-subscription-reqests', 'activeESIMSubscriptionReqests');
    Route::post('/telco/store-esim-email-get-esim-from-obe', 'storeESIMEmailGetESIMFromOBE');
    Route::post('/telco/store-esim-email-and-get-esim-from-obe-for-replace', 'storeESIMEmailANDGetESIMFromOBEForReplace');
    Route::post('telco/store-esim-email-and-send-notification', 'storeESIMEmailAndSendNotification');
    Route::get('/telco/pending-request', 'getPendingRequest');
});

Route::prefix('api/v1')->controller(NewTelcoController::class)->middleware(['locale','auth'])->group(function(){    
    // Added on 22/04/2021
    Route::get('/telco/subscriptions', 'subscriptions');
    Route::get('/telco/options', 'options');
    Route::get('/telco/passes', 'passes');
});

Route::prefix('api/v1')->controller(TelcoAssetSubscriptionController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get('/telco/telco-subscriptions', 'getAssetSubscription');
    Route::get('/telco/telco-msisdn-subscriptions', 'getMSISDNSubscription');
    Route::get('/telco/telco-asset-by-iccid', 'getAllAssetDetailsfromiccid');
    Route::get('/telco/assetDynamicData', 'assetDynamicData');
    Route::get('/telco/getTravelDataControlValues', 'getTravelDataControlValues');
    Route::get('/telco/downloadSplitBillForm/{id}', 'downloadSplitBillForm');
    Route::get('/telco/getTenantAndEmployeeInfo', 'getTenantAndEmployeeInfo');
    Route::post('/telco/storeTransferToPrepaid', 'storeTransferToPrepaid');
    Route::get('/telco/get-user-subscriptions', 'getUserSubscriptions');
    Route::get('/telco/getAssetsWithNoSubscription', 'getAssetsWithNoSubscription');
    Route::get('/telco/get-telco-request-details/{id}', 'getTelcoRequestDetails');
});

Route::prefix('api/v1')->controller(EspmdmController::class)->middleware(['locale','auth'])->group(function(){    
    // Routing for ESP MDM Mappings (its not a new micro service, just to differentiate controller actions, its is there in Asset Micro Service)
    Route::get('/test', 'get_mdm_fields');
    //////////////////// ESP-MDM Mappings //////////////////////////////
    Route::post('/espmdm/client/{client_id}', 'index');
    Route::post('/espmdm/store', 'store');
    Route::post('/espmdm/get_mdm_fields', 'get_mdm_fields');
});

Route::prefix('api/v1')->controller(AssetActionController::class)->middleware(['locale','auth'])->group(function(){    
    // Routing for Assets Actions
    ////////////////////// Routing for Asset Actions Management ///////////////////////////////////////
    Route::get('/assetaction', 'index');
    Route::get('/assetaction/{id}', 'show');
    Route::post('/assetaction', 'store');
    Route::put('/assetaction/{id}', 'update');
    Route::patch('/assetaction/{id}', 'update');
    Route::delete('/assetaction/{id}/', 'destroy');

    // run / execute / peform a specific action
    Route::post('/assetaction/perform/{action}', 'perform_action');
});

Route::prefix('api/v1')->controller(ServerLogController::class)->middleware(['locale','auth'])->group(function(){    
    /*Log service*/
    Route::get('/serverlogs', 'show');
    Route::post('/log-create', 'create');
    Route::delete('/server-log-delete/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(AssetLogController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get('/assetlogs', 'show');
    Route::delete('/asset-log-delete/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(RequestLogController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get('/requestlogs', 'show');
    Route::delete('/request-log-delete/{id}', 'destroy');
});
    
Route::prefix('api/v1')->controller(AirWatchController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/airwatch/queue/refresh', 'refresh');
    Route::post('/airwatch/get_groups', 'getGroups');
    Route::post('/airwatch/get_groups_devices', 'getGroupDevices');
    Route::post('/airwatch/migration/statusUpdate/{id}', 'mgStatusUpdate');
});    

Route::prefix('api/v1')->controller(MobileIronController::class)->middleware(['locale','auth'])->group(function(){    
    // MobileIron Micro Service
    Route::post('/mobileiron/queue/refresh', 'refresh');
    Route::post('/mobileiron/get_labels', 'getLabels');
    Route::post('/mobileiron/get_label_devices', 'getLabelDevices');
    Route::post('/mobileiron/migration/statusUpdate/{id}', 'mgStatusUpdate');
});        

Route::prefix('api/v1')->controller(MobileIronCloudController::class)->middleware(['locale','auth'])->group(function(){    
    // MobileIron Cloud Micro Service
    Route::post('/mobileironcloud/queue/refresh', 'refresh');
    Route::post('/mobileironcloud/get_labels', 'getLabels');
    Route::post('/mobileironcloud/get_label_devices', 'getLabelDevices');
});   

Route::prefix('api/v1')->controller(JAMFController::class)->middleware(['locale','auth'])->group(function(){    
    // JAMF Micro Service
    Route::post('/jamf/queue/refresh', 'refresh');
}); 

Route::prefix('api/v1')->controller(InTunesController::class)->middleware(['locale','auth'])->group(function(){    
    // InTunes Micro Service
    Route::post('/intunes/queue/refresh', 'refresh');
    Route::post('/intunes/get_groups', 'getGroups');
    Route::post('/intunes/get_groups_devices', 'getGroupDevices');
    Route::post('/intunes/migration/statusUpdate/{id}', 'mgStatusUpdate');
}); 

Route::prefix('api/v1')->controller(SamsungKnoxController::class)->middleware(['locale','auth'])->group(function(){    
    // Samsung Knox Micro Service
    Route::post('/samsungknox/queue/refresh', 'refresh');
    Route::post('/samsungknox/get_groups', 'getGroups');
    Route::post('/samsungknox/get_organizations', 'getOrganizations');
    Route::post('/samsungknox/get_groups_devices', 'getGroupDevices');
}); 

Route::prefix('api/v1')->controller(MDMController::class)->middleware(['locale','auth'])->group(function(){    
    /* MDM Management */
    Route::get('/mdm', 'index');
    Route::get('/mdm/{id}', 'show');
    Route::post('/mdm', 'store');
    Route::put('/mdm/{id}', 'update');
    Route::delete('/mdm/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(TelcoController::class)->middleware(['locale','auth'])->group(function(){    
    /* Telco Management */
    Route::get('/telcos', 'index');
    Route::post('/telcos', 'store');
    Route::get("/telcos/{id}", 'show');
    Route::put('/telcos/{id}', 'update');
    Route::delete('/telcos/{id}', 'destroy');
    Route::get('/get-sorted-telcos', 'getSortedTelcos');
    Route::get('/telcos-by-tenant', 'getTelcobyTenant');
    Route::post('tnt_order/{id}', 'storeTnt');
    Route::get('tnt_order_details/{id}', 'showTnt');
    Route::get('telco-operators', 'operators');
});

Route::prefix('api/v1')->controller(TelcoSettingController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get('/get-telco-settings', 'show');
    Route::get('/get-telco-sim-type', 'getSimType');
});

Route::prefix('api/v1')->controller(ExternalServicesController::class)->middleware(['locale','auth'])->group(function(){    
    /* External ticket service Management */
    Route::get('/external-services/getAll','index');
    Route::get('/external-services/{id}/get','show');
    Route::get('/external-services/{id}/toggle','toggle');

    Route::post('/external-services/post','store');
    Route::put('/external-services/{id}/put','update');
    Route::delete('/external-services/{id}/delete','destroy');

    Route::get('/external-services/ticketFields', 'getTicketFields');
    Route::get('/external-services/{service}/{type}/mappings', 'getMappings');
    Route::post('/external-services/mappings', 'storeMappings');
    Route::get('/external-services/{service}/{additionalData}/externalFields', 'getExternalFields');
    Route::post('/external-services/{service}/incidentFields', 'getIncidentFields');

});

Route::prefix('api/v1')->controller(EmailContentTemplateController::class)->middleware(['locale','auth'])->group(function(){    
    // Email Template Content Management
    Route::get('/email-content-templates', 'index');
    Route::post('/email-content-template-by-type-lang', 'getByTypeAndLang');
    Route::get("/email-content-templates/{id}", 'show');
    Route::post('/email-content-templates', 'store');
    Route::post('/email-content-templates-unique', 'storeUnique');
    Route::put('/email-content-templates-by-temp-lang', 'updateByTemplateAndLang');
    Route::delete('/email-content-templates/{id}', 'destroy');
    Route::delete('/email-content-templates-by-type/{type}', 'destroyAllByType');
    Route::post("email-content-templates/reArrangeRecords", "reArrangeRecords");
    Route::post("email-content-templates/exportData", "exportData");
    Route::post('/email-content-templates/uploadFile', 'uploadFile');
    Route::post('/email-content-templates/importFileData', 'importFileData');
    Route::put('/email-content-templates/statusUpdate/{id}', 'updateEmailContentTemplateStatus');
});

Route::prefix('api/v1')->controller(VoucherController::class)->middleware(['locale','auth'])->group(function(){    
    // Automatic Voucher
    Route::get('/vouchers', 'index');
    Route::get("/vouchers/{id}", 'show');
    Route::post('/vouchers', 'store');
    Route::put('/vouchers/{id}', 'update');
    Route::delete('/vouchers/{id}', 'destroy');

    // AVC Allocation on LOGIN
    Route::post('/allocation', 'allocation');

    // Bulk Upload
    Route::post('/bulk-upload', 'bulkUpload');
});

Route::prefix('api/v1')->controller(ADVoucherController::class)->middleware(['locale','auth'])->group(function(){    
    // AD Automatic Voucher
    Route::get('/ad-vouchers', 'index');
    Route::get("/ad-vouchers/{id}", 'show');
    Route::post('/ad-vouchers', 'store');
    Route::put('/ad-vouchers/{id}', 'update');
    Route::put('/ad-vouchers/status/{id}', 'updateVoucherStatus');


    Route::delete('/ad-vouchers/{id}', 'destroy');
    Route::get('/get-voucher-models', 'getVoucherModels');

    // AVC Allocation on LOGIN
    Route::post('/adv-allocation', 'allocation');
});

Route::prefix('api/v1')->controller(EmailTemplateController::class)->middleware(['locale','auth'])->group(function(){    
    /* Email Templates Management */
    Route::get('/email-templates', 'index');
    Route::get("/email-templates/{id}", 'show');
    Route::post('/email-templates', 'store');
    Route::put('/email-templates/{id}', 'update');
    Route::delete('/email-templates/{id}', 'destroy');
    Route::post('/email-templates/updateEmailTemplateStatus', 'updateEmailTemplateStatus');
    Route::get("/get_client_email_templates/{client_id}", 'get_client_templates');

    Route::get("/get_client_email_templates_details/{id}/{client_id}", 'get_client_email_template_details');
    Route::get("/get-client-repair-payment-template/{client_id}/{lang}", 'getClientRepairTemplate');
    Route::post("/get_client_email_templates_details_by_alias/{client_id}", 'get_client_email_template_details_by_alias');

    // get email templates by actions/types, added on 08/Sep/2021
    Route::post('/email-templates-by-types', 'get_templates_by_types');
    Route::post('/unique-emailtemplates-by-alias','getUniqueEmailtemplatesByAlias');
    Route::post('/email-templates-filter-by-alias-type','getEmailTemplatesFilterByAliasAndType');
    Route::get('/email-templates-by-types-lang-alias', 'emailTemplatesByTypesLangalias');
    Route::post("email-templates/exportData", "exportData");
    Route::post('/email-templates/uploadFile', 'uploadFile');
    Route::post('/email-templates/importFileData', 'importFileData');
});

Route::prefix('api/v1')->controller(EmailProjectController::class)->middleware(['locale','auth'])->group(function(){    
    // email projects
    Route::post('/email-project', 'storeEmailProject');
    Route::post('/email-project/resend', 'resendEmails');
    Route::get('/email-projects', 'getEmailProjects');
    Route::get('/email-projects/{id}', 'show');
    Route::delete('/email-projects/{id}', 'destroy');
});

Route::prefix('api/v1')->controller(SupplierController::class)->middleware(['locale','auth'])->group(function(){    
    //////////////////// Supplier //////////////////////////////////////
    Route::get('/suppliers/client/{client_id}', 'index');
    Route::post('/suppliers', 'store');
    Route::get('/suppliers/get-sorted-suppliers', 'getSortedSuppliers');

    Route::get('/suppliers/{id}', 'show');
    Route::put('/suppliers/{id}', 'update');
    Route::patch('/suppliers/{id}', 'update');
    Route::delete('/suppliers/{id}', 'destroy');

    Route::get('/suppliers/get-model-suppliers/{id}', 'getModelSuppliers');
});

Route::prefix('api/v1')->controller(MigrationController::class)->middleware(['locale','auth'])->group(function(){    
    //////////////////// Migration //////////////////////////////////////
    Route::get('/migrations', 'index');
    Route::post('/migrations', 'store');
    Route::get('/migrations/get-sorted-migrations', 'getSortedMigrations');

    Route::get('/migrations/{id}', 'show');
    Route::put('/migrations/{id}', 'update');
    Route::patch('/migrations/{id}', 'update');
    Route::delete('/migrations/{id}', 'destroy');

    Route::get('/migrations/get-migration-details/{id}', 'getMigrationDetails');

    Route::post('/migrations/play-pause', 'playPause');

    Route::get('/migration/get-email-template', 'getEmailTemplate');

    Route::post('/migration/store-email-templates', 'storeEmailTemplate');

    Route::post('migrations/copy-migration/{id}', 'copyMigration');

    Route::post('/migration/invite-device', 'inviteDevice');

    Route::post('migration/send-test-email', 'sendTestEmail');

    Route::get('/migration/migration-details-front', 'migrationDetailsFront');

    Route::post('migration/send-manual-reminder', 'sendManualReminder');
});

Route::prefix('api/v1')->controller(OrderController::class)->middleware(['locale','auth'])->group(function(){    
    //////////////////// Orders (Procurement) //////////////////////////////////////
    Route::get('/orders', 'index');
    Route::post('/orders', 'store');
    Route::get('/orders/{id}', 'show');
    Route::put('/orders/{id}', 'update');

    Route::put('/orders-update/{id}', 'updateField');

    Route::patch('/orders/{id}', 'update');
    Route::delete('/orders/{id}', 'destroy');

    Route::post('/orders/bypo', 'bypo');
    Route::post('/orders/send', 'sendOrders');
});


Route::prefix('api/v1')->controller(ClientController::class)->middleware(['locale'])->group(function(){    
  
    Route::get('/getAllSubscriptionOptionCombo/{id}', 'getAllSubscriptionOptionCombo');
    Route::get('/getAllSubscriptionPassCombo/{id}', 'getAllSubscriptionPassCombo');
});

Route::prefix('api/v1')->controller(ClientController::class)->middleware(['locale','auth'])->group(function(){    
    // Getting all clients list
    Route::get('/get-all-clients', 'getAllClients');
});

Route::prefix('api/v1')->controller(ShopController::class)->middleware(['locale','auth'])->group(function(){    
    //////////////////////// Prestahop API ///////////////////////////////
    Route::post('/shop/access', 'accessShop');
});

Route::prefix('api/v1')->controller(OwnTicketController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/ownTickets', 'getTickets');
    Route::get('/ownTicket/{id}', 'getTicketDetail');
    Route::post('/ownTicket', 'createTicket');
    Route::put('/ownTicket/{id}', 'updateTicket');
    Route::delete('/ownTicket/{id}', 'deleteTicket');
});

Route::prefix('api/v1')->controller(CustomerServiceController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/ticket_read', 'makeTicketRead');
    Route::post('/tickets_new', 'getTicketsNew');
    Route::post("/tickets_new_employee", 'getTicketsNewEmployee');
    Route::post('/ticket_support', 'createTicketSupport');
    Route::get('/ticket_view/{id}', 'getTicketDetailView');

    // Ticket Repair Estimation
    Route::post('ticket_repairt_cost/{id}', 'getTicketRepairCost');

    // Update Ticket Repair Estimation
    Route::put('ticket_repair_cost/{id}', 'updateTicketRepairCost');

    Route::put("/customer-service/{id}", 'updateCustomerService');
    Route::post("/customer-service", 'createCustomerService');
    // this is called both in admin and employee check old code
    // Route::post('/ownTicket', 'createTicket');
    // Route::post('/ticket', 'TicketController@createTicket');
    // both points to one common

    // Ticket merge
    Route::post('/list_for_ticket_merge', 'listingForTicketMerge');
    Route::post('/merge_ticket', 'requestTicketMerge');
    Route::put("/unmerge_ticket/{id}", 'requestTicketUnmerge');

    //update-tickets on login
    Route::post('/tickets/update_employee_tickets', 'updateTicketsNewEmployee');

    Route::get('/getRepairReport', 'getRepairReport');

    Route::get('/exportCustomerTickets', 'exportCustomerTickets');
});

Route::prefix('api/v1')->controller(TicketTypeController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/ticketType/updateTicketTypeStatus', 'updateTicketTypeStatus');
    Route::post('/ticketTypesbyUserGroup', 'getTicketTypesbyUserGroup');
    Route::post('/ticketTypes', 'getTicketTypes');
    Route::get('/ticketType/{id}', 'getTicketTypeDetail');
    Route::post('/ticketType', 'createTicketType');
    Route::put('/ticketType/{id}', 'updateTicketType');

    Route::delete('/ticketType/{id}', 'deleteTicketType');
    Route::post("ticketType/reArrangeRecords", "reArrangeRecords");
    Route::post('/ticketTypeProcess', 'createTicketTypeProcess');
    Route::put('/ticketTypeProcess/{id}', 'updateTicketTypeProcess');
    Route::delete('/ticketTypeProcess/{id}', 'deleteTicketTypeProcess');
    Route::post("ticketTypeProcess/reArrangeRecords", "reArrangeRecordsProcess");
});

Route::prefix('api/v1')->controller(TicketController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/tickets', 'getTickets');
    Route::get('/ticket/{id}', 'getTicketDetail');
    Route::post('/ticket', 'createTicket');
    Route::put('/ticket/{id}', 'updateTicket');
    Route::delete('/ticket/{id}', 'deleteTicket');
});

Route::prefix('api/v1')->controller(OwnTicketController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/ownTicketTypes', 'getTicketTypes');
    Route::get('/ownTicketType/{id}', 'getTicketTypeDetail');
    Route::post('/ownTicketType', 'createTicketType');
    Route::put('/ownTicketType/{id}', 'updateTicketType');
    Route::delete('/ownTicketType/{id}', 'deleteTicketType');
    Route::post('/ownTicketHandlers', 'getTicketHandlers');
    Route::get('/ownTicketHandler/{id}', 'getTicketHandlerDetail');
    Route::post('/ownTicketHandler', 'createTicketHandler');
    Route::put('/ownTicketHandler/{id}', 'updateTicketHandler');
    Route::delete('/ownTicketHandler/{id}', 'deleteTicketHandler');
});

Route::prefix('api/v1')->controller(TicketHandlerController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/ticketHandlers', 'getTicketHandlers');
    Route::get('/ticketHandler/{id}', 'getTicketHandlerDetail');
    Route::post('/ticketHandler', 'createTicketHandler');
    Route::put('/ticketHandler/{id}', 'updateTicketHandler');
    Route::delete('/ticketHandler/{id}', 'deleteTicketHandler');
});

Route::prefix('api/v1')->controller(ShopController::class)->middleware(['locale','auth'])->group(function(){    
    Route::POST('/shop/authenticate-conection', 'authenticateConnection');
    Route::POST('/shop/permission-groups', 'permissionGroups');
    Route::POST('/shop/create-cart-rules', 'createCartRule');

    Route::get('/shop/{id}', 'show');
    Route::post('/shop', 'store');
    Route::put('/shop/{id}', 'update');
    Route::patch('/shop/{id}', 'update');
    Route::delete('/shop/{id}', 'destroy');

    Route::get('/get-all-prestashop-groups', 'getAllPrestashopGroups');

    Route::post('/storeOtherStore', 'storeOtherStore');
    Route::post('/storeDefaultStore', 'storeDefaultStore');
    Route::get('/showStoreInfo', 'showStoreInfo');
    Route::delete('/destroyOtherStore', 'destroyOtherStore');
    Route::delete('/destroyDefaultStore', 'destroyDefaultStore');

    //Address related APIs
    Route::get('/getTenantDefaultShopAddress', 'getTenantDefaultShopAddress');
    Route::get('/getPrestashopCountries', 'getPrestashopCountries');
    Route::get('/getPrestashopStates', 'getPrestashopStates');

    Route::post('/storeTenantDefaultShopAddress', 'storeTenantDefaultShopAddress');
    Route::delete('/destroyAddress/{id}', 'destroyAddress');
});

Route::prefix('api/v1')->controller(BotConfigController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/bot/create-bot', 'addBotConfig');
    Route::delete('/bot/delete-bot/{id}', 'deleteBotConfig');
    Route::get('/bot/get-all-bots', 'getAllBotConfig');
    Route::post('bot/updatestatus', 'updatestatus');
    Route::get('bot/bot-config/{id}', 'show');
    Route::post('/bot/bot-config/state', 'updateState');
    Route::put('/bot/bot-config/{id}', 'update');
});

Route::prefix('api/v1')->controller(AssetBotConfigController::class)->middleware(['locale','auth'])->group(function(){    
    Route::post('/bot/create-asset-bot', 'addBotConfig');
    Route::delete('/bot/delete-asset-bot/{id}', 'deleteBotConfig');
    Route::get('/bot/get-all-asset-bots', 'getAllBotConfig');
    Route::post('bot/updateAssetBotStatus', 'updatestatus');
    Route::get('bot/asset-bot-config/{id}', 'show');
    Route::post('/bot/bot-asset-config/state', 'updateState');
    Route::put('/bot/asset-bot-config/{id}', 'update');
});

Route::prefix('api/v1')->controller(BotController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get("/bots", 'index');
    Route::get("/bot/{id}", 'show');
    Route::post("/bot", 'store');
    Route::put("/bot/{id}", 'update');
    Route::delete("/bot/{id}", 'destroy');
});

Route::prefix('api/v1')->controller(AssetBotController::class)->middleware(['locale','auth'])->group(function(){    
    Route::delete("/assetBot/{id}", 'destroy');
});

Route::prefix('api/v1')->controller(SamlController::class)->middleware(['locale','auth'])->group(function(){    
    /* SAML Routes */
    Route::get('/samls', 'index');
    Route::post('/samls', 'store');
    Route::get("/samls/{id}", 'show');
    Route::put('/samls/{id}', 'update');
    Route::delete('/samls/{id}', 'destroy');

    Route::get('/get-sorted-samls', 'getSortedSamls');
    Route::post('/saml-group', 'createSamlGroup');
    Route::post('/saml-group-delete', 'deleteSamlGroup');
});

Route::prefix('api/v1')->controller(SamlOnboardingController::class)->middleware(['locale','auth'])->group(function(){    
    // /* SAML Routes */
    Route::get('/saml-onboarding', 'index');

    Route::get('/saml-onboarding/onboarding-details-front', 'onboardingDetailsFront');
    Route::get("/saml-onboarding/{id}", 'show');

    Route::delete('/saml-onboarding/{id}', 'destroy');

    Route::post('/saml-onboarding/play-pause', 'playPause');
    Route::post('/saml-onboarding', 'store');
    Route::post('/saml-onboarding/{id}', 'update');
    Route::post('/saml-onboarding/copy-onboarding/{id}', 'copyOnboarding');
    Route::get('/saml-userlogindata', 'userLoginData');
    Route::get('/saml-userprojectdetail', 'userProjectDetails');
    Route::post('/saml-onboarding2/store-email-templates', 'storeEmailTemplate');
    Route::get('/saml-onboarding2/get-email-template', 'getEmailTemplate');
    Route::post('/saml-onboarding2/send-test-email', 'sendTestEmail');
    Route::post('/saml-onboarding2/invite-device', 'inviteDevice');
    Route::post('/saml-onboarding2/send-manual-reminder', 'sendManualReminder');
    Route::get('/saml-onboarding2/send-reminder', 'sendReminder');
});

Route::prefix('api/v1')->controller(DashBoardController::class)->middleware(['locale','auth'])->group(function(){    
    // Admin Dashboard
    Route::post('/get_admin_dashboard_data', 'index');
});

Route::prefix('api/v1')->controller(ADSyncController::class)->middleware(['locale','auth'])->group(function(){    
    // AD Sync
    Route::post('/adsync/refresh', 'refresh');
});

Route::prefix('api/v1')->controller(TelcoGroupController::class)->middleware(['locale','auth'])->group(function(){    
    Route::get('/telco-group', 'index');
    Route::post('/telco-group', 'store');
    Route::get('/telco-group/{id}', 'show');
    Route::put('/telco-group/{id}', 'update');
    Route::delete('/telco-group/{id}', 'destroy');
    Route::post('/telco-group-by-ldap-group', 'getTelcoGroupsByLDAPGroup');
});

Route::prefix('api/v1')->controller(WSSOBEAPIController::class)->middleware(['locale','auth'])->group(function(){    
    // Added on 17 May 2021 (during pandemic :(   ) to upload telco assets in WSS OBE  DB
    Route::post('/upload-telco-file', 'uploadTelcoFile');
    Route::get('/searchTelcoCustomer/{mobile_number}', 'searchCustomerByMobileNumber');
    Route::get('/searchTelcoInstalledOffers/{mobile_number}', 'searchInstalledOfferByMobileNumber');
    Route::post('/WSOrderEntry', 'WSOrderEntry');
});

Route::prefix('api/v1')->controller(TelcoMigrationController::class)->middleware(['locale','auth'])->group(function(){    
    //////////////////// Telco Migration //////////////////////////////////////
    Route::get('/telco-migrations', 'index');
    Route::get('/telco-migrations/devices', 'devices');
    Route::get('/telco-migrations/compatibility', 'checkCompatibility');
    Route::post('/telco-migrations/changeMigrationStatus/{id}', 'changeMigrationStatus');
    Route::post('/telco-migrations', 'store');

    Route::get('/telco-migrations/{id}', 'show');
    Route::post('/telco-migrations/play-pause', 'playPause');
    Route::post('/telco-migrations/{id}', 'update');
    Route::delete('/telco-migrations/{id}', 'destroy');

    Route::get('/telco-migration/get-email-template', 'getEmailTemplate');
    Route::post('/telco-migration/store-email-templates', 'storeEmailTemplate');
    Route::post('telco-migrations/copy-migration/{id}', 'copyMigration');
    Route::post('/telco-migration/invite-device', 'inviteDevice');
    Route::post('telco-migration/send-test-email', 'sendTestEmail');
    Route::get('/telco-migration/migration-details-front', 'migrationDetailsFront');
    Route::post('telco-migration/send-manual-reminder', 'sendManualReminder');
});