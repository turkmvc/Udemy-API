<?php
class postsController extends  Controller
{
    public function list($id)
    {

        $returnArray = [];
        $returnArray['status'] = false;
        $c = $this->db->prepare("select * from category where id = ?");
        $c->execute(array($id));
        $count = $c->rowCount();
        if($count == 0) {
            $returnArray['message'] = "Böyle bir kategori yok";
            return;
        }

        $list = $this->db->prepare("select * from posts where categoryId = ?");
        $list->execute(array($id));
        $result = $list->fetchAll(PDO::FETCH_ASSOC);
        $returnArray['status'] = true;
        $returnArray['data'] = $result;
        echo json_encode($returnArray);
    }


    public function detail($id)
    {

        $returnArray = [];
        $returnArray['status'] = false;

        $c = $this->db->prepare("select * from posts where id = ?");
        $c->execute(array($id));



        $count = $c->rowCount();
        if($count == 0) {
            $returnArray['message']  ="Böyle Bir Post Bulunamadı";
            return;
        }


        $data = $c->fetch(PDO::FETCH_ASSOC);
        $returnArray['data'] = $data;
        $returnArray['status'] = true;

        echo json_encode($returnArray);
    }
}