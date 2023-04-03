<?php
header('Content-Type: application/json');
require ('payme.php');
$payme = new Payme('karta id raqami'); //Payme ilovasi orqali olish mumkin.

//Yangi toʻlov uchun kontent yaratish:
echo $payme->createPayment(10000, "Bot uchun to'lov cheki!","https://t.me/telegram"); //Birinchi maydonga summa, ikkinchi maydonga izoh, uchunvhi maydonga orqaga qaytish kontenti.

//To'lov xolatini ko'rish
//echo $payme->checkPayment('642aa15457151d99cccf300e');
