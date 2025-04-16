## Templify KB – BWL Knowledge Base Manager Addon

**Templify KB** is a powerful template addon for the BWL Knowledge Base Manager WordPress plugin to manage KB Categories, Tags, and single-page custom templates without touching a single file inside the theme folder. Addon has a responsive and mobile-friendly grid layout system. So, you can easily display your knowledge base contents on small devices without any issues.

![overview of templify kb addon](https://xenioushk.github.io/docs-plugins-addon/bkbm-addon/templify/screenshot/01_templify_kb.png)

The addon has a built-in widget feature. You can add any custom widgets to the sidebar areas (there are seven types of unique widgets available with BWL Knowledge Base Manager), but only for KB items. That means you have complete freedom to manage the knowledge base page sidebar contents.

[Demo](https://projects.bluewindlab.net/wpplugin/bkbm/) | [Download](https://bluewindlab.net/portfolio/templify-kb-bwl-knowledge-base-manager-addon/) | [Documentation](https://xenioushk.github.io/docs-plugins-addon/bkbm-addon/templify/index.html)

## Addon requirements

You need to install [BWL Knowledge Base Manager WordPress plugin](https://1.envato.market/bkbm-wp) to use the addon.

You need at least WordPress version 4.8+ installed for this plugin to work properly. It is strongly recommended that you always use the latest stable version of WordPress to ensure all known bugs and security issues are fixed.

## Technical requirements

- WordPress 5.6 or greater.
- PHP version 7.4 or greater.
- MySQL version 5.5.51 or greater.

## Filters Hook

1. `bkbm_single_custom_class`

- **Description**: Filters the custom CSS class for the single template container.
- **Parameters**:
  - `$custom_class` _(string)_: The default custom class.
- **Usage**:
  ```php
  add_filter( 'bkbm_single_custom_class', function( $custom_class ) {
      return $custom_class . ' additional-class';
  } );
  ```

2. bkbm_single_custom_id
   Description: Filters the custom ID for the single template container.
   Parameters:
   $custom_id (string): The default custom ID.
   Usage:

```php
add_filter( 'bkbm_single_custom_id', function( $custom_id ) {
  return 'custom-single-id';
} );
```

3. bkbm_sidebar_custom_class
   Description: Filters the custom CSS class for the sidebar container.
   Parameters:
   $custom_class (string): The default custom class.
   Usage:

```php
add_filter( 'bkbm_sidebar_custom_class', function( $custom_class ) {
    return $custom_class . ' custom-sidebar-class';
} );
```

4. bkbm_sidebar_custom_id
   Description: Filters the custom ID for the sidebar container.
   Parameters:
   $custom_id (string): The default custom ID.
   Usage:

```php
add_filter( 'bkbm_sidebar_custom_id', function( $custom_id ) {
    return 'custom-sidebar-id';
} );
```

5. bkbm_before_main_content_wrapper
   Description: Filters the HTML wrapper before the main content.
   Parameters:
   $content_string (string): The default wrapper HTML.
   Usage:

```php
add_filter( 'bkbm_before_main_content_wrapper', function( $content_string ) {
    return '<div class="custom-wrapper">' . $content_string;
} );
```

6. bkbm_after_main_content_wrapper
   Description: Filters the HTML wrapper after the main content.
   Parameters:
   $content_string (string): The default wrapper HTML.
   Usage:

```php
add_filter( 'bkbm_after_main_content_wrapper', function( $content_string ) {
    return $content_string . '</div>';
} );
```

## Actions Hook

1. bkbm_before_main_content
   Description: Fires before the main content is rendered.
   Parameters:
   $layout (int): The layout type.
   Usage

```php
add_action( 'bkbm_before_main_content', function( $layout ) {
    echo '<div class="before-main-content">Custom Content</div>';
} );
```

2. bkbm_after_main_content
   Description: Fires after the main content is rendered.
   Parameters:
   $layout (int): The layout type.
   Usage:

```php
add_action( 'bkbm_after_main_content', function( $layout ) {
    echo '<div class="after-main-content">Custom Content</div>';
} );
```

3. bkbm_before_single_content
   Description: Fires before the single content is rendered.
   Parameters: None.
   Usage:

   ```php
   add_action( 'bkbm_before_single_content', function() {
    echo '<div class="before-single-content">Custom Content</div>';
   } );
   ```

4. bkbm_after_single_content
   Description: Fires after the single content is rendered.
   Parameters: None.
   Usage:

```php
add_action( 'bkbm_after_single_content', function() {
  echo '<div class="after-single-content">Custom Content</div>';
} );
```

5. bkbm_before_sidebar_content
   Description: Fires before the sidebar content is rendered.
   Parameters:
   $layout (int): The layout type.
   Usage:
   ```php
   add_action( 'bkbm_before_sidebar_content', function( $layout ) {
    echo '<div class="before-sidebar-content">Custom Sidebar Content</div>';
   } );
   ```
6. bkbm_after_sidebar_content
   Description: Fires after the sidebar content is rendered.
   Parameters:
   $layout (int): The layout type.
   Usage:

   ```php
   add_action( 'bkbm_after_sidebar_content', function( $layout ) {
    echo '<div class="after-sidebar-content">Custom Sidebar Content</div>';
   } );
   ```

## Change log

- [Change log](https://xenioushk.github.io/docs-plugins-addon/bkbm-addon/templify/index.html#changelog)

## Acknowledgement

- [bluewindlab.net](https://bluewindlab.net)
- [BWL KB Manager WordPress plugin](https://1.envato.market/bkbm-wp)

```

```
