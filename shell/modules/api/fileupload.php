<?php

$filetype = array("image/jpeg", "image/png", "image/gif");
$path = $_SERVER['DOCUMENT_ROOT'] . "/omp/";
try {
        if ($_FILES['fileToUpload']['error'] != "") {
                switch ($_FILES['fileToUpload']['error']) {
                        case UPLOAD_ERR_NO_FILE:
                                throw new Exception("没有文件被选择", -1);
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                                throw new Exception("超出了大小限制", -2);
                        default:
                                throw new Exception("未知的上传错误", -3);
                }
        }
        if ($_FILES['fileToUpload']['size'] > 999999999) {
                throw new Exception("文件太大，异常中断了", -4);
        }
        if (!in_array($_FILES['fileToUpload']["type"], $filetype)) {
                //throw new Exception("未被允许的文件格式", -5);
        }
        // 检查目录
        $basedir = 'files/pic/' . Date("Ym") . '/';
        $dir = '' . $basedir;

        if (!is_dir($path . $dir)) {
                mkdir($path . $dir);
        }

        // 生成文件名掩码
        $extension = pathinfo($_FILES['fileToUpload']['name']);
        $basedir .= uniqid() . "." . $extension['extension'];
        $dir = '' . $basedir;
        if (!move_uploaded_file($_FILES['fileToUpload']["tmp_name"], $path . $dir)) {
                throw new Exception("文件创建失败，请系统管理员检查文件权限或磁盘空间。" . $path . $dir, -6);
        }
        $result = $tools->call($basedir);
} catch (Exception $e) {
        $result = $tools->call($e->getMessage(), $e->getCode());
}

echo "<script>parent.callback($result);</script>";
