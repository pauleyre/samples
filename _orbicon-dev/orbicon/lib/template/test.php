<?php

require 'class.group.php';
require 'class.layout.php';
require 'class.template.php';
require 'class.tag.php';

$div = new Tag('div');
$div->innerHTML = $div->stats();
$div->style='border:2px inset red;padding:3px';
$div->id='x';
$div->class = 'my_class';

echo $div;

$img = new Tag('img');
$img->SRC = 'abc.jpg';

echo $img;

$tpl = new Template('test.html');
$tpl->set('ABC', 'lolz');

/*$array = array('bla', 'ble');

foreach ($array as $x) {
	$tpl->bind('XXX', array('IMG_ID' => $x));
}*/

/*$layout_ = new Layout();
$layout_->insert($tpl);
$layout_->insert_html_tag($div);
*/
$layout = new Layout(Layout::DEFAULT_CONTENT_TYPE, 'windows-1250');
$layout->insert($tpl);
/*$layout->insert_html_tag($div);
$layout->insert_html_tag($img, 'html');
$layout->merge($layout_);
$layout->insert_top(new Template('test.html'));
$layout->insert(new Template('test.html'));
$layout->insert_before(file_get_contents('test.txt'), 0);
$layout->insert_after('xxx', 1);*/
$layout->render_and_display();

?>