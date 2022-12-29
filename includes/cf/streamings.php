<?php
/**
 * Retrieving the values:
 * create_at = get_post_meta( get_the_ID(), 'twchr-from-api_create_at', true )
 * description = get_post_meta( get_the_ID(), 'twchr-from-api_description', true )
 * duration = get_post_meta( get_the_ID(), 'twchr-from-api_duration', true )
 * id = get_post_meta( get_the_ID(), 'twchr-from-api_id', true )
 * languaje = get_post_meta( get_the_ID(), 'twchr-from-api_languaje', true )
 * muted_segment = get_post_meta( get_the_ID(), 'twchr-from-api_muted_segment', true )
 * published_at = get_post_meta( get_the_ID(), 'twchr-from-api_published_at', true )
 * stream_id = get_post_meta( get_the_ID(), 'twchr-from-api_stream_id', true )
 * thumbnail_url = get_post_meta( get_the_ID(), 'twchr-from-api_thumbnail_url', true )
 * type = get_post_meta( get_the_ID(), 'twchr-from-api_type', true )
 * url = get_post_meta( get_the_ID(), 'twchr-from-api_url', true )
 * user_id = get_post_meta( get_the_ID(), 'twchr-from-api_user_id', true )
 * user_login = get_post_meta( get_the_ID(), 'twchr-from-api_user_login', true )
 * user_name = get_post_meta( get_the_ID(), 'twchr-from-api_user_name', true )
 * view_count = get_post_meta( get_the_ID(), 'twchr-from-api_view_count', true )
 * viewble = get_post_meta( get_the_ID(), 'twchr-from-api_viewble', true )
 * title = get_post_meta( get_the_ID(), 'twchr-from-api_title', true )
 */
class Twttcher {
	private $config = '{"title":"Twittcher Stream","prefix":"twchr-from-api_","domain":"twittcher","class_name":"Twttcher","post-type":["post"],"context":"normal","priority":"high","cpt":"twchr_streams","fields":[{"type":"text","label":"create_at","id":"twchr-from-api_create_at"},{"type":"text","label":"description","id":"twchr-from-api_description"},{"type":"text","label":"duration","id":"twchr-from-api_duration"},{"type":"number","label":"id","step":"1","id":"twchr-from-api_id"},{"type":"text","label":"languaje","id":"twchr-from-api_languaje"},{"type":"text","label":"muted_segment","id":"twchr-from-api_muted_segment"},{"type":"text","label":"published_at","id":"twchr-from-api_published_at"},{"type":"text","label":"stream_id","id":"twchr-from-api_stream_id"},{"type":"text","label":"thumbnail_url","id":"twchr-from-api_thumbnail_url"},{"type":"text","label":"type","id":"twchr-from-api_type"},{"type":"text","label":"url","id":"twchr-from-api_url"},{"type":"text","label":"user_id","id":"twchr-from-api_user_id"},{"type":"text","label":"user_login","id":"twchr-from-api_user_login"},{"type":"text","label":"user_name","id":"twchr-from-api_user_name"},{"type":"number","label":"view_count","step":"1","id":"twchr-from-api_view_count"},{"type":"text","label":"viewble","id":"twchr-from-api_viewble"},{"type":"text","label":"title","id":"twchr-from-api_title"}]}';

	public function __construct() {
		$this->config = json_decode( $this->config, true );
		$this->process_cpts();
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public function process_cpts() {
		if ( ! empty( $this->config['cpt'] ) ) {
			if ( empty( $this->config['post-type'] ) ) {
				$this->config['post-type'] = array();
			}
			$parts = explode( ',', $this->config['cpt'] );
			$parts = array_map( 'trim', $parts );
			$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
		}
	}

	public function add_meta_boxes() {
		foreach ( $this->config['post-type'] as $screen ) {
			add_meta_box(
				sanitize_title( $this->config['title'] ),
				$this->config['title'],
				array( $this, 'add_meta_box_callback' ),
				$screen,
				$this->config['context'],
				$this->config['priority']
			);
		}
	}

	public function save_post( $post_id ) {
		foreach ( $this->config['fields'] as $field ) {
			switch ( $field['type'] ) {
				case 'url':
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = esc_url_raw( $_POST[ $field['id'] ] );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
					break;
				default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( $_POST[ $field['id'] ] );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
			}
		}
	}

	public function add_meta_box_callback() {
		$this->fields_table();
	}

	private function fields_table() {
		?><table class="form-table" role="presentation">
			<tbody>
			<?php
			foreach ( $this->config['fields'] as $field ) {
				?>
					<tr>
						<th scope="row"><?php $this->label( $field ); ?></th>
						<td><?php $this->field( $field ); ?></td>
					</tr>
					<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}

	private function label( $field ) {
		switch ( $field['type'] ) {
			default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'],
					$field['label']
				);
		}
	}

	private function field( $field ) {
		switch ( $field['type'] ) {
			case 'textarea':
				$this->textarea( $field );
				break;
			default:
				$this->input( $field );
		}
	}

	private function input( $field ) {
		printf(
			'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
			isset( $field['class'] ) ? $field['class'] : '',
			$field['id'],
			$field['id'],
			isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
			$field['type'],
			$this->value( $field )
		);
	}

	private function textarea( $field ) {
		printf(
			'<textarea class="regular-text" id="%s" name="%s" rows="%d">%s</textarea>',
			$field['id'],
			$field['id'],
			isset( $field['rows'] ) ? $field['rows'] : 5,
			$this->value( $field )
		);
	}

	private function value( $field ) {
		global $post;
		if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
		} else if ( isset( $field['default'] ) ) {
			$value = $field['default'];
		} else {
			return '';
		}
		return str_replace( '\u0027', "'", $value );
	}

}
new Twttcher();
