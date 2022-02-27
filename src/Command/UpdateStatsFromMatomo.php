<?php

namespace D4rk0s\WpMatomoAPI\Command;

use D4rk0s\WpMatomoAPI\WpMatomoAPI;
use DateTime;
use Roots\WPConfig\Config;

class UpdateStatsFromMatomo
{

    public function runCommand()
    {
        $url = Config::get("MATOMO_URL");
        $currentYear = (new DateTime())->format('Y');
        $previousYear = (int) $currentYear - 1;
        $postData = [
            'module' => 'API',
            'method' => 'VisitsSummary.get',
            'idSite' => 1,
            'period' => 'year',
            'format' => 'JSON',
            'token_auth' => Config::get("MATOMO_API_KEY")
        ];

        $postData['date'] = "01/01/$currentYear";
        $currentYearVisitsStats = $this->executeQuery($postData, $url);
        update_option(WpMatomoAPI::CURRENT_YEAR_VISIT_OPTION_LABEL, $currentYearVisitsStats['nb_visits']);

        if(get_option(WpMatomoAPI::PREVIOUS_YEAR_VISIT_OPTION_LABEL)) {
            $postData['date'] = "01/01/$previousYear";
            $previousYearVisitsStats = $this->executeQuery($postData, $url);
            update_option(WpMatomoAPI::PREVIOUS_YEAR_VISIT_OPTION_LABEL, $previousYearVisitsStats['nb_visits']);
        }
    }

    private function executeQuery(array $postData, string $url) : array
    {
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($postData)
            )
        );

        $context = stream_context_create($opts);

        return json_decode(file_get_contents($url, false, $context), true, 512, JSON_THROW_ON_ERROR);
    }
}