<?php

/**
 * cms实体类
 * @package OMP_CMS_dao
 * @require {@see db}
 */
class cms extends db {

	public $data;

	public function __construct($data) {
		parent::__construct();
		$this->data = $data;
	}

	public function upload_soft($info) {
		$edit = false;
		if ($this->data['flag'] == "edit") {
			$edit = true;
		}
		if ($info['soft_name']['size'] >= '31457280' || $info['soft_name']['size'] == 0) {
			return false;
		}
		$soft_name = isset($info['soft_name']['name']) ? $info['soft_name']['name'] : '';
		$tmp_file = isset($info['soft_name']['tmp_name']) ? $info['soft_name']['tmp_name'] : '';
//          $p_time=data("Y-m-d");

		if (!empty($tmp_file)) {
			$p_content = file_get_contents($tmp_file);
		}
		if (!$edit) {
			$sql = 'INSERT INTO "public"."T_Packet" ("p_dir","p_file", "p_version","p_time", "p_type", "p_content") VALUES (:p_dir,:p_file, :p_version, :p_time, :p_type, :p_content)';
			$sth = $this->pdo->prepare($sql);
		} else {
			$sql = 'UPDATE "public"."T_Packet" SET p_dir = :p_dir,p_file = :p_file,p_version = :p_version,p_time = :p_time,p_type = :p_type,p_content = :p_content WHERE p_id = :p_id';

			$sth = $this->pdo->prepare($sql);

			$sth->bindValue(':p_id', $this->data["pid"]);
		}
		$sth->bindValue(':p_dir', addslashes($this->data["dir_name"]));
		$sth->bindValue(':p_file', addslashes($soft_name));
		$sth->bindValue(':p_version', addslashes($this->data["ptt_version"]));
		$sth->bindValue(':p_time', 'now()', PDO::PARAM_INT);
		$sth->bindValue(':p_type', $this->data["ptype"]);
		$sth->bindValue(':p_content', $p_content, PDO::PARAM_LOB);
		try {
			$sth->execute();
			$this->updatezero();
		} catch (Exception $ex) {
			echo $ex;
			$log = DL('新建目录保存失败，原因：') . $ex->getMessage();
			$this->log($log, 3, 2);
			$msg["msg"] = L('新建目录保存失败，原因：') . $ex->getMessage();
			$msg["status"] = -1;
			return $msg;
		}
		$msg["status"] = 0;

		if ($edit) {
			$log = DL('版本文件修改成功');
			$msg["msg"] = L('版本文件修改成功');
		} else {
			$log = DL('版本文件上传成功');
			$msg["msg"] = L('版本文件上传成功');
		}

		$this->log($log, 3, 0);
		return $msg;
	}

	function getIos($order = false) {
		$where = " WHERE 1=1 ";
		$where .= "AND p_type = 'ios'";

		if ($order) {
			$where .= ' ORDER BY p_id ASC';
		}
		return $where;
	}

	function getAndroid($order = false) {
		$where = " WHERE 1=1 ";
		$where .= "AND p_type = 'android'";
		if ($order) {
			$where .= ' ORDER BY p_id ASC';
		}
		return $where;
	}

	public function getandroidList() {
		$sql = 'SELECT p_id,p_type,p_version,p_file,p_dir FROM "T_Packet"';
		$sql = $sql . $this->getAndroid();
		$sql = $sql . $limit;

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function getiosList() {
		$sql = 'SELECT p_id,p_type,p_version,p_file,p_dir FROM "T_Packet"';
		$sql = $sql . $this->getIos();
		$sql = $sql . $limit;

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

//
	public function getById() {
		$sql = 'SELECT p_id,p_dir,p_file,p_version,p_time,p_type FROM "T_Packet" WHERE p_id = :p_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':p_id', $this->data['p_id'], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	public function getanTotal() {
		$sql = 'SELECT COUNT(p_id) AS total FROM "public"."T_Packet"';
		$sql = $sql . $this->getAndroid();
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function getiosTotal() {
		$sql = 'SELECT COUNT(p_id) AS total FROM "public"."T_Packet"';
		$sql = $sql . $this->getIos();
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function getfetchinfo($ptype, $pdir) {
		$sql = 'SELECT p_id,p_dir,p_file,p_version,p_time,p_type FROM "T_Packet" WHERE p_dir = :p_dir AND p_type = :p_type';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':p_dir', $pdir);
		$sth->bindValue(':p_type', $ptype);
		$data = $sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	public function getinfo() {
		$sql = 'SELECT p_id,p_file,p_version,p_dir FROM "T_Packet" WHERE "T_Packet".p_id = :p_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':p_id', $this->data["p_id"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	public function del_dir() {
		$data = $this->data;
		$p_id = (int) $data['p_id'];
		$sql = 'DELETE  FROM "T_Packet" WHERE "T_Packet".p_id =' . $p_id;
//             $sth = $this->pdo->prepare($sql);
		//             $sth->bindValue(':p_id', $this->data["p_id"], PDO::PARAM_INT);
		$count = $this->pdo->exec($sql);
		$this->updatezero();
		return $count;
	}

	public function empty_dir() {
		$data = $this->data;
		$p_id = $data['p_id'];
		//var_dump($p_id);
		//$p_time=time();
		$soft_name = "";
		$version = "";
		$p_content = null;
		$sql = 'UPDATE "public"."T_Packet" SET p_file = :p_file,p_version = :p_version,p_time = :p_time ,p_content = :p_content WHERE p_id = :p_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':p_id', $p_id, PDO::PARAM_INT);
//            return var_dump($this->get0id());

		$sth->bindValue(':p_file', $soft_name);
		$sth->bindValue(':p_version', $version);
		$sth->bindValue(':p_time', 'now()', PDO::PARAM_INT);
		$sth->bindValue(':p_content', $p_content);

		$count = $sth->execute();
//             return 2;
		try {
			$this->updatezero();
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
		return $count;
	}

	public function updatezero() {

		if ($this->get0id() === false) {
			$sql0 = 'INSERT INTO "public"."T_Packet" ("p_id","p_dir","p_file", "p_version","p_time", "p_type", "p_content") VALUES ( :p_id,:p_dir,:p_file, :p_version, :p_time, :p_type,:p_content )';
			$sth0 = $this->pdo->prepare($sql0);
			$sth0->bindValue(':p_id', 0, PDO::PARAM_INT);
			$sth0->bindValue(':p_dir', "", PDO::PARAM_STR);
			$sth0->bindValue(':p_file', $soft_name, PDO::PARAM_STR);
			$sth0->bindValue(':p_version', $version, PDO::PARAM_STR);
			$sth0->bindValue(':p_time', 'now()', PDO::PARAM_INT);
			$sth0->bindValue(':p_type', "", PDO::PARAM_STR);
			$sth0->bindValue(':p_content', $p_content, PDO::PARAM_LOB);
		} else {

			$sql0 = 'UPDATE "public"."T_Packet" SET p_time = :p_time WHERE p_id = :p_id';
			$sth0 = $this->pdo->prepare($sql0);
			$sth0->bindValue(':p_id', 0, PDO::PARAM_INT);
			$sth0->bindValue(':p_time', 'now()', PDO::PARAM_INT);
		}

		$sth0->execute();
	}

	public function get0id() {
		$sql = 'SELECT p_id,p_file,p_version,p_dir FROM "T_Packet" WHERE "T_Packet".p_id = :p_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':p_id', 0, PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

}
