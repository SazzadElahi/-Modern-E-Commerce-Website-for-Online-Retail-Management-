<?php
class HomeController extends BaseController { public function index(mysqli $db): void { $d=$this->data($db);$d['categories']=Category::all($db);$d['products']=Product::browse($db,trim($_GET['search']??''),(int)($_GET['category']??0),$_GET['sort']??'');render('home',$d); } }
