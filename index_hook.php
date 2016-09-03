<?php
// An example index hook for the SSO server.
// (C) 2012 CubicleSoft

if (!defined("SSO_FILE"))  exit();

$versions = array(
	"legal" => 4,
	"marketing_ads" => 6
);
$latestversion = max($versions);
if ($sso_userrow->version == 0)
{
	// Awesome.
	if (SSO_FrontendFieldValue("submit") !== false)
	{
		// Process form submission.
//			$sso_user_info["first_name"] = "Colonel";
//			$sso_user_info["last_name"] = "Sanders";

		// Save changes.
		SSO_SetUserVersion($latestversion);

		header("Location: " . $sso_target_url);
		exit();
	}

	echo "New account!  You rock!";
}
else if ($sso_userrow->version < $versions["legal"])
{
	// Legal sent this down the other day.
	echo "New Terms of Service and Privacy Policy - BORING!";
}
else if ($sso_userrow->version < $versions["marketing_ads"])
{
	// Because we want our users to give us their money.
	echo "Latest promotion/advertisement/feature!  Slobbery hugs and kisses!";
}
else
{
	// Automate some fields here.
	$changed = false;

	// ...

	// Save changes.
	if ($changed)
	{
		SSO_SetUserVersion($latestversion);

		header("Location: " . $sso_target_url);
		exit();
	}

	if (count($sso_missingfields))
	{
		// Have the user fill in the remaining missing fields.
		if (SSO_FrontendFieldValue("submit") !== false)
		{
			// Process form submission.

			// Save changes.
			SSO_SetUserVersion($latestversion);

			header("Location: " . $sso_target_url);
			exit();
		}

		// Display form here.
		echo "Need some additional information to continue.  Sell your soul (or privacy) here.";
	}
	else
	{
		SSO_ValidateUser();

		SSO_DisplayError("Error:  Unable to validate the new session.  Most likely cause:  Internal error.");
	}
}

?>
