
<?php
        session_start();
        include 'cnx.php' ;
            if(isset($_GET['id'])){
                $id = mysqli_real_escape_string($link,$_GET['id']);
            }
        
        $select = "SELECT * FROM client,commande,products WHERE 
            client.username= '{$_SESSION['usernameClient']}'
           AND client.username = commande.usernameClient
           AND products.id = commande.numProduit 
           AND commande.numCommande = '$id'
           AND commande.statut = 'commander' ";
        
         $result = mysqli_query($link,$select) or die(mysqli_error($link));
         $produit = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="pdf.css" />
    <script src="pdf.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

</head>

<body>
    <div class="container d-flex justify-content-center mt-50 mb-50">
        <div class="row">
            <div class="col-md-12 text-right mb-3">
                <button class="btn btn-primary" id="download"> download pdf</button>
            </div>
            <div class="col-md-12">
                <div class="card" id="invoice">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title text-primary">Facture de paiement</h6>
                        <h4 style="color:#000; font-size:20px;" >Cher/Chere <?php echo $_SESSION['usernameClient'];?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-4 pull-left">

                                    <ul class="list list-unstyled mb-0 text-left">
                                        <li>33 skoukou</li>
                                        <li>kiffa city</li>
                                        <li>+1 474 44737 47 </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4 ">
                                    <div class="text-sm-right">
                                        <h4 class="invoice-color mb-2 mt-md-2">Numero : <?php echo $produit['numCommande'];?></h4>
                                        <ul class="list list-unstyled mb-0">
                                            <li>Date Commande: <span class="font-weight-semibold"><?php echo $produit['date_commande'];?></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Qantite Commande</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="font-weight-semibold"><?php echo $produit['name']?></span></td>
                                    <td><span class="font-weight-semibold"><?php echo $produit['qte_demande']?></span></td>
                                    <td><span class="font-weight-semibold"><?php echo $produit['qte_demande'] * $produit['price'] ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        </tbody>
                                    </table>
                                    <span class="gg">Achat reussie ! </span>
                                 <span class="more"> Besoin d'autres produits ?</span>
                                 <a href="main.php"><button id="valider">Visiter notre boutique</button></a></br></br>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</body>

</html>