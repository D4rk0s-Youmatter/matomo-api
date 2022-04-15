<?php

namespace D4rk0s\WpMatomoAPI\Command;

use D4rk0s\WpMatomoAPI\Toolbox\MatomoToolbox;
use D4rk0s\WpMatomoAPI\WpMatomoAPI;
use DateTime;
use Roots\WPConfig\Config;
use WP_CLI;

class UpdateStatsFromMatomo
{

    use MatomoToolbox;

    public function runCommand()
    {
        $currentYear = (new DateTime())->format('Y');
        $postData = [
            'method' => 'VisitsSummary.get',
            'idSite' => 1,
            'period' => 'year',
        ];

        $postData['date'] = "01/01/$currentYear";
        $currentYearVisitsStats = $this->executeQuery($postData);
        update_site_option(WpMatomoAPI::CURRENT_YEAR_VISIT_OPTION_LABEL, $currentYearVisitsStats['nb_visits']);

        WP_CLI::success('Mise à jour réalisée');
    }
}