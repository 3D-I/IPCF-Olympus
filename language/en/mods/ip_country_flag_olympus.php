<?php

/** 
*
* IP Country Flag Olympus [English]
*
* @package language
* @version $Id: ip_country_flag_olympus.php,v 1.009 2009/01/17 12:43:00 3Di Exp $
* @copyright (c) 2007, 2008, 2009 3Di (Marco T.) 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// IP Country Flag Olympus
$lang = array_merge($lang, array(
	'TABLE_DOES_NOT_EXISTS'						=> 'The CF_ISO_TABLE seems to be missing, please check your edits into constants.php',
	'INSTALLATION_COMPLETE'						=> 'Installation completed',
	'POPULATING_THE_IP_TO_COUNTRY_DATABASE'		=> 'Please wait while we are populating your database',
	'FAILED_TO_UPDATE_THE_IP_TO_COUNTRY_TABLE'	=> 'The population has failed for whatsoever reason',
	'ERROR_OPEN_DATAFILE'						=> 'Sorry, I could not read the file %s',
	'UPDATE_CONFIRM'							=> 'This panel will populate your IP to Country DB with data extracted from the files detailed below.',
	'BACKUP_WARNING'							=> 'Remember to make backups of your database before proceeding!',
	'PROCEED'									=> 'Proceed',
	'AUTO_REFRESH'								=> 'Process all files sequentially:',
	'PENDING'									=> 'Pending',
	'PROCESSED'									=> 'Processed',
	'DELETE_THIS_FILE'							=> 'The DB has been populated with success. Delete this folder ASAP.',
	'CLICK_RETURN_INDEX'						=> 'Click %sHERE%s to return to the forum\'s Index',
	'ERROR_WHILE_READING_IP_TO_COUNTRY_TABLE'  	=> 'The IP to Country table seems to be missing. Make sure you have completely installed the MOD.',
	'GUEST_ONLINE'                              => 'Guests (based on visits over the past session lenght - here is 24 hours) ',
	'GUEST_ONLINE_NONE'                         => 'None',
	'PAST_GUESTS'                               => 'Guests who have visited this board during the session\'s lenght:<br />',
	'LOGIN_ADMIN'                               => 'Login Admin',
	'LOGIN_ADMIN_SUCCESS'                       => 'Login Admin success',
	'DATAFILE_STATUS'                           => 'Status of file',
	'NO_FOUNDER'								=> 'Only the board\'s FOUNDER can use this script!',
	'NO_ADMIN'                                  => 'Access denied, you are not an ADMIN',
	'IP_CF_DB_POP_HEADER'                       => 'IP Country Flag Olympus Database Populator',
	'DELETED_THIS_FILE'                         => 'Operation completed, congratulations!',
	'GUESTS_ONLINE'                             => '<br />Guests Online:&nbsp;',
	'PAST_REGISTERED_NONE'						=> 'None',
	'PAST_ONLINE'								=> 'Past 24hrs registered users online: ',
	'USERS_24HOUR_TOTAL'						=> '%d Users active over the last 24 hours',
//	'USERS_24HOUR_TOTAL_NONE'					=> 'None',

	'country'	=> array(
	'AX'										=> 'Åland Islands',
	'AL'										=> 'Albania',
	'DZ'										=> 'Algeria',
	'AS'										=> 'American Samoa',
	'AD'										=> 'Andorra',
	'AO'										=> 'Angola',
	'AI'										=> 'Anguilla',
	'AQ'										=> 'Antarctica',
	'AG'										=> 'Antigua And Barbuda',
	'AR'										=> 'Argentina',
	'AM'										=> 'Armenia',
	'AW'										=> 'Aruba',
	'AU'										=> 'Australia',
	'AT'										=> 'Austria',
	'AZ'										=> 'Azerbaijan',
	'BS'										=> 'Bahamas',
	'BH'										=> 'Bahrain',
	'BD'										=> 'Bangladesh',
	'BB'										=> 'Barbados',
	'BY'										=> 'Belarus',
	'BE'										=> 'Belgium',
	'BZ'										=> 'Belize',
	'BJ'										=> 'Benin',
	'BM'										=> 'Bermuda',
	'BT'										=> 'Bhutan',
	'BO'										=> 'Bolivia',
	'BA'										=> 'Bosnia And Herzegovina',
	'BW'										=> 'Botswana',
	'BV'										=> 'Bouvet Island',
	'BR'										=> 'Brazil',
	'IO'										=> 'British Indian Ocean Territory',
	'BN'										=> 'Brunei Darussalam',
	'BG'										=> 'Bulgaria',
	'BF'										=> 'Burkina Faso',
	'BI'										=> 'Burundi',
	'KH'										=> 'Cambodia',
	'CM'										=> 'Cameroon',
	'CA'										=> 'Canada',
	'CV'										=> 'Cape Verde',
	'KY'										=> 'Cayman Islands',
	'CF'										=> 'Central African Republic',
	'TD'										=> 'Chad',
	'CL'										=> 'Chile',
	'CN'										=> 'China',
	'CX'										=> 'Christmas Island',
	'CC'										=> 'Cocos (Keeling) Islands',
	'CO'										=> 'Colombia',
	'KM'										=> 'Comoros',
	'CG'										=> 'Congo',
	'CD'										=> 'Congo, The Democratic Republic Of The',
	'CK'										=> 'Cook Islands',
	'CR'										=> 'Costa Rica',
	'CI'										=> 'Cote D\'Ivoire',
	'HR'										=> 'Croatia',
	'CU'										=> 'Cuba',
	'CY'										=> 'Cyprus',
	'CZ'										=> 'Czech Republic',
	'DK'										=> 'Denmark',
	'DJ'										=> 'Djibouti',
	'DM'										=> 'Dominica',
	'DO'										=> 'Dominican Republic',
	'EC'										=> 'Ecuador',
	'EG'										=> 'Egypt',
	'SV'										=> 'El Salvador',
	'GQ'										=> 'Equatorial Guinea',
	'ER'										=> 'Eritrea',
	'EE'										=> 'Estonia',
	'ET'										=> 'Ethiopia',
	'FK'										=> 'Falkland Islands (Malvinas)',
	'FO'										=> 'Faroe Islands',
	'FJ'										=> 'Fiji',
	'FI'										=> 'Finland',
	'FR'										=> 'France',
	'GF'										=> 'French Guiana',
	'PF'										=> 'French Polynesia',
	'TF'										=> 'French Southern Territories',
	'GA'										=> 'Gabon',
	'GM'										=> 'Gambia',
	'GE'										=> 'Georgia',
	'DE'										=> 'Germany',
	'GH'										=> 'Ghana',
	'GI'										=> 'Gibraltar',
	'GR'										=> 'Greece',
	'GL'										=> 'Greenland',
	'GD'										=> 'Grenada',
	'GP'										=> 'Guadeloupe',
	'GU'										=> 'Guam',
	'GT'										=> 'Guatemala',
	'GG'										=> 'Guernsey',
	'GN'										=> 'Guinea',
	'GW'										=> 'Guinea-Bissau',
	'GY'										=> 'Guyana',
	'HT'										=> 'Haiti',
	'HM'										=> 'Heard Island And Mcdonald Islands',
	'VA'										=> 'Holy See (Vatican City State)',
	'HN'										=> 'Honduras',
	'HK'										=> 'Hong Kong',
	'HU'										=> 'Hungary',
	'IS'										=> 'Iceland',
	'IN'										=> 'India',
	'ID'										=> 'Indonesia',
	'IR'										=> 'Iran, Islamic Republic Of',
	'IQ'										=> 'Iraq',
	'IE'										=> 'Ireland',
	'IM'										=> 'Isle Of Man',
	'IL'										=> 'Israel',
	'IT'										=> 'Italy',
	'JM'										=> 'Jamaica',
	'JP'										=> 'Japan',
	'JE'										=> 'Jersey',
	'JO'										=> 'Jordan',
	'KZ'										=> 'Kazakhstan',
	'KE'										=> 'Kenya',
	'KI'										=> 'Kiribati',
	'KP'										=> 'Korea, Democratic People\'S Republic Of',
	'KR'										=> 'Korea, Republic Of',
	'KW'										=> 'Kuwait',
	'KG'										=> 'Kyrgyzstan',
	'LA'										=> 'Lao People\'S Democratic Republic',
	'LV'										=> 'Latvia',
	'LB'										=> 'Lebanon',
	'LS'										=> 'Lesotho',
	'LR'										=> 'Liberia',
	'LY'										=> 'Libyan Arab Jamahiriya',
	'LI'										=> 'Liechtenstein',
	'LT'										=> 'Lithuania',
	'LU'										=> 'Luxembourg',
	'MO'										=> 'Macao',
	'MK'										=> 'Macedonia',
	'MG'										=> 'Madagascar',
	'MW'										=> 'Malawi',
	'MY'										=> 'Malaysia',
	'MV'										=> 'Maldives',
	'ML'										=> 'Mali',
	'MT'										=> 'Malta',
	'MH'										=> 'Marshall Islands',
	'MQ'										=> 'Martinique',
	'MR'										=> 'Mauritania',
	'MU'										=> 'Mauritius',
	'YT'										=> 'Mayotte',
	'MX'										=> 'Mexico',
	'FM'										=> 'Micronesia, Federated States Of',
	'MD'										=> 'Moldova, Republic Of',
	'MC'										=> 'Monaco',
	'MN'										=> 'Mongolia',
	'ME'										=> 'Montenegro',
	'MS'										=> 'Montserrat',
	'MA'										=> 'Morocco',
	'MZ'										=> 'Mozambique',
	'MM'										=> 'Myanmar',
	'NA'										=> 'Namibia',
	'NR'										=> 'Nauru',
	'NP'										=> 'Nepal',
	'NL'										=> 'Netherlands',
	'AN'										=> 'Netherlands Antilles',
	'NC'										=> 'New Caledonia',
	'NZ'										=> 'New Zealand',
	'NI'										=> 'Nicaragua',
	'NE'										=> 'Niger',
	'NG'										=> 'Nigeria',
	'NU'										=> 'Niue',
	'NF'										=> 'Norfolk Island',
	'MP'										=> 'Northern Mariana Islands',
	'NO'										=> 'Norway',
	'OM'										=> 'Oman',
	'PK'										=> 'Pakistan',
	'PW'										=> 'Palau',
	'PS'										=> 'Palestinian Territory, Occupied',
	'PA'										=> 'Panama',
	'PG'										=> 'Papua New Guinea',
	'PY'										=> 'Paraguay',
	'PE'										=> 'Peru',
	'PH'										=> 'Philippines',
	'PN'										=> 'Pitcairn',
	'PL'										=> 'Poland',
	'PT'										=> 'Portugal',
	'PR'										=> 'Puerto Rico',
	'QA'										=> 'Qatar',
	'RE'										=> 'Reunion',
	'RO'										=> 'Romania',
	'RU'										=> 'Russian Federation',
	'RW'										=> 'Rwanda',
	'SH'										=> 'Saint Helena',
	'KN'										=> 'Saint Kitts And Nevis',
	'LC'										=> 'Saint Lucia',
	'PM'										=> 'Saint Pierre And Miquelon',
	'VC'										=> 'Saint Vincent And The Grenadines',
	'WS'										=> 'Samoa',
	'SM'										=> 'San Marino',
	'ST'										=> 'Sao Tome And Principe',
	'SA'										=> 'Saudi Arabia',
	'SN'										=> 'Senegal',
	'RS'										=> 'Serbia',
	'CS'										=> 'Serbia and Montenegro',
	'SC'										=> 'Seychelles',
	'SL'										=> 'Sierra Leone',
	'SG'										=> 'Singapore',
	'SK'										=> 'Slovakia',
	'SI'										=> 'Slovenia',
	'SB'										=> 'Solomon Islands',
	'SO'										=> 'Somalia',
	'ZA'										=> 'South Africa',
	'GS'										=> 'South Georgia And The South Sandwich Islands',
	'ES'										=> 'Spain',
	'LK'										=> 'Sri Lanka',
	'SD'										=> 'Sudan',
	'SR'										=> 'Suriname',
	'SJ'										=> 'Svalbard And Jan Mayen',
	'SZ'										=> 'Swaziland',
	'SE'										=> 'Sweden',
	'CH'										=> 'Switzerland',
	'SY'										=> 'Syrian Arab Republic',
	'TW'										=> 'Taiwan, Province Of China',
	'TJ'										=> 'Tajikistan',
	'TZ'										=> 'Tanzania, United Republic Of',
	'TH'										=> 'Thailand',
	'TL'										=> 'Timor-Leste',
	'TG'										=> 'Togo',
	'TK'										=> 'Tokelau',
	'TO'										=> 'Tonga',
	'TT'										=> 'Trinidad And Tobago',
	'TN'										=> 'Tunisia',
	'TR'										=> 'Turkey',
	'TM'										=> 'Turkmenistan',
	'TC'										=> 'Turks And Caicos Islands',
	'TV'										=> 'Tuvalu',
	'UG'										=> 'Uganda',
	'UA'										=> 'Ukraine',
	'AE'										=> 'United Arab Emirates',
	'GB'										=> 'United Kingdom',
	'US'										=> 'United States',
	'UM'										=> 'United States Minor Outlying Islands',
	'UY'										=> 'Uruguay',
	'UZ'										=> 'Uzbekistan',
	'VU'										=> 'Vanuatu',
	'VE'										=> 'Venezuela',
	'VN'										=> 'Viet Nam',
	'VG'										=> 'Virgin Islands, British',
	'VI'										=> 'Virgin Islands, U.S.',
	'WF'										=> 'Wallis And Futuna',
	'EH'										=> 'Western Sahara',
	'YE'										=> 'Yemen',
	'ZM'										=> 'Zambia',
	'ZW'										=> 'Zimbabwe',
	'WO'										=> 'unknown IP',
	'LH'										=> 'Localhost AKA your PC!',
	'GUEST'										=> 'Guest',
	'ZZ'										=> 'Not yet selected User Flag',
	'ZV'										=> 'Image Flag missing!',

    ),

)); 

?>