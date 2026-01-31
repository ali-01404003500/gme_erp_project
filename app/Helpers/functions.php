<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
/**
 * Get Status Meta
 *
 * @param string $status_key
 * @return array Status
 */
function get_status_meta($status_key = '')
{
    $metas = [
        'active' => [
            'label' => 'Active',
            'class' => 'success',
        ],
        'inactive' => [
            'label' => 'Inactive',
            'class' => 'warning',
        ],
        'blocked' => [
            'label' => 'Blocked',
            'class' => 'danger',
        ],
    ];

    if (empty($status_key)) {
        return $metas;
    }

    if (in_array($status_key, array_keys($metas))) {
        return $metas[$status_key];
    }

    return [];
}

/**
 * Get Status Class
 *
 * @param string $status_key
 * @return string Status Class
 */
function get_status_class($status_key = '')
{
    $status_meta = get_status_meta($status_key);

    if (empty($status_meta['class'])) {
        return '';
    }

    return $status_meta['class'];
}

/**
 * Get Status label
 *
 * @param string $status_key
 * @return string Status label
 */
function get_status_label($status_key = '')
{
    $status_meta = get_status_meta($status_key);

    if (empty($status_meta['label'])) {
        return '';
    }

    return $status_meta['label'];
}

/**
 * Translate the given message.
 *
 * @param string $key
 * @param array $replace
 * @param string $locale
 * @return string|array|null
 */
function t_($key, $replace = [], $locale = null)
{
    $t = trans($key, $replace, $locale);
    if ($t != $key) {
        return $t;
    } else {
        Log::warning('Language key ' . $key . ' not found');
        $exKey = explode('.', $key);
        return str_replace('-', ' ', ucfirst($exKey[count($exKey) - 1]));
    }
}

/**
 * Formats a given number with a specified number of decimal places.
 *
 * @param float $number The number to be formatted.
 * @param int|null $limit The number of decimal places to display. Defaults to 2 if not provided.
 * @throws \Throwable If an error occurs during the formatting process.
 * @return float The formatted number.
 */
function numberFormat($number, $limit = 0){
    return sprintf('%.'.$limit.'f', $number);	
}
function decimal_format($n)
{

    if (is_numeric($n) && floor($n) != $n) {
        return number_format($n, 3, '.', '');
    }

    return number_format($n, 0, '.', '');
}

