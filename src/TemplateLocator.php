<?php
/**
 * Template loader.
 */

namespace RalfHortt\TemplateLoader;

/**
 * Template file locater.
 */
class TemplateLocator
{
    /**
     * Template types.
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
        'front-page',
        'page',
        'paged',
        'search',
        'single',
        'singular',
        'attachment',
    ];

    /**
     * Add WordPress hooks.
     *
     * @param string $directory Directory to check for template files
     */
    public function __construct(protected string $directory)
    {
    }

    /**
     * Prefix template folder.
     *
     * @param array $templates Template files
     *
     * @return array Template files
     */
    public function hierarchy(array $templates): array
    {
        array_walk(
            $templates,
            function (&$item) {
                $item = trailingslashit($this->directory).$item;
            }
        );

        return $templates;
    }

    /**
     * Custom page template.
     *
     * @param string[]     $post_templates Array of page templates. Keys are filenames,
     *                                     values are translated names.
     * @param WP_Theme     $this           The theme object.
     * @param WP_Post|null $post           The post being edited, provided for context, or null.
     * @param string       $post_type      Post type to get the templates for.
     *
     * @return array Array of page templates. Keys are filenames, values are translated names.
     **/
    public function templates(array $postTemplates, \WP_Theme $theme, $post, string $postType): array
    {
        $files = $theme->get_files('php', 3);
        array_walk(
            $files,
            function ($absolutePath, $relativePath) use (&$postTemplates) {
                if (false === strpos($relativePath, $this->directory)) {
                    return;
                }
                $headers = get_file_data($absolutePath, ['Template Name']);
                $relativePath = str_replace(trailingslashit($this->directory), '', $relativePath);

                $postTemplates[$relativePath] = $headers[0];
            }
        );

        return array_filter($postTemplates);
    }

    /**
     * Add WordPress hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('theme_templates', [$this, 'templates'], 10, 4);
        foreach (self::TEMPLATES as $templateType) {
            add_filter("{$templateType}_template_hierarchy", [$this, 'hierarchy']);
        }
    }
}
