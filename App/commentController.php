<?php
class commentController extends Controller
{
    public function store()
    {
        if($_POST)
        {
            $returnArray = [];
            $returnArray['status'] = false;
            $userid = mHelper::postIntegerVariable('userid');
            $postid = mHelper::postIntegerVariable('postid');
            $text = mHelper::postVariable('text');

            if($text == "")
            {
                $returnArray['message'] = "Text Alanı Boş bırakılamaz";
                return;
            }

            $c = $this->db->prepare("select * from posts where id = ?");
            $c->execute(array($postid));
            $count = $c->rowCount();
            if($count == 0){
                $returnArray['message'] = "Böyle bir yazı yok";
                return;
            }


            $date = date("Y-m-d");
            $insert = $this->db->prepare("insert into comments(userid,postid,text,date) value (? ,? ,? ,?)");
            $insertResult = $insert->execute(array($userid,$postid,$text,$date));
            if($insertResult){
                $returnArray['message'] = "Yorum Başarı ile eklendi";
                $returnArray['status'] = true;
            }
            else
            {
                $returnArray['message'] = "Yorum Eklenemedi";
            }

            echo json_encode($returnArray);


        }
    }

    public function get($id)
    {
        $returnArray = [];
        $returnArray['status'] = false;
        $c = $this->db->prepare("select * from posts where id = ?");
        $c->execute(array($id));
        $count = $c->rowCount();
        if($count == 0){
            $returnArray['message'] = "Böyle bir post bulunamadı";
            return;
        }

        $list = $this->db->prepare("select * from comments where postid = ?");
        $list->execute(array($id));
        $result = $list->fetchAll(PDO::FETCH_ASSOC);

        /* Data Düzenleme */
        $returnDataArray = [];
        foreach($result as $key => $value)
        {
            $user = $this->db->prepare("select * from users where id = ?");
            $user->execute(array($value['userid']));
            $userInfo = $user->fetch(PDO::FETCH_ASSOC);
            $returnDataArray[$key]['id'] = $value['id'];
            $returnDataArray[$key]['postid'] = $value['postid'];
            $returnDataArray[$key]['user'] = $userInfo['name']." ".$userInfo['surname'];
            $returnDataArray[$key]['text'] = $value['text'];
            $returnDataArray[$key]['date'] = $value['date'];
        }


        $returnArray['status'] = true;
        $returnArray['data'] = $returnDataArray;
        echo json_encode($returnArray);

    }
}