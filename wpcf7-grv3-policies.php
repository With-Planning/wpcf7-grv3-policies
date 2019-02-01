<?php

/*
Plugin Name: WPCF7 Google reCaptcha v3 Policies
Description: ContactForm7で、Google reCaptchaの利用規約に関するテキストの挿入と、追従する利用規約の表示を消します。
Version: 1.2
Author: With-Planning
Author URI: https://with-planning.co.jp
License: GPL2
*/

wp_add_inline_style('contact-form-7', '<style>.grecaptcha-badge{opacity: 0;}</style>');

add_shortcode('recaptcha_v3_policies', function($atts){
	$atts = shortcode_atts( array(
		'lang' => get_option('WPLANG'),
	), $atts, 'recaptcha_v3_policies' );

	ob_start();
	if($atts['lang'] === 'ja'):
	?>
    <div class="wpcf7-grv3-policies-container">
        このサイトはreCAPTCHAによって保護されており、Googleの
        <a href="https://policies.google.com/privacy">プライバシーポリシー</a>と
        <a href="https://policies.google.com/terms">利用規約</a>が適用されます。
    </div>
	<?php
    else: ?>
    <div class="wpcf7-grv3-policies-container">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
    </div>
    <?php
    endif;
	$contents = ob_get_contents();
	ob_end_clean();

	return $contents;
});

if(function_exists('wpcf7_add_shortcode')){

	/**
	 * @param $tag WPCF7_FormTag
	 *
	 * @return false|string
	 */
	function recaptcha_v3_policies_callback($tag){
		// TODO CF7のタグにパラメータを指定して、日英と言語を切り替えられるようにしたい。

        // タグから言語設定を取得する
		$lang = $tag->get_option('lang', '', true);

		// 言語の指定がない場合、WordPressの設定を使う
		if(empty($lang)){
		    $lang = get_option('WPLANG');
        }

		ob_start();
		if($lang === 'ja'):
		?>
        <div class="wpcf7-grv3-policies-container">
            このサイトはreCAPTCHAによって保護されており、Googleの
            <a href="https://policies.google.com/privacy">プライバシーポリシー</a>と
            <a href="https://policies.google.com/terms">利用規約</a>が適用されます。
        </div>
		<?php
        else: ?>
        <div class="wpcf7-grv3-policies-container">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy">Privacy Policy</a> and
            <a href="https://policies.google.com/terms">Terms of Service</a> apply.
        </div>
        <?php endif;

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	wpcf7_add_form_tag('recaptcha_v3_policies', 'recaptcha_v3_policies_callback');

    if(class_exists('WPCF7_TagGenerator')){
	    WPCF7_TagGenerator::get_instance()->add('wpcf7_add_shortcode', 'GRv3利用規約', function(){
		    ?>
            <div class="insert-box" style="left: auto; right: auto; bottom: auto; height: auto">
                <input type="text" name="recaptcha_v3_policies" class="tag code" readonly="readonly" onfocus="this.select()" />

                <div class="submitbox">
                    <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
                </div>

                <br class="clear" />

                <p class="description mail-tag"><label for="recaptcha_v3_policies"><input type="text" class="mail-tag code hidden" readonly="readonly" id="recaptcha_v3_policies" /></label></p>
            </div>
		    <?php
	    });
    }
}

/**
 * Update notifier.
 */
if ( is_admin() ) {
	require __DIR__ . '/inc/plugin-update-checker/plugin-update-checker.php';
	$update_checker = Puc_v4_Factory::buildUpdateChecker(
		'https://wp-update.with-planning.co.jp/?action=get_metadata&slug=wpcf7-grv3-policies',
		__FILE__,
		'wpcf7-grv3-policies'
	);
}