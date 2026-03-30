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
     * Publisher rates based on competitor analysis (cuty.io, exe.io, ouo.io averages)
     * and real advertising market data (PropellerAds, Adsterra, HilltopAds).
     *
     * Strategy: Competitive with cuty.io/exe.io mid-point, sustainable margins.
     * Publisher rate = ~65% of advertiser rate (platform keeps ~35% margin).
     *
     * Tiers:
     *   Tier 1 (Premium):    $8.00 - $16.00 publisher
     *   Tier 2 (Mid):        $3.50 - $7.50 publisher
     *   Tier 3 (Developing): $1.50 - $3.50 publisher
     *   Tier 4 (Default):    $1.00 - $1.50 publisher
     */
    public function run(): void
    {
        // [ISO Code => ['publisher' => X.XX, 'advertiser' => Y.YY]]
        // publisher_rate: what we pay the link creator per 1000 views
        // advertiser_rate: what advertisers pay us per 1000 impressions
        $rates = [

            // ════════════════════════════════════════════════
            // TIER 1 — Premium English-Speaking & Nordic Markets
            // ════════════════════════════════════════════════
            'US' => ['publisher' => 10.00, 'advertiser' => 15.00],  // United States
            'CA' => ['publisher' => 8.50,  'advertiser' => 12.50],  // Canada
            'GB' => ['publisher' => 9.00,  'advertiser' => 13.00],  // United Kingdom
            'AU' => ['publisher' => 8.00,  'advertiser' => 11.50],  // Australia
            'NZ' => ['publisher' => 6.50,  'advertiser' => 9.50],   // New Zealand
            'IE' => ['publisher' => 6.00,  'advertiser' => 8.50],   // Ireland

            // Nordics
            'NO' => ['publisher' => 7.50,  'advertiser' => 11.00],  // Norway
            'SE' => ['publisher' => 6.50,  'advertiser' => 9.50],   // Sweden
            'DK' => ['publisher' => 6.50,  'advertiser' => 9.50],   // Denmark
            'FI' => ['publisher' => 6.00,  'advertiser' => 8.50],   // Finland
            'IS' => ['publisher' => 5.50,  'advertiser' => 8.00],   // Iceland

            // Western Europe (Core)
            'CH' => ['publisher' => 7.00,  'advertiser' => 10.50],  // Switzerland
            'DE' => ['publisher' => 7.50,  'advertiser' => 11.00],  // Germany
            'NL' => ['publisher' => 6.50,  'advertiser' => 9.50],   // Netherlands
            'BE' => ['publisher' => 5.50,  'advertiser' => 8.00],   // Belgium
            'AT' => ['publisher' => 5.50,  'advertiser' => 8.00],   // Austria
            'LU' => ['publisher' => 6.00,  'advertiser' => 8.50],   // Luxembourg
            'LI' => ['publisher' => 6.00,  'advertiser' => 8.50],   // Liechtenstein

            // ════════════════════════════════════════════════
            // TIER 2 — Western Europe, Gulf, Developed Asia
            // ════════════════════════════════════════════════
            'FR' => ['publisher' => 5.50,  'advertiser' => 8.00],   // France
            'ES' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Spain
            'IT' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Italy
            'PT' => ['publisher' => 3.80,  'advertiser' => 5.50],   // Portugal
            'GR' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Greece
            'MT' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Malta
            'CY' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Cyprus
            'MC' => ['publisher' => 5.00,  'advertiser' => 7.50],   // Monaco
            'SM' => ['publisher' => 4.00,  'advertiser' => 6.00],   // San Marino
            'VA' => ['publisher' => 4.00,  'advertiser' => 6.00],   // Vatican City
            'AD' => ['publisher' => 3.80,  'advertiser' => 5.50],   // Andorra

            // Gulf / Middle East (Wealthy)
            'AE' => ['publisher' => 5.50,  'advertiser' => 8.00],   // UAE
            'SA' => ['publisher' => 5.00,  'advertiser' => 7.50],   // Saudi Arabia
            'QA' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Qatar
            'KW' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Kuwait
            'BH' => ['publisher' => 3.80,  'advertiser' => 5.50],   // Bahrain
            'OM' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Oman
            'IL' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Israel

            // Developed Asia
            'JP' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Japan
            'KR' => ['publisher' => 4.00,  'advertiser' => 6.00],   // South Korea
            'SG' => ['publisher' => 5.50,  'advertiser' => 8.00],   // Singapore
            'HK' => ['publisher' => 4.50,  'advertiser' => 6.50],   // Hong Kong
            'TW' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Taiwan

            // Eastern Europe (EU Members)
            'PL' => ['publisher' => 3.80,  'advertiser' => 5.50],   // Poland
            'CZ' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Czech Republic
            'HU' => ['publisher' => 3.20,  'advertiser' => 4.50],   // Hungary
            'RO' => ['publisher' => 3.00,  'advertiser' => 4.50],   // Romania
            'BG' => ['publisher' => 3.00,  'advertiser' => 4.50],   // Bulgaria
            'HR' => ['publisher' => 3.20,  'advertiser' => 4.50],   // Croatia
            'SI' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Slovenia
            'SK' => ['publisher' => 3.20,  'advertiser' => 4.50],   // Slovakia
            'EE' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Estonia
            'LV' => ['publisher' => 3.20,  'advertiser' => 4.50],   // Latvia
            'LT' => ['publisher' => 3.20,  'advertiser' => 4.50],   // Lithuania

            // ════════════════════════════════════════════════
            // TIER 2/3 — Latin America, Southeast Asia, Other
            // ════════════════════════════════════════════════

            // Latin America
            'MX' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Mexico
            'BR' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Brazil
            'AR' => ['publisher' => 2.50,  'advertiser' => 3.50],   // Argentina
            'CL' => ['publisher' => 2.80,  'advertiser' => 4.00],   // Chile
            'CO' => ['publisher' => 2.50,  'advertiser' => 3.50],   // Colombia
            'PE' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Peru
            'VE' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Venezuela
            'EC' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Ecuador
            'BO' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Bolivia
            'PY' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Paraguay
            'UY' => ['publisher' => 2.50,  'advertiser' => 3.50],   // Uruguay
            'CR' => ['publisher' => 2.50,  'advertiser' => 3.50],   // Costa Rica
            'PA' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Panama
            'GT' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Guatemala
            'SV' => ['publisher' => 1.80,  'advertiser' => 2.80],   // El Salvador
            'HN' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Honduras
            'NI' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Nicaragua
            'CU' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Cuba
            'DO' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Dominican Republic
            'JM' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Jamaica
            'TT' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Trinidad and Tobago
            'BS' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Bahamas
            'BB' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Barbados
            'GY' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Guyana
            'SR' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Suriname
            'HT' => ['publisher' => 1.50,  'advertiser' => 2.50],   // Haiti
            'PR' => ['publisher' => 3.50,  'advertiser' => 5.00],   // Puerto Rico (US territory)

            // Southeast Asia
            'TH' => ['publisher' => 3.20,  'advertiser' => 4.80],   // Thailand
            'MY' => ['publisher' => 3.00,  'advertiser' => 4.50],   // Malaysia
            'ID' => ['publisher' => 2.80,  'advertiser' => 4.00],   // Indonesia
            'PH' => ['publisher' => 2.80,  'advertiser' => 4.00],   // Philippines
            'VN' => ['publisher' => 2.50,  'advertiser' => 3.80],   // Vietnam
            'BN' => ['publisher' => 2.50,  'advertiser' => 3.80],   // Brunei
            'MM' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Myanmar
            'KH' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Cambodia
            'LA' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Laos
            'TL' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Timor-Leste

            // South Asia
            'IN' => ['publisher' => 2.20,  'advertiser' => 3.20],   // India
            'PK' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Pakistan
            'BD' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Bangladesh
            'LK' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Sri Lanka
            'NP' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Nepal
            'MV' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Maldives
            'BT' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Bhutan

            // East Asia
            'CN' => ['publisher' => 2.80,  'advertiser' => 4.00],   // China
            'MN' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Mongolia
            'MO' => ['publisher' => 3.00,  'advertiser' => 4.50],   // Macau

            // ════════════════════════════════════════════════
            // TIER 2/3 — Eastern Europe & Central Asia
            // ════════════════════════════════════════════════
            'RU' => ['publisher' => 2.50,  'advertiser' => 3.80],   // Russia
            'UA' => ['publisher' => 2.50,  'advertiser' => 3.80],   // Ukraine
            'BY' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Belarus
            'RS' => ['publisher' => 2.50,  'advertiser' => 3.80],   // Serbia
            'BA' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Bosnia
            'ME' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Montenegro
            'MK' => ['publisher' => 2.00,  'advertiser' => 3.00],   // North Macedonia
            'AL' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Albania
            'GE' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Georgia
            'AM' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Armenia
            'AZ' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Azerbaijan
            'MD' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Moldova
            'KZ' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Kazakhstan
            'UZ' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Uzbekistan
            'KG' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Kyrgyzstan
            'TJ' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Tajikistan
            'TM' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Turkmenistan

            // ════════════════════════════════════════════════
            // TIER 3 — Middle East & North Africa
            // ════════════════════════════════════════════════
            'TR' => ['publisher' => 3.00,  'advertiser' => 4.50],   // Turkey
            'EG' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Egypt
            'DZ' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Algeria
            'MA' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Morocco
            'TN' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Tunisia
            'LY' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Libya
            'SD' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Sudan
            'SS' => ['publisher' => 1.50,  'advertiser' => 2.20],   // South Sudan
            'IQ' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Iraq
            'SY' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Syria
            'JO' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Jordan
            'LB' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Lebanon
            'PS' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Palestine
            'YE' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Yemen
            'IR' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Iran
            'AF' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Afghanistan

            // ════════════════════════════════════════════════
            // TIER 3 — Sub-Saharan Africa
            // ════════════════════════════════════════════════
            'ZA' => ['publisher' => 2.80,  'advertiser' => 4.00],   // South Africa
            'NG' => ['publisher' => 2.20,  'advertiser' => 3.20],   // Nigeria
            'KE' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Kenya
            'GH' => ['publisher' => 2.00,  'advertiser' => 3.00],   // Ghana
            'ET' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Ethiopia
            'TZ' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Tanzania
            'UG' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Uganda
            'AO' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Angola
            'CM' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Cameroon
            'CI' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Ivory Coast
            'SN' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Senegal
            'ZM' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Zambia
            'ZW' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Zimbabwe
            'BW' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Botswana
            'NA' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Namibia
            'MW' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Malawi
            'MZ' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Mozambique
            'MG' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Madagascar
            'RW' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Rwanda
            'BI' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Burundi
            'SO' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Somalia
            'ML' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Mali
            'BF' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Burkina Faso
            'NE' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Niger
            'TD' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Chad
            'MR' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Mauritania
            'SL' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Sierra Leone
            'LR' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Liberia
            'GN' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Guinea
            'GW' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Guinea-Bissau
            'GM' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Gambia
            'TG' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Togo
            'BJ' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Benin
            'CD' => ['publisher' => 1.60,  'advertiser' => 2.50],   // DR Congo
            'CG' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Republic of Congo
            'GA' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Gabon
            'GQ' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Equatorial Guinea
            'LS' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Lesotho
            'SZ' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Eswatini
            'MU' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Mauritius
            'SC' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Seychelles
            'KM' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Comoros
            'DJ' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Djibouti
            'ER' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Eritrea
            'RE' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Reunion
            'CV' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Cape Verde
            'ST' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Sao Tome
            'CF' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Central African Republic

            // ════════════════════════════════════════════════
            // Pacific Islands & Microstates
            // ════════════════════════════════════════════════
            'PG' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Papua New Guinea
            'FJ' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Fiji
            'WS' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Samoa
            'TO' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Tonga
            'VU' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Vanuatu
            'SB' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Solomon Islands
            'KI' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Kiribati
            'TV' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Tuvalu
            'NR' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Nauru
            'PW' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Palau
            'FM' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Micronesia
            'MH' => ['publisher' => 1.50,  'advertiser' => 2.20],   // Marshall Islands

            // Caribbean microstates
            'AG' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Antigua and Barbuda
            'LC' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Saint Lucia
            'VC' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Saint Vincent
            'GD' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Grenada
            'DM' => ['publisher' => 1.60,  'advertiser' => 2.50],   // Dominica
            'KN' => ['publisher' => 1.70,  'advertiser' => 2.60],   // Saint Kitts and Nevis

            // French Overseas
            'MQ' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Martinique
            'GP' => ['publisher' => 1.80,  'advertiser' => 2.80],   // Guadeloupe
        ];

        $updated = 0;
        $notFound = 0;

        foreach ($rates as $isoCode => $rateData) {
            // Try both the given ISO code and common variants
            $country = Country::where('iso_code', $isoCode)->first();

            // Handle UK: database uses 'GB' but seeder may have 'UK'
            if (!$country && $isoCode === 'UK') {
                $country = Country::where('iso_code', 'GB')->first();
            }

            if ($country) {
                CpmRate::updateOrCreate(
                    ['country_id' => $country->id],
                    [
                        'publisher_rate'  => $rateData['publisher'],
                        'advertiser_rate' => $rateData['advertiser'],
                    ]
                );
                $updated++;
                $this->command->info("✓ {$country->name} ({$isoCode}): Publisher \${$rateData['publisher']}, Advertiser \${$rateData['advertiser']}");
            } else {
                $notFound++;
                $this->command->warn("✗ Country with ISO code '{$isoCode}' not found in database");
            }
        }

        $this->command->info("\n✅ Done! {$updated} countries updated, {$notFound} not found.");
        $this->command->info("📊 Rate structure: US \$10.00 | UK/DE \$7.50-9.00 | TR \$3.00 | IN \$2.20 | Default \$1.50");
    }
}
