<?php

/*
  Plugin Name: WooCommerce Greek Regions
  Plugin URI: https://www.webdesires.gr
  Description: Αντικαθιστά τις προεπιλεγμένες περιφέρειες του WooCommerce με τους νομούς της Ελλάδας.
  Version: 1.0
  Author: Webdesires
  Author URI: https://www.webdesires.gr
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  WC tested up to: 9.5.2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Αντικαθιστά τις περιφέρειες με τους νομούς σε σωστή αλφαβητική σειρά
add_filter('woocommerce_states', 'replace_woocommerce_greek_regions');

function replace_woocommerce_greek_regions($states) {
    $regions = array(
        'AGR' => 'Αιτωλοακαρνανίας',
        'ACH' => 'Αχαΐας',
        'ARG' => 'Αργολίδας',
        'ARK' => 'Αρκαδίας',
        'ART' => 'Άρτας',
        'ATT' => 'Αττικής',
        'DRM' => 'Δράμας',
        'EVR' => 'Έβρου',
        'EVO' => 'Εύβοιας',
        'ZAK' => 'Ζακύνθου',
        'GRE' => 'Γρεβενών',
        'THS' => 'Θεσσαλονίκης',
        'THP' => 'Θεσπρωτίας',
        'IOA' => 'Ιωαννίνων',
        'KAV' => 'Καβάλας',
        'KAS' => 'Καστοριάς',
        'KZR' => 'Κοζάνης',
        'KOR' => 'Κορινθίας',
        'KIL' => 'Κιλκίς',
        'KER' => 'Κέρκυρας',
        'KFF' => 'Κεφαλληνίας',
        'LAS' => 'Λακωνίας',
        'LAR' => 'Λάρισας',
        'LFT' => 'Λευκάδας',
        'LES' => 'Λέσβου',
        'MAG' => 'Μαγνησίας',
        'MESS' => 'Μεσσηνίας',
        'PIE' => 'Πιερίας',
        'PRV' => 'Πρέβεζας',
        'RHO' => 'Ρόδου',
        'SER' => 'Σερρών',
        'CHAN' => 'Χανίων',
        'CHI' => 'Χίου',
        'FTH' => 'Φθιώτιδας',
        'FLR' => 'Φλώρινας',
        'TRK' => 'Τρικάλων',
        'CYL' => 'Κυκλάδων',
        'DKA' => 'Δωδεκανήσου',
        'SAM' => 'Σάμου',
        'RTH' => 'Ρεθύμνου',
        'HER' => 'Ηρακλείου',
        'LASI' => 'Λασιθίου',
        'KRD' => 'Καρδίτσας',
    );

    // Χρησιμοποιούμε το Collator για σωστή αλφαβητική σειρά στην ελληνική γλώσσα
    if (class_exists('Collator')) {
        $collator = new Collator('el_GR');
        $collator->asort($regions);
    } else {
        asort($regions); // Αν δεν υπάρχει η επέκταση Intl, fallback σε απλή ταξινόμηση
    }

    $states['GR'] = $regions;

    return $states;
}

// Αλλάζει την ετικέτα στο checkout σε "Νομός" και το κάνει υποχρεωτικό
add_filter('woocommerce_default_address_fields', 'change_checkout_state_label_and_required');

function change_checkout_state_label_and_required($fields) {
    if (isset($fields['state'])) {
        $fields['state']['label'] = 'Νομός';
        $fields['state']['placeholder'] = 'Επιλέξτε Νομό';
        $fields['state']['required'] = true; // Το πεδίο γίνεται υποχρεωτικό
    }

    return $fields;
}

// Καθαρίζει την ένδειξη "(προαιρετικό)" από τα υποχρεωτικά πεδία
add_action('wp_footer', 'remove_optional_label_from_required_fields');

function remove_optional_label_from_required_fields() {
    if (is_checkout()) : ?>
        <script>
            jQuery(document).ready(function ($) {
                // Αφαιρεί την ένδειξη "(προαιρετικό)" από τις υποχρεωτικές ετικέτες
                $('label.required span.optional').remove();
            });
        </script>
    <?php
    endif;
}