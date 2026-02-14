<?php /* WordPress CMS Theme WSC Project. */ ?>
<?php get_template_part('template-parts/floating', 'bar'); ?>
<footer>
	<div class="top">
		<a href="<?php echo home_url( '/' );?>tou/">特定商取引法に基づく表記</a>
		<a href="<?php echo home_url( '/' );?>privacypolicy/">プライバシーポリシー</a>
		<a href="<?php echo home_url( '/' );?>rules/">利用規約</a>
		<a href="<?php echo home_url( '/' );?>#form">お問い合わせ</a>
	</div>
	<div class="btm">&copy; 2025 CODELESS LAB.</div>
</footer>
<div class="bg"><img src="<?php echo get_template_directory_uri(); ?>/images/bg.webp"loading="lazy" alt="画像"></div>
<?php wp_footer(); ?>
<?php include("jq.php")?>
<?php include("jq.php"); ?>
<?php include("animation.php"); ?>
<script>
$(window).on("load", function() {
	$('#map').html("<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3240.6530276709623!2d139.7757714!3d35.6855446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188954295310c1%3A0x33c2e361135a6118!2z44CSMTAzLTAwMjIg5p2x5Lqs6YO95Lit5aSu5Yy65pel5pys5qmL5a6k55S677yR5LiB55uu77yR77yR4oiS77yR77yS!5e0!3m2!1sja!2sjp!4v1768279351929!5m2!1sja!2sjp' width='100%' height='650' style='border:0;' allowfullscreen='' loading='lazy' referrerpolicy='no-referrer-when-downgrade'></iframe>");
	setTimeout(function() {
		$("main").addClass("on");
		$("header").addClass("on");
	}, 500);

	setTimeout(function() {
		new WOW().init();
		$(".pagettl").addClass("on");
	}, 1000);
});

var classes = ['fadeInRight', 'fadeInLeft', 'fadeInUp'];
var randomClass1 = classes[Math.floor(Math.random() * classes.length)];
var randomClass2 = classes[Math.floor(Math.random() * classes.length)];
var randomClass3 = classes[Math.floor(Math.random() * classes.length)];
var randomClass4 = classes[Math.floor(Math.random() * classes.length)];
$('section h2').addClass("wow " + randomClass1);
$('section h3').addClass("wow " + randomClass2);
$('section p').addClass("wow " + randomClass3);
$('section .cell').addClass("wow " + randomClass1);
$('section img').addClass("wow " + randomClass4);
$('section a').addClass("wow " + randomClass4);
$('section input').addClass("wow " + randomClass1);
$('section button').addClass("wow " + randomClass2);
$('section textarea').addClass("wow " + randomClass3);

</script>
<script>
$(function() {
  const gMenu = $("header nav").html();
  $(".spnavi").html(gMenu);
});
$(function() {
  $('#spMenu .menu-trigger').on('click', function(){
    $(this).toggleClass('active');
    $(".spnavi").toggleClass('active');
    return false;
  });
  $('.spMenu a').on('click', function(){
    $(".spnavi").toggleClass('active');
    $("#spMenu .menu-trigger").toggleClass('active');
  });
  $('.spnavi a').on('click', function(){
    $(".spnavi").toggleClass('active');
    $("#spMenu .menu-trigger").toggleClass('active');
  });
});
</script>
<script>
var cf=document.getElementById('contactForm');if(cf)cf.addEventListener('submit', function(e) {
  e.preventDefault();

  const form = this;
  const result = form.querySelector('.form-result');
  const formData = new FormData(form);
  formData.append('action', 'send_contact_form');

  fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    result.style.display = 'block';
    result.textContent = data.message;

    if (data.success) {
      form.reset();
    }
  })
  .catch(() => {
    result.style.display = 'block';
    result.textContent = '送信に失敗しました。時間をおいて再度お試しください。';
  });
});
</script>
<?php if (is_front_page()): ?>
<script>
(function(){
  var b=document.querySelector('.kv .btnbox');if(b)b.innerHTML='<a href="<?php echo esc_attr(home_url('/#form')); ?>">お問い合わせ</a>';
})();
</script>
<?php endif; ?>

</body>
</html>
