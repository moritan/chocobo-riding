<?php 
$user = $this->session->get('user');
$jgrowls = $this->session->get_once('jgrowls', array());

if ( ! empty($jgrowls)):
?>

<script>
	$(function() {
	<?php foreach ($jgrowls as $content): ?>
		$.jGrowl(
			'<?php echo addslashes($content) ?>',
			{sticky: true}
		);
	<?php endforeach; ?>
	});
</script>

<?php endif; ?>
