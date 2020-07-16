<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::post('login', 'Api\Auth\ApiAuthController@loginApi');


Route::get("okok", function () {
    return "working";
});

Route::post('login', 'Api\Auth\ApiAuthController@loginApi');
Route::post('register', 'Api\Auth\ApiAuthController@registerApi');

// ********************************************************************************
// ***************************   Admin Panel APIs    ****************************
// ********************************************************************************

Route::group([
    'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Gig Categories APIs
    Route::get('gig/categories', 'Gig\ApiGigCategoryController@index');
    Route::post('gig/categories', 'Gig\ApiGigCategoryController@store');
    Route::get('gig/categories/{id}', 'Gig\ApiGigCategoryController@show');
    Route::post('gig/categories/{id}/update', 'Gig\ApiGigCategoryController@update');
    Route::delete('gig/categories/{id}/delete', 'Gig\ApiGigCategoryController@destroy');

    // Gig ServiceType APIs
    Route::get('gig/servicetypes', 'Gig\ApiGigServiceTypeController@index');
    Route::post('gig/servicetypes', 'Gig\ApiGigServiceTypeController@store');
    Route::get('gig/servicetypes/{id}', 'Gig\ApiGigServiceTypeController@show');
    Route::post('gig/servicetypes/{id}/update', 'Gig\ApiGigServiceTypeController@update');
    Route::delete('gig/servicetypes/{id}/delete', 'Gig\ApiGigServiceTypeController@destroy');
});

// ********************************************************************************
// ***************************  App User APIs  ************************************
// ********************************************************************************
Route::group([
    'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Verify User Account
    Route::get('verify-account', 'Auth\ApiAuthController@startTwoFactorAuth'); // this will make user authy account and send a verification code at user phone number
    Route::post('verify-account', 'Auth\ApiAuthController@verifyAccount');
    Route::get('resend-verification-code', 'Auth\ApiAuthController@resendVerificationCode')->middleware('throttle:1,1');

    // Social Login Handle
    Route::get('user/social-login', 'Auth\ApiAuthController@socialLoginHandle');

    // Check Login Status To AutoLogout
    Route::get('check-login-status', 'Auth\ApiAuthController@checkLoginStatus');

    // Change User Password
    Route::post('user/change-password', 'Auth\ApiAuthController@changePassword');

    // Update User Role
    Route::post('user-role/update', 'User\ApiUserController@changeUserRole');

    // Logout API
    Route::post('logout', 'Auth\ApiAuthController@logoutApi');

    // User Profile APIs
    Route::get('user/profile', 'User\ApiUserController@getUserProfileData');
    Route::post('user/profile/update', 'User\ApiUserController@updateUserProfile');
    Route::post('user/profile-img/update', 'User\ApiUserController@updateUserProfileImage');
    Route::delete('user', 'User\ApiUserController@deleteUserAccount');

    // Search User API
    Route::post('search/user', 'User\ApiUserController@searchPerson');

    // User Security Questions APIs
    Route::get('user/security-questions', 'User\ApiUserController@getUserSecurityQuestions');
    // Route::post('user/security-questions/add-single', 'User\ApiUserController@addUserSecurityQuestion'); // add single question
    Route::post('user/security-questions/add', 'User\ApiUserController@addUserSecurityQuestions'); // add all questions (3)
    Route::post('user/security-questions/update', 'User\ApiUserController@updateUserSecurityQuestions'); // update all question (3)
    Route::post('user/security-questions/{id}/update', 'User\ApiUserController@updateUserSecurityQuestion');
    Route::delete('user/security-questions/{id}/delete', 'User\ApiUserController@deleteUserSecurityQuestion');

    // Personal Gigs APIs
    Route::get('user/gigs/{days_for_stats?}', 'Gig\ApiUserGigController@index'); // sort gigs listing page
    Route::get('user/gigs/status/{status?}', 'Gig\ApiUserGigController@getSpecificStatusGigs'); // get gigs of specific status // default is publish
    Route::post('user/gigs', 'Gig\ApiUserGigController@store');
    Route::get('user/gigs/{id}/data', 'Gig\ApiUserGigController@show'); // this is used in edit gig and other
    Route::get('user/gigs/{id}/preview', 'Gig\ApiUserGigController@getPreviewGigData'); // this is used in preview gig
    Route::put('user/gigs/{id}/{status}', 'Gig\ApiUserGigController@changeGigStatus'); // change gig status
    Route::post('user/gigs/{id}/update', 'Gig\ApiUserGigController@update');
    Route::delete('user/gigs/{id}/delete', 'Gig\ApiUserGigController@destroy');
    Route::delete('user/gigs/{status}', 'Gig\ApiUserGigController@deleteGigsWithStatus'); // delete all gigs wih specific status
    Route::get('username/{id}', 'Gig\ApiUserGigController@getGigDetails'); // this is to show gig publicaly

    // Gig Categories
    Route::get('gig-categories/parent', 'Gig\ApiGigCategoryController@getParentCategories');
    Route::get('gig-categories/{parentId}/childs', 'Gig\ApiGigCategoryController@getChildCategories');

    // Gig ServiceType
    Route::get('gig-categories/{childID}/service-types', 'Gig\ApiGigCategoryController@getCategoryServiceTypes');

    // Personal JobRequests APIs
    Route::get('user/jobrequests', 'JobRequest\ApiJobRequestController@index');
    Route::get('user/available-jobrequests', 'JobRequest\ApiJobRequestController@getAvailableJobRequests');
    Route::post('user/jobrequests', 'JobRequest\ApiJobRequestController@store');
    Route::get('user/jobrequests/{id}/datatoedit', 'JobRequest\ApiJobRequestController@getJobRequestDataToEdit');
    Route::get('user/jobrequests/{id}/data', 'JobRequest\ApiJobRequestController@show');
    Route::put('user/jobrequests/{id}/{status}', 'JobRequest\ApiJobRequestController@changeJobRequestStatus'); // change gig status
    Route::post('user/jobrequests/{id}/update', 'JobRequest\ApiJobRequestController@update');
    Route::delete('user/jobrequests/{id}/delete', 'JobRequest\ApiJobRequestController@destroy');
    Route::delete('user/jobrequests/{status}', 'JobRequest\ApiJobRequestController@deleteJobRequestsWithStatus'); // delete all gigs wih specific status
    Route::get('username/{id}', 'JobRequest\ApiJobRequestController@getGigDetails'); // this is to show JobRequest publicaly

    // Seller Routes For JobRequests APIs
    Route::get('jobrequests', 'JobRequest\ApiJobRequestController@getJobRequests');
    Route::get('user/gig-categories', 'JobRequest\ApiJobRequestController@userGigCategories'); // to get seller gig categories to apply filter

    // Membership Plans APIs
    Route::get('membership-plans', 'Shared\ApiMembershipPlanController@index');
    Route::post('membership-plans', 'Shared\ApiMembershipPlanController@store');
    Route::get('membership-plans/{id}', 'Shared\ApiMembershipPlanController@show');
    Route::post('membership-plans/{id}/update', 'Shared\ApiMembershipPlanController@update');
    Route::delete('membership-plans/{id}/delete', 'Shared\ApiMembershipPlanController@destroy');

    Route::get('membership-plans/usertype/{usertype}', 'Shared\ApiMembershipPlanController@getSpecificUserPlans');
    Route::post('update-membership-plan', 'Shared\ApiMembershipPlanController@updateMembershipPlan');

    // Save Job Requests APIs
    Route::get('job/save', 'JobRequest\ApiJobRequestController@getAllSaveJobRequests');
    Route::post('job/save', 'JobRequest\ApiJobRequestController@saveJobRequest');
    Route::delete('job/save/{id}/delete', 'JobRequest\ApiJobRequestController@deleteJobRequest');

    // Place Job Offers APIs
    Route::get('user/joboffers', 'JobOffer\ApiJobOfferController@index');
    Route::post('user/joboffers', 'JobOffer\ApiJobOfferController@store');

    // Messages Routes
    Route::get('user/{reciver_id}/messages', 'Chat\MessagesController@index');
    Route::post('user/{reciver_id}/messages', 'Chat\MessagesController@store');

    // Chat Users Routes
    Route::get('user/chat-users', 'Chat\ChatUsersController@index');
    Route::get('user/chat-users/{chatUser}/userdata', 'Chat\ChatUsersController@getChatUserData');
    Route::post('user/chat-users', 'Chat\ChatUsersController@store');
    Route::post('user/chat-users/{chatUser}/update', 'Chat\ChatUsersController@update');
    Route::delete('user/chat-users/{chatUser}', 'Chat\ChatUsersController@destroy');

    // Report & Spam Messages Routes
    Route::post('user/report-spam-message', 'Chat\RepostMessagesController@store');
    Route::delete('user/report-spam-message/{messageID}', 'Chat\RepostMessagesController@destroy');

    // Quick Responses Routes
    Route::get('user/quick-responses', 'Chat\QuickResponsesController@index');
    Route::post('user/quick-responses', 'Chat\QuickResponsesController@store');
    Route::post('user/quick-responses/{quickResponse}/update', 'Chat\QuickResponsesController@update');
    Route::delete('user/quick-responses/delete-all', 'Chat\QuickResponsesController@destroyAllQuickResponses');
    Route::delete('user/quick-responses/{quickResponse}', 'Chat\QuickResponsesController@destroy');

    // User Notifications
    Route::post('user/notifications', 'User\ApiUserNotificationController@index');
    Route::post('user/notifications/read-it', 'User\ApiUserNotificationController@markNotificationAsRead');
});

