<?php $directOpen = null ?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="/assets/css/profile.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<title>Gotap profile</title>
	<style>
		.profile_img {
			display: flex;
			justify-content: center;
			border-radius: 50%;
			margin-top: -10%;

		}

		.profile_img img {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 7px solid #fff;
			object-fit: cover;
		}
	</style>
</head>

<body>
	<!-- <div style="box-shadow: 0 0 15px 5px #ccc"> -->
	<div style="border: 1px solid">
		<header>
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-md-5 col-12 ">
						<div class="row">
							<a target="_blank" href="https://www.gotaps.me" class="col-12 header-navbar TopBanner">
								<div class="TopBanner">
									Tap here to get your Gotap profile
								</div>
							</a>
							<div class="col-12 d-flex justify-content-center" style="padding: 0px;">
								<div class="col-12" style="padding: 0px;">
									<img style=" width: 100%;" src="<?= $user->cover_photo  ? $user->cover_photo : './assets/icons/defaultProfile.png' ?>" height="300px" alt="">
									<div class="profile_img">
										<img src="<?= $user->photo  ? $user->photo : './assets/icons/defaultProfile.png' ?>" alt="">
									</div>
								</div>
							</div>
							<div class="col-12 d-flex justify-content-center" style="padding: 0px;">
								<div class="col-md-6" style="padding: 0px;">
									<h1 style=" margin-left:30px;" class="user-name"><?= $user->username ?>
										<?php if ($user->verified) { ?>
											<img style="display:inline; margin-bottom:05px;" src="/assets/icons/check.jpg" width="20px">
										<?php } ?>
									</h1><br>
									<h1 style="font-size:20px; width:60%; margin-left:auto; margin-right:auto" class="user-bio"><?= $user->bio ?></h1><br>
									<h1 class="user-tiks"><?= $user->tiks ?></h1><br><br><br>
								</div>
							</div>
							<div class="col-12 d-flex justify-content-center" style="padding: 0px;">
								<div class=" col-md-6 col-8" style="padding: 0px;">
									<button id="connectBTN" class="btn btn-block AddBtn rounded-pill px-4 py-3 " style="background-color: #000000;">
										<a target="_blank" class="text-white" href="addToContact/<?= $user->id ?>"><b>Save to contact</b></a>
									</button>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>


		</header>

		<main>


			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-md-5 col-12 ">
						<?= $content ?>
					</div>
				</div>
			</div>

			<div class="BottomBanner " style="background-color:#ffffff; margin-bottom:15px">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="col-md-5 col-12 ">
							<!--Tap here to order your personal Metrotap-->
							<a type="button" class="mr-5" target="_blank" href="https://play.google.com/store/apps/details?id=com.horizam.deezyy">
								<img src="/assets/icons/android.png" style="height:45px;">
							</a>
							<a type="button" target="_blank" class="ml-6" href="https://apps.apple.com/us/app/gotaps/id1562125398">
								<img src="/assets/icons/ios.png" style="height:45px;">
							</a>
						</div>
					</div>
				</div>
			</div>
		</main>

	</div>
	<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
<script>
	let directOpen = '<?= $directOpen ?>';
	if (directOpen.length)
		location.href = directOpen;

	// window.onscroll = function(ev) {
	// 	document.querySelector(".BottomBanner").style.display = 'none';
	// };
</script>

</html>