function cuntriesNames()
{
    return [
        'Afghanistan',
        'Aland Islands',
        'Albania',
        'Algeria',
        'American Samoa',
        'Andorra',
        'Angola',
        'Anguilla',
        'Antarctica',
        'Antigua and Barbuda',
        'Argentina',
        'Armenia',
        'Aruba',
        'Australia',
        'Austria',
        'Azerbaijan',
        'Bahamas',
        'Bahrain',
        'Bangladesh',
        'Barbados',
        'Belarus',
        'Belgium',
        'Belize',
        'Benin',
        'Bermuda',
        'Bhutan',
        'Bolivia',
        'Bonaire, Sint Eustatius and Saba',
        'Bosnia and Herzegovina',
        'Botswana',
        'Bouvet Island',
        'Brazil',
        'British Indian Ocean Territory',
        'Brunei Darussalam',
        'Bulgaria',
        'Burkina Faso',
        'Burundi',
        'Cabo Verde',
        'Cambodia',
        'Cameroon',
        'Canada',
        'Cayman Islands',
        'Central African Republic',
        'Chad',
        'Chile',
        'China',
        'Christmas Island',
        'Cocos (Keeling) Islands',
        'Colombia',
        'Comoros',
        'Congo',
        'Congo, the Democratic Republic of the',
        'Cook Islands',
        'Costa Rica',
        'Cote d\'Ivoire',
        'Croatia',
        'Cuba',
        'Curacao',
        'Cyprus',
        'Czech Republic',
        'Denmark',
        'Djibouti',
        'Dominica',
        'Dominican Republic',
        'Ecuador',
        'Egypt',
        'El Salvador',
        'Equatorial Guinea',
        'Eritrea',
        'Estonia',
        'Ethiopia',
        'Falkland Islands (Malvinas)',
        'Faroe Islands',
        'Fiji',
        'Finland',
        'France',
        'French Guiana',
        'French Polynesia',
        'French Southern Territories',
        'Gabon',
        'Gambia',
        'Georgia',
        'Germany',
        'Ghana',
        'Gibraltar',
        'Greece',
        'Greenland',
        'Grenada',
        'Guadeloupe',
        'Guam',
        'Guatemala',
        'Guernsey',
        'Guinea',
        'Guinea-Bissau',
        'Guyana',
        'Haiti',
        'Heard Island and McDonald Islands',
        'Holy See (Vatican City State)',
        'Honduras',
        'Hong Kong',
        'Hungary',
        'Iceland',
        'India',
        'Indonesia',
        'Iran, Islamic Republic of',
        'Iraq',
        'Ireland',
        'Isle of Man',
        'Israel',
        'Italy',
        'Jamaica',
        'Japan',
        'Jersey',
        'Jordan',
        'Kazakhstan',
        'Kenya',
        'Kiribati',
        'Korea, Democratic People\'s Republic of',
        'Korea, Republic of',
        'Kuwait',
        'Kyrgyzstan',
        'Lao People\'s Democratic Republic',
        'Latvia',
        'Lebanon',
        'Lesotho',
        'Liberia',
        'Libya',
        'Liechtenstein',
        'Lithuania',
        'Luxembourg',
        'Macao',
        'Macedonia, the former Yugoslav Republic of',
        'Madagascar',
        'Malawi',
        'Malaysia',
        'Maldives',
        'Mali',
        'Malta',
        'Marshall Islands',
        'Martinique',
        'Mauritania',
        'Mauritius',
        'Mayotte',
        'Mexico',
        'Micronesia, Federated States of',
        'Moldova, Republic of',
        'Monaco',
        'Mongolia',
        'Montenegro',
        'Montserrat',
        'Morocco',
        'Mozambique',
        'Myanmar',
        'Namibia',
        'Nauru',
        'Nepal',
        'Netherlands',
        'New Caledonia',
        'New Zealand',
        'Nicaragua',
        'Niger',
        'Nigeria',
        'Niue',
        'Norfolk Island',
        'Northern Mariana Islands',
        'Norway',
        'Oman',
        'Pakistan',
        'Palau',
        'Palestinian Territory, Occupied',
        'Panama',
        'Papua New Guinea',
        'Paraguay',
        'Peru',
        'Philippines',
        'Pitcairn',
        'Poland',
        'Portugal',
        'Puerto Rico',
        'Qatar',
        'Reunion',
        'Romania',
        'Russian Federation',
        'Rwanda',
        'Saint Barthelemy',
        'Saint Helena, Ascension and Tristan da Cunha',
        'Saint Kitts and Nevis',
        'Saint Lucia',
        'Saint Martin (French part)',
        'Saint Pierre and Miquelon',
        'Saint Vincent and the Grenadines',
        'Samoa',
        'San Marino',
        'Sao Tome and Principe',
        'Saudi Arabia',
        'Senegal',
        'Serbia',
        'Seychelles',
        'Sierra Leone',
        'Singapore',
        'Sint Maarten (Dutch part)',
        'Slovakia',
        'Slovenia',
        'Solomon Islands',
        'Somalia',
        'South Africa',
        'South Georgia and the South Sandwich Islands',
        'South Sudan',
        'Spain',
        'Sri Lanka',
        'Sudan',
        'Suriname',
        'Svalbard and Jan Mayen',
        'Swaziland',
        'Sweden',
        'Switzerland',
        'Syrian Arab Republic',
        'Taiwan, Province of China',
        'Tajikistan',
        'Tanzania, United Republic of',
        'Thailand',
        'Timor-Leste',
        'Togo',
        'Tokelau',
        'Tonga',
        'Trinidad and Tobago',
        'Tunisia',
        'Turkey',
        'Turkmenistan',
        'Turks and Caicos Islands',
        'Tuvalu',
        'Uganda',
        'Ukraine',
        'United Arab Emirates',
        'United Kingdom',
        'United States',
        'United States Minor Outlying Islands',
        'Uruguay',
        'Uzbekistan',
        'Vanuatu',
        'Venezuela, Bolivarian Republic of',
        'Viet Nam',
        'Virgin Islands, British',
        'Virgin Islands, U.S.',
        'Wallis and Futuna',
        'Western Sahara',
        'Yemen',
        'Zambia',
        'Zimbabwe',
    ];
}

function getDivision()
{
    return array_keys(locationsOfBangladesh());
}

function getDistrict($division = ''){
    return array_keys(locationsOfBangladesh()[$division]);
}

