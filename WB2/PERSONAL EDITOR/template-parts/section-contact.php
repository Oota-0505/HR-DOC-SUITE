<?php

$cf7_contact_form_id = defined('PERSONAL_EDITOR_CF7_CONTACT_FORM_ID') ? PERSONAL_EDITOR_CF7_CONTACT_FORM_ID : 'b75f227';
?>
<section class="p-contact" id="contact">
  <div class="p-contact__inner l-inner">
    <p class="p-contact__subtitle js-animate js-animate-fade-scale">CONTACT</p>
    <h2 class="p-contact__heading js-animate js-animate-delay js-animate-from-top">お問い合わせ</h2>
    <div class="p-contact__form js-animate js-animate-delay">
      <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_contact_form_id) . '" title="お問い合わせ" html_class="p-contact__form wpcf7-form"]'); ?>
    </div>
  </div>
</section>