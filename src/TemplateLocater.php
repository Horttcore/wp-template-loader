<?php
/**
 * Template loader
 */
namespace Horttcore\TemplateLoader;


/**
 * Template file locater
 */
class TemplateLocator
{


    /**
     * Template types
     *
     * @var array
     */
    const TEMPLATES = [
        'index',
        '404',
        'archive',
        'author',
        'category',
        'tag',
        'taxonomy',
        'date',
        'embed',
        'home',
        'frontpage',
        'page',
        'paged',
        'search',
        'single',
        'singular',
        'attachment'
    ];


    /**
     * Add WordPress hooks
     *
     * @param string $directory Directory to check for template files
     */
    public function __construct( $directory )
    {
        $this->directory = $directory;
    }


    /**
     * Prefix template folder
     *
     * @param array $templates Template files
     *
     * @return array Template files
     */
    public function hierarchy(array $templates): array
    {
        array_walk(
            $templates,
            function (&$item, $key) {
                $item = trailingslashit($this->directory . $item);
            }
        );

        return $templates;
    }


    /**
     * Add WordPress hooks
     *
     * @return void
     */
    public function register()
    {
        foreach ( self::templateTypes as $templateType ) :
            add_filter("{$templateType}_template_hierarchy", [$this, 'hierarchy']);
        endforeach;
    }


}
