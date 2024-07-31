# Editor Button Convert

The Drupal product `editor_button_link` has no support for CKEditor5. This module simply converts any content that has the `data-drupal-button-link="button"` and `data-drupal-button-link-style` attrubites into classes so they remain as buttons once the `editor_button_link` module has been uninstalled.

## Requirements

- Drupal 10.x or higher

## Installation

### Using Composer

1. Ensure your `composer.json` includes the repository:

   ```json
   "repositories": [
       {
           "type": "vcs",
           "url": "git@github.com:dannyboy/editor_button_convert.git"
       }
   ]
   ```

2. Require the module using Composer:

   ```bash
   composer require dannyboy/editor_button_convert
   ```

### Manual Installation

1. Download the module from the GitHub repository:

   ```bash
   git clone git@github.com:dannyboy/editor_button_convert.git web/modules/custom/editor_button_convert
   ```

2. Clear the cache:

   ```bash
   drush cr
   ```

## Maintainers

- Dan Thorne

## License

This project is licensed under the GPL-2.0-or-later License.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request on GitHub.

## Support

If you encounter any issues or have questions, please open an issue on the GitHub repository.
