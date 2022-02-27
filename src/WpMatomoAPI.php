<?php

namespace D4rk0s\WpMatomoAPI;

use D4rk0s\WpMatomoAPI\Command\UpdateStatsFromMatomo;
use WP_CLI;

class WpMatomoAPI
{
    public const CURRENT_YEAR_VISIT_OPTION_LABEL = 'current_year_visits';
    public const PREVIOUS_YEAR_VISIT_OPTION_LABEL = 'previous_year_visits';

    public static function pluginActivationSequence() : void
    {
        add_option(self::CURRENT_YEAR_VISIT_OPTION_LABEL);
        add_option(self::PREVIOUS_YEAR_VISIT_OPTION_LABEL);
    }

    public static function pluginDeactivationSequence() : void
    {
        delete_option(self::CURRENT_YEAR_VISIT_OPTION_LABEL);
        delete_option(self::PREVIOUS_YEAR_VISIT_OPTION_LABEL);
    }

    public static function registerCommand()
    {
        WP_CLI::add_command('matomo_update_visit_stats', [UpdateStatsFromMatomo::class, 'runCommand']);
    }
}