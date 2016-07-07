<?php
require 'jajjimento.php';

session_start();

class JajjimentoTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        $this->Jajji = new Jajjimento();
        $this->data  = ['username' => 'YamiOdymel',
                        'password' => 'yamiodymel',
                        'confirm'  => 'yamiodymel',
                        'birthday' => '1998-07-13',
                        'email'    => 'yamiodymel@gmail.com',
                        'gender'   => 'f',
                        'option'   => 'C',
                        'age'      => '18',
                        'ip'       => '127.0.0.1',
                        'ipv6'     => '::1',
                        'url'      => 'http://teameow.com/'];
        $this->minData  = ['username' => 'y',
                           'password' => 'y',
                           'confirm'  => 'y',
                           'birthday' => '1',
                           'email'    => 'y',
                           'gender'   => 'c',
                           'option'   => 'F',
                           'age'      => '0',
                           'ip'       => '1',
                           'ipv6'     => '2',
                           'url'      => '..'];
    }

    function testBasic()
    {
        $this->Jajji->add('username')->type('length')->min(3)->max(12)->required()
                    ->add('password')->type('length')->min(6)->max(30)->required()
                    ->add('birthday')->type('date')->dateFormat('YYYY-mm-dd')->required()
                    ->add('email')->type('email')->required()
                    ->add('gender')->type('gender')->required()
                    ->add('option')->type('in')->inside(['A', 'B', 'C'])->required()
                    ->add('confirm')->equals('password')
                    ->add('age')->type('range')->min(1)->max(99)->format('/0-9/')->trim()->required()
                    ->add('ip')->type('ip')->required()
                    ->add('ip')->type('ipv4')->required()
                    ->add('ipv6')->type('ipv6')->required()
                    ->add('url')->type('url')->required();

        $this->assertTrue($this->Jajji->source($this->data)->check());
    }

    function testShorthands()
    {
        $this->Jajji->add('username')->length(3, 12)->req()
                    ->add('password')->length(6, 30)->req()
                    ->add('birthday')->date('YYYY-mm-dd')->req()
                    ->add('email')->email()->req()
                    ->add('gender')->gender()->req()
                    ->add('option')->in(['A', 'B', 'C'])->req()
                    ->add('confirm')->equals('password')
                    ->add('age')->range(1, 99)->format('/0-9/')->trim()->req()
                    ->add('ip')->ip()->req()
                    ->add('ip')->ipv4()->req()
                    ->add('ipv6')->ipv6()->req()
                    ->add('url')->url()->req();

        $this->assertTrue($this->Jajji->source($this->data)->check());
    }

    function testFails()
    {
        $this->Jajji->add('username')->type('length')->min(3)->max(12)->required()
                    ->add('password')->type('length')->min(6)->max(30)->required()
                    ->add('birthday')->type('date')->dateFormat('YYYY-mm-dd')->required()
                    ->add('email')->type('email')->required()
                    ->add('gender')->type('gender')->required()
                    ->add('option')->type('in')->inside(['A', 'B', 'C'])->required()
                    ->add('confirm')->equals('password')
                    ->add('age')->type('range')->min(1)->max(99)->format('/0-9/')->trim()->required()
                    ->add('ip')->type('ip')->required()
                    ->add('ip')->type('ipv4')->required()
                    ->add('ipv6')->type('ipv6')->required()
                    ->add('url')->type('url')->required();

        $this->assertFalse($this->Jajji->source($this->minData)->check());
    }

    function testCsrf()
    {
        $this->Jajji->csrf = true;

        $this->Jajji->add('username')->length(3, 12)->req()
                    ->add('password')->length(6, 30)->req()
                    ->add('birthday')->date('YYYY-mm-dd')->req()
                    ->add('email')->email()->req();

        $this->assertFalse($this->Jajji->source($this->data)->check());

        echo $this->Jajji->getCrumbValue();
    }

    function testInsertCrumb()
    {
        $this->Jajji->csrf = true;

        $this->Jajji->add('username')->length(3, 12)->req()
                    ->add('password')->length(6, 30)->req()
                    ->add('birthday')->date('YYYY-mm-dd')->req()
                    ->add('email')->email()->req();

        $this->assertFalse($this->Jajji->source($this->data)->check());

        echo $this->Jajji->insertCrumb();
        echo $this->Jajji->insertCrumb(['ng-model' => 'test',
                                        'ng-name'  => 'test2']);
    }

    function testSaveAndLoad()
    {
        $rules = $this->Jajji->add('username')->length(3, 12)->req()
                             ->add('password')->length(6, 30)->req()
                             ->add('birthday')->date('YYYY-mm-dd')->req()
                             ->add('email')->email()->req()
                             ->add('gender')->gender()->req()
                             ->add('option')->in(['A', 'B', 'C'])->req()
                             ->add('confirm')->equals('password')
                             ->add('age')->range(1, 99)->format('/0-9/')->trim()->req()
                             ->add('ip')->ip()->req()
                             ->add('ip')->ipv4()->req()
                             ->add('ipv6')->ipv6()->req()
                             ->add('url')->url()->req()->save();

        $this->assertTrue($this->Jajji->source($this->data)->loadCheck($rules));
    }
}
?>