<?php

/*
    Plugin Name: wp_new_post_vk_inform
    Plugin URI: http://blog.kpa6.ru
    Description: Плагин отправляет информацию о новом посте администратору в личные сообщения ВК
    Version: 1.0
    Author: kpa6
    Author URI: http://blog.kpa6.ru
    License: GPL
*/

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/* - - - - - - - - - - - - - - - - - - -*/

/**
 * Настройка скрипта
 */

// Токен для доступа в аккаунт, с которого будем информировать себя
define('VK_ACCOUNT_TOKEN', '');

// ID информируемого аккаунта
define('VK_PERSON_ID', 142278422);

/* - - - - - - - - - - - - - - - - - - -*/

// Функция отсылки сообщения о новом посте
function new_post_inform($id, $post){

    // Автор поста
    $author = $post->post_author; // ID автора
    $author_name = get_the_author_meta('display_name', $author); // Ник автора

    // Заголовок поста
    $title = $post->post_title;

    // Ссылка на пост
    $link = get_permalink($id);

    $message = "Новый пост!\nАвтор: $author_name\nНазвание: $title\nСсылка: $link";

    $data = array(
        'user_id'      => VK_PERSON_ID,
        'random_id'    => rand(1, 1000000),
        'message'      => $message,
        'v'            => 5.52,
        'access_token' => VK_ACCOUNT_TOKEN
    );

    $url = "https://api.vk.com/method/messages.send";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.10.229 Version/11.61");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $rez = curl_exec($ch);
    curl_close($ch);
    print_r($rez);
}

add_action('publish_post', 'new_post_inform', 10, 2);
?>