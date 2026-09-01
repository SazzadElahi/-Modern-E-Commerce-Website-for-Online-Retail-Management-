<?php
class ProductController extends BaseController { public function show(mysqli $db): void { $id=(int)($_GET['id']??0);$p=Product::find($db,$id);if(!$p){header('Location: index.php');exit;}$d=$this->data($db);$d['product']=$p;$d['reviews']=Review::forProduct($db,$id);render('product',$d); } }
