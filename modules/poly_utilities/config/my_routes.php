<?php defined('BASEPATH') or exit('No direct script access allowed');

$base = 'poly_utilities/article/details';
$route['article/(:any)'] = $base . '/$1';
$route[$base . '/(:any)'] = 'poly_utilities/redirect/utilities_article_details/$1';
