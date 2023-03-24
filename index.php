<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="images/ars_def.png" />
		<title>Gestion de stock</title>
		<link rel="stylesheet" href="CSS/bootstrap.min.css">
		<script src="js/jquery.slim.min.js"> </script>
		<script src="js/popper.min.js"> </script>
		<script src="js/bootstrap.bundle.min.js"> </script>
		<style>
			body{
				margin: 0px;
				background-color: #292929;
				cursor: url("https://accrazed.github.io/YoRHA-UI-BetterDiscord/Pointers/Hack_Normal_Select_v2.cur"), default !important;
			}
			a,button{
				cursor: url("https://accrazed.github.io/YoRHA-UI-BetterDiscord/Pointers/Hack_Link_Select_v2.cur"), default !important;
			}
			#main{
				width: 100%;
				height: 100%;
				position: absolute;
				overflow: hidden;
			}
			#box1{
				width: 530px;
				height: 625px;
				background-image: url('images/arsb.png');
				background-repeat: no-repeat;
				position: absolute;
				bottom: -100%;
				left: 30%;
				animation: anim 2s forwards, anim2 2s forwards 2s;
			}
			#box1:after
			{
				content: '';
				position: absolute;
				width: 530px;
				height: 625px;
				background-image: url('images/ars.png');
				background-repeat: no-repeat;
				left: 0px;
				z-index: -1;
			}@keyframes anim{
				from{
					bottom: -100%;
				}
				to{
					bottom: 0%;
				}
			}
			@keyframes anim2{
				from{
					left: 30%;
					width: 530px;
				}
				to{
					width: 0px;
					left: 50%;
				}
			}
			#box2{
				width: auto;
				height: auto;
				font-family: 'Bahnschrift Condensed';
				color: white;
				font-size: 10em;
				font-weight: 500;
				line-height: 130px;
				position: absolute;
				top: 250px;
				left: 100px;
				overflow: hidden;
			}
			#text{
				position: relative;
				left: -100%;
				animation: anim3 2s forwards 3s;
			}
			@keyframes anim3{
			from
			{
			left: -100%;
			}
			to{
					left: 0%;
			}
			}
			#box3{
				overflow: hidden;
			}
			#container{
				width: 100%;
				position: absolute;
				top: -100%;
				animation: anim4 2s forwards 2.5s;
			}
			#logo{
				float: left;
				margin-left: 100px;
				margin-top: 20px;
			}
			#logo img {
				width: 150px;
				height: 80px;
			}
			#menu{
				font-family: 'Behnschrift Condensed';
				font-size: 14px;
				color: white;
				letter-spacing: 2px;
				margin-right: 150px;
				margin-top: 20px;
				float: right;
			}
			#menu ul{
				list-style: none;
			}
			#menu ul li{
				display: inline-block;
				margin-left: 100px;
			}
			@keyframes anim4{
				from{
					top: -100%;
				}
				to{
					top: 0%;
				}
			}
			#text{
				position: relative;
				left: -100%;
				animation: anim3 2s forwards 3s;
			}
		</style>
	</head>
	<body>
		<div id="main">
			<div id="box1"></div>
			
			<div id="box2">
				<div id="text">
					GESTION<br/>DE STOCK
				</div>
			</div>
			<div id="box3">
				<div id="container">
					<div id="logo">
						<!-- <img src="images/logo.gif"/> -->
					</div>
					<div id="menu">
						<ul>
							<li>
								<button type="button" class="btn btn-dark">
									<a href="php/inscription.php" style="color:#ffffff;">Inscription</a></button>
							</li>
							<li>
								<button type="button" class="btn btn-dark">
									<a href="php/connexion.php" style="color:#ffffff;">Connexion</a></button>
								
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>