<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

    public function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {
        $file = str_replace('.php', '', $langfile);
        if ($add_suffix === TRUE) {
            $file = preg_replace('/_lang$/', '', $file) . '_lang';
        }
        $file .= '.php';

        if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom)) {
            $config =& get_config();
            $idiom = empty($config['language']) ? 'english' : $config['language'];
        }

        if ($idiom !== 'english') {
            // First load english version as base fallback
            parent::load($langfile, 'english', $return, $add_suffix, $alt_path);
            
            // Check if the targeted translation file physically exists
            $found = false;
            if (file_exists(BASEPATH . 'language/' . $idiom . '/' . $file)) {
                $found = true;
            }
            
            if (!$found && $alt_path !== '') {
                if (file_exists($alt_path . 'language/' . $idiom . '/' . $file)) {
                    $found = true;
                }
            }
            
            if (!$found) {
                $CI =& get_instance();
                if ($CI) {
                    foreach ($CI->load->get_package_paths(TRUE) as $package_path) {
                        if (file_exists($package_path . 'language/' . $idiom . '/' . $file)) {
                            $found = true;
                            break;
                        }
                    }
                }
            }
            
            // If the translation file does not exist for this language,
            // skip lang->load() to prevent a CI crash/warning.
            // The English base strings (loaded above) act as the fallback.
            if (!$found) {
                if ($return === TRUE) {
                    return $this->language;
                }
                return;
            }
        }

        return parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
    }
}
