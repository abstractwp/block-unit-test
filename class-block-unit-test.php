<?php
/**
 * Plugin Name: Gutenberg Block Unit Test
 * Plugin URI: https://github.com/abstractwp/block-unit-test/
 * Description: The Block Unit Test plugin creates a page for WordPress theme authors to prepare their WordPress themes for Gutenberg. Test nearly every variation of core blocks within Gutenberg.
 * Author: AbstractWP
 * Author URI: https://www.abstractwp.com/
 * Tags: gutenberg, editor, block, unit test, coblocks
 * Version: 1.0.9
 * Text Domain: 'block-unit-test'
 * Domain Path: languages
 * Tested up to: 6.0.2
 *
 * Block Unit Test is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Block Unit Test. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   Block Unit Test
 * @author    AbstractWP
 * @license   GPL-3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Block Unit Test Class
 *
 * @since 1.0.0
 */
class Block_Unit_Test {

	/**
	 * The plugin instance.
	 *
	 * @var Block_Unit_Test
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new Block_Unit_Test();
		}
	}

	/**
	 * The plugin version.
	 *
	 * @var string $version
	 */
	private $version;

	/**
	 * The base URL path.
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * The Constructor.
	 */
	private function __construct() {

		$this->version = '@@pkg.version';
		$this->url     = untrailingslashit( plugins_url( '/assets/images', __FILE__ ) );

		// Actions.
		add_action( 'admin_init', array( $this, 'create_block_unit_test_page' ) );
		add_action( 'admin_init', array( $this, 'update_block_unit_test_page' ) );
		add_action( 'upgrader_process_complete', array( $this, 'upgrade_completed' ), 10, 2 );
	}

	/**
	 * Creates a page for the blocks to be rendered on.
	 */
	public function create_block_unit_test_page() {

		$title     = apply_filters( 'block_unit_test_title', 'Block Unit Test ' );
		$post_type = apply_filters( 'block_unit_test_post_type', 'page' );

		// Do not create the post if it's already present.
		if ( post_exists( $title ) ) {
			return;
		}

		// Create the Block Unit Test page.
		wp_insert_post(
			array(
				'post_title'     => $title,
				'post_content'   => $this->content(),
				'post_status'    => 'draft',
				'post_author'    => 1,
				'post_type'      => $post_type,
				'comment_status' => 'closed',
			)
		);
	}

	/**
	 * Updates the blocks page upon plugin updates.
	 */
	public function update_block_unit_test_page() {

		$title = apply_filters( 'block_unit_test_title', 'Block Unit Test ' );
		$post  = get_page_by_title( $title, OBJECT, 'page' );

		// Return if the page does not exist.
		if ( ! post_exists( $title ) ) {
			return;
		}

		// Return if the update transient does not exist.
		if ( ! get_transient( 'block_unit_test_updated' ) ) {
			return;
		}

		// Update the post with the latest content update.
		wp_update_post(
			array(
				'ID'           => $post->ID,
				'post_content' => $this->content(),
			)
		);

		// Delete the transient.
		delete_transient( 'block_unit_test_updated' );
	}

	// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter -- Parameter required for hook.
	/**
	 * This function runs when WordPress completes its upgrade process.
	 * It iterates through each plugin updated to see if Block Unit Test is included.
	 *
	 * @param array $upgrader_object Updates.
	 * @param array $options Plugins.
	 */
	public function upgrade_completed( $upgrader_object, $options ) {

		$block_unit_test = plugin_basename( __FILE__ );

		// If an update has taken place and the updated type is plugins and the plugins element exists.
		if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {

			// Iterate through the plugins being updated and check if ours is there.
			foreach ( $options['plugins'] as $plugin ) {

				if ( $plugin === $block_unit_test ) {
					// Set a transient to record that our plugin has just been updated.
					set_transient( 'block_unit_test_updated', 1 );
				}
			}
		}
	}
	// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter

