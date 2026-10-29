<?php

/**
 * Single source of truth for the motorcycle-group identity.
 * Rename the whole group or adjust brand accent colours here only —
 * Blade and CSS consume these values, so nothing else needs editing.
 */
return [

    // Group display name (placeholder, easy to rename).
    'name' => 'APEX MOTO GROUP',

    // Short tagline used across hero / footer / meta.
    'tagline' => 'FUELLED BY YOUR CONVICTION',

    // Map each brand slug to a design accent.
    // The hero re-themes the whole page to the active brand's accent.
    'accents' => [
        'neo'      => '#1ee05a', // Urban Mobility  – electric green (default)
        'atlas'    => '#f59e0b', // Adventure      – amber
        'vayu'     => '#ef4444', // Performance     – red
        'aurum'    => '#d4af37', // Premium         – gold
    ],

    // Default accent used before a brand is selected.
    'default_accent' => '#1ee05a',

    // Secondary warm highlight, used sparingly.
    'secondary_highlight' => '#f59e0b',
];
