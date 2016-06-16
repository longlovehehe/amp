<?php

/**
 * 图片管理类
 * @package OMP_Common_dao
 */
class pic extends db {

    public function __construct($data) {
        parent::__construct();
        $this->data = $data;
    }

    public function getFile() {
        $filetype = array("image/jpeg", "image/pjpeg");

        if ($_FILES['fileToUpload']['error'] != "") {
            switch ($_FILES['fileToUpload']['error']) {
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception(L("没有文件被选择"), -1);
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception(L("超出了大小限制"), -2);
                default:
                    throw new Exception(L("未知的上传错误"), -3);
            }
        }
        if ($_FILES['fileToUpload']['size'] > 2048 * 1000) {
            throw new Exception(L("文件太大，不给上传"), -4);
        }
        if (!in_array($_FILES['fileToUpload']["type"], $filetype)) {
            throw new Exception(L("未被允许的文件格式【仅支持jpg格式】"), -5);
        }

        $extension = pathinfo($_FILES['fileToUpload']['name']);
        $basename = uniqid() . "." . $extension['extension'];

        $file['name'] = $basename;
        $file['data'] = $_FILES['fileToUpload']["tmp_name"];
        return $file;
    }

    public function getId() {
        $file = $this->getFile();
        $pid = $this->md5r();

        $sql = <<<SQL
INSERT INTO
        "T_Pic"("p_id","p_data")
VALUES(:p_id,:p_data)
SQL;
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':p_id', $pid);
        $sth->bindValue(':p_data', file_get_contents($file['data']), PDO::PARAM_LOB);
        $sth->execute();
        return $pid;
    }

    public function show() {
        $pid = $this->data['pid'];

        if ($pid == '') {
            header('Content-type: image/jpg');
            echo file_get_contents('../www/images/face.jpg');
            return;
        }
        $where = <<<WHERE
                        p_id = '$pid'
WHERE;
        $result = $this->table('T_Pic')->filed(array('p_id', 'p_data::bytea'), FALSE)->where($where)->select();
        header('Content-type: image/jpg');

        //文件大小超出9999999，将停止读取
        /*
         * 以下情况也将停止读取
         * 读取了 length 个字节
          到达了文件末尾（EOF）
          a packet becomes available or the socket timeout occurs (for network streams)
          if the stream is read buffered and it does not represent a plain file, at most one read of up to a number of bytes equal to the chunk size (usually 8192) is made; depending on the previously buffered data, the size of the returned data may be larger than the chunk size.
         */
        if (!is_null($result[0]['p_data'])) {
            print fread($result[0]['p_data'], 9999999);
            exit;
        }
        echo file_get_contents('../images/face.jpg');
    }

    public function clearAll() {
        $sql = <<<SQL
                        DELETE
FROM
	"T_Pic"
WHERE
	p_id NOT IN (SELECT u_pic FROM "T_User")
SQL;
        $this->pdo->exec($sql);
    }

}