function locationsOfBangladesh()
{
    return [
        'Barisal' => [
            'Barguna' => ['Amtali', 'Bamna', 'Barguna Sadar', 'Betagi', 'Patharghata', 'Taltali'],
            'Barisal' => ['Agailjhara', 'Babuganj', 'Bakerganj', 'Banaripara', 'Gaurnadi', 'Hizla', 'Barishal Sadar', 'Mehendiganj', 'Muladi', 'Wazirpur'],
            'Bhola' => ['Bhola Sadar', 'Burhanuddin', 'Char Fasson', 'Daulatkhan', 'Lalmohan', 'Manpura', 'Tazumuddin'],
            'Jhalokati' => ['Jhalokati Sadar', 'Kathalia', 'Nalchity', 'Rajapur'],
            'Patuakhali' => ['Bauphal', 'Dashmina', 'Galachipa', 'Kalapara', 'Mirzaganj', 'Patuakhali Sadar', 'Rangabali', 'Dumki'],
            'Pirojpur' => ['Bhandaria', 'Kawkhali', 'Mathbaria', 'Nazirpur', 'Pirojpur Sadar', 'Nesarabad (Swarupkati)', 'Zianagar'],
        ],
        'Chittagong' => [
            'Bandarban' => ['Ali Kadam', 'Bandarban Sadar', 'Lama', 'Naikhongchhari', 'Rowangchhari', 'Ruma', 'Thanchi'],
            'Brahmanbaria' => ['Akhaura', 'Bancharampur', 'Brahmanbaria Sadar', 'Kasba', 'Nabinagar', 'Nasirnagar', 'Sarail', 'Ashuganj', 'Bijoynagar'],
            'Chandpur' => ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Haziganj', 'Kachua', 'Matlab Dakshin', 'Matlab Uttar', 'Shahrasti'],
            'Chittagong' => ['Anwara', 'Banshkhali', 'Boalkhali', 'Chandanaish', 'Fatikchhari', 'Hathazari', 'Karnaphuli', 'Lohagara', 'Mirsharai', 'Patiya', 'Rangunia', 'Raozan', 'Sandwip', 'Satkania', 'Sitakunda', 'Bandar Thana', 'Chandgaon Thana', 'Double Mooring Thana', 'Kotwali Thana', 'Pahartali Thana', 'Panchlaish Thana', 'Bhujpur Thana'],
            'Comilla' => ['Barura', 'Brahmanpara', 'Burichang', 'Chandina', 'Chauddagram', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Muradnagar', 'Nangalkot', 'Cumilla Adarsha Sadar', 'Meghna', 'Titas', 'Monohargonj', 'Cumilla Sadar Dakshin'],
            'Cox\'s Bazar' => ['Chakaria', 'Cox\'s Bazar Sadar', 'Kutubdia', 'Maheshkhali', 'Ramu', 'Teknaf', 'Ukhia', 'Pekua'],
            'Feni' => ['Chhagalnaiya', 'Daganbhuiyan', 'Feni Sadar', 'Parshuram', 'Sonagazi', 'Fulgazi'],
            'Khagrachhari' => ['Dighinala', 'Khagrachhari', 'Lakshmichhari', 'Mahalchhari', 'Manikchhari', 'Matiranga', 'Panchhari', 'Ramgarh'],
            'Lakshmipur' => ['Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati', 'Kamalnagar'],
            'Noakhali' => ['Begumganj', 'Noakhali Sadar', 'Chatkhil', 'Companiganj', 'Hatiya', 'Senbagh', 'Sonaimuri', 'Subarnachar', 'Kabirhat'],
            'Rangamati' => ['Bagaichhari', 'Barkal', 'Kawkhali (Betbunia)', 'Belaichhari', 'Kaptai', 'Juraichhari', 'Langadu', 'Naniyachar', 'Rajasthali', 'Rangamati Sadar'],
        ],
        'Dhaka' => [
            'Dhaka' => ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar', 'Tejgaon Circle'],
            'Faridpur' => ['Alfadanga', 'Bhanga', 'Boalmari', 'Charbhadrasan', 'Faridpur Sadar', 'Madhukhali', 'Nagarkanda', 'Sadarpur', 'Saltha'],
            'Gazipur' => ['Gazipur Sadar', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Sreepur'],
            'Gopalganj' => ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara'],
            'Kishoreganj' => ['Austagram', 'Bajitpur', 'Bhairab', 'Hossainpur', 'Itna', 'Karimganj', 'Katiadi', 'Kishoreganj Sadar', 'Kuliarchar', 'Mithamain', 'Nikli', 'Pakundia'],
            'Madaripur' => ['Rajoir', 'Madaripur Sadar', 'Kalkini', 'Shibchar'],
            'Manikganj' => ['Daulatpur', 'Ghior', 'Harirampur', 'Manikgonj Sadar', 'Saturia', 'Shivalaya', 'Singair'],
            'Munshiganj' => ['Gazaria', 'Lohajang', 'Munshiganj Sadar', 'Sirajdikhan', 'Sreenagar', 'Tongibari'],
            'Narayanganj' => ['Araihazar', 'Bandar', 'Narayanganj Sadar', 'Rupganj', 'Sonargaon'],
            'Narsingdi' => ['Narsingdi Sadar', 'Belabo', 'Monohardi', 'Palash', 'Raipura', 'Shibpur'],
            'Rajbari' => ['Baliakandi', 'Goalandaghat', 'Pangsha', 'Rajbari Sadar', 'Kalukhali'],
            'Shariatpur' => ['Bhedarganj', 'Damudya', 'Gosairhat', 'Naria', 'Shariatpur Sadar', 'Zajira', 'Shakhipur'],
            'Tangail' => ['Gopalpur', 'Basail', 'Bhuapur', 'Delduar', 'Ghatail', 'Kalihati', 'Madhupur', 'Mirzapur', 'Nagarpur', 'Sakhipur', 'Dhanbari', 'Tangail Sadar'],
        ],
        'Khulna' => [
            'Bagerhat' => ['Bagerhat Sadar', 'Chitalmari', 'Fakirhat', 'Kachua', 'Mollahat', 'Mongla', 'Morrelganj', 'Rampal', 'Sarankhola'],
            'Chuadanga' => ['Alamdanga', 'Chuadanga Sadar', 'Damurhuda', 'Jibannagar'],
            'Jessore' => ['Abhaynagar', 'Bagherpara', 'Chaugachha', 'Jhikargachha', 'Keshabpur', 'Jashore Sadar', 'Manirampur', 'Sharsha'],
            'Jhenaidah' => ['Harinakunda', 'Jhenaidah Sadar', 'Kaliganj', 'Kotchandpur', 'Maheshpur', 'Shailkupa'],
            'Khulna' => ['Batiaghata', 'Dacope', 'Dumuria', 'Dighalia', 'Koyra', 'Paikgachha', 'Phultala', 'Rupsha', 'Terokhada', 'Daulatpur Thana', 'Khalishpur Thana', 'Khan Jahan Ali Thana', 'Kotwali Thana', 'Sonadanga Thana', 'Harintana Thana'],
            'Kushtia' => ['Bheramara', 'Daulatpur', 'Khoksa', 'Kumarkhali', 'Kushtia Sadar', 'Mirpur'],
            'Magura' => ['Magura Sadar', 'Mohammadpur', 'Shalikha', 'Sreepur'],
            'Meherpur' => ['Gangni', 'Meherpur Sadar', 'Mujibnagar'],
            'Narail' => ['Kalia', 'Lohagara', 'Narail Sadar', 'Naragati Thana'],
            'Satkhira' => ['Assasuni', 'Debhata', 'Kalaroa', 'Kaliganj', 'Satkhira Sadar', 'Shyamnagar', 'Tala'],
        ],
        'Mymensingh' => [
            'Jamalpur' => ['Baksiganj', 'Dewanganj', 'Islampur', 'Jamalpur Sadar', 'Madarganj', 'Melandaha', 'Sarishabari'],
            'Mymensingh' => ['Trishal', 'Dhobaura', 'Fulbaria', 'Gaffargaon', 'Gauripur', 'Haluaghat', 'Ishwarganj', 'Mymensingh Sadar', 'Muktagachha', 'Nandail', 'Phulpur', 'Bhaluka', 'Tara Khanda'],
            'Netrakona' => ['Atpara', 'Barhatta', 'Durgapur', 'Khaliajuri', 'Kalmakanda', 'Kendua', 'Madan', 'Mohanganj', 'Netrokona Sadar', 'Purbadhala'],
            'Sherpur' => ['Jhenaigati', 'Nakla', 'Nalitabari', 'Sherpur Sadar', 'Sreebardi'],
        ],
        'Rajshahi' => [
            'Bogra' => ['Adamdighi', 'Bogura Sadar', 'Dhunat', 'Dhupchanchia', 'Gabtali', 'Kahaloo', 'Nandigram', 'Sariakandi', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Sonatola'],
            'Chapainawabganj' => ['Bholahat', 'Gomastapur', 'Nachole', 'Nawabganj Sadar', 'Shibganj'],
            'Joypurhat' => ['Akkelpur', 'Joypurhat Sadar', 'Kalai', 'Khetlal', 'Panchbibi'],
            'Naogaon' => ['Atrai', 'Badalgachhi', 'Manda', 'Dhamoirhat', 'Mohadevpur', 'Naogaon Sadar', 'Niamatpur', 'Patnitala', 'Porsha', 'Raninagar', 'Sapahar'],
            'Natore' => ['Bagatipara', 'Baraigram', 'Gurudaspur', 'Lalpur', 'Natore Sadar', 'Singra', 'Naldanga'],
            'Pabna' => ['Atgharia', 'Bera', 'Bhangura', 'Chatmohar', 'Faridpur', 'Ishwardi', 'Pabna Sadar', 'Santhia', 'Sujanagar'],
            'Rajshahi' => ['Bagha', 'Bagmara', 'Charghat', 'Durgapur', 'Godagari', 'Mohanpur', 'Paba', 'Puthia', 'Tanore'],
            'Sirajganj' => ['Belkuchi', 'Chauhali', 'Kamarkhanda', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Sirajganj Sadar', 'Tarash', 'Ullahpara'],
        ],
        'Rangpur' => [
            'Dinajpur' => ['Birampur', 'Birganj', 'Biral', 'Bochaganj', 'Chirirbandar', 'Phulbari', 'Ghoraghat', 'Hakimpur', 'Kaharole', 'Khansama', 'Dinajpur Sadar', 'Nawabganj', 'Parbatipur'],
            'Gaibandha' => ['Phulchhari', 'Gaibandha Sadar', 'Gobindaganj', 'Palashbari', 'Sadullapur', 'Sughatta', 'Sundarganj'],
            'Kurigram' => ['Bhurungamari', 'Char Rajibpur', 'Chilmari', 'Phulbari', 'Kurigram Sadar', 'Nageshwari', 'Rajarhat', 'Raomari', 'Ulipur'],
            'Lalmonirhat' => ['Aditmari', 'Hatibandha', 'Kaliganj', 'Lalmonirhat Sadar', 'Patgram'],
            'Nilphamari' => ['Dimla', 'Domar', 'Jaldhaka', 'Kishoreganj', 'Nilphamari Sadar', 'Saidpur'],
            'Panchagarh' => ['Atwari', 'Boda', 'Debiganj', 'Panchagarh Sadar', 'Tetulia'],
            'Rangpur' => ['Badarganj', 'Gangachhara', 'Kaunia', 'Rangpur Sadar', 'Mithapukur', 'Pirgachha', 'Pirganj', 'Taraganj'],
            'Thakurgaon' => ['Baliadangi', 'Haripur', 'Pirganj', 'Ranisankail', 'Thakurgaon Sadar'],
        ],
        'Sylhet' => [
            'Habiganj' => ['Ajmiriganj', 'Bahubal', 'Baniyachong', 'Chunarughat', 'Habiganj Sadar', 'Lakhai', 'Madhabpur', 'Nabiganj', 'Sayestaganj'],
            'Moulvibazar' => ['Barlekha', 'Juri', 'Kamalganj', 'Kulaura', 'Moulvibazar Sadar', 'Rajnagar', 'Sreemangal'],
            'Sunamganj' => ['Bishwamvarpur', 'Chhatak', 'Dakshin Sunamganj', 'Derai', 'Dharamapasha', 'Dowarabazar', 'Jagannathpur', 'Jamalganj', 'Sullah', 'Sunamganj Sadar', 'Tahirpur'],
            'Sylhet' => ['Balaganj', 'Beanibazar', 'Bishwanath', 'Companigonj', 'Dakshin Surma', 'Fenchuganj', 'Golapganj', 'Gowainghat', 'Jaintiapur', 'Kanaighat', 'Osmani Nagar', 'Sylhet Sadar', 'Zakiganj'],
        ],
    ];
}


// function s3FileToBase64($filename)
// {
//     if($filename == ''){
//         return '';
//     }
//     $fileExtension = explode('.', $filename);
//     $fileExtension = end($fileExtension);

//     $directory = str_replace('/files/', '', $filename);
   
//     $s3FileContent = Storage::disk('s3')->get($directory);
//     switch ($fileExtension) {
//         case 'jpg':
//         case 'jpeg':
//             $imageType = 'image/jpeg';
//             break;
//         case 'png':
//             $imageType = 'image/png';
//             break;
//         case 'gif':
//             $imageType = 'image/gif';
//             break;
//         default:
//             throw new \InvalidArgumentException("Unsupported image type: $fileExtension");No-image.jpg
//     }

//     $base64Image = "data:$imageType;base64," . base64_encode($s3FileContent);

//     return $base64Image;
// }


function s3FileToBase64($filename)
{
    if (empty($filename) || $filename == NULL) {
        return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
    }

    $directory = str_replace('/files/', '', $filename);

    try {
        // Get raw file content from S3
        $s3FileContent = Storage::disk('s3')->get($directory);
        
        // Critical fix: Use file content to determine MIME type (NOT file extension)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $s3FileContent);
        finfo_close($finfo);
        
        // Validate and normalize MIME type
        $allowedTypes = [
            'image/jpeg' => 'image/jpeg',
            'image/jpg' => 'image/jpeg',
            'image/png' => 'image/png',
            'image/gif' => 'image/gif',
            'image/avif' => 'image/avif'
        ];
        
        $normalizedType = $allowedTypes[strtolower($mimeType)] ?? null;
        
        if (!$normalizedType) {
            // Fallback to default SVG if invalid type
            return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
        }

        // Special handling for AVIF - convert to JPEG
        if ($normalizedType === 'image/avif') {
            return convertAvifToJpegBase64($s3FileContent);
        }

        return "data:{$normalizedType};base64," . base64_encode($s3FileContent);
        
    } catch (\Exception $e) {
        // Return the base64 string for the default image
        return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
    }
}

/**
 * Convert AVIF image content to JPEG format and return as base64 data URL
 * 
 * @param string $avifContent Raw AVIF image content
 * @return string Base64 encoded JPEG data URL
 */
function convertAvifToJpegBase64($avifContent)
{
    try {
        // Check if GD extension supports AVIF (PHP 8.1+)
        if (!function_exists('imagecreatefromavif')) {
            // Fallback: Try using Imagick if available
            if (extension_loaded('imagick')) {
                return convertAvifToJpegWithImagick($avifContent);
            }
            
            // If neither GD nor Imagick supports AVIF, return default image
            return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
        }

        // Create a temporary file to work with AVIF data
        $tempFile = tempnam(sys_get_temp_dir(), 'avif_');
        file_put_contents($tempFile, $avifContent);

        // Create image resource from AVIF
        $image = imagecreatefromavif($tempFile);
        
        if ($image === false) {
            unlink($tempFile);
            return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
        }

        // Start output buffering to capture JPEG data
        ob_start();
        imagejpeg($image, null, 85); // 85% quality
        $jpegContent = ob_get_contents();
        ob_end_clean();

        // Clean up resources
        imagedestroy($image);
        unlink($tempFile);

        // Return base64 encoded JPEG
        return "data:image/jpeg;base64," . base64_encode($jpegContent);
        
    } catch (\Exception $e) {
        // Clean up temp file if it exists
        if (isset($tempFile) && file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        // Return default image on error
        return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
    }
}

/**
 * Convert AVIF to JPEG using Imagick (fallback method)
 * 
 * @param string $avifContent Raw AVIF image content
 * @return string Base64 encoded JPEG data URL
 */
function convertAvifToJpegWithImagick($avifContent)
{
    try {
        // Create Imagick object from AVIF content
        $imagick = new \Imagick();
        $imagick->readImageBlob($avifContent);
        
        // Convert to JPEG format
        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompressionQuality(85);
        
        // Get JPEG content
        $jpegContent = $imagick->getImageBlob();
        
        // Clean up
        $imagick->clear();
        $imagick->destroy();
        
        return "data:image/jpeg;base64," . base64_encode($jpegContent);
        
    } catch (\Exception $e) {
        // Return default image on Imagick error
        return "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgR2VuZXJhdG9yOiBTVkcgUmVwbyBNaXhlciBUb29scyAtLT4NCjxzdmcgZmlsbD0iIzAwMDAwMCIgaGVpZ2h0PSI4MDBweCIgd2lkdGg9IjgwMHB4IiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIA0KCSB2aWV3Qm94PSIwIDAgNTggNTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZD0iTTU3LDZIMUMwLjQ0OCw2LDAsNi40NDcsMCw3djQ0YzAsMC41NTMsMC40NDgsMSwxLDFoNTZjMC41NTIsMCwxLTAuNDQ3LDEtMVY3QzU4LDYuNDQ3LDU3LjU1Miw2LDU3LDZ6IE01Niw1MEgyVjhoNTRWNTB6Ig0KCQkvPg0KCTxwYXRoIGQ9Ik0xNiwyOC4xMzhjMy4wNzEsMCw1LjU2OS0yLjQ5OCw1LjU2OS01LjU2OEMyMS41NjksMTkuNDk4LDE5LjA3MSwxNywxNiwxN3MtNS41NjksMi40OTgtNS41NjksNS41NjkNCgkJQzEwLjQzMSwyNS42NCwxMi45MjksMjguMTM4LDE2LDI4LjEzOHogTTE2LDE5YzEuOTY4LDAsMy41NjksMS42MDIsMy41NjksMy41NjlTMTcuOTY4LDI2LjEzOCwxNiwyNi4xMzhzLTMuNTY5LTEuNjAxLTMuNTY5LTMuNTY4DQoJCVMxNC4wMzIsMTksMTYsMTl6Ii8+DQoJPHBhdGggZD0iTTcsNDZjMC4yMzQsMCwwLjQ3LTAuMDgyLDAuNjYtMC4yNDlsMTYuMzEzLTE0LjM2MmwxMC4zMDIsMTAuMzAxYzAuMzkxLDAuMzkxLDEuMDIzLDAuMzkxLDEuNDE0LDBzMC4zOTEtMS4wMjMsMC0xLjQxNA0KCQlsLTQuODA3LTQuODA3bDkuMTgxLTEwLjA1NGwxMS4yNjEsMTAuMzIzYzAuNDA3LDAuMzczLDEuMDQsMC4zNDUsMS40MTMtMC4wNjJjMC4zNzMtMC40MDcsMC4zNDYtMS4wNC0wLjA2Mi0xLjQxM2wtMTItMTENCgkJYy0wLjE5Ni0wLjE3OS0wLjQ1Ny0wLjI2OC0wLjcyLTAuMjYyYy0wLjI2NSwwLjAxMi0wLjUxNSwwLjEyOS0wLjY5NCwwLjMyNWwtOS43OTQsMTAuNzI3bC00Ljc0My00Ljc0Mw0KCQljLTAuMzc0LTAuMzczLTAuOTcyLTAuMzkyLTEuMzY4LTAuMDQ0TDYuMzM5LDQ0LjI0OWMtMC40MTUsMC4zNjUtMC40NTUsMC45OTctMC4wOSwxLjQxMkM2LjQ0Nyw0NS44ODYsNi43MjMsNDYsNyw0NnoiLz4NCjwvZz4NCjwvc3ZnPg==";
    }
}


 



function getLT20($n) {
    $a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ',
        'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    if ($n < 20) {
        return $a[intval($n)];
    }
    return '';
}


function getGT20($n) {
    $b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    return $b[$n[0]] . ' ' . getLT20($n[1]);
}

function getLP20($n) {
    $a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine '];
    return $a[intval($n)] != "" ? $a[intval($n)] : 'zero';
}

function convertIntegerPart($numStr) {
    if (strlen($numStr) > 9) {
        throw new Exception('overflow'); // Does not support converting more than 9 digits yet
    }

    $numStr = str_pad($numStr, 9, '0', STR_PAD_LEFT);
    $n1 = substr($numStr, 0, 2);
    $n2 = substr($numStr, 2, 2);
    $n3 = substr($numStr, 4, 2);
    $n4 = substr($numStr, 6, 1);
    $n5 = substr($numStr, 7, 2);


    $str = '';
    $str .= $n1 != 0 ? (getLT20($n1) ?: getGT20($n1)) . 'crore ' : '';
    $str .= $n2 != 0 ? (getLT20($n2) ?: getGT20($n2)) . 'lakh ' : '';
    $str .= $n3 != 0 ? (getLT20($n3) ?: getGT20($n3)) . 'thousand ' : '';
    $str .= $n4 != 0 ? getLT20($n4) . 'hundred ' : '';
    $str .= $n5 != 0 && $str != '' ? 'and ' : '';
    $str .= $n5 != 0 ? (getLT20($n5) ?: getGT20($n5)) : '';

    return trim($str);
}

function convertFractionalPart($fractionStr) {
    $str = 'point ';
    foreach (str_split($fractionStr) as $digit) {
        $str .= getLP20($digit) . ' ';
    }
    return trim($str);
}

function numWords($input) {
    $num = floatval($input);
    if (is_nan($num)) return '';
    if ($num === 0) return 'zero';

    $input = number_format($num, 2, '.', ' ');
    $exp = explode('.', strval($input));
    $integerPart=$exp[0];
    $fractionalPart=$exp[1];

    $result = convertIntegerPart($integerPart);

    if($fractionalPart!=0){
        $result .= ' ' . convertFractionalPart($fractionalPart);
    }
    return trim($result);
}


function convert_number($number) {
    $integerPart = floor($number);
    $fractionalPart = round(($number - $integerPart) * 100); // Assuming two decimal places for fractional part
    if ($integerPart < 0 || $integerPart > 999999999) {
        throw new Exception("Number is out of range");
    }
    $Kt = floor($integerPart / 10000000); /* Crore */
    $integerPart -= $Kt * 10000000;
    $Gn = floor($integerPart / 100000);  /* Lac  */
    $integerPart -= $Gn * 100000;
    $kn = floor($integerPart / 1000);     /* Thousand */
    $integerPart -= $kn * 1000;
    $Hn = floor($integerPart / 100);      /* Hundred */
    $integerPart -= $Hn * 100;
    $Dn = floor($integerPart / 10);       /* Tens */
    $n = $integerPart % 10;                    /* Ones */
    $res = "";
    if ($Kt) {
        $res .= convert_number($Kt) . " Crore ";
    }
    if ($Gn) {
        $res .= convert_number($Gn) . " Lac ";
    }
    if ($kn) {
        $res .= ($res === "" ? "" : " ") .
            convert_number($kn) . " Thousand ";
    }
    if ($Hn) {
        $res .= ($res === "" ? "" : " ") .
            convert_number($Hn) . " Hundred ";
    }
    $ones = [
        "", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen"
    ];
    $tens = [
        "", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eighty", "Ninety"
    ];
    if ($Dn || $n) {
        if ($res !== "") {
            $res .= " and ";
        }
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];
            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }
    if ($res === "") {
        $res = "zero";
    }
    if ($fractionalPart) {
        $res .= " point ";
        if ($fractionalPart < 20) {
            $res .= $ones[$fractionalPart];
        } else {
            $fractionTens = floor($fractionalPart / 10);
            $fractionOnes = $fractionalPart % 10;
            $res .= $tens[$fractionTens];
            if ($fractionOnes) {
                $res .= "-" . $ones[$fractionOnes];
            }
        }
    }
    return $res;
}



function splitHtml($html, $limit) {
    $parts = [];
    $currentPart = '';
    $tagStack = [];
    $currentLength = 0;
    $position = 0;
    $htmlLength = strlen($html);
    
    // Remove extra whitespace and normalize HTML
    $html = preg_replace('/\s+/', ' ', $html);
    $html = str_replace('> <', '><', $html);
    
    while ($position < $htmlLength) {
        // Find next tag
        $nextTag = strpos($html, '<', $position);
        
        if ($nextTag === false) {
            // No more tags, process remaining text
            $remainingText = substr($html, $position);
            $remainingLength = strlen($remainingText);
            
            if ($currentLength + $remainingLength <= $limit) {
                // Add to current part
                $currentPart .= $remainingText;
                $currentLength += $remainingLength;
                $position = $htmlLength;
            } else {
                // Split text at space boundary
                $remainingChars = $limit - $currentLength;
                $splitPos = $nextTag;
                
                // Find the last space within the limit
                $lastSpace = strrpos(substr($remainingText, 0, $remainingChars), ' ');
                if ($lastSpace !== false) {
                    $splitPos = $lastSpace;
                } else {
                    $splitPos = $remainingChars;
                }
                
                $currentPart .= substr($remainingText, 0, $splitPos);
                $parts[] = $currentPart;
                
                // Start new part with remaining text and reopen tags
                $currentPart = implode('', array_reverse($tagStack)) . 
                              substr($remainingText, $splitPos);
                $currentLength = strlen($currentPart);
                $position = $htmlLength;
            }
            break;
        }
        
        // Process text before tag
        if ($nextTag > $position) {
            $textBeforeTag = substr($html, $position, $nextTag - $position);
            $textLength = strlen($textBeforeTag);
            
            if ($currentLength + $textLength > $limit) {
                // Split text at space boundary
                $remainingChars = $limit - $currentLength;
                $lastSpace = strrpos(substr($textBeforeTag, 0, $remainingChars), ' ');
                
                if ($lastSpace !== false) {
                    $splitPos = $lastSpace;
                } else {
                    $splitPos = $remainingChars;
                }
                
                $currentPart .= substr($textBeforeTag, 0, $splitPos);
                $parts[] = $currentPart;
                
                // Start new part with remaining text and reopen tags
                $currentPart = implode('', array_reverse($tagStack)) . 
                              substr($textBeforeTag, $splitPos);
                $currentLength = strlen($currentPart);
                $position = $nextTag;
                continue;
            } else {
                $currentPart .= $textBeforeTag;
                $currentLength += $textLength;
            }
        }
        
        $position = $nextTag;
        
        // Process tag
        $tagEnd = strpos($html, '>', $position);
        if ($tagEnd === false) {
            break;
        }
        
        $tagLength = $tagEnd - $position + 1;
        $tag = substr($html, $position, $tagLength);
        
        // Handle self-closing tags
        if (preg_match('/<(\w+)[^>]*\/>/', $tag)) {
            $currentPart .= $tag;
            $currentLength += $tagLength;
            $position = $tagEnd + 1;
            continue;
        }
        
        // Handle opening tags
        if (preg_match('/<(\/?)(\w+)/', $tag, $matches)) {
            $isClosing = $matches[1] === '/';
            $tagName = $matches[2];
            
            if (!$isClosing) {
                // Opening tag
                $tagStack[] = "</$tagName>";
                $currentPart .= $tag;
                $currentLength += $tagLength;
            } else {
                // Closing tag
                if (!empty($tagStack) ){
                    array_pop($tagStack);
                }
                $currentPart .= $tag;
                $currentLength += $tagLength;
            }
        } else {
            // Comment or other tag type
            $currentPart .= $tag;
            $currentLength += $tagLength;
        }
        
        $position = $tagEnd + 1;
        
        // Check if we need to split after this tag
        if ($currentLength >= $limit) {
            $parts[] = $currentPart;
            $currentPart = implode('', array_reverse($tagStack));
            $currentLength = strlen($currentPart);
        }
    }
    
    // Add the final part
    if (!empty($currentPart)) {
        $parts[] = $currentPart;
    }
    
    return $parts;
}


function splitHtmlString($html, $charLimit = 1500) {
    // Self-closing tags that don't need closing
    $selfClosingTags = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 
        'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];
    
    // Tags that should not be split (content should stay together)
    $noSplitTags = ['script', 'style', 'textarea', 'pre', 'code'];
    
    $parts = [];
    $currentPart = '';
    $openTags = []; // Stack to track open tags
    $position = 0;
    $htmlLength = strlen($html);
    $inNoSplitTag = null;
    
    while ($position < $htmlLength) {
        // Check if we're at the character limit
        if (strlen($currentPart) >= $charLimit && $inNoSplitTag === null) {
            // Find a good breaking point (space or after a tag)
            $breakPoint = findBreakPoint($html, $position, $charLimit - strlen($currentPart));
            
            if ($breakPoint > $position) {
                // Add remaining content up to break point
                $remainingContent = substr($html, $position, $breakPoint - $position);
                $currentPart .= $remainingContent;
                $position = $breakPoint;
            }
            
            // Close open tags and finalize current part
            $closingTags = '';
            for ($i = count($openTags) - 1; $i >= 0; $i--) {
                $closingTags .= '</' . $openTags[$i] . '>';
            }
            
            $parts[] = $currentPart . $closingTags;
            
            // Start new part with reopening tags
            $currentPart = '';
            foreach ($openTags as $tag) {
                $currentPart .= '<' . $tag . '>';
            }
            
            continue;
        }
        
        // Check if we're at the start of an HTML tag
        if ($html[$position] === '<') {
            $tagEnd = strpos($html, '>', $position);
            if ($tagEnd === false) {
                // Malformed HTML, just add the rest
                $currentPart .= substr($html, $position);
                break;
            }
            
            $fullTag = substr($html, $position, $tagEnd - $position + 1);
            $tagInfo = parseTag($fullTag);
            
            if ($tagInfo) {
                $tagName = $tagInfo['name'];
                $isClosing = $tagInfo['closing'];
                $isSelfClosing = $tagInfo['selfClosing'] || in_array($tagName, $selfClosingTags);
                
                // Handle no-split tags
                if (in_array($tagName, $noSplitTags)) {
                    if (!$isClosing) {
                        $inNoSplitTag = $tagName;
                    } else {
                        $inNoSplitTag = null;
                    }
                }
                
                // Add the tag to current part
                $currentPart .= $fullTag;
                
                // Update open tags stack
                if (!$isSelfClosing) {
                    if ($isClosing) {
                        // Remove from open tags stack
                        $index = array_search($tagName, $openTags);
                        if ($index !== false) {
                            array_splice($openTags, $index, 1);
                        }
                    } else {
                        // Add to open tags stack
                        $openTags[] = $tagName;
                    }
                }
                
                $position = $tagEnd + 1;
            } else {
                // Not a valid tag, just add the character
                $currentPart .= $html[$position];
                $position++;
            }
        } else {
            // Regular character, add to current part
            $currentPart .= $html[$position];
            $position++;
        }
    }
    
    // Add the last part if it has content
    if (!empty($currentPart)) {
        // Close any remaining open tags
        $closingTags = '';
        for ($i = count($openTags) - 1; $i >= 0; $i--) {
            $closingTags .= '</' . $openTags[$i] . '>';
        }
        $parts[] = $currentPart . $closingTags;
    }
    
    return $parts;
}

/**
 * Parse an HTML tag and extract information
 */
function parseTag($tag) {
    // Remove < and >
    $tag = trim($tag, '<>');
    
    // Check if it's a closing tag
    $isClosing = strpos($tag, '/') === 0;
    if ($isClosing) {
        $tag = substr($tag, 1);
    }
    
    // Check if it's self-closing
    $isSelfClosing = substr($tag, -1) === '/';
    if ($isSelfClosing) {
        $tag = rtrim($tag, '/');
    }
    
    // Extract tag name (first word)
    $parts = preg_split('/\s+/', trim($tag), 2);
    $tagName = strtolower($parts[0]);
    
    // Validate tag name
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $tagName)) {
        return null; // Invalid tag name
    }
    
    return [
        'name' => $tagName,
        'closing' => $isClosing,
        'selfClosing' => $isSelfClosing,
        'attributes' => isset($parts[1]) ? $parts[1] : ''
    ];
}

/**
 * Find a good breaking point near the character limit
 */
function findBreakPoint($html, $startPos, $maxChars) {
    $endPos = min($startPos + $maxChars, strlen($html));
    
    // Try to find a space to break at
    for ($i = $endPos; $i > $startPos; $i--) {
        if ($html[$i] === ' ') {
            return $i + 1; // Break after the space
        }
        // Also break after closing tags
        if ($html[$i] === '>') {
            return $i + 1;
        }
    }
    
    // If no good break point found, use the limit
    return $endPos;
}


