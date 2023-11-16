<?php
session_start();
date_default_timezone_set('Europe/Kiev');
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// die;

$mail           = '';

$crm_token      = '427565f6349f0c60f367516e3318a1e9';
$crm_adress     = 'http://vzuttevuymagnat.lp-crm.biz/';
$botToken = '6856825729:AAFWk-WyLYPNcn4mIhTaL2FrVkrfh17pm58'; // Token
$chatId = '-4080053579'; // Chat id

$name           = $_POST['name'];
$phone          = preg_replace('/[^0-9]/', '', $_POST['phone']);
$comment        = '';


if(isset($_POST['product'])){
    $product_title     = $_POST['product'];
}

if(isset($_POST['product_id'])){
    $product_id     = $_POST['product_id'];
} else{
    $product_id = 5;
}

if(isset($_POST['product_price'])){
    $product_price     = $_POST['product_price'];
}else{
    $product_price = 2170;
}

if(isset($_POST['upsell'])){
    $upsell_title     = $_POST['upsell'];
}

if(isset($_POST['upsell_price'])){
    $upsell_price     = $_POST['upsell_price'];
}

if(isset($_POST['upsell'])){
    $upsell_id     = $_POST['upsell_id'];
}

if(isset($_POST['type'])){
    $type_form = $_POST['type'];
}
 
if(isset($_POST['discount'])){
    switch ($_POST['discount']) {
        case 1:
            $product_id = 19;
            $product_price = 299;
            break;
		case 2:
            $product_id = 20;
            $product_price = 847;
            break;
        case 3:
            $product_id = 21;
            $product_price = 1395;
            break;
            
        case 4:
            $product_id = 22;
            $product_price = 2699;
            break;
            
        
    }
} 
 
if ($crm_token != '') {
    $products_list = array(
        0 => array(
            'product_id' => $product_id,
            'price'      => $product_price,
            'count'      => '1',
        ),
    );
    $products = urlencode(serialize($products_list));
    $sender = urlencode(serialize($_SERVER));
    $data = array(
        'key'             => $crm_token,
        'order_id'        => number_format(round(microtime(true) * 10), 0, '.', ''),
        'country'         => 'UA',                         // Географическое направление заказа
        'office'          => '1',                          // Офис (id в CRM)
        'products'        => $products,                    // массив с товарами в заказе
        'bayer_name'      => $name,            // покупатель (Ф.И.О)
        'phone'           => $phone,                        // телефон
        'payment'         => '',                           // вариант оплаты (id в CRM)
        'sender'          => $sender,
    );
            
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    
        
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $crm_adress . '/api/addNewOrder.html');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $out = curl_exec($curl);
    curl_close($curl);

    // echo "<pre>";
    // print_r($out);
    // echo "</pre>";
        
}
        
$arr = array(
    'New order:'            => '',
    'Name: '                => $name,
    'Phone: '               => $phone,
    'ProductID: '           => $product_id,
    'ProductID: '           => 2170,
    'ProductPrice: '        => $product_price,
    'Order date: '          => date("Y-m-d H:i:s"),
    'Client IP-adress: '    => $_SERVER['REMOTE_ADDR'],
    'Site: '                => $_SERVER['SERVER_NAME']. dirname($_SERVER['SCRIPT_NAME']),
);
    

// echo "<pre>";
// print_r($arr);
// echo "</pre>";


?>

<!--    Telegram Bot    -->

<?php 
$currentDateTime = date("Y-m-d H:i:s"); // Date
$message = "<b>Заявка з сайту:</b> <i>{$_SERVER['HTTP_HOST']}</i>\n<b>Ім`я замовника:</b> <i>{$name}</i>\n<b>Телефон:</b> <i>{$phone}</i>\n<b>Товар:</b> <i>Кросовки Ecco</i>\n<b>Ціна: 2170</b><i> {$new_price} грн</i>\n<b>Кількість: </b><i>'1' шт.</i>\n====== <b>Інші дані</b> ======\n<b>Дата:</b> <i>$currentDateTime</i>\n<b>IP:</b> <i>{$_SERVER['REMOTE_ADDR']}</i>";


// URL для відправки запиту до API Телеграма
$telegramApiUrl = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';

$params = array(
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'HTML'
);


