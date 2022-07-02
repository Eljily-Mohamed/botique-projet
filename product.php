
<?php
include 'cnx.php' ;
session_start();
$_SESSION['$msg'] = " ";
$slug = $_GET['product'];	
$sql = "SELECT *, products.name AS prodname, products.id AS prodid FROM products  WHERE name = '$slug'";
$result = mysqli_query($link, $sql);
$product =  mysqli_fetch_assoc($result);	
$product_id = $product['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecElec.com</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="product.css">
</head>
<body>
<!-- header section starts  -->
<header>
    <input type="checkbox" name="" id="toggler">
    <label for="toggler" class="fas fa-bars"></label>
    <a href="#" class="logo">TecElec<span>.</span></a>
    <nav class="navbar">
        <a href="main.php">home</a>
        <a href="main.php">about</a>
        <a href="main.php">products</a>
        <a href="main.php">contact</a>
    </nav>
    <div class="icons">
        <a href="#" class="fas fa-heart"></a>
        <a href="cart.php" class="fas fa-shopping-cart"></a>
        <a href="logout.php" class="fa-solid fa-right-from-bracket"></i></a>
    </div>

</header>
<!-- about section starts  -->

<section class="about" id="about"  style="margin-top:5%;">
    <h1 class="heading"> <span> about </span>product</h1>
    <?php
if(isset($_POST['save'])) {     
	@$qte_demande = $_POST["quantity"];
	$idProduit = $_POST["id"];
	$check = "SELECT * from commande WHERE numProduit = '$idProduit' AND usernameClient = '{$_SESSION['usernameClient']}' ";
	$result = mysqli_query($link,$check) or die(mysqli_errno($link));
	$produits = mysqli_fetch_all($result,MYSQLI_ASSOC);
	if(mysqli_num_rows($result) && $produits[0]["statut"]=="en panier"){
        $_SESSION['$msg']= "<div class='alert-red'>
        <strong>Danger!</strong> Deja exsite dans Voter Card. <a href='cart.php' >Voir Cart</a>";
			@$newquantity = $qte_demande + $produits[0]['qte_demande'];
     	$update_qte =   " UPDATE commande SET qte_demande = '$newquantity' WHERE commande.numProduit =  '$idProduit' ";
	   $result = mysqli_query($link,$update_qte ) or die(mysqli_errno($link));
	}

	else{
        $_SESSION['$msg']= "<div class='alert'>
        <strong>Success!!</strong> Bien register Voir Your cart . <a href='cart.php'>Voir Cart</a>";
			$statut = "en panier";
		$insert = "INSERT INTO commande(usernameClient,numProduit,qte_demande,statut)
         VALUES('{$_SESSION['usernameClient']}','$idProduit',' $qte_demande','$statut') ";
		$send =  mysqli_query($link,$insert) or die(mysqli_errno($link));   
	}
		
	}?>
		<?php if (isset ($_SESSION['$msg'])){
            	echo $_SESSION['$msg'];?>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
       </div><?php }?>
	
    <div class="row">
        <div class="video-container">
		<img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : ''; ?>" width="90%"  >
        </div>
        <div class="content">
            <h3><?php echo $product['prodname']; ?></h3>
            <p><?php echo $product['descr']; ?></p>
            <p> PRICE : <b>&#36; <?php echo number_format($product['price'], 2); ?></b></p>
			<form method="post" action="">
			<input type="text" name="quantity" id="quantity" class="form-control input-lg" placeholder="Add Qantite" style="margin-bottom:10px; font-size:20px;">
			<input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
           <button type="submit" name="save"  class="btn"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
          </form>
        </div>
    </div>
</section>
</body>
</html>