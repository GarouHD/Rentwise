<?php
/**
 * The Template for displaying the registration forms.
 *
 * This template can be overridden by copying it to yourtheme/wpum/forms/form-registration.php
 *
 * HOWEVER, on occasion WPUM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get plugin URL for CSS
$plugin_url = plugins_url( '', dirname( dirname( __FILE__ ) ) );

?>

<link rel="stylesheet" href="<?php echo esc_url( $plugin_url . '/assets/css/wpum-dashboard-forms.css?v=2.0.0' ); ?>" type="text/css" media="all" />

<div class="wpum-template wpum-form wpum-registration-form">

	<div class="wpum-form-card">

	<?php do_action( 'wpum_before_registration_form', $data ); ?>

	<h2 class="wpum-registration-title"><?php esc_html_e( 'Create Your Account', 'wp-user-manager' ); ?></h2>
	<p class="wpum-registration-subtitle"><?php esc_html_e( 'Fill in your details to get started', 'wp-user-manager' ); ?></p>

	<form action="<?php echo esc_url( $data->action ); ?>" method="post" id="wpum-submit-registration-form" enctype="multipart/form-data">

		<!-- Name Field -->
		<fieldset class="fieldset-first_name">
			<label for="first_name">
				<?php esc_html_e( 'Full Name', 'wp-user-manager' ); ?>
				<span class="wpum-required">*</span>
			</label>
			<div class="field required-field">
				<input type="text" name="first_name" id="first_name" value="<?php echo isset( $_POST['first_name'] ) ? esc_attr( $_POST['first_name'] ) : ''; ?>" required />
			</div>
		</fieldset>

		<?php foreach ( $data->fields as $key => $field ) : ?>

			<?php
			/**
			 * Hook to render form field. Always use conditional check to
			 * make sure the field type. Otherwise field would render multiple times.
			 *
			 * @var $field
			 */
			do_action( 'wpum_registration_form_field', $field, $key, $data->fields );
			?>

		<?php endforeach; ?>

		<input type="hidden" name="wpum_form" value="<?php echo esc_attr( $data->form ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		<?php wp_nonce_field( 'verify_registration_form', 'registration_nonce' ); ?>

		<?php do_action( 'wpum_before_submit_button_registration_form', $data ); ?>

		<?php
		$label = isset( $data->submit_label ) ? $data->submit_label : esc_html__( 'Register', 'wp-user-manager' );
		?>
		<input type="submit" name="submit_registration" class="button"
			   value="<?php echo esc_html( apply_filters( 'wpum_registration_form_submit_label', $label ) ); ?>"/>

	</form>

	<?php do_action( 'wpum_after_registration_form', $data ); ?>

	</div>

</div>