$ch = curl_init($telegramApiUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
?>

   
<!DOCTYPE html>
<html lang="ru"> 

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://i.ibb.co/KwXbcKf/favicon-32x32.png" type="image/x-icon">
    <title>
        <?php if ($_POST['phone']) {
            echo 'Дякуємо за замовлення!';
        } else {
            echo 'Якась проблема...';
        } ?>
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
</head>

<body>
    <div class="main">
        <div class="infos">
            <?php if (isset($_POST['phone'])) : ?>
            <h2>ДЯКУЄМО!</h2>
            <p class="text_infos">Ваше замовлення прийнято. Менеджер зв'яжеться з Вами найближчим часом.</p>
            <div class="client_info">
                <p><span>Ваше ім'я:</span> <span id="client">
                        <? print($_REQUEST['name']); ?></span></p>
                <p><span>Ваш номер телефону:</span><span id="client">
                        <? print($_REQUEST['phone']); ?></span></p>
            </div>
            
            <a href="/">Повернутися назад</a>
            <?php else : ?>
            <div>
                <h1>СТОРІНКА НЕ ІСНУЄ</h1>
                <div><a href="/">Повернутися назад</a></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        body {
            background-image: url('https://i.ibb.co/gmNVzPD/photo-1560264280-88b68371db39.jpg');
            background-size: cover;
            background-repeat: no-repeat
        }
        .infos{
            text-align: center;
            font-weight: 700;
        }

        .main {
            width: 100%;
            background-color: rgba(195, 195, 195, .7);
            padding: 10%
        }

        .order-info {
            padding: 20px;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center
        }

        .order-info h2 {
            font-weight: 700;
            margin-bottom: 30px
        }

        .order-info a {
            margin-top: 40px
        }

        .sale_text {
            font-weight: 700;
            margin: 30px 0 10px
        }

        .sale_link {
            text-decoration: none;
            text-transform: uppercase;
            font-weight: 900;
            font-size: 20px;
            color: #0d6efd
        }

        .upsell-block {
            width: 100%;
            margin-top: 20px;
            padding: 40px;
            border-radius: 16px;
            background-image: radial-gradient(circle 1976px at 51.2% 51%, rgba(11, 27, 103, 1) 0%, rgba(16, 66, 157, 1) 0%, rgba(11, 27, 103, 1) 17.3%, rgba(11, 27, 103, 1) 58.8%, rgba(11, 27, 103, 1) 71.4%, rgba(16, 66, 157, 1) 100.2%, rgba(187, 187, 187, 1) 100.2%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upsells {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            width: 100%;
        }

        .upsell-block span {
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            font-weight: 700;
        }

        .upsell-block .upsell-card {
            width: 40%;
            padding: 30px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: -4px 4px 19px 0px rgba(255, 255, 255, 0.75);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }

 

        .card__head span {
            color: #0d6efd;
            font-weight: 900;
            font-size: 20px;
            margin-bottom: 10px;

        }

        .card-img {
            width: 100%;
            position: relative;
        }

        .card-img .discount{
            position: absolute;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-weight: 700;
            z-index: 1000;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            right: 0px;
        }

        .product-main-img{
            width: 100%;
            height: 200px;
            object-fit: contain;
        }


        .swiper {
            width: 100%;
            height: 200px;

        }

        .swiper img {
            object-fit: contain;
            width: 100%;
            height: 100%;
        }

        .upsell-form {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .upsell-form input {
            flex-basis: 45%;
        }

        .card-prices {
            margin-top: 20px;
            border-top: 1px solid;
            border-bottom: 1px solid;
            border-color: rgba(255, 255, 255, 0.75);
            color: #0d6efd;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .card-btns {
            margin: 10px 0px;
            position: relative;
            width: 100%;
        }

        .form-container {
            display: none;
            position: absolute;
            left: 0;
            z-index: 10000;
            top: 120%;
            height: 250px;
            background-color: white;
            width: 100%;
            border: 1px solid #0d6efd;
            border-radius: 4px;
            padding: 5px;
        }

        button {
            padding: 16px 20px;
            background-color: white;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            border: 2px solid #0d6efd;
            border-radius: 16px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0px;
            padding: 10px;
            width: 100%;
        }

        input {

            width: 100%;
            border-color: #0d6efd;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 10px;
            color: #0d6efd;
        }

        form button {
            background-color: #0d6efd;
            color: white;
            letter-spacing: 0px;
            margin-top: 10px;
        }

        @media (max-width: 992px) {

        .upsell-block .upsell-card {
                width: 100%;
                padding: 20px 10px;
            }
        }

        @media (max-width: 768px) {

.main {
        width: 100%;
        padding: 10px;
    }

    .upsell-block{
        padding: 20px;
    }

    .card__head{
        margin-bottom: 40px;
    }

    .card__head span {
            color: #0d6efd;
            font-weight: 800;
            font-size: 16px;
            margin-bottom: 10px;

        }

    form input{
        flex-basis: unset
    }

    form button{
        padding: 10px 10px;
    font-size: 12px;
    }

    .form-container{
        padding: 10px;
        top: unset;
        bottom: 50%;
    }
}
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {

            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        

        $(document).on('click', '.js-show-form', function (e) {
            e.preventDefault
            var form_container = $(this).parents('.card-btns').find('.form-container')
            $(form_container).fadeToggle()
        })
    </script>
</body>

</html>