// ********************************************************************************
// ***************************  Publich APIs  *************************************
// ********************************************************************************
Route::group([
    // 'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // User Profile
    Route::get('user/{userID}/profile', 'User\ApiUserController@getSpecificUserProfileData');

    // User Gigs
    Route::get('user/{userID}/gigs/status/{status?}', 'Gig\ApiUserGigController@getOtherUserSpecificStatusGigs'); // get gigs of specific status // default is publish
});

Route::group([
    //'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Logout API
    Route::post('logout', 'Auth\ApiAuthController@logoutApi');

    // User Profile APIs
    Route::get('user/profile', 'User\ApiUserController@getUserProfileData');
    Route::post('user/profile/update', 'User\ApiUserController@updateUserProfile');
    Route::delete('user/{id}', 'User\ApiUserController@deleteUserAccount');

    // Search User API
    Route::post('search/user', 'User\ApiUserController@searchPerson');

    // User Security Questions APIs
    Route::get('user/security-questions', 'User\ApiUserController@getUserSecurityQuestions');
    Route::post('user/security-questions/add', 'User\ApiUserController@addUserSecurityQuestion');
    Route::post('user/security-questions/{id}/update', 'User\ApiUserController@updateUserSecurityQuestion');
    Route::delete('user/security-questions/{id}/delete', 'User\ApiUserController@deleteUserSecurityQuestion');



    Route::get('home/categories/{type}', 'Support\ApiCategoryController@getHomeCategories');
    Route::get('all/categories/{type}', 'Support\ApiCategoryController@getAllCategories');

    Route::get('category/{category_id}/{type}', 'Support\ApiCategoryController@getCategoryById');
    Route::get('article/{article_id}/{type}', 'Support\ApiArticleController@getArticleById');
});


