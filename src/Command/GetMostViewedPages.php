<?php

namespace D4rk0s\WpMatomoAPI\Command;

use D4rk0s\WpMatomoAPI\Toolbox\MatomoToolbox;
use D4rk0s\WpMatomoAPI\WpMatomoAPI;
use WP_CLI;

class GetMostViewedPages
{
    use MatomoToolbox;

    public static function runCommand()
    {
        global $wpdb;

        $postData = [
            'filter_limit' => 15,
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

        // Traitement des résultats
        $pagesViews = [];
        $pageLabels = [];
        foreach($jsonResponse as $page) {
            $pagesViews[] = [
                'label' => $page['label'],
                'nb_visits'=> (int) $page['nb_visits']
            ];
            $pageLabels[] = $page['label'];
        }

        // Récupération des articles dans la base
        switch_to_blog(3);
        $dbResults = $wpdb->get_results("SELECT ID, post_name from ".$wpdb->posts." WHERE post_name IN ('".implode('\',\'',$pageLabels)."')");
        $pageNamedOrderedResults = [];
        foreach($dbResults as $dbResult) {
            $pageNamedOrderedResults[$dbResult->post_name] = (int) $dbResult->ID;
        }

        // Retourne les ids dans l'ordre
        $finalResult = [];
        foreach($pagesViews as $data) {
            if(!isset($pageNamedOrderedResults[$data['label']])) {
                continue;
            }
            $finalResult[] = $pageNamedOrderedResults[$data['label']];
        }

        update_site_option(WpMatomoAPI::MOST_READ_ARTICLES_FR, $finalResult);

        WP_CLI::success('Récupération des articles les plus lus terminé');
    }
}