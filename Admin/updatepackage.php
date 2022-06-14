<?php if (!isset($_SESSION)) {
	session_start();
} ?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />

	<link href="../css/bootstrap.css" rel='stylesheet' type='text/css' />
	<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<script src="js/jquery.min.js"></script>

</head>

<body>
	<?php
	if ($_SESSION['loginstatus'] == "") {
		header("location:loginform.php");
	}
	?>

	<?php include('function.php'); ?>

	<?php
	if (isset($_POST["sbmt"])) {
		$cn = makeconnection();
		$f1 = 0;
		$f2 = 0;
		$f3 = 0;

		$target_dir = "packimages/";
		//t4
		$target_file = $target_dir . basename($_FILES["t4"]["name"]);
		$uploadok = 1;
		$imagefiletype = pathinfo($target_file, PATHINFO_EXTENSION);

		if (move_uploaded_file($_FILES["t4"]["tmp_name"], $target_file)) {
			$f1 = 1;
		}

		//t5
		$target_file = $target_dir . basename($_FILES["t5"]["name"]);
		$uploadok = 1;
		$imagefiletype = pathinfo($target_file, PATHINFO_EXTENSION);

		//t6
		$target_file = $target_dir . basename($_FILES["t6"]["name"]);
		$uploadok = 1;
		$imagefiletype = pathinfo($target_file, PATHINFO_EXTENSION);

		//check file size
		if ($_FILES["t6"]["size"] > 500000) {
			echo "<script type='text/javascript'>toastr.success('Your file is too large! ')</script>";
			$uploadok = 0;
		} else {
			if (move_uploaded_file($_FILES["t6"]["tmp_name"], $target_file)) {
				$f3 = 1;
			}
		}
	}

	?>

	<?php
	if (isset($_POST["sbmt"])) {
		$cn = makeconnection();

		if (empty($_FILES['t3']['name'])) {

			$s = "update package set Packname='" . $_POST["t1"] . "',Category='" . $_POST["t2"] . "',Subcategory='" . $_POST["t3"] . "',Packprice='" . $_POST["t8"] . "',Pic1='" . $_POST["h1"] . "',Pic2='" . $_POST["h2"] . "',Pic3='" . $_POST["h3"] . "',Detail='" . $_POST["t7"] . "' where Packid='" . $_POST["s1"] . "'";
		} else {


			$s = "update package set Packname='" . $_POST["t1"] . "',Category='" . $_POST["t2"] . "',Subcategory='" . $_POST["t3"] . "',Packprice='" . $_POST["t8"] . "',Pic1='" .  basename($_FILES["t4"]["name"]) . "',Pic2='" .  basename($_FILES["t5"]["name"]) . "',Pic3='" .  basename($_FILES["t6"]["name"]) . "',Detail='" . $_POST["t7"] . "' where Packid='" . $_POST["s1"] . "'";
		}
		mysqli_query($cn, $s);
		mysqli_close($cn);
		echo "<script type='text/javascript'>toastr.success('Package updated')</script>";
	}
	?>

	<div style="padding-top:100px; box-shadow:1px 1px 20px black; min-height:100vh" class="container">
		<div style="border-right:1px solid #999; min-height:450px;">
			<?php include('left.php'); ?>
		</div>
		<div>
			<form method="post" enctype="multipart/form-data">
				<table border="0" width="500px" height="700px" align="center" class="tableshadow" style="border-color: darkgray;">
					<tr>
						<td colspan="2" class="toptd" style="background-color: dimgray; ">Update Package</td>
					</tr>
					<tr>
						<td class="lefttxt" style="color: dimgray; ">Select Package</td>
						<td><select name="s1" required />
							<option value="">Select</option>

							<?php
							$cn = makeconnection();
							$s = "select * from package";
							$result = mysqli_query($cn, $s);
							$r = mysqli_num_rows($result);

							while ($data = mysqli_fetch_array($result)) {
								if (isset($_POST["show"]) && $data[0] == $_POST["s1"]) {
									echo "<option value=$data[0] selected>$data[1]</option>";
								} else {
									echo "<option value=$data[0]>$data[1]</option>";
								}
							}
							mysqli_close($cn);


							?>

							</select>
							<input type="submit" value="Show" name="show" formnovalidate />
							<?php
							if (isset($_POST["show"])) {
								$cn = makeconnection();
								$s = "select * from package where Packid='" . $_POST["s1"] . "'";
								$result = mysqli_query($cn, $s);
								$r = mysqli_num_rows($result);

								$data = mysqli_fetch_array($result);
								$Packid = $data[0];
								$Packname = $data[1];
								$Category = $data[2];
								$Subcategory = $data[3];
								$Packprice = $data[4];
								$Pic1 = $data[5];
								$Pic2 = $data[6];
								$Pic3 = $data[7];
								$Detail = $data[8];

								mysqli_close($cn);
							}

							?>

						</td>
					</tr>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Package Name</td>
						<td><input type="text" value="<?php if (isset($_POST["show"])) {
															echo $Packname;
														} ?> " name="t1" required pattern="[a-z A-z ]{1,50}" title"Please Enter Only Characters between 1 to 50 for Package Name" /></td>
					</tr>
					<tr>
						<td class="lefttxt" style="color: dimgray; ">Select Category</td>
						<td><select name="t2" required />
							<option value="">Select</option>

							<?php
							$cn = makeconnection();
							$s = "select * from category";
							$result = mysqli_query($cn, $s);
							$r = mysqli_num_rows($result);

							while ($data = mysqli_fetch_array($result)) {
								if (isset($_POST["show"]) && $Category == $data[0]) {

									echo "<option value=$data[0] selected='selected' >$data[1]</option>";
								} else {
									echo "<option value=$data[0]>$data[1]</option>";
								}
							}
							mysqli_close($cn);

							?>

							</select>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Select Subcategory</td>
						<td><select name="t3" required />
							<option value="">Select</option>

							<?php
							$cn = makeconnection();
							$s = "select * from subcategory";
							$result = mysqli_query($cn, $s);
							$r = mysqli_num_rows($result);

							while ($data = mysqli_fetch_array($result)) {
								if (isset($_POST["show"]) && $Subcategory == $data[0]) {

									echo "<option value=$data[0] selected='selected' >$data[1]</option>";
								} else {
									echo "<option value=$data[0]>$data[1]</option>";
								}
							}
							mysqli_close($cn);

							?>

							</select>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Package Price</td>
						<td><input type="text" name="t8" value="<?php if (isset($_POST["show"])) {
																	echo $Packprice;
																} ?> " /></td>
					</tr>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Old Pic</td>
						<td><img src="packimages/<?php echo @$Pic1; ?>" width="150px" height="50px" />
							<input type="hidden" name="h1" value="<?php if (isset($_POST["show"])) {
																		echo $Pic1;
																	} ?>" />
						</td>
					</tr>
					<tr>
						<td class="lefttxt" style="color: dimgray; ">Upload Pic1</td>
						<td><input type="file" name="t4" /></td>
					</tr>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Old Pic</td>
						<td><img src="packimages/<?php echo @$Pic2; ?>" width="150px" height="50px" />
							<input type="hidden" name="h2" value="<?php if (isset($_POST["show"])) {
																		echo $Pic2;
																	} ?>" />
						</td>
					</tr>
					<tr>
						<td class="lefttxt" style="color: dimgray; ">Upload Pic2</td>
						<td><input type="file" name="t5" /></td>
					</tr>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Old Pic</td>
						<td><img src="packimages/<?php echo @$Pic3; ?>" width="150px" height="50px" />
							<input type="hidden" name="h3" value="<?php if (isset($_POST["show"])) {
																		echo $Pic3;
																	} ?>" />
						</td>
					</tr>
					<tr>
						<td class="lefttxt" style="color: dimgray; ">Upload Pic3</td>
						<td><input type="file" name="t6" /></td>
					</tr>

					<tr>
						<td class="lefttxt" style="color: dimgray; ">Details</td>
						<td><textarea name="t7" /><?php if (isset($_POST["show"])) {
														echo $Detail;
													} ?></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Update" name="sbmt" class="btn btn-primary btn-sm" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>

</html>