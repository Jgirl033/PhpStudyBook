<?php
/**
 * Created by PhpStorm.
 * User: jgirl
 * Date: 16-2-16
 * Time: 下午9:53
 * Description:　<<深入PHP...>>第三章
 */

//知识大点一：类
class ShopProduct{//知识点１：创建类
    public $title="default product";
    public $producerMainName="main name";
    public $producerFirstName="first name";
    public $price=0;//知识点２：创建类中的属性

    function __construct($title,$firstName,$mainName,$price)
    {//知识点３：类中的构造方法，PHP5以上使用的方法
        $this->title=$title;
        $this->producerFirstName=$firstName;
        $this->producerMainName=$mainName;
        $this->price=$price;
    }

    function getProducer(){//知识点４：使用类中的方法
        return "{$this->producerFirstName}"." {$this->producerMainName}";
    }
}

//知识大点二：对象
//知识点１：有什么方法可以设置对象的属性
//实例化类生成对象，通过构造方法设置对象属性
$product1=new ShopProduct("My Antonia","Jgirl","Smith",0);
$product2=new ShopProduct("My Antonia","Willa","Cather",0);

//实例化类生成对象，通过直接赋值设置对象属性
$product2->title="My Antonia";
$product2->producerFirstName='Willa';
$product2->producerMainName='Cather';

var_dump($product1);
print $product1->title;
echo "<br>";
var_dump($product2);
echo "<br>";
print "author: {$product2->getProducer()}";
echo "<br>";


//知识大点三：参数和类型
//知识点１：PHP的基本类型和参数类型检查
class AddressManager{
    private $addresses=array("209.131.36.159","74.125.19.106");

    /**
     * 输出地址列表
     * 如果$resolve为true,则每个地址都会被解析成域名
     * @param $resolve boolean 是否解析地址
     */
    function outputAddress($resolve){
        foreach($this->addresses as $address){
            print $address;
            if(!is_bool($resolve)){
                print "outputAddress() required a Boolean arguement";
                echo "<br>";
//                die("outputAddress() required a Boolean arguement");
            }
            else{
                if($resolve){
                    //if(is_string($resolve)){
                    //$resolve=(preg_match("/false|no|off|/i",$resolve))?false:true;
                    print " (".gethostbyaddr($address).")";
                }
            }
            print "\n";
        }
    }
}

$settings=simplexml_load_file("settings.xml");//使用了SimpleXML API中来获取resolvedomains,PHP5才增加
$manager=new AddressManager();
//这里传递了字符串false到函数outputAddress
$manager->outputAddress((string)$settings->resolvedomains);


//知识点２：对象类型
class ShopProducerWriter{
    public function write(ShopProduct $shopProduct){
        $str="{$shopProduct->title}: ".$shopProduct->getProducer()." ({$shopProduct->price})";
        print $str;
    }
}
$writer=new ShopProducerWriter();
$writer->write($product2);

class Wrong{ }
//$writer->write(new Wrong());//由于存在类型限制，不能传入其他类型的对象

//知识大点四：继承
class Product{
    public $title;
    public $producerMainName="main name";
    public $producerFirstName="first name";
    protected $price=0;//使子类可以访问
    private $discount=0;
    function __construct($title,$firstName,$mainName,$price)
    {//父类设置存放公有属性
        $this->title=$title;
        $this->producerFirstName=$firstName;
        $this->producerMainName=$mainName;
        $this->price=$price;
    }

    function getProducer(){
        return "{$this->producerFirstName}"." {$this->producerMainName}";
    }

    function getSummaryLine()
    {
        $base = "$this->title ( {$this->producerMainName}, {$this->producerFirstName} )";
        return $base;
    }

    function setDiscount($num){
        $this->discount=$num;
    }

    function getPrice(){
        return ($this->price - $this->discount);//获得最后的价格
    }
}

class CdProduct extends Product{
    public $playLength;
    function __construct($title, $firstName, $mainName, $price, $playLength)
    {//不重复开发父类中有的功能
        parent::__construct($title, $firstName, $mainName, $price);
        $this->playLength=$playLength;
    }

    function getPlayLength(){
        return $this->playLength;
    }

    /**
     * 覆盖父类的方法，具体根据不同的类实现，体现多态
     * @return string　商品的信息
     */
    function getSummaryLine()
    {

        $base=parent::getSummaryLine();
        $base=$base.": play time - {$this->playLength}";
        return $base;
    }
}


class BookProduct extends Product{
    public $numPages;
    function __construct($title, $firstName, $mainName, $price,$numPages)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->numPages=$numPages;
    }

    function getNumberOfPages(){
        return $this-> numPages;
    }

    function getSummaryLine()
    {
        $base=parent::getSummaryLine();
        $base=$base.": page count - {$this->numPages}";
        return $base;
    }
}

class ProductWriter{
    public $products=array();
    public function addProduct(Product $product){
        $this->products[]=$product;
    }

    public function write(){
        $str="";
        foreach($this->products as $product){
            $str="{$product->title}: ".$product->getProducer()." ({$product->getPrice()})\n";
        }
        print $str;
    }
}
?>