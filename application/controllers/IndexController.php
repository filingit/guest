<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->request = new Zend_Controller_Request_Http();
        $this->autUser = new Zend_Session_Namespace('autUser');
        $this->view->autUser = $this->autUser;
    }

    public function indexAction()
    {
		if (($this->request->getParam('login')=='admin') && ($this->request->getParam('login')=='admin')){
			$this->view->autUser->isAdmin = true;
		}
		
    }
    
	public function logoutAction()
	{
		$this->autUser->isAdmin = false;
		$this->_helper->redirector('index');
		
	}
	
	public function readAction()
	{
		$comments = array();
		$file = file('comments.txt');
		foreach ($file as $line){
			$comment = explode('|',$line);
			$comments[] = array(
				'id'=>$comment[0]
				,'name'=>$comment[1]
				,'email'=>$comment[2]
				,'comment'=>$comment[3]
				);
		}
		$this->view->comments = $comments;
		$this->view->edit_id = $this->request->getParam('edit_id');
		
	}

	public function addAction()
	{
		$file = fopen('comments.txt', 'a');
		fwrite($file, time().'|'.$this->request->getParam('user').'|'.$this->request->getParam('email').'|'.$this->request->getParam('comment')."\n");
		fclose($file);
	}
	
	public function saveAction()
	{
		if($this->autUser->isAdmin){
			$fileIn = file('comments.txt');
			$fileOut = fopen('comments.txt', 'w');
			foreach ($fileIn as $line){
				if (strpos($line,$this->request->getParam('id').'|')!==0){
					fwrite($fileOut,$line);
				}else{
					$comment = explode('|',$line);
					fwrite($fileOut, $comment[0].'|'.$this->request->getParam('user').'|'.$comment[2].'|'.$this->request->getParam('comment'));
				}
			}
			fclose($fileOut);
		}
	}
	
	public function delAction()
	{
		if($this->autUser->isAdmin){
			$fileIn = file('comments.txt');
			$fileOut = fopen('comments.txt', 'w');
			foreach ($fileIn as $line){
				if (strpos($line,$this->request->getParam('id').'|')!==0){
					fwrite($fileOut,$line);
				}
			}
			fclose($fileOut);
		}
	}
}

