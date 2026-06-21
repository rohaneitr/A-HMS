<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - Bengali
* Description: Bengali language file for Ion Auth messages and errors
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'অ্যাকাউন্ট সফলভাবে তৈরি করা হয়েছে';
$lang['account_creation_unsuccessful'] 	 	 = 'অ্যাকাউন্ট তৈরি করতে ব্যর্থ হয়েছে';
$lang['account_creation_duplicate_email'] 	 = 'ইমেল ইতিমধ্যে ব্যবহৃত হয়েছে অথবা এটি অকার্যকর';
$lang['account_creation_duplicate_username'] = 'ব্যবহারকারীর নাম ইতিমধ্যে ব্যবহৃত হয়েছে অথবা এটি অকার্যকর';
$lang['account_creation_missing_default_group'] = 'ডিফল্ট গ্রুপ সেট করা নেই';
$lang['account_creation_invalid_default_group'] = 'অবৈধ ডিফল্ট গ্রুপ সেট করা হয়েছে';

// Password
$lang['password_change_successful'] 	 	 = 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে';
$lang['password_change_unsuccessful'] 	  	 = 'পাসওয়ার্ড পরিবর্তন করতে ব্যর্থ হয়েছে';
$lang['forgot_password_successful'] 	 	 = 'পাসওয়ার্ড রিসেটের ইমেল পাঠানো হয়েছে';
$lang['forgot_password_unsuccessful'] 	 	 = 'পাসওয়ার্ড রিসেট করতে ব্যর্থ হয়েছে';

// Activation
$lang['activate_successful'] 		  	     = 'অ্যাকাউন্ট সক্রিয় করা হয়েছে';
$lang['activate_unsuccessful'] 		 	     = 'অ্যাকাউন্ট সক্রিয় করতে ব্যর্থ হয়েছে';
$lang['deactivate_successful'] 		  	     = 'অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে';
$lang['deactivate_unsuccessful'] 	  	     = 'অ্যাকাউন্ট নিষ্ক্রিয় করতে ব্যর্থ হয়েছে';
$lang['activation_email_successful'] 	  	 = 'সক্রিয়করণ ইমেল পাঠানো হয়েছে';
$lang['activation_email_unsuccessful']   	 = 'সক্রিয়করণ ইমেল পাঠাতে ব্যর্থ হয়েছে';

// Login / Logout
$lang['login_successful'] 		  	         = 'সফলভাবে লগইন করা হয়েছে';
$lang['login_unsuccessful'] 		  	     = 'লগইন তথ্য সঠিক নয়';
$lang['login_unsuccessful_not_active'] 		 = 'অ্যাকাউন্টটি নিষ্ক্রিয় অবস্থায় আছে';
$lang['login_timeout']                       = 'সাময়িকভাবে লগ আউট করা হয়েছে। অনুগ্রহ করে পরে আবার চেষ্টা করুন।';
$lang['logout_successful'] 		 	         = 'সফলভাবে লগআউট করা হয়েছে';

// Account Changes
$lang['update_successful'] 		 	         = 'অ্যাকাউন্টের তথ্য সফলভাবে আপডেট করা হয়েছে';
$lang['update_unsuccessful'] 		 	     = 'অ্যাকাউন্টের তথ্য আপডেট করতে ব্যর্থ হয়েছে';
$lang['delete_successful']                   = 'ব্যবহারকারী মুছে ফেলা হয়েছে';
$lang['delete_unsuccessful']                 = 'ব্যবহারকারী মুছে ফেলতে ব্যর্থ হয়েছে';

// Groups
$lang['group_creation_successful']           = 'গ্রুপ সফলভাবে তৈরি করা হয়েছে';
$lang['group_already_exists']                 = 'গ্রুপের নামটি ইতিমধ্যে নেওয়া হয়েছে';
$lang['group_update_successful']              = 'গ্রুপের বিবরণ আপডেট করা হয়েছে';
$lang['group_delete_successful']              = 'গ্রুপ মুছে ফেলা হয়েছে';
$lang['group_delete_unsuccessful'] 	         = 'গ্রুপ মুছে ফেলতে ব্যর্থ হয়েছে';
$lang['group_delete_notallowed']             = 'অ্যাডমিনিস্ট্রেটর গ্রুপটি মুছে ফেলা সম্ভব নয়';
$lang['group_name_required'] 		         = 'গ্রুপের নাম আবশ্যক';
$lang['group_name_admin_not_alter']          = 'অ্যাডমিন গ্রুপের নাম পরিবর্তন করা সম্ভব নয়';

// Activation Email
$lang['email_activation_subject']            = 'অ্যাকাউন্ট সক্রিয়করণ';
$lang['email_activate_heading']              = '%s এর জন্য অ্যাকাউন্ট সক্রিয় করুন';
$lang['email_activate_subheading']           = 'অনুগ্রহ করে এই লিঙ্কে ক্লিক করুন %s।';
$lang['email_activate_link']                 = 'আপনার অ্যাকাউন্ট সক্রিয় করুন';

// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'ভুলে যাওয়া পাসওয়ার্ড যাচাইকরণ';
$lang['email_forgot_password_heading']       = '%s এর জন্য পাসওয়ার্ড রিসেট করুন';
$lang['email_forgot_password_subheading']    = 'অনুগ্রহ করে পাসওয়ার্ড রিসেট করতে এই লিঙ্কে ক্লিক করুন %s।';
$lang['email_forgot_password_link']          = 'আপনার পাসওয়ার্ড রিসেট করুন';

// New Password Email
$lang['email_new_password_subject']          = 'নতুন পাসওয়ার্ড';
$lang['email_new_password_heading']          = '%s এর জন্য নতুন পাসওয়ার্ড';
$lang['email_new_password_subheading']       = 'আপনার পাসওয়ার্ড রিসেট করে এটি করা হয়েছে: %s';