	/**
	 * Content for the test page.
	 */
	public function content() {

		$content = '';

		$content .= '
			<!-- wp:paragraph -->
			<p>' . esc_html__( 'Donec id elit non mi porta gravida at eget metus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.', '@@textdomain' ) . '</p>
			<!-- /wp:paragraph -->
			<!-- wp:more -->
			<!--more-->
			<!-- /wp:more -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":1} -->
			<h1>' . esc_html__( 'Heading One', '@@textdomain' ) . '</h1>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Heading Two', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":3} -->
			<h3>' . esc_html__( 'Heading Three', '@@textdomain' ) . '</h3>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":4} -->
			<h4>' . esc_html__( 'Heading Four', '@@textdomain' ) . '</h4>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":5} -->
			<h5>' . esc_html__( 'Heading Five', '@@textdomain' ) . '</h5>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":6} -->
			<h6>' . esc_html__( 'Heading Six', '@@textdomain' ) . '</h6>
			<!-- /wp:heading -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>Preformatted Block</h2>
			<!-- /wp:heading -->

			<!-- wp:preformatted -->
			<pre class="wp-block-preformatted"><strong>The Road Not Taken</strong>, <em>by Robert Frost</em><br/><br/>Two roads diverged in a yellow wood,<br/>And sorry I could not travel both<br/>And be one traveler, long I stood <br/>And looked down one as far as I could<br/>To where it bent in the undergrowth;<br/>Then took the other, as just as fair,<br/>And having perhaps the better claim,<br/>Because it was grassy and wanted wear;<br/>Though as for that the passing there<br/>Had worn them really about the same,<br/>And both that morning equally lay<br/>In leaves no step had trodden black.<br/>Oh, I kept the first for another day!<br/>Yet knowing how way leads on to way,<br/>I doubted if I should ever come back.<br/>I shall be telling this with a sigh<br/>Somewhere ages and ages hence:<br/>Two roads diverged in a wood, and I—<br/>I took the one less traveled by,<br/>And that has made all the difference.<br/><br/>...and heres a line of some really, really, really, really long text, just to see how it is handled and to find out how it overflows;</pre>
			<!-- /wp:preformatted -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>Ordered List</h2>
			<!-- /wp:heading -->

			<!-- wp:list {"ordered":true} -->
			<ol>
				<li>Nullam id dolor id nibh ultricies vehicula ut id elit.</li>
				<li>Donec ullamcorper nulla non metus auctor fringilla.
					<ol>
						<li>Condimentum euismod aenean.</li>
						<li>Purus commodo ridiculus.</li>
						<li>Nibh commodo vestibulum.</li>
					</ol>
				</li>
				<li>Cras justo odio, dapibus ac facilisis in.</li>
			</ol>
			<!-- /wp:list -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Unordered List', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:list -->
			<ul>
				<li>Nullam id dolor id nibh ultricies vehicula ut id elit.</li>
				<li>Donec ullamcorper nulla non metus auctor fringilla.
					<ul>
						<li>Nibh commodo vestibulum.</li>
						<li>Aenean eu leo quam.</li>
						<li>Pellentesque ornare sem lacinia.</li>
					</ul>
				</li>
				<li>Cras justo odio, dapibus ac facilisis in.</li>
			</ul>
			<!-- /wp:list -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Verse', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>' . esc_html__( 'This is an example of the core Gutenberg verse block.', '@@textdomain' ) . '</p>
			<!-- /wp:paragraph -->

			<!-- wp:verse -->
			<pre class="wp-block-verse">A block for haiku? <br/>Why not? <br/>Blocks for all the things!</pre>
			<!-- /wp:verse -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Separator', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>' . esc_html__( 'Here are examples of the three separator styles of the core Gutenberg separator block.', '@@textdomain' ) . '</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator {"className":""} -->
			<hr class="wp-block-separator"/>
			<!-- /wp:separator -->

			<!-- wp:separator {"className":" is-style-wide"} -->
			<hr class="wp-block-separator  is-style-wide"/>
			<!-- /wp:separator -->

			<!-- wp:separator {"className":"is-style-dots"} -->
			<hr class="wp-block-separator is-style-dots"/>
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Table', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is an example of the core Gutenberg table block. </p>
			<!-- /wp:paragraph -->

			<!-- wp:table -->
			<table class="wp-block-table"><tbody><tr><td>Employee</td><td>Salary</td><td>Position</td></tr><tr><td>Jane Doe<br></td><td>$100k</td><td>CEO</td></tr><tr><td>John Doe</td><td>$100k</td><td>CTO</td></tr><tr><td>Jane Bloggs</td><td>$100k</td><td>Engineering</td></tr><tr><td>Fred Bloggs</td><td>$100k</td><td>Marketing</td></tr></tbody></table>
			<!-- /wp:table -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Latest Posts, List View', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cras justo odio, dapibus ac facilisis in, egestas eget quam. </p>
			<!-- /wp:paragraph -->

			<!-- wp:latest-posts /-->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Latest Posts, Grid View', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>And now for the Grid View. The Latest Posts block also displays at wide and full width alignments, so be sure to check those styles as well.</p>
			<!-- /wp:paragraph -->

			<!-- wp:latest-posts {"postLayout":"grid","columns":2} /-->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Blockquote', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas sed diam eget risus varius blandit sit amet non magna sed diam ed diam eget risus varius eget.</p>
			<!-- /wp:paragraph -->

			<!-- wp:quote {"align":"left"} -->
			<blockquote class="wp-block-quote" style="text-align:left">
				<p>Donec sed odio dui. Maecenas faucibus mollis interdum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio.</p><cite>Rich Tabor</cite></blockquote>
			<!-- /wp:quote -->

			<!-- wp:paragraph -->
			<p>Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas sed diam eget risus varius blandit sit amet non magna sed diam ed diam eget risus varius eget.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading -->
			<h2>' . esc_html__( 'Alternate Blockquote', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>The alternate block quote style can be tarageted using the <strong>.wp-block-quote.is-large</strong>. CSS selector. Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
			<!-- /wp:paragraph -->

			<!-- wp:quote {"align":"left","style":2} -->
			<blockquote class="wp-block-quote is-large" style="text-align:left">
				<p>Donec sed odio dui. Maecenas faucibus mollis interdum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p><cite>Rich Tabor</cite></blockquote>
			<!-- /wp:quote -->

			<!-- wp:paragraph -->
			<p>Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas sed diam eget risus varius blandit sit amet non magna sed diam ed diam eget risus varius eget.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Audio', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Donec sed odio dui. Aenean lacinia bibendum nulla sed consectetur. Nullam id dolor id nibh ultricies vehicula ut id elit. <strong>Center aligned</strong>:</p>
			<!-- /wp:paragraph -->

			<!-- wp:audio {"align":"center"} -->
			<figure class="wp-block-audio aligncenter"><audio controls src="https://example.com"></audio>
				<figcaption>An example of an Audio Block caption</figcaption>
			</figure>
			<!-- /wp:audio -->

			<!-- wp:paragraph -->
			<p>Curabitur blandit tempus porttitor. Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Curabitur blandit tempus porttitor.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Buttons', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Donec sed odio dui. Aenean lacinia bibendum nulla sed consectetur. Nullam id dolor id nibh ultricies vehicula ut id elit. <strong>Center aligned</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:button {"align":"center"} -->
			<div class="wp-block-button aligncenter"><a class="wp-block-button__link" href="https://themebeans.com">Center Aligned Button</a></div>
			<!-- /wp:button -->

			<!-- wp:paragraph -->
			<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. </p>
			<!-- /wp:paragraph -->

			<!-- wp:button {"align":"left"} -->
			<div class="wp-block-button alignleft"><a class="wp-block-button__link" href="https://themebeans.com">Left Aligned Button</a></div>
			<!-- /wp:button -->

			<!-- wp:paragraph -->
			<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla. Maecenas sed diam eget risus varius.</p>
			<!-- /wp:paragraph -->

			<!-- wp:button {"align":"right"} -->
			<div class="wp-block-button alignright"><a class="wp-block-button__link">Right Aligned Button</a></div>
			<!-- /wp:button -->

			<!-- wp:paragraph -->
			<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla. Maecenas sed diam eget risus varius.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Categories', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:categories {"showPostCounts":true,"showHierarchy":true,"align":"center"} /-->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Archives', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:archives {"showPostCounts":true} /-->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Columns', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:columns -->
			<div class="wp-block-columns has-2-columns"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Donec ullamcorper nulla non metus auctor fringilla. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Curabitur blandit tempus porttitor.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Donec ullamcorper nulla non metus auctor fringilla. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Curabitur blandit tempus porttitor.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns -->

			<!-- wp:separator -->
			<hr class="wp-block-separator"/>
			<!-- /wp:separator -->

			<!-- wp:columns {"columns":3} -->
			<div class="wp-block-columns has-3-columns"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns -->

			<!-- wp:separator -->
			<hr class="wp-block-separator"/>
			<!-- /wp:separator -->

			<!-- wp:columns {"columns":4} -->
			<div class="wp-block-columns has-4-columns"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condim entum nibh.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condim entum nibh.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condim entum nibh.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condim entum nibh.</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Pull Quotes', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is an example of the core pull quote block, set to display centered. Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
			<!-- /wp:paragraph -->

			<!-- wp:pullquote {"align":"center"} -->
			<figure class="wp-block-pullquote aligncenter"><blockquote>
				<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere est at lobortis.</p><cite>Rich Tabor, ThemeBeans.com</cite></blockquote></figure>
			<!-- /wp:pullquote -->
		';

		if ( get_theme_support( 'align-wide' ) ) {
			$content .= '
				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Wide aligned', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Here is an example of the core pull quote block, set to display with the wide-aligned attribute, if the theme allows it. Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
				<!-- /wp:paragraph -->

				<!-- wp:pullquote {"align":"wide"} -->
				<figure class="wp-block-pullquote alignwide"><blockquote>
					<p>Nulla vitae elit libero, a pharetra augue. Vestibulum id ligula porta felis euismod semper. Aenean lacinia bibendum nulla sed ibendum nulla sed consectetur. </p><cite>Rich Tabor, Founder at ThemeBeans.com</cite></blockquote></figure>
				<!-- /wp:pullquote -->

				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Full width', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>And finally, here is an example of the core pull quote block, set to display with the full-aligned attribute, if the theme allows it. Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
				<!-- /wp:paragraph -->

				<!-- wp:pullquote {"align":"full"} -->
				<figure class="wp-block-pullquote alignfull"><blockquote>
					<p>Etiam porta sem malesuada magna mollis euismod. Sed posuere consectetur est at lobortis. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. </p><cite>Rich Tabor, Founder at ThemeBeans.com</cite></blockquote></figure>
				<!-- /wp:pullquote -->

				<!-- wp:paragraph -->
				<p>Etiam porta sem malesuada magna mollis euismod. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec sed odio dui. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.</p>
				<!-- /wp:paragraph -->
			';
		}

		$content .= '
			<!-- wp:pullquote {"align":"left","className":"alignleft"} -->
			<figure class="wp-block-pullquote alignleft"><blockquote><p>Here we have a left-aligned pullquote.</p><cite>Rich Tabor</cite></blockquote></figure>
			<!-- /wp:pullquote -->

			<!-- wp:paragraph -->
			<p>Donec id elit non mi porta gravida at eget metus. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras mattis consectetur purus sit amet fermentum. Vestibulum id ligula porta felis euismod semper.</p>
			<!-- /wp:paragraph -->

			<!-- wp:pullquote {"align":"right","className":"alignright"} -->
			<figure class="wp-block-pullquote alignright"><blockquote><p>Here we have a right-aligned pullquote.</p><cite>Rich Tabor</cite></blockquote></figure>
			<!-- /wp:pullquote -->

			<!-- wp:paragraph -->
			<p>Donec ullamcorper nulla non metus auctor fringilla. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam porta sem malesuada magna mollis euismod. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->

			<!-- wp:heading {"level":2} -->
			<h2>Image Block</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Maecenas faucibus mollis interdum.</p>
			<!-- /wp:paragraph -->

			<!-- wp:image {"id":2117,"align":"center"} -->
				<figure class="wp-block-image aligncenter"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"id":2117,"align":"center"} -->
				<figure class="wp-block-image aligncenter"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" />
					<figcaption>And an image with a caption</figcaption>
				</figure>
			<!-- /wp:image -->
		';

