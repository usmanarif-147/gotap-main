<?php $totalPlatforms = 0; ?>
<div class="container">
	<div class=" row">
		<?php if ($user->private) { ?>
			<h1 style="font-size:37px; margin-top:30px; margin-right:auto; margin-left:auto" class="text-center">This Profile is Private</h1>
		<?php } else {
		?>
			<?php foreach ($categories as $category) {
				foreach ($category['platforms'] as $platform) {
					if ($platform->saved == 1) {
						$totalPlatforms++;
						if ($platform->direct == 1) {
							$directOpen = $platform->baseURL . $platform->path;
							if ($directOpen)
								redirect($directOpen);
						}
			?>

						<div class="gallery-item col-6" tabindex="0">
							<?php
							if (strtolower($platform->input) == 'email')
								$ref = "mailto:$platform->path";
							else if (strtolower($platform->input) == 'username')
								$ref = $platform->baseURL . $platform->path;
							else if (strtolower($platform->title) == 'text' || strtolower($platform->title) == 'message')
								$ref = "sms:$platform->path";
							else if (strtolower($platform->title) == 'kontakt')
								$ref = "/contact/create/$user->id-$platform->path";
							else if (strtolower($platform->input) == 'phone')
								$ref = "tel:$platform->path";
							else
								$ref = $platform->path;
							?>
							<a href="<?= $ref ?>" target="_blank">
								<img id="platform_<?= $totalPlatforms ?>" src="/<?= $platform->icon ?>" class="gallery-image <?= isset($_GET['source']) ? 'ml-4' : '' ?>" alt="">

							</a>
						</div>

		<?php }
				}
			}
		} ?>

	</div>
	<!-- End of gallery -->

</div>

<script>
	const totalPlatforms = <?= $totalPlatforms ?>;
	if (totalPlatforms % 2 != 0) {
		const platform = document.querySelector(`#platform_${totalPlatforms}`);
		platform.classList.add('lastPlatform');
		platform.parentElement.classList.remove('col-6');
		platform.parentElement.parentElement.classList.add('col-12');
	}
</script>
<style>
	.lastPlatform {
		margin-left: auto;
		margin-right: auto;
	}
</style>