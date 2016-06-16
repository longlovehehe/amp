<?php

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
        // 检查目录
        $dir = "/files/doc/" . Date("Ym") . "/";
        if (!is_dir($path . $dir)) {
                mkdir($path . $dir);
        }

        // 生成文件名掩码
        $extension = pathinfo($_FILES['fileToUpload']['name']);
        $dir .= uniqid() . "." . $extension['extension'];

        if (!move_uploaded_file($_FILES['fileToUpload']["tmp_name"], $path . $dir)) {
                throw new Exception("文件创建失败，请系统管理员检查文件权限或磁盘空间", -6);
        }
        //$result = $tools->call($dir);
} catch (Exception $e) {
        $result = $tools->call($e->getMessage(), $e->getCode());
}

echo "<script>parent.callback($result);</script>";

