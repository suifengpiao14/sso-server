<?php
/*
	if (!defined("SSO_FILE"))  exit();

	if ($_SERVER["REMOTE_ADDR"] != "192.168.23.1")  exit();

	$bb_usertoken = "ab4bd853c65f671f680afe52809322d1";
	$sso_site_admin = true;*/


if (!defined("SSO_FILE"))  exit();

require_once "client/config.php";
require_once SSO_CLIENT_ROOT_PATH . "/index.php";

$sso_client = new SSO_Client;
$sso_client->Init(array("sso_impersonate", "sso_remote_id"));

if (!$sso_client->LoggedIn())  $sso_client->Login("", "You must login to use this system.");

// Send the browser cookies.
$sso_client->SaveUserInfo();

// Test permissions for the user.
if (!$sso_client->IsSiteAdmin() && !$sso_client->HasTag("sso_admin"))  $sso_client->Login("", "insufficient_permissions");

// Get the internal token for use with XSRF defenses.
$bb_usertoken = $sso_client->GetSecretToken();

$sso_site_admin = $sso_client->IsSiteAdmin();
$sso_user_id = $sso_client->GetUserID();

// Add a menu option to logout.
function AdminHook_MenuOpts()
{
    global $sso_menuopts, $sso_client;

    $sso_menuopts["SSO Server Options"]["Logout"] = BB_GetRequestURLBase() . "?action=logout&sec_t=" . BB_CreateSecurityToken("logout");

    if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "logout")
    {
        $sso_client->Logout();

        header("Location: " . BB_GetFullRequestURLBase());
        exit();
    }
}
?>
