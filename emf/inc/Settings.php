<?php
/**
 * Handels the Settings page in admin.
 * 
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 */
namespace Fromexo\Emf;

defined('WPINC') or die;

class Settings
{
    protected $optionGroup;
    protected $optionName;
    protected $page;

    public function __construct($optionGroup, $optionName, $page)
    {
        $this->optionGroup = $optionGroup;
        $this->optionName = $optionName;
        $this->page = $page;
    }

    public function hook()
    {
        add_action('admin_menu', [$this, 'registerPage']);
        add_action('admin_init', [$this, 'registerOptions'] );
    }
    
    public function unregister()
    {
        unregister_setting($this->optionGroup, $this->optionName);
    }

    public function registerOptions()
    {
        register_setting($this->optionGroup, $this->optionName, [$this, 'validate']);

        $apiSection = $this->optionName.'_api_section';
        add_settings_section($apiSection, 'Ebas API', [$this, 'apiDescText'], $this->page);

        add_settings_field($this->optionName.'_api_key', 'API Key', [$this, 'inputField'], $this->page, $apiSection, ['key' => 'api_key']);
        add_settings_field($this->optionName.'_fid', 'F-id', [$this, 'inputField'], $this->page, $apiSection, ['key' => 'fid', 'placeholder'=>'Fxxxxxx-x']);

        $mailSection = $this->optionName.'_mail_section';
        add_settings_section($mailSection, 'MailGun', [$this, 'mailDescText'], $this->page);

        
        add_settings_field($this->optionName.'_mg_api_key', 'API Key', [$this, 'inputField'], $this->page, $mailSection, ['key' => 'mg_api_key']);
        add_settings_field($this->optionName.'_mg_domain', 'Domain', [$this, 'inputField'], $this->page, $mailSection, ['key' => 'mg_domain']);
        add_settings_field($this->optionName.'_mg_subject', 'Subject', [$this, 'inputField'], $this->page, $mailSection, ['key' => 'mg_subject']);
        add_settings_field($this->optionName.'_mg_from', 'From', [$this, 'inputField'], $this->page, $mailSection, ['key' => 'mg_from']);
        add_settings_field($this->optionName.'_mg_template', 'Template', [$this, 'inputField'], $this->page, $mailSection, ['key' => 'mg_template']);


        $termsSection = $this->optionName.'_term_section';
        add_settings_section($termsSection, 'Stadgar & Pul', [$this, 'termDescText'], $this->page);

        add_settings_field($this->optionName.'_statues', 'Statues', [$this, 'inputField'], $this->page, $termsSection, ['key' => 'statues', 'placeholder'=>'https://']);
        add_settings_field($this->optionName.'_pul', 'Pul', [$this, 'inputField'], $this->page, $termsSection, ['key' => 'pul', 'placeholder'=>'https://']);
    }
    
    public function apiDescText()
    {
        echo '';
    }

    public function mailDescText()
    {
        echo '';
    }

    public function termDescText()
    {
        echo '';
    }

    public function registerPage()
    {
        add_options_page(
            'Ebas Member Forms', // string $page_title,
            'Ebas Member Forms', // string $menu_title,
            'install_plugins', // string $capability,
            'ebas-member-forms', // string $menu_slug,
            [$this, 'renderPage'], // callable $function = '',
            null // int $position = null
        );
    }

    public function renderPage()
    {
        echo '<div class="wrap"><h1>Settings</h1><form action="options.php" method="POST">';
        settings_fields($this->optionGroup);
        do_settings_sections($this->page);
        echo '<input type="submit" name="submit" class="button button-primary" value="' . esc_attr('Save') . '"></form></div>';
    }

    public function inputField($args)
    {
        $options = get_option($this->optionGroup);
        $name = $this->optionGroup . '[' . $args['key'] . ']';
        $value = esc_attr($options[$args['key']]);
        $placeholder = isset($args['placeholder']) ? ' placeholder="'.esc_attr($args['placeholder']).'"' : null ;
        echo '<input type="text" class="regular-text ltr" name="'.$name.'" id="ef_plugin_setting_'.$args['key'].'" value="'.$value.'"'.$placeholder.'>';
    }

    public function validate($input)
    {
        return $input;
    }

}