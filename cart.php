<?php

session_start();
include 'cnx.php' ;
$tax=0.27;
$select = "SELECT 
commande.qte_demande,commande.numProduit,products.name , products.photo
, products.price , commande.numCommande , commande.statut , commande.error
FROM 
client,commande,products WHERE 
client.username = '{$_SESSION['usernameClient']}'
AND client.username = commande.usernameClient
AND products.id = commande.numProduit 
AND commande.statut = 'en panier'";

$result = mysqli_query($link,$select) or die(mysqli_error($link));
$produits = mysqli_fetch_all($result,MYSQLI_ASSOC);

$nrows = mysqli_num_rows($result);
#UPDATE QUANTITY

if(isset($_POST["plus"]))
{
$currentProduit =   $_POST["numCommande"];
$plus = "UPDATE commande SET qte_demande = commande.qte_demande + 1  WHERE numCommande = '$currentProduit' ";
$result_update= mysqli_query($link,$plus) or die(mysqli_error($link));
header("location:cart.php");
} 
if(isset($_POST["minus"]))
{
$currentProduit =   $_POST["numCommande"];
$plus = "UPDATE commande SET qte_demande = commande.qte_demande - 1  WHERE numCommande = '$currentProduit' ";
$result_update= mysqli_query($link,$plus) or die(mysqli_error($link));

header("location:cart.php");

} 


#DELETE ITEM
if(isset($_POST["delete"]))
{
$currentProduit =   $_POST["numCommande"];
$delete = "DELETE FROM commande WHERE commande.numCommande = '$currentProduit' ";
$result_delete = mysqli_query($link,$delete) or die(mysqli_error($link));
header("location:cart.php");
}
# SI LE PRODUIT A ETE ACHETER :
if(isset($_POST["submit"]))
{       
$currentProduit =   $_POST["numProduit"];
$numCommande = $_POST["numcommande"];
$qte_demande = $_POST["qte_demande"];
$codeAchat = $_POST["code"];
$select = "SELECT qteProduit FROM products WHERE id = '$currentProduit' ";
$result = mysqli_query($link,$select) or die(mysqli_error($link));
$row = mysqli_fetch_row($result);
$qte_stock = $row['0'];

if( $qte_stock < $qte_demande)
{
$update_error = "UPDATE commande SET error = 'Quantité en stock insuffisante !'  WHERE numCommande = '$numCommande' ";
$request =  mysqli_query($link,$update_error) or die(mysqli_error($link));
header("location:cart.php");
} 
else if( $qte_demande <= 0)
{
$update_error = "UPDATE commande SET error = 'Quantité invalide'  WHERE numCommande = '$numCommande' ";
$request =  mysqli_query($link,$update_error) or die(mysqli_error($link));


header("location:cart.php");
}
else{
$update_error = "UPDATE commande SET error = ''  WHERE numCommande = '$numCommande' ";
$request =  mysqli_query($link,$update_error) or die(mysqli_error($link));

$update2 = "UPDATE commande SET statut = 'commander' WHERE numCommande = '$numCommande'";
$result2 = mysqli_query($link,$update2) or die(mysqli_error($link));

$quantite = $qte_stock - $qte_demande;
$update3 = "UPDATE products SET qteProduit = '$quantite' WHERE id = '$currentProduit'";
$result3 = mysqli_query($link,$update3) or die(mysqli_error($link));
header("location:confirm.php?id=$numCommande");
} 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecElec.com</title>
  <link rel="stylesheet" href="cart.css">
  <link
    href="https://fonts.googleapis.com/css?family=Source+Sans+3:200,300,regular,500,600,700,800,900,200italic,300italic,italic,500italic,600italic,700italic,800italic,900italic"
    rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
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
        <a href="main.php">review</a>
        <a href="main.php">contact</a>
    </nav>

    <div class="icons">
        <a href="#" class="fas fa-heart"></a>
        <a href="cart.php" class="fas fa-shopping-cart"></a>
        <a href="logout.php" class="fa-solid fa-right-from-bracket"></i></a>
    </div>

</header>
<!-- about section starts  -->

  <main class="container">
    
    <div class="item-flex">

<section class="cart">

  <div class="cart-item-box">

    <h2 class="section-heading">Produits</h2>
    <?php $total = 0 ?>
    <?php foreach($produits as $produit) : ?>
    <div class="product-card">
      <div class="card">
        <div class="img-box">
          <img src="images/<?php echo $produit['photo'] ?>" alt="Cabbage" width="80px" class="product-img">
        </div>
        <div class="detail">
          <h4 class="product-name"><?php echo $produit['name'] ?></h4>
          <div class="wrapper">
          <form action="cart.php" method="POST">
            <div class="product-qty">
              <button type="submit"  name="minus" id="decrement">
                <ion-icon name="remove-outline"></ion-icon>
              </button>
              <input type="number" name="qte_demande" style = " width:20px;"value="<?php echo $produit['qte_demande'] ?>">
              <input type="hidden" name="numCommande" value="<?php echo $produit['numCommande'];?>">
              <button type ="submit" name="plus" id="increment">
                <ion-icon name="add-outline"></ion-icon>
              </button>
              </div>
              </form>                  
            <div class="price">
              $ <span id="price"><?php echo $produit['price'] ?></span>
            </div>
          </div>
        </div>
        <form action="cart.php" method="POST">
        <input type="hidden" name="numCommande" value="<?php echo $produit['numCommande'];?>">
        <button type="submit" name="delete" class="product-close-btn">
          <ion-icon name="close-outline"></ion-icon>
        </button>
        </form>
      </div>
    </div>   
    <?php $total += ($produit['price']*$produit['qte_demande'])?>
    <?php endforeach ; ?>
  </div>
</section>
      <section class="checkout">
        <h2 class="section-heading">Payment Details</h2>
        <div class="payment-form">
          <div class="wrapper">
            <div class="discount-token">
              <label for="discount-token" class="label-default" >Code D'achat</label>             
              <div class="wrapper-flex">
              <form action="cart.php" method="POST">
                    <input type="text" name="code" id="discount-token" class="input-default"> 
                    <button type="submit" name="submit" id="valider" class="btn btn-outline" style="margin-top: 20px; margin-left:200px">Valide</button>
                    <input type="hidden" name="numProduit" value="<?php echo $produit['numProduit'];?>">
                    <input type="hidden" name="numcommande" value="<?php echo $produit['numCommande'];?>">
                    <input type="hidden" name="qte_demande" value="<?php echo $produit['qte_demande'];?>">
              </div>
              </form>
            </div>
  
            <div class="amount">
  
              <div class="subtotal">
                <span>Subtotal</span> <span>$ <span id="subtotal"><?php echo $total - $tax?> </span></span>
              </div>
  
              <div class="tax">
                <span>Tax</span> <span>$ <span id="tax"><?php echo $tax ?> </span></span>
              </div>

              <div class="shipping">
                <span>Number of Items</span> <span><span id="shipping"><?PHP  echo sizeof($produits);?></span></span>
              </div>

              <div class="total">
                <span>Total</span> <span>$ <span id="total"><?php echo $total ?> </span></span>
              </div>
  
            </div>
  
          </div>


    </div>
  </main>
  <script src="scriptcart.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>