		if ( get_theme_support( 'align-wide' ) ) {
			$content .= '
				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Wide aligned', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:image {"id":2117,"align":"wide"} -->
				<figure class="wp-block-image alignwide"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" /></figure>
				<!-- /wp:image -->

				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Full Width', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:image {"id":2117,"align":"full"} -->
				<figure class="wp-block-image alignfull"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" />
					<figcaption>Here is an example of an image block caption</figcaption>
				</figure>
				<!-- /wp:image -->
			';
		}

		$content .= '
			<!-- wp:paragraph -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
			<!-- /wp:paragraph -->

			<!-- wp:image {"id":2117,"align":"left","width":275,"height":196} -->
			<figure class="wp-block-image alignleft is-resized"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" width="275" height="196" /></figure>
			<!-- /wp:image -->

			<!-- wp:paragraph -->
			<p><strong>Left aligned:</strong> dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph -->

			<!-- wp:image {"id":2117,"align":"right","width":281,"height":200} -->
			<figure class="wp-block-image alignright is-resized"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" class="wp-image-2117" width="281" height="200" />
				<figcaption>This one is captioned</figcaption>
			</figure>
			<!-- /wp:image -->

			<!-- wp:paragraph -->
			<p>Nullam quis risus eget urna mollis ornare vel eu leo. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Nullam quis risus.</p>
			<!-- /wp:paragraph -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->
		';

