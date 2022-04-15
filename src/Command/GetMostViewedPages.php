<?php

namespace D4rk0s\WpMatomoAPI\Command;

use D4rk0s\WpMatomoAPI\Toolbox\MatomoToolbox;
use D4rk0s\WpMatomoAPI\WpMatomoAPI;
use DateTime;
use Roots\WPConfig\Config;
use WP_CLI;

class GetMostViewedPages
{
    use MatomoToolbox;

    public static function runCommand()
    {
        // Il faut boucler sur l'ensemble des articles et récupérer le post-name
        // Ca sera la clef à faire matcher avec la colonne label du retour de matomo.
        // Il faut vérifier aussi si il nous faut la même chose.
        // Une fois que l'on a pris les 8 premiers,

        // https://matomo.youmatter.world/?module=API&filter_limit=50&idSubtable=336&showColumns=nb_visits&expanded=0&method=Actions.getPageUrls&idSite=1&period=year&date=yesterday&format=JSON&token_auth=6746d778730db1d260ca40fb6250a9bb&filter_sort_column=nb_visits&filter_sort_order=desc
        $postData = [
            'filter_limit' => 50,
            'idSubtable' => 336,
            'showColumns' => 'nb_visits',
            'expanded' => 0,
            'method' => 'Actions.getPageUrls',
            'idSite' => 1,
            'period' => 'year',
            'date'=> 'yesterday',
            'filter_sort_column' => 'nb_visits',
            'filter_sort_order' => 'desc'
        ];

        $jsonResponse = self::executeQuery($postData);
        var_dump($jsonResponse); die;
        update_site_option(WpMatomoAPI::CURRENT_YEAR_VISIT_OPTION_LABEL, $currentYearVisitsStats['nb_visits']);

        WP_CLI::success('Mise à jour réalisée');
    }
}