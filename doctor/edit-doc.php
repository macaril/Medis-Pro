<?php

//import database
include("../connection.php");



if($_POST){
        //print_r($_POST);
        // Baris ini dihapus: $result= $database->query("select * from webuser");
        
        $name=$_POST['name'];
        $nik=$_POST['nik'];
        $oldemail=$_POST["oldemail"];
        $spec=$_POST['spec'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $id=$_POST['id00'];
        
        if ($password==$cpassword){
            $error='3';

            // Check if the new email already exists and belongs to a different doctor (PDO Prepared Statement)
            $sql_check = "SELECT doctor.docid FROM doctor INNER JOIN webuser ON doctor.docemail=webuser.email WHERE webuser.email = ?";
            $stmt_check = $database->prepare($sql_check);
            $stmt_check->execute([$email]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            // $id2 stores the docid of the new email, or the current ID if no match
            $id2 = $result ? $result['docid'] : $id;
            
            // [DIHAPUS]: echo $id2."jdfjdfdh"; // Menghapus output debugging
            
            // Periksa jika ada bentrokan email (ID Dokter baru tidak sama dengan ID Dokter lama)
            if($id2!=$id){
                $error='1';
            }else{

                // UPDATE doctor (PDO Prepared Statement, mengganti query() lama)
                $sql1="update doctor set docemail=?, docname=?, docpassword=?, docnic=?, doctel=?, specialties=? where docid=?";
                $stmt_doctor = $database->prepare($sql1);
                $stmt_doctor->execute([$email, $name, $password, $nik, $tele, $spec, $id]);
                
                // UPDATE webuser (PDO Prepared Statement, mengganti query() lama)
                $sql2="update webuser set email=? where email=?";
                $stmt_webuser = $database->prepare($sql2);
                $stmt_webuser->execute([$email, $oldemail]);

                $error= '4';
                
            }
            
        }else{
            $error='2';
        }
    
    
        
        
    }else{
        //header('location: signup.php');
        $error='3';
    }
    

    header("location: doctors.php?action=edit&error=".$error."&id=".$id);
    ?>
    
   

</body>
</html>