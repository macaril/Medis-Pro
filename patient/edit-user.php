<?php

include("../connection.php");



if($_POST){
        //print_r($_POST);
        // Baris ini dihapus: $result= $database->query("select * from webuser");

        $name=$_POST['name'];
        $nic=$_POST['nic'];
        $oldemail=$_POST["oldemail"];
        $address=$_POST['address'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $id=$_POST['id00'];
        
        if ($password==$cpassword){
            $error='3';

            // Check if the new email already exists and belongs to a different user PID (PDO Prepared Statement)
            $sqlmain= "SELECT patient.pid FROM patient INNER JOIN webuser ON patient.pemail=webuser.email WHERE webuser.email = ?";
            $stmt = $database->prepare($sqlmain);
            $stmt->execute([$email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC); // Ambil PID dari email baru

            // $id2 stores the PID of the new email, or the current ID if no match (which means the email is unique or belongs to current user)
            $id2 = $result ? $result['pid'] : $id;
            
            // Periksa jika ada bentrokan email (PID baru tidak sama dengan PID lama)
            if($id2!=$id){
                $error='1';
            }else{

                // UPDATE patient (PDO Prepared Statement, mengganti query() lama)
                $sql1="update patient set pemail=?, pname=?, ppassword=?, pnik=?, ptel=?, paddress=? where pid=?";
                $stmt_patient = $database->prepare($sql1);
                $stmt_patient->execute([$email, $name, $password, $nic, $tele, $address, $id]);
                
                // [DIHAPUS]: echo $sql1; // Menghapus output debugging

                // UPDATE webuser (PDO Prepared Statement, mengganti query() lama)
                $sql2="update webuser set email=? where email=?";
                $stmt_webuser = $database->prepare($sql2);
                $stmt_webuser->execute([$email, $oldemail]);
                
                // [DIHAPUS]: echo $sql1; // Menghapus output debugging

                $error= '4';
                
            }
            
        }else{
            $error='2';
        }
    
    
        
        
    }else{
        //header('location: signup.php');
        $error='3';
    }
    

    header("location: settings.php?action=edit&error=".$error."&id=".$id);
    ?>
    
   

</body>
</html>