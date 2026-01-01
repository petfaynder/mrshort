<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\CpmRate;

class CpmRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder populates CPM rates for all countries based on:
     * - Publisher Rates: Link shortener market (Ouo.io/Adf.ly) + 30-50% premium for Tier 1
     * - Advertiser Rates: PropellerAds optimal CPM data (rounded up)
     */
    public function run(): void
    {
        // Rate data: [ISO Code => ['publisher' => X.XX, 'advertiser' => Y.YY]]
        $rates = [
            // Top Tier 1 Countries (Premium publisher rates, PropellerAds advertiser data)
            'US' => ['publisher' => 6.50, 'advertiser' => 2.50],  // United States
            'UK' => ['publisher' => 6.00, 'advertiser' => 2.25],  // United Kingdom
            'DE' => ['publisher' => 4.50, 'advertiser' => 1.50],  // Germany
            'CA' => ['publisher' => 5.50, 'advertiser' => 1.70],  // Canada
            'AU' => ['publisher' => 6.20, 'advertiser' => 2.00],  // Australia
            'CH' => ['publisher' => 6.80, 'advertiser' => 2.55],  // Switzerland
            'NO' => ['publisher' => 7.00, 'advertiser' => 3.75],  // Norway
            'SE' => ['publisher' => 4.80, 'advertiser' => 1.40],  // Sweden
            'DK' => ['publisher' => 4.60, 'advertiser' => 1.80],  // Denmark
            'FI' => ['publisher' => 5.20, 'advertiser' => 3.65],  // Finland
            'NL' => ['publisher' => 5.00, 'advertiser' => 1.90],  // Netherlands
            'BE' => ['publisher' => 4.50, 'advertiser' => 1.70],  // Belgium
            'AT' => ['publisher' => 4.70, 'advertiser' => 1.25],  // Austria
            'IE' => ['publisher' => 5.50, 'advertiser' => 0.90],  // Ireland
            'NZ' => ['publisher' => 5.80, 'advertiser' => 2.20],  // New Zealand
            'SG' => ['publisher' => 5.00, 'advertiser' => 1.30],  // Singapore
            'HK' => ['publisher' => 4.80, 'advertiser' => 3.65],  // Hong Kong
           
            // High Tier 1 / Tier 2 Countries
            'FR' => ['publisher' => 3.80, 'advertiser' => 1.80],  // France
            'IT' => ['publisher' => 3.60, 'advertiser' => 2.00],  // Italy
            'ES' => ['publisher' => 3.50, 'advertiser' => 1.90],  // Spain
            'JP' => ['publisher' => 3.50, 'advertiser' => 1.25],  // Japan
            'KR' => ['publisher' => 3.40, 'advertiser' => 1.45],  // South Korea
            'TW' => ['publisher' => 3.20, 'advertiser' => 1.25],  // Taiwan
            'AE' => ['publisher' => 4.20, 'advertiser' => 1.30],  // United Arab Emirates
            'SA' => ['publisher' => 3.80, 'advertiser' => 3.70],  // Saudi Arabia
            'IL' => ['publisher' => 3.60, 'advertiser' => 2.25],  // Israel
            'QA' => ['publisher' => 3.50, 'advertiser' => 0.65],  // Qatar
            'KW' => ['publisher' => 3.40, 'advertiser' => 1.55],  // Kuwait
            'BH' => ['publisher' => 3.20, 'advertiser' => 0.60],  // Bahrain
            'OM' => ['publisher' => 3.00, 'advertiser' => 1.00],  // Oman
            'PT' => ['publisher' => 3.20, 'advertiser' => 1.30],  // Portugal
            'GR' => ['publisher' => 3.00, 'advertiser' => 1.80],  // Greece
            'PL' => ['publisher' => 3.40, 'advertiser' => 2.25],  // Poland
            'CZ' => ['publisher' => 3.20, 'advertiser' => 1.90],  // Czech Republic
            'HU' => ['publisher' => 3.00, 'advertiser' => 1.30],  // Hungary
            'RO' => ['publisher' => 2.80, 'advertiser' => 1.35],  // Romania
            'BG' => ['publisher' => 2.60, 'advertiser' => 5.25],  // Bulgaria
            'HR' => ['publisher' => 2.70, 'advertiser' => 4.20],  // Croatia
            'SI' => ['publisher' => 2.80, 'advertiser' => 0.65],  // Slovenia
            'SK' => ['publisher' => 2.60, 'advertiser' => 0.90],  // Slovakia
            'EE' => ['publisher' => 2.70, 'advertiser' => 6.50],  // Estonia
            'LV' => ['publisher' => 2.60, 'advertiser' => 4.00],  // Latvia
            'LT' => ['publisher' => 2.60, 'advertiser' => 0.70],  // Lithuania
            
            // Tier 2 - Asia
            'TH' => ['publisher' => 3.20, 'advertiser' => 4.60],  // Thailand
            'MY' => ['publisher' => 3.00, 'advertiser' => 1.90],  // Malaysia
            'ID' => ['publisher' => 2.80, 'advertiser' => 1.70],  // Indonesia
            'PH' => ['publisher' => 2.60, 'advertiser' => 1.75],  // Philippines
            'VN' => ['publisher' => 2.50, 'advertiser' => 2.05],  // Vietnam
            'IN' => ['publisher' => 2.20, 'advertiser' => 0.30],  // India
            'PK' => ['publisher' => 1.80, 'advertiser' => 1.35],  // Pakistan
            'BD' => ['publisher' => 1.70, 'advertiser' => 1.35],  // Bangladesh
            'LK' => ['publisher' => 2.00, 'advertiser' => 1.90],  // Sri Lanka
            'NP' => ['publisher' => 1.80, 'advertiser' => 3.05],  // Nepal
            'MM' => ['publisher' => 1.60, 'advertiser' => 6.05],  // Myanmar
            'KH' => ['publisher' => 1.70, 'advertiser' => 1.20],  // Cambodia
            'LA' => ['publisher' => 1.50, 'advertiser' => 0.35],  // Laos
            'MN' => ['publisher' => 1.60, 'advertiser' => 1.40],  // Mongolia
            'CN' => ['publisher' => 2.40, 'advertiser' => 1.45],  // China
            'MO' => ['publisher' => 2.60, 'advertiser' => 3.85],  // Macau
            'BN' => ['publisher' => 2.20, 'advertiser' => 1.00],  // Brunei
            'TL' => ['publisher' => 1.50, 'advertiser' => 0.90],  // Timor-Leste
            
            // Tier 2 - Latin America
            'BR' => ['publisher' => 2.20, 'advertiser' => 1.10],  // Brazil
            'MX' => ['publisher' => 2.40, 'advertiser' => 3.35],  // Mexico
            'AR' => ['publisher' => 2.00, 'advertiser' => 1.20],  // Argentina
            'CL' => ['publisher' => 2.10, 'advertiser' => 1.10],  // Chile
            'CO' => ['publisher' => 1.90, 'advertiser' => 0.75],  // Colombia
            'PE' => ['publisher' => 1.80, 'advertiser' => 0.70],  // Peru
            'VE' => ['publisher' => 1.70, 'advertiser' => 0.65],  // Venezuela
            'EC' => ['publisher' => 1.80, 'advertiser' => 1.60],  // Ecuador
            'BO' => ['publisher' => 1.60, 'advertiser' => 2.15],  // Bolivia
            'PY' => ['publisher' => 1.50, 'advertiser' => 0.55],  // Paraguay
            'UY' => ['publisher' => 1.90, 'advertiser' => 1.35],  // Uruguay
            'CR' => ['publisher' => 1.80, 'advertiser' => 1.75],  // Costa Rica
            'PA' => ['publisher' => 1.70, 'advertiser' => 1.50],  // Panama
            'GT' => ['publisher' => 1.60, 'advertiser' => 0.80],  // Guatemala
            'SV' => ['publisher' => 1.60, 'advertiser' => 1.10],  // El Salvador
            'HN' => ['publisher' => 1.55, 'advertiser' => 0.65],  // Honduras
            'NI' => ['publisher' => 1.55, 'advertiser' => 1.70],  // Nicaragua
            'CU' => ['publisher' => 1.70, 'advertiser' => 1.90],  // Cuba
            'DO' => ['publisher' => 1.65, 'advertiser' => 0.75],  // Dominican Republic
            'JM' => ['publisher' => 1.60, 'advertiser' => 0.50],  // Jamaica
            'TT' => ['publisher' => 1.70, 'advertiser' => 0.60],  // Trinidad and Tobago
            'BS' => ['publisher' => 1.75, 'advertiser' => 1.10],  // Bahamas
            'BB' => ['publisher' => 1.65, 'advertiser' => 0.45],  // Barbados
            'GY' => ['publisher' => 1.50, 'advertiser' => 0.25],  // Guyana
            'SR' => ['publisher' => 1.50, 'advertiser' => 0.70],  // Suriname
            'HT' => ['publisher' => 1.45, 'advertiser' => 8.00],  // Haiti
            'PR' => ['publisher' => 2.20, 'advertiser' => 2.75],  // Puerto Rico
            
            // Tier 2/3 - Eastern Europe & Central Asia
            'RU' => ['publisher' => 2.60, 'advertiser' => 0.95],  // Russia (data not in PropellerAds, estimated)
            'UA' => ['publisher' => 2.40, 'advertiser' => 0.85],  // Ukraine (estimated)
            'BY' => ['publisher' => 2.20, 'advertiser' => 2.60],  // Belarus
            'RS' => ['publisher' => 2.10, 'advertiser' => 0.90],  // Serbia
            'BA' => ['publisher' => 2.00, 'advertiser' => 0.70],  // Bosnia and Herzegovina
            'ME' => ['publisher' => 1.90, 'advertiser' => 0.55],  // Montenegro
            'MK' => ['publisher' => 1.85, 'advertiser' => 0.15],  // North Macedonia
            'AL' => ['publisher' => 1.80, 'advertiser' => 0.65],  // Albania
            'GE' => ['publisher' => 2.00, 'advertiser' => 1.15],  // Georgia
            'AM' => ['publisher' => 1.85, 'advertiser' => 0.65],  // Armenia
            'AZ' => ['publisher' => 2.05, 'advertiser' => 1.90],  // Azerbaijan
            'KZ' => ['publisher' => 2.00, 'advertiser' => 1.05],  // Kazakhstan
            'UZ' => ['publisher' => 1.90, 'advertiser' => 3.55],  // Uzbekistan
            'KG' => ['publisher' => 1.80, 'advertiser' => 3.55],  // Kyrgyzstan
            'TJ' => ['publisher' => 1.75, 'advertiser' => 5.75],  // Tajikistan
            'TM' => ['publisher' => 1.70, 'advertiser' => 0.80],  // Turkmenistan (estimated)
            'MD' => ['publisher' => 1.80, 'advertiser' => 0.65],  // Moldova
            
            // Tier 3 - Middle East & North Africa
            'TR' => ['publisher' => 2.30, 'advertiser' => 0.50],  // Turkey
            'EG' => ['publisher' => 2.00, 'advertiser' => 0.75],  // Egypt
            'DZ' => ['publisher' => 1.80, 'advertiser' => 0.45],  // Algeria
            'MA' => ['publisher' => 2.00, 'advertiser' => 1.95],  // Morocco
            'TN' => ['publisher' => 1.75, 'advertiser' => 0.40],  // Tunisia
            'LY' => ['publisher' => 1.70, 'advertiser' => 0.25],  // Libya
            'SD' => ['publisher' => 1.60, 'advertiser' => 1.20],  // Sudan
            'SS' => ['publisher' => 1.50, 'advertiser' => 1.25],  // South Sudan
            'IQ' => ['publisher' => 1.90, 'advertiser' => 0.60],  // Iraq
            'SY' => ['publisher' => 1.70, 'advertiser' => 0.60],  // Syria
            'JO' => ['publisher' => 1.85, 'advertiser' => 0.85],  // Jordan
            'LB' => ['publisher' => 1.80, 'advertiser' => 0.45],  // Lebanon
            'PS' => ['publisher' => 1.75, 'advertiser' => 0.65],  // Palestine
            'YE' => ['publisher' => 1.60, 'advertiser' => 0.20],  // Yemen
            'IR' => ['publisher' => 1.80, 'advertiser' => 0.40],  // Iran
            'AF' => ['publisher' => 1.60, 'advertiser' => 1.25],  // Afghanistan
            
            // Tier 3 - Sub-Saharan Africa
            'ZA' => ['publisher' => 2.40, 'advertiser' => 3.25],  // South Africa
            'NG' => ['publisher' => 2.00, 'advertiser' => 2.15],  // Nigeria
            'KE' => ['publisher' => 1.90, 'advertiser' => 1.10],  // Kenya
            'GH' => ['publisher' => 1.85, 'advertiser' => 5.05],  // Ghana
            'ET' => ['publisher' => 1.70, 'advertiser' => 0.60],  // Ethiopia
            'TZ' => ['publisher' => 1.80, 'advertiser' => 1.90],  // Tanzania
            'UG' => ['publisher' => 1.75, 'advertiser' => 1.45],  // Uganda
            'AO' => ['publisher' => 1.85, 'advertiser' => 3.80],  // Angola
            'CM' => ['publisher' => 1.80, 'advertiser' => 6.85],  // Cameroon
            'CI' => ['publisher' => 1.85, 'advertiser' => 2.85],  // Ivory Coast
            'SN' => ['publisher' => 1.80, 'advertiser' => 2.15],  // Senegal
            'ZM' => ['publisher' => 1.80, 'advertiser' => 3.40],  // Zambia
            'ZW' => ['publisher' => 1.75, 'advertiser' => 5.10],  // Zimbabwe
            'BW' => ['publisher' => 1.80, 'advertiser' => 1.25],  // Botswana
            'NA' => ['publisher' => 1.80, 'advertiser' => 5.85],  // Namibia
            'MW' => ['publisher' => 1.75, 'advertiser' => 9.55],  // Malawi
            'MZ' => ['publisher' => 1.75, 'advertiser' => 13.20],  // Mozambique
            'MG' => ['publisher' => 1.70, 'advertiser' => 2.30],  // Madagascar
            'RW' => ['publisher' => 1.75, 'advertiser' => 5.65],  // Rwanda
            'BI' => ['publisher' => 1.65, 'advertiser' => 2.90],  // Burundi
            'SO' => ['publisher' => 1.70, 'advertiser' => 2.20],  // Somalia
            'ML' => ['publisher' => 1.75, 'advertiser' => 5.35],  // Mali
            'BF' => ['publisher' => 1.75, 'advertiser' => 2.15],  // Burkina Faso
            'NE' => ['publisher' => 1.65, 'advertiser' => 2.15],  // Niger
            'TD' => ['publisher' => 1.70, 'advertiser' => 3.30],  // Chad
            'MR' => ['publisher' => 1.70, 'advertiser' => 4.75],  // Mauritania
            'SL' => ['publisher' => 1.70, 'advertiser' => 5.05],  // Sierra Leone
            'LR' => ['publisher' => 1.70, 'advertiser' => 9.45],  // Liberia
            'GN' => ['publisher' => 1.75, 'advertiser' => 16.80],  // Guinea
            'GW' => ['publisher' => 1.65, 'advertiser' => 4.60],  // Guinea-Bissau
            'GM' => ['publisher' => 1.70, 'advertiser' => 1.05],  // Gambia
            'TG' => ['publisher' => 1.70, 'advertiser' => 2.10],  // Togo
            'BJ' => ['publisher' => 1.75, 'advertiser' => 7.30],  // Benin
            'CD' => ['publisher' => 1.75, 'advertiser' => 3.50],  // Democratic Republic of Congo
            'CG' => ['publisher' => 1.70, 'advertiser' => 12.20],  // Republic of Congo
            'GA' => ['publisher' => 1.75, 'advertiser' => 5.05],  // Gabon
            'GQ' => ['publisher' => 1.65, 'advertiser' => 1.50],  // Equatorial Guinea (estimated)
            'LS' => ['publisher' => 1.70, 'advertiser' => 11.35],  // Lesotho
            'SZ' => ['publisher' => 1.65, 'advertiser' => 0.75],  // Eswatini (Swaziland)
            'MU' => ['publisher' => 1.75, 'advertiser' => 0.40],  // Mauritius
            'SC' => ['publisher' => 1.70, 'advertiser' => 1.00],  // Seychelles (estimated)
            'KM' => ['publisher' => 1.65, 'advertiser' => 2.70],  // Comoros
            'DJ' => ['publisher' => 1.65, 'advertiser' => 0.90],  // Djibouti (estimated)
            'ER' => ['publisher' => 1.60, 'advertiser' => 0.85],  // Eritrea (estimated)
            'RE' => ['publisher' => 1.70, 'advertiser' => 0.85],  // Reunion (France overseas)
            
            // Tier 3 - Pacific Islands
            'PG' => ['publisher' => 1.60, 'advertiser' => 0.50],  // Papua New Guinea
            'FJ' => ['publisher' => 1.65, 'advertiser' => 0.95],  // Fiji
            'MV' => ['publisher' => 1.65, 'advertiser' => 0.25],  // Maldives
            'WS' => ['publisher' => 1.50, 'advertiser' => 0.80],  // Samoa (estimated)
            'TO' => ['publisher' => 1.50, 'advertiser' => 0.75],  // Tonga (estimated)
            'VU' => ['publisher' => 1.50, 'advertiser' => 0.70],  // Vanuatu (estimated)
            'SB' => ['publisher' => 1.45, 'advertiser' => 0.65],  // Solomon Islands (estimated)
            
            // Tier 3 - Caribbean
            'AG' => ['publisher' => 1.65, 'advertiser' => 1.05],  // Antigua and Barbuda
            'LC' => ['publisher' => 1.60, 'advertiser' => 0.90],  // Saint Lucia (estimated)
            'VC' => ['publisher' => 1.60, 'advertiser' => 0.85],  // Saint Vincent and the Grenadines (estimated)
            'GD' => ['publisher' => 1.60, 'advertiser' => 0.85],  // Grenada (estimated)
            'DM' => ['publisher' => 1.55, 'advertiser' => 0.80],  // Dominica (estimated)
            'KN' => ['publisher' => 1.60, 'advertiser' => 0.90],  // Saint Kitts and Nevis (estimated)
            
            // Europe - Microstates
            'LU' => ['publisher' => 5.20, 'advertiser' => 1.25],  // Luxembourg
            'MT' => ['publisher' => 3.40, 'advertiser' => 1.20],  // Malta
            'CY' => ['publisher' => 3.00, 'advertiser' => 2.05],  // Cyprus
            'IS' => ['publisher' => 4.80, 'advertiser' => 3.05],  // Iceland
            'LI' => ['publisher' => 5.50, 'advertiser' => 2.00],  // Liechtenstein (estimated)
            'MC' => ['publisher' => 5.80, 'advertiser' => 2.20],  // Monaco (estimated)
            'SM' => ['publisher' => 4.40, 'advertiser' => 1.60],  // San Marino (estimated)
            'VA' => ['publisher' => 4.50, 'advertiser' => 1.65],  // Vatican City (estimated)
            'AD' => ['publisher' => 4.20, 'advertiser' => 1.50],  // Andorra (estimated)
            
            // Additional countries from PropellerAds data
            'MQ' => ['publisher' => 1.75, 'advertiser' => 5.70],  // Martinique
            'GP' => ['publisher' => 1.70, 'advertiser' => 0.75],  // Guadeloupe
        ];

        foreach ($rates as $isoCode => $rateData) {
            $country = Country::where('iso_code', $isoCode)->first();
            
            if ($country) {
                CpmRate::updateOrCreate(
                    ['country_id' => $country->id],
                    [
                        'publisher_rate' => $rateData['publisher'],
                        'advertiser_rate' => $rateData['advertiser'],
                    ]
                );
                
                $this->command->info("✓ {$country->name} ({$isoCode}): Publisher \${$rateData['publisher']}, Advertiser \${$rateData['advertiser']}");
            } else {
                $this->command->warn("✗ Country with ISO code '{$isoCode}' not found in database");
            }
        }

        $this->command->info("\n" . count($rates) . " countries updated with CPM rates.");
    }
}
