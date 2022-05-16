<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
if (!empty($sub_domain)) {
    $default = 'login';
} else {
    $default = "frontend";
}
$route['default_controller'] = $default;

$route['404'] = "login/not_found";
$route['domain-not-available'] = "setup/domain_not_available";
$route['admin/mark_attendance'] = "admin/dashboard/mark_attendance";
$route['knowledgebase'] = "frontend/knowledgebase";
$route['features'] = "frontend/features";
$route['pricing'] = "frontend/pricing";
$route['requestQuote'] = "frontend/requestQuote";
$route['tos'] = "frontend/term_of_service/$1";
$route['privacy'] = "frontend/term_of_service";
$route['sign-up'] = 'frontend/choosePlan';
$route['signed_up'] = 'frontend/signed_up';
$route['manifest.json'] = 'frontend/manifest';
$route['request-for-demo'] = 'frontend/request_demo';
$route['paypal/cancel'] = 'payment/paypal/cancel';
$route['NewPlan'] = "admin/global_controller/NewPlan";
$route['NewPlanByCompany/(:any)'] = "admin/global_controller/NewPlan/$1";
$route['admin/proposals/template_edit'] = "admin/proposals/template_add";
$route['upgradePlan/(:any)'] = 'admin/settings/upgradePlan/$1';
$route['upgradePlanByCompany/(:any)/(:any)'] = 'admin/settings/upgradePlan/$1/$2';
$route['buyPlan/(:any)/(:any)'] = 'admin/settings/buyPlan/$1/$2';
$route['checkout'] = 'admin/global_controller/checkout';
$route['updatePackage'] = 'admin/global_controller/updatePackage';
$route['checkoutPayment'] = 'admin/global_controller/checkoutPayment';
$route['active_subscription'] = 'admin/global_controller/active_subscription';
$route['subscription_details'] = 'admin/global_controller/subscriptions_details';
$route['upload/image'] = 'frontend/upload_image';
$route['upload_tinymce/image'] = 'frontend/upload_image_tinymce';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['tickets'] = 'frontend/tickets';


function is_subdomain($domain = null)
{
    $isIP = @($_SERVER['SERVER_ADDR'] === trim($_SERVER['HTTP_HOST'], '[]'));
    if (!empty($isIP)) {
        return false;
    }
    $default_url = config_item('default_url');
    $base_url = guess_base_url();
    $scheme = parse_url($default_url, PHP_URL_SCHEME);
    if (empty($scheme)) {
        $default_url = 'http://' . $default_url;
    }
    $default_parts = parse_url($default_url)['host'];
    $request_parts = parse_url($base_url)['host'];
    if ($request_parts != $default_parts) {
        $subdomain = explode('.', $request_parts);
        if ($subdomain[0] == 'www') {
            return false;
        }
        return $subdomain[0];
    }
    return false;
}


function is_localhost()
{
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return true;
    }

    return false;
}
/* End of file routes.php */
/* Location: ./application/config/routes.php */