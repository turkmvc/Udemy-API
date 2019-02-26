<?php
class userController extends Controller
{
    public function store()
    {
        if($_POST)
        {
            $returnArray = [];
            $returnArray['status'] = false;

            $name = mHelper::postVariable("name");
            $surname = mHelper::postVariable("surname");
            $email = mHelper::postVariable("email");
            $password = mHelper::postVariable("password");
            $gender = mHelper::postIntegerVariable("gender");

            if($name!="" and $surname!="" and $email!="" and $password!="")
            {
                // 1. Email Kontrolü yap
                if(!filter_var($email,FILTER_VALIDATE_EMAIL))
                {
                    $returnArray['message'] = "Email Formatı Hatalı";
                    return;
                }

                $c = $this->db->prepare("select * from users where email = ?");
                $c->execute(array($email));
                $count = $c->rowCount();
                if($count!=0)
                {
                    $returnArray['message'] = "Bu Email Kullanımda";
                    return;
                }


                $password = md5($password);
                $date = date("Y-m-d");
                $eklemeSorgu = $this->db->prepare("insert into users(name,surname,email,password,gender,date) values(?,?,?,?,?,?)");
                $result = $eklemeSorgu->execute(array($name,$surname,$email,$password,$gender,$date));
                if($result)
                {
                    $returnArray['status'] = true;
                    $returnArray['userId'] = $this->db->lastInsertId();
                    $returnArray['message'] = "Kullanıcı Başarı ile Eklendi";
                }
                else
                {
                    $returnArray['message'] = "Kullanıcı Eklenemedi";
                }

            }
            else
            {
                $returnArray['status'] = false;
                $returnArray['message'] = "Lütfen Tüm Alanları Doldurunuz";
            }

            echo json_encode($returnArray);

        }
        else
        {
            die("Post İşlemi Yapılmamış");
        }
    }

    public function info($id)
    {

        $returnArray = [];
        $returnArray['status'] = false;
        // 1. veritabanında böyle bir kullanıcı varmı
        $c = $this->db->prepare('select * from users where id = ?');
        $c->execute(array($id));
        $count = $c->rowCount();
        if($count == 0){
            $returnArray['message'] = "Böyle bir kullanıcı bulunamadı";
            return;
        }

        $w = $this->db->prepare('select * from users where id = ?');
        $w->execute(array($id));
        $result = $w->fetch(PDO::FETCH_ASSOC);
        $returnArray['data'] = $result;
        $returnArray['status'] = true;

        echo json_encode($returnArray);

    }

    public function login()
    {
        if($_POST) {

            $returnArray = [];
            $returnArray['status'] = false;
            $email = mHelper::postVariable("email");
            $password = mHelper::postVariable("password");

            if ($email == "" and $password == "") {
                $returnArray['message'] = "Lütfen Tüm Alanları Doldurunuz";
                return;
            }

            $c = $this->db->prepare("select * from users where email = ?");
            $c->execute(array($email));
            $count = $c->rowCount();
            if ($count == 0) {
                $returnArray['message'] = "Bu Email Sistemde kayıtlı Değil";
                return;
            }

            $w = $this->db->prepare("select * from users where email = ?");
            $w->execute(array($email));
            $result = $w->fetch(PDO::FETCH_ASSOC);
            if ($result['password'] != md5($password)) {
                $returnArray['message'] = "Şifreniz hatalı";
                return;
            }

            $returnArray['status'] = true;
            $returnArray['userId'] = $result['id'];
            $returnArray['message'] = "Başarılı bir şekilde Giriş Yaptınız";

            echo json_encode($returnArray);
        }
    }


    public function update()
    {
        if($_POST)
        {

            $returnArray = [];
            $returnArray['status'] = false;
            $id = mHelper::postIntegerVariable("id");
            $name = mHelper::postVariable("name");
            $surname = mHelper::postVariable("surname");
            $email = mHelper::postVariable("email");
            $password = mHelper::postVariable("password");
            $gender = mHelper::postIntegerVariable("gender");

            if($name=="" and $surname=="" and $email=="")
            {
                $returnArray['message'] = "Lütfen Tüm Alanları Doldurunuz";
                return;
            }

            // 2. Kullanıcı varmı kontrol et
            $c = $this->db->prepare("select * from users where id = ?");
            $c->execute(array($id));
            $count = $c->rowCount();
            if($count == 0){
                $returnArray['message'] = "Böyle bir kullanıcı yok";
                return;
            }

            // 3. email varmı kontrol et
            $cEmail = $this->db->prepare("select * from users where id != ? and email = ?");
            $cEmail->execute(array($id,$email));
            $countEmail = $cEmail->rowCount();
            if($countEmail !=0){
                $returnArray['message'] = "Bu Email Kullanımda";
            }


            $w = $this->db->prepare("select * from users where id = ? ");
            $w->execute(array($id));
            $result = $w->fetch(PDO::FETCH_ASSOC);
            // 4. şifre varmı kontrol et
            if($password == "")
            {
                $password = $result['password'];
            }
            else
            {
                $password = md5($result['password']);
            }

            // 5. Update et

            $update = $this->db->prepare("update users set name = ?, surname = ? , email = ? ,password = ? , gender = ? where id = ?");
            $updateResult = $update->execute(array($name,$surname,$email,$password,$gender,$id));
            if($updateResult)
            {
                $returnArray['status'] = true;
                $returnArray['message'] = "Bilgiler başarı ile değiştirildi";
            }
            else
            {
                $returnArray['message'] = "Bilgiler Düzenlenemedi";
            }

            echo json_encode($returnArray);

        }

    }
}