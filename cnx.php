
        <?php
        $link=mysqli_connect("localhost","root","","hh");
        if(mysqli_connect_errno()){
            printf("ECHEC DE LA CONNEXION: %s\n",mysqli_connect_error());
            exit();
        }
        ?>