		if ( get_theme_support( 'align-wide' ) ) {
			$content .= '
				<!-- wp:heading {"level":2} -->
				<h2>' . esc_html__( 'Video Block', '@@textdomain' ) . '</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Lets check out the positioning and styling of the video core block. We will check the wide and full alignments too.</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Wide aligned', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:embed {"url":"https://vimeo.com/253989945","type":"video","providerNameSlug":"vimeo","responsive":true,"align":"wide","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
				<figure class="wp-block-embed alignwide is-type-video is-provider-vimeo wp-block-embed-vimeo"><div class="wp-block-embed__wrapper">
				https://vimeo.com/253989945
				</div></figure>
				<!-- /wp:embed -->

				<!-- wp:heading {"level":3} -->
				<h3>Full Width</h3>
				<!-- /wp:heading -->

				<!-- wp:embed {"url":"https://vimeo.com/253989945","type":"video","providerNameSlug":"vimeo","responsive":true,"align":"full","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
				<figure class="wp-block-embed alignfull is-type-video is-provider-vimeo wp-block-embed-vimeo wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
				https://vimeo.com/253989945
				</div></figure>
				<!-- /wp:embed -->
			';
		}

		$content .= '
			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Cover Image Block', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Check out the positioning and styling of the cover image core block. We will check the wide and full alignments, as well as left/right.</p>
			<!-- /wp:paragraph -->

		';

		if ( get_theme_support( 'align-wide' ) ) {
			$content .= '
				<!-- wp:heading {"level":3} -->
				<h3>' . esc_html__( 'Wide aligned', '@@textdomain' ) . '</h3>
				<!-- /wp:heading -->

				<!-- wp:cover-image {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","align":"wide","id":2117} -->
				<div class="wp-block-cover-image has-background-dim alignwide" style="background-image:url(' . esc_url( $this->url . '/placeholder.jpg' ) . ')">
					<p class="wp-block-cover-image-text">' . esc_html__( 'Wide Cover Image Block', '@@textdomain' ) . '</p>
				</div>
				<!-- /wp:cover-image -->

				<!-- wp:heading {"level":3} -->
				<h3>Full Width</h3>
				<!-- /wp:heading -->

				<!-- wp:cover-image {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","align":"full","id":2117} -->
				<div class="wp-block-cover-image has-background-dim alignfull" style="background-image:url(' . esc_url( $this->url . '/placeholder.jpg' ) . ')">
					<p class="wp-block-cover-image-text">' . esc_html__( 'Full Width Cover Image', '@@textdomain' ) . '</p>
				</div>
				<!-- /wp:cover-image -->

				<!-- wp:paragraph -->
				<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. </p>
				<!-- /wp:paragraph -->
			';
		}

		$content .= '
			<!-- wp:cover {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","dimRatio":50,"isDark":false,"align":"left"} -->
			<div class="wp-block-cover alignleft is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background" src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
			<p class="has-text-align-center has-large-font-size">Left Aligned Cover Image</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->

			<!-- wp:cover {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","dimRatio":50,"isDark":false,"align":"right"} -->
			<div class="wp-block-cover alignright is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background" src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
			<p class="has-text-align-center has-large-font-size">Right Aligned Cover Image</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->

			<!-- wp:cover {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","id":2117,"dimRatio":50,"isDark":false} -->
			<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background wp-image-2117" src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
			<p class="has-text-align-center has-large-font-size">Center Aligned Cover Image</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->

			<!-- wp:separator -->
			<hr class="wp-block-separator" />
			<!-- /wp:separator -->
		';

		$content .= '
			<!-- wp:heading {"level":2} -->
			<h2>' . esc_html__( 'Gallery Blocks', '@@textdomain' ) . '</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Let us check out the positioning and styling of the gallery blocks.</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3>Two Column Gallery</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Below we have a Gallery Block inserted with two columns and two images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":2,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-2 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Three Column</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Below we have a Gallery Block inserted with three columns and three images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Four Column</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Below we have a Gallery Block inserted with four columns and four images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":4,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-4 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Five Column</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Below we have a Gallery Block inserted with five columns and five images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":5,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-5 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Four Column, Five Images</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Let us switch things up a bit. Now we have a Gallery Block inserted with four columns and five images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":4,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-4 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Three Column, Five Images</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Now we have a Gallery Block inserted with three columns and five images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:paragraph -->
			<p>Below you will find a Gallery Block inserted with two columns and five images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3>Two Column, Five Images</h3>
			<!-- /wp:heading -->

			<!-- wp:gallery {"columns":2,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-2 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading {"level":3} -->
			<h3>Three Column, Four Images</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Below you will find a Gallery Block inserted with three columns and four images.</p>
			<!-- /wp:paragraph -->

			<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
			<figure class="wp-block-gallery alignnone has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image -->

			<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
			<!-- /wp:image --></figure>
			<!-- /wp:gallery -->

			<!-- wp:heading -->
			<h2>Media &amp; Text</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here are examples of the core Gutenberg media &amp; text block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3>Text on right</h3>
			<!-- /wp:heading -->

			<!-- wp:media-text {"align":"","mediaType":"image","className":"alignnone"} -->
			<div class="wp-block-media-text is-stacked-on-mobile alignnone"><figure class="wp-block-media-text__media"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"placeholder":"Content…"} -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:media-text -->

			<!-- wp:separator -->
			<hr class="wp-block-separator has-alpha-channel-opacity"/>
			<!-- /wp:separator -->

			<!-- wp:heading {"level":3} -->
			<h3>Text on left</h3>
			<!-- /wp:heading -->

			<!-- wp:media-text {"align":"","mediaPosition":"right","mediaType":"image","className":"alignnone"} -->
			<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile alignnone"><figure class="wp-block-media-text__media"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"placeholder":"Content…"} -->
			<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. </p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:media-text -->

			<!-- wp:heading -->
			<h2>Calendar</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is example of the core Gutenberg calendar block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:calendar /-->

			<!-- wp:heading -->
			<h2>Latest Comments</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is example of the core Gutenberg latest comments block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:latest-comments /-->

			<!-- wp:heading -->
			<h2>WordPress TV blocks</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here are examples of the core Gutenberg WordPress tv blocks.</p>
			<!-- /wp:paragraph -->

			<!-- wp:embed {"url":"https://www.youtube.com/watch?v=a3ICNMQW7Ok","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
			<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
			https://www.youtube.com/watch?v=a3ICNMQW7Ok
			</div></figure>
			<!-- /wp:embed -->

			<!-- wp:embed {"url":"https://vimeo.com/253989945","type":"video","providerNameSlug":"vimeo","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
			<figure class="wp-block-embed is-type-video is-provider-vimeo wp-block-embed-vimeo wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
			https://vimeo.com/253989945
			</div></figure>
			<!-- /wp:embed -->

			<!-- wp:heading -->
			<h2>Search block</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is example of the core Gutenberg search block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:search {"label":"Search","buttonText":"Search"} /-->

			<!-- wp:heading -->
			<h2>Latest posts block</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is example of the core Gutenberg Latest Posts block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:latest-posts {"displayPostContent":true,"excerptLength":25,"displayAuthor":true,"displayPostDate":true,"displayFeaturedImage":true} /-->

			<!-- wp:heading -->
			<h2>Social Icons block</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here is example of the core Gutenberg Social Icons block.</p>
			<!-- /wp:paragraph -->

			<!-- wp:social-links {"openInNewTab":true,"showLabels":true} -->
			<ul class="wp-block-social-links has-visible-labels">

			<!-- wp:social-link {"url":"#","service":"facebook"} /-->

			<!-- wp:social-link {"url":"#","service":"twitter"} /-->

			<!-- wp:social-link {"url":"#","service":"youtube"} /-->

			<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

			<!-- wp:social-link {"url":"#","service":"instagram"} /-->

			<!-- wp:social-link {"url":"#","service":"tiktok"} /-->

			<!-- wp:social-link {"url":"#","service":"telegram"} /-->

			<!-- wp:social-link {"url":"#","service":"skype"} /--></ul>
			<!-- /wp:social-links -->

			<!-- wp:heading -->
			<h2>Cover block</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Here are examples of the core Gutenberg Cover blocks.</p>
			<!-- /wp:paragraph -->

			<!-- wp:cover {"overlayColor":"secondary","isDark":false} -->
			<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
			<p class="has-text-align-center has-large-font-size">This is cover block with color background</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->

			<!-- wp:cover {"url":"' . esc_url( $this->url . '/placeholder.jpg' ) . '","id":16,"dimRatio":50} -->
			<div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background wp-image-16" alt="" src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
			<p class="has-text-align-center has-large-font-size">This is background image cover block</p>
			<!-- /wp:paragraph --></div></div>
			<!-- /wp:cover -->
		';

		if ( get_theme_support( 'align-wide' ) ) {
			$content .= '
				<!-- wp:heading {"level":2} -->
				<h2>Wide aligned Gallery Blocks</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Let us check out the positioning and styling of the gallery blocks..</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":3} -->
				<h3>Two Column Gallery</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below we have a Gallery Block inserted with two columns and two images. It is set to display with the new Wide alignment (if the theme supports it).</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":2,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-2 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Three Column</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below we have a Gallery Block inserted with three columns and three images. It is also set to display with the new Wide alignment.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":3,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Four Column</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below we have a Gallery Block inserted with four columns and four images. It is also set to display with the new Wide alignment.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":4,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-4 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Five Column</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below we have a Gallery Block inserted with five columns and five images. It is also set to display with the new Wide alignment.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":5,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-5 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Four Column, Five Images</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Let us switch things up a bit. Now we have a Gallery Block inserted with four columns and five images, also displayed with the new Wide alignment option.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":4,"linkTo":"none","className":"alignwide"} -->
				<figure class="wp-block-gallery has-nested-images columns-4 is-cropped alignwide"><!-- wp:image {"linkDestination":"none"} -->
				<figure class="wp-block-image"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"linkDestination":"none"} -->
				<figure class="wp-block-image"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"linkDestination":"none"} -->
				<figure class="wp-block-image"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"linkDestination":"none"} -->
				<figure class="wp-block-image"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure>
				<!-- /wp:image -->

				<!-- wp:image {"linkDestination":"none"} -->
				<figure class="wp-block-image"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Three Column, Five Images</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Now we have a Gallery Block inserted with three columns and five images displayed with the new Wide alignment option.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":3,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Two Column, Five Images</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below you will find a Gallery Block inserted with two columns and five images also displayed with the new Wide alignment option.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":2,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-2 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Three Column, Four Images</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below you will find a Gallery Block inserted with three columns and four images, also displayed with the new Wide alignment option.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":3,"linkTo":"none","align":"wide"} -->
				<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading {"level":3} -->
				<h3>Full Width Gallery Block</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>Below you will find a Gallery Block inserted with three columns and four images, also displayed with the new Wide alignment option.</p>
				<!-- /wp:paragraph -->

				<!-- wp:gallery {"columns":3,"linkTo":"none","align":"full"} -->
				<figure class="wp-block-gallery alignfull has-nested-images columns-3 is-cropped"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image -->

				<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt="" /></figure>
				<!-- /wp:image --></figure>
				<!-- /wp:gallery -->

				<!-- wp:heading -->
				<h2>Media &amp; Text</h2>
				<!-- /wp:heading -->

				<!-- wp:media-text {"mediaType":"image"} -->
				<div class="wp-block-media-text alignwide is-stacked-on-mobile"><figure class="wp-block-media-text__media"><img src="' . esc_url( $this->url . '/placeholder.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"placeholder":"Content…","fontSize":"large"} -->
				<p class="has-large-font-size">Large text</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p>This is part of the InnerBlocks text for the Media &amp; Text block.</p>
				<!-- /wp:paragraph --></div></div>
				<!-- /wp:media-text -->
			';
		}
		return apply_filters( 'block_unit_test_content', $content );
	}
}
Block_Unit_Test::register();
