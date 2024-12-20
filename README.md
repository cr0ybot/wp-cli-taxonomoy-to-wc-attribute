cr0ybot/taxonomy-to-wc-attribute
================================

Convert taxonomy terms to WooCommerce attributes.

Currently this package only supports fixing an attribute that has already had terms transfered to it. When you transfer terms from a standard taxonomy to an attribute, those attributes are not automatically added to the product. This package will add the attribute to the product based on the terms that were transfered.

You can use the [Taxonomy Switcher plugin](https://wordpress.org/plugins/taxonomy-switcher/) to handle the initial transfer of terms.

[![Build Status](https://travis-ci.org/cr0ybot/taxonomy-to-wc-attribute.svg?branch=master)](https://travis-ci.org/cr0ybot/taxonomy-to-wc-attribute)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing) | [Support](#support)

## Using

~~~
wp fix-taxonomy-attributes <attribute>
~~~

Note that `<attribute>` is the attribute slug without the `pa_` prefix.

This command will add the attribute to all products that have terms assigned to it.

## Installing

Installing this package requires WP-CLI v2.5 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install the latest stable version of this package with:

```bash
wp package install cr0ybot/taxonomy-to-wc-attribute:@stable
```

To install the latest development version of this package, use the following command instead:

```bash
wp package install cr0ybot/taxonomy-to-wc-attribute:dev-master
```

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/). This package follows those policy and guidelines.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/cr0ybot/taxonomy-to-wc-attribute/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/cr0ybot/taxonomy-to-wc-attribute/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/cr0ybot/taxonomy-to-wc-attribute/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience. See "[Setting up](https://make.wordpress.org/cli/handbook/pull-requests/#setting-up)" for details specific to working on this package locally.

## Support

GitHub issues aren't for general support questions, but there are other venues you can try: https://wp-cli.org/#support


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
