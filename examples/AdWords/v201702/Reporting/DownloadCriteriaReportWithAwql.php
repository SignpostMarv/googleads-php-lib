<?php
/**
 * Copyright 2017 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\AdsApi\Examples\AdWords\v201702\Reporting;

require '../../../../vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201702\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201702\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * Downloads CRITERIA_PERFORMANCE_REPORT for the specified client customer ID.
 */
class DownloadCriteriaReportWithAwql {

  public static function runExample(AdWordsSession $session, $reportFormat) {
    // Create report query to get the data for last 7 days.
    $reportQuery = 'SELECT CampaignId, AdGroupId, Id, Criteria, CriteriaType, '
        . 'Impressions, Clicks, Cost FROM CRITERIA_PERFORMANCE_REPORT '
        . 'WHERE Status IN [ENABLED, PAUSED] DURING LAST_7_DAYS';

    // Download report as a string.
    $reportDownloader = new ReportDownloader($session);
    $reportDownloadResult = $reportDownloader->downloadReportWithAwql(
        $reportQuery, $reportFormat);
    print "Report was downloaded and printed below:\n";
    print $reportDownloadResult->getAsString();
  }

  public static function main() {
    // Generate a refreshable OAuth2 credential for authentication.
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // See: ReportSettingsBuilder for more options (e.g., suppress headers)
    // or set them in your adsapi_php.ini file.
    $reportSettings = (new ReportSettingsBuilder())
        ->fromFile()
        ->includeZeroImpressions(false)
        ->build();

    // See: AdWordsSessionBuilder for setting a client customer ID that is
    // different from that specified in your adsapi_php.ini file.
    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
        ->withReportSettings($reportSettings)
        ->build();

    self::runExample($session, DownloadFormat::CSV);
  }
}

DownloadCriteriaReportWithAwql::main();
