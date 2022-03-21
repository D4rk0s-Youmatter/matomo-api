<?php

namespace D4rk0s\WpMatomoAPI\Command;

use D4rk0s\WpMatomoAPI\WpMatomoAPI;
use DateTime;
use Roots\WPConfig\Config;
use WP_CLI;

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
        $nbVisits = (int) $currentYearVisitsStats['nb_visits'];

	update_site_option(WpMatomoAPI::CURRENT_YEAR_VISIT_OPTION_LABEL, (int) $currentYear === 2022 ? $nbVisits + 800000 : $nbVisits);
        if(get_site_option(WpMatomoAPI::PREVIOUS_YEAR_VISIT_OPTION_LABEL)) {
            $postData['date'] = "01/01/$previousYear";
            $previousYearVisitsStats = $this->executeQuery($postData, $url);
            update_site_option(WpMatomoAPI::PREVIOUS_YEAR_VISIT_OPTION_LABEL, $previousYearVisitsStats['nb_visits']);
        }

        WP_CLI::success('Mise à jour réalisée');
    }

    /**
     * @todo: Probleme de sécurité avec le SSL.
     * @param array $postData
     * @param string $url
     * @throws \JsonException
     * @return array
     */
    private function executeQuery(array $postData, string $url) : array
    {
        $opts = array(
            'http' =>
            [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($postData)
            ],
            'ssl' => [
                'verify_peer' => false
            ]
        );

        $context = stream_context_create($opts);

        return json_decode(file_get_contents($url, false, $context), true, 512, JSON_THROW_ON_ERROR);
    }
}
