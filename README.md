# Gutenberg Block Unit Test

[![License](https://img.shields.io/badge/license-GPL--3.0%2B-red.svg)](https://github.com/richtabor/block-unit-test/blob/master/license.txt)

![Screenshot](https://demotest.abstractwp.com/wp-content/uploads/2022/08/block-unit-test-screenshot.jpg)

Testing every core block — and every variation of every block — is no small task. That's why I built the Block Unit Test WordPress plugin. Deploy the Block Unit Test WordPress plugin and review every core Gutenberg block to ensure your theme fully supports Gutenberg.

## :question: Why is this a thing?

Coming soon.

## :movie_camera: Try Block Unit Test

See Block Unit Test at [WP Test Demo](https://demotest.abstractwp.com/block-unit-test/)

## :electric_plug: Installation

1. Install the offical [Gutenberg](https://wordpress.org/plugins/gutenberg/) plugin. Note that Gutenberg is not suggested for use on production sites.
2. Download the plugin from the [WordPress plugin directory](https://wordpress.org/plugins/block-unit-test/).
3. Activate the Block Unit Test plugin
4. You will find a new page added, titled "Block Unit Test". Each of the core Gutenberg blocks will be added here for you to start testing.

## :hammer: Development

1. Clone the GitHub repository: `https://github.com/abstractwp/block-unit-test.git`
2. Browse to the folder in the command line.
3. Run the `npm install` command to install the plugin's dependencies within a /node_modules/ folder.
4. Develop stuff.
5. Run the `build` gulp task to process build files and generate a zip.

## :bomb: Bugs

If you find a 🐞 or an issue, [create an issue](https://github.com/abstractwp/block-unit-test/issues/new).

## :information_desk_person: Contributions

Please read the [guidelines for contributing](https://github.com/abstractwp/block-unit-test/blob/master/CONTRIBUTING.md) to the Block Unit Test. Anyone is welcome to contribute!

There are various ways you can contribute:

1. Raise an [Issue](https://github.com/abstractwp/block-unit-test/issues/new) on GitHub
2. Send a pull request with your changes and/or bug fixes
3. Provide feedback and suggestions on [enhancements](https://github.com/abstractwp/block-unit-test/issues?direction=desc&labels=Enhancement&page=1&sort=created&state=open)

## :dart: Roadmap

Coming soon

## :tada: Spread the Word

Please help support Block Unit Test by:

- Adding a GitHub Star to the project!
- Tweet about the project on your Twitter!
  - Tag [@anabstractny](https://twitter.com/anabstractny) and/or `#block-unit-test`
- Leave us a review [Block Unit Test](https://wordpress.org/plugins/block-unit-test/)!

Thank you so much for your support!

## :scroll: Changelog

### 1.0.7 version

- Fix: Broken some blocks and add missing blocks.

### 1.0.5 version

- Tweak: Update the CoBlocks and Gutenberg unit tests

### 1.0.4 version

- Tweak: Update the CoBlocks unit test

### 1.0.3 version

- New: Suggest running a unit test for CoBlocks
- New: Add a CoBlocks unit test if the plugin is activated
- New: Add the Archives block to the unit test
- New: Add styles for the core Separator block
- New: Add more tests for various column counts

### 1.0.2 version

- New: Automagically update the contents of the Block Unit Test page upon plugin update
- New: Add captions to image, gallery and video blocks
- Tweak: Add heading levels to heading blocks

### 1.0.1 version

- Fix undefined variable $content as